<?php 
/**
 * php_curl的类
 * 
 * 
 * 支持以下功能：
 * 1：支持ssl连接和proxy代理连接
 * 2: 对cookie的自动支持
 * 3: 支持的请求有：GET,POST,SEARCH,BPROPPATCH
 * 4: 支持单个文件上传或同字段的多文件上传,支持相对路径或绝对路径.
 * 5: 支持返回发送请求前和请求后所有的服务器信息和服务器Header信息
 * 6: 自动支持lighttpd服务器
 * 7: 支持自动设置 REFERER 引用页
 * 9: 其它可选项,如自定义端口，超时时间，USERAGENT，Gzip压缩等.
 * 
 * @author Coly
 * @version $ID 2012-8-27 $
 */
class PhpCurl
{
	//curl句柄
	private $ch = null;
	
	//返回的信息
	private $info = array();
	
	//存储单例的静态变量
	static  private  $instance;
	
	//保存初始化时的header信息，因为除了post和get之外的请求，header都不一样
	private $originHeader = array();
	
	//cookie的完整路径
	private $cookfile = null;
	
	private $setopt = array(
			'use_proxy'=>false,         //是否使用代理
			'proxyHost'=>'',            //代理地址
			'proxyPort'=>'',           //代理端口
			'proxyType'=>'HTTP',        //代理类型,可选择 HTTP 或 SOCKS5
			'proxyAuth'=>false,			    //代理是否要身份认证(HTTP方式时)
			'proxyAuthType'=>'',		    //认证的方式.可选择 BASIC 或 NTLM 方式
			'proxyAuthUser'=>'BASIC',	  //认证的用户名
			'proxyAuthPwd'=>'',	        //认证的密码
			'use_cookie'=>false,      //是否使用cookkie
			'cookie_path'=>'',          //cookie存放的路径
			'cookie_fileName'=>'',     //cookie文件的名字
			'ssl'=>false,					      //是否支持SSL
			'gzip'=>true,					      //客户端是否支持 gzip压缩
			'header'=>array(),          //header头部属性
			'userAgent'=>'',				    //客户端 USERAGENT,如:"Mozilla/4.0",为空则使用用户的浏览器
			'timeOut'=>30,					    //连接等待时间
			'printHeader'=>true         //是否输出头信息
			);
	
	public  function __construct($configArray = array())
	{
		function_exists('curl_init') || die('CURL Library Not Loaded');		//如果没有安装CURL则终止程序
		
		$this->initialize($configArray);
		
		$this->originHeader = @$configArray['header'];
	}
	
	/**
	 * 以get方式获取url的数据
	 * 
	 * @param string $url : 请求的URL
	 *
	 * @return 错误返回:false 正确返回:结果内容
	 */
	public function get ($url)
	{
		$this->_setHeader($this->originHeader);
		//把常规请求设置回来
		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
	  return $this->_request($url);
	}
	
	
	/**
	 * 以 POST 方式执行请求
	 *
	 * @param string $url :请求的URL
	 *
	 * @param array $params ：请求的参数,格式如: array('id'=>10,'name'=>'yuanwei')
	 *
	 * @param array $referer :引用页面,引用页面,为空时自动设置,如果服务器有对这个控制的话则一定要设置的.
	 *
	 * @param array $uploadFile :上传的文件,支持相对路径,格式如下:
	 * 单个文件上传:array('img1'=>'./file/a.jpg'),同字段多个文件上传:array('img'=>array('./file/a.jpg','./file/b.jpg'))
	 *
	 *
	 * @return 错误返回:false 正确返回:结果内容
	 */
	public function post($url, $params=array(),$referer='',$uploadFile=array()){
		
		//发送一个常规的POST请求
	  curl_setopt($this->ch, CURLOPT_POST, true);
	  
	  //把常规请求设置回来
	  curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'POST');
	  
	  //设置POST字段值
		$postData = http_build_query($params);	
		
		//如果有上传文件
		if(count($uploadFile)> 0){
			foreach($uploadFile as $key=>$file){
				if(is_array($file)){
					$n = 0;
					foreach($file as $f){
						$postData[$key.'['.$n++.']'] = '@'.realpath($f);	//文件必需是绝对路径
					}
				}else{
					$postData[$key] = '@'.realpath($file);
				}
			}
		}
		
		//把header重新设置回初始化时的header，因为如果有post和get之外的请求，会改变header
		$this->_setHeader($this->originHeader);
		
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postData);

		return $this->_request($url,  $referer);
	}
	
	
	/**
	 * 
	 * 除了get和post之外的其他请求
	 * 
	 * 主要用来执行除了post和get之外的其他请求，如exchange的search和bproppatch
	 *
	 * @param string $request 请求的类型
	 * @param string $url     请求的URL
	 * @param array $data     传递的数据
	 * @param array $header   header
	 * @return Ambigous <错误返回:false, boolean, string>
	 */
	public function customRequest ($request,$url,$data,$header)
	{
		$this->_setHeader($header);
	
		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $request);
	
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
	
		return $this->_request($url);
	}
	
	
	
	/**
	 * 执行请求
	 *
	 * @param string $method :HTTP请求方式
	 *
	 * @param string $url :请求的URL
	 *
	 * @param array $referer :引用页面
	 *
	 * @return 错误返回:false 正确返回:结果内容
	 */
	private function _request($url,$referer='')
	{
		curl_setopt($this->ch, CURLOPT_URL, $url);	//设置请求的URL
	
		//设置了引用页,否则自动设置
		if($referer){
			curl_setopt($this->ch, CURLOPT_REFERER, $referer);
		}else{
			curl_setopt($this->ch, CURLOPT_AUTOREFERER, true);
		}
	
		//获得cookie
		curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookfile);
		
		//传入cookie
		curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookfile);
		
		//curl_getinfo用来获取每次请求的所有信息，包括url,http_code等
		$this->info['before'] = curl_getinfo($this->ch);				//得到所有设置的信息
		
		$result = curl_exec($this->ch);									//开始执行请求
	  
		$headerSize = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);	//得到报文头
		$this->info['header'] = substr($result, 0, $headerSize);
	
		$result = substr($result, $headerSize);							//去掉报文头
		$this->info['after'] = curl_getinfo($this->ch);					//得到所有包括服务器返回的信息
		
		//如果请求成功
		if($this->getErrorNum() == 0){ //&& $this->info['after']['http_code'] == 200
			return $result;
		}else{
			return false;
		}
	
	}
	
	/**
	 * 设置头部信息
	 * 
	 * 独立出来是因为除了post和get，其他的请求都需要单独设置header
	 * @param Array $header 头信息数组
	 */
	private function _setHeader ($header)
	{
		//设置header头部信息
		count($header)>0 ? curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header) : null;
	}
	
	
	/**
	 * 初始化所有的参数
	 * 
	 * 对默认参数和用户传入的参数进行合并，并且初始化
	 * @param Array $configArray  用户传入的配置数组
	 */
	public function initialize ($configArray  = array())
	{
		$this->setopt = array_merge($this->setopt,$configArray);
		
		$this->ch = curl_init();		//初始化
		
		//使用代理
		if($this->setopt['use_proxy']){
			$proxyType = $this->setopt['proxyType']=='HTTP' ? CURLPROXY_HTTP : CURLPROXY_SOCKS5;
			curl_setopt($this->ch, CURLOPT_PROXYTYPE, $proxyType);
			curl_setopt($this->ch, CURLOPT_PROXY, $this->setopt['proxyHost']);
			curl_setopt($this->ch, CURLOPT_PROXYPORT, $this->setopt['proxyPort']);
		
			//代理要认证
			if($this->setopt['proxyAuth']){
				$proxyAuthType = $this->setopt['proxyAuthType']=='BASIC' ? CURLAUTH_BASIC : CURLAUTH_NTLM;
				curl_setopt($this->ch, CURLOPT_PROXYAUTH, $proxyAuthType);
				$user = "[{$this->setopt['proxyAuthUser']}]:[{$this->setopt['proxyAuthPwd']}]";
				curl_setopt($this->ch, CURLOPT_PROXYUSERPWD, $user);
			}
		}
		
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);	//启用时会将服务器服务器返回的“Location:”放在header中递归的返回给服务器
		
		
	//打开的支持SSL
		if($this->setopt['ssl']){
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);	//不对认证证书来源的检查
      @curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, true);	//从证书中检查SSL加密算法是否存在
		}

		//设置header
		$this->_setHeader($this->setopt['header']);
		
		//设置 HTTP USERAGENT,如果用户没有设置，即使用客户浏览器的userAgent
		$userAgent = $this->setopt['userAgent'] ? $this->setopt['userAgent'] : $_SERVER['HTTP_USER_AGENT'];		
		curl_setopt($this->ch, CURLOPT_USERAGENT, $userAgent);
		
		//设置连接等待时间,0不等待
		curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->setopt['timeOut']);	
		//设置curl允许执行的最长秒数
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->setopt['timeOut']);			
		
		//设置客户端是否支持 gzip压缩
		if($this->setopt['gzip']){
			curl_setopt($this->ch, CURLOPT_ENCODING, 'gzip');
		}
		
		//是否使用到COOKIE
		if($this->setopt['use_cookie']){
			$cookiePath = $this->setopt['cookie_path'] ? $this->setopt['cookie_path'] : dirname(__FILE__).'/';
			$cookieName = $this->setopt['cookie_fileName'] ? $this->setopt['cookie_fileName'] : 'cookie.txt';

			$this->cookfile = $cookiePath.$cookieName;	//生成存放临时COOKIE的文件
			
// 			//获得cookie
// 			curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookfile);
			
		}
		//是否将头文件的信息作为数据流输出(HEADER信息),这里保留报文
		curl_setopt($this->ch, CURLOPT_HEADER, true);			//是否将头文件的信息作为数据流输出(HEADER信息),这里保留报文
			
		
		//获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true) ;	
		curl_setopt($this->ch, CURLOPT_BINARYTRANSFER, true) ;
    		
	}
	
	/**
	 * 得到发送请求前和请求后所有的服务器信息和服务器Header信息:
	 * [before] ：请求前所设置的信息
	 * [after] :请求后所有的服务器信息
	 * [header] :服务器Header报文信息
	 *
	 * @return array
	 */
	public function returnInfo ()
	{
		return $this->info;
	}
	
	/**
	 * 返回错误信息
	 *
	 * @return string
	 */
	public function getError ()
	{
		return curl_error($this->ch);
	}
	
	/**
	 * 返回错误信息代码
	 *
	 * @return number
	 */
	public function getErrorNum ()
	{
		return curl_errno($this->ch);
	}
	
	/**
	 * 析构函数
	 *
	 */
	public function __destruct(){
		curl_close($this->ch);
	}
	
}
?>
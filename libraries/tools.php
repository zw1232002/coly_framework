<?php

/**
 * 工具类
 * 
 * @author Coly Zhang  <zw1232002@gmail.com>
 * @version $ID 2014-6-23 $
 */
class Tools
{
  
  /**
   * 判断是否是post请求
   * 
   * @return boolean
   */
  public static function is_post()
  {
    return count($_POST) >0 ? true : false;
  }
  
  /**
   * 将结果集数组转换成显示用的key value数组
   *
   * @param array  $dataArray   结果集数组
   * @param string $valueFieldName  用来作为value的字段名
   * @param string $showFieldName  用来作为显示名的字段名
   * @return  array
   */
  public static function getKeyValueArray($dataArray,$valueFieldName,$showFieldName)
  {
    $optionArray = array();
    foreach ($dataArray as $value)
    {
      @ $optionArray[$value[$valueFieldName]] = $value[$showFieldName];
    }
    return $optionArray;
  }
  
  /**
   * 根据传入的路径获取扩展名
   *
   * @param string $path 传入的路径
   * @return string      返回扩展名
   */
  public static function getExtraName($path) {
    return array_pop(@explode('.', $path));
  }
  
  
  /**
   * 设置cookie
   * 
   * @param string    $name      cookie名称
   * @param mutiple  $value      cookie值
   * @param int         $expire     过期时间
   * @param string    $domain   cookie域
   * @param string    $path       cookie路径，默认根路径
   * @param string    $prefix     cookie前缀
   * @param boolean $secure   是否只允许通过https链接
   */ 
  public static function setCookie($name = '', $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = FALSE)
  {
    if ( ! is_numeric($expire))
    {
      $expire = time() - 86500;
    }
    else
    {
      $expire = ($expire > 0) ? time() + $expire : 0;
    }
    setcookie($prefix.$name, $value, $expire, $path, $domain, $secure);
  }
  
  
  /**
   * 获取cookie
   * 
   * @param string $index cookie的key
   */
  public static function getCookie($index = '')
	{
	  return $index  ? @$_COOKIE[$index] : $_COOKIE;
	}
  
  
  /**
   * 获取二维数组中某个key的值得总和
   * 
   * @param array  $dataArray  数组名
   * @param string $keyName   要累加的key名称
   * @return Ambigous <number, unknown>
   */
  public static function getArrayKeysSum($dataArray,$keyName)
  {
    $sum = 0;
    foreach ($dataArray as $value)
    {
      if(isset($value[$keyName])) $sum+=$value[$keyName];
    }
    return $sum;
  }
  
  /**
   * 获取某个目录下的所有文件
   * 
   * @param string $dir 目录
   * @return string|Ambigous <multitype:number string , boolean, string>
   */
  public static function listDir($dir){
    if(!file_exists($dir)||!is_dir($dir)){
      return '';
    }
    $dirList=array('dirNum'=>0,'fileNum'=>0,'lists'=>'');
    $dir=opendir($dir);
    $i=0;
    while($file=readdir($dir)){
      if($file!=='.'&&$file!=='..'){
        $dirList['lists'][$i]['name']=$file;
        if(is_dir($file)){
          $dirList['lists'][$i]['isDir']=true;
          $dirList['dirNum']++;
        }else{
          $dirList['lists'][$i]['isDir']=false;
          $dirList['fileNum']++;
        }
        $i++;
      };
    };
    closedir($dir);
    return $dirList;
  }
  
  /**
   * 获取文件的类名
   * 
   * @param string $fileName 文件完整路径
   * @return string
   */
  public static function getClassNameByFileName($fileName)
  {
    $contents = file_get_contents($fileName);
    preg_match('/class ([A-Z][a-zA-Z]+)\s/', $contents,$match);
    return $match[1];
  }
  
  /**
   * 读取csv文件的内容
   * 
   * @param string $csvFullPath  csv文件完整路径
   * @return array                        数据数组
   */
  public static function readCSV($csvFullPath)
  {
    $file = fopen($csvFullPath, 'r');
    //读取csv内容
    $contents =  fread($file, filesize($csvFullPath));
    //转编码
    $contents = mb_convert_encoding($contents,"UTF-8","GBK");
    //把csv内容按照换行符分割
    $listArray = preg_split('/\r\n/', $contents);
    $dataArray = array();
    //再将每一行按照逗号分割
    foreach ($listArray as $key => $val)
    {
      $dataArray[$key] = explode(',', $val);
    }
    foreach($dataArray as $key=>$val)
    {
      if ($key > 0 && !empty($dataArray[$key][2]))
      {
        $dataArray[$key][1] = $dataArray[$key][1].'('.$dataArray[$key][2].')';
      }
    };
    fclose($file);
    return $dataArray;
  } 
  
  /**
   * 将内容生成到csv文件
   * 
   * @param array  $dataArray      要保存到csv文件的数据数组
   * @param string $saveFullPath 保存的完整路径
   * @param array  $headerArray  cvs头部数组，如果该参数不传，则可以把头部放到数据数组中
   * @return string                        返回文件路径
   */
  public static function saveCSV($dataArray,$saveFullPath,$headerArray='')
  {
    $returnArray = array();
    //头部
    if($headerArray)
    {
      foreach ($headerArray as $value)
      {
        $returnArray[] = implode(',', $value);
      }
    }
    //把数组重新安装逗号组合
    foreach ($dataArray as $value)
    {
      $returnArray[] = implode(',', $value);
    }
    $saveContents = implode("\n", $returnArray);
    //转编码
    $saveContents = mb_convert_encoding($saveContents,"GBK","UTF-8");
    $mFile = fopen($saveFullPath,'w');
    fwrite($mFile, $saveContents);
    fclose($mFile);
    return $saveFullPath;
  }
  
}


?>
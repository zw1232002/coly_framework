<?php


/**
 * 载入模板函数
 *
 * @param string    $templateName 模板具体路径，不带.php后缀
 * @return null
 */
function template($templateName)
{
  $templateFullPath = TEMPLATES_PATH.str_replace('.php', '', $templateName).'.php';
  if(file_exists($templateFullPath))
  {
    extract($GLOBALS['templateAssigns']);
    include_once $templateFullPath;
  }else 
  {
    echo '模板文件不存在！';
  }
}


/**
 * 自动加载类（不包含控制器）
 *
 * 根据类名的后缀判断是在哪个文件夹下面，来实现自动加载
 * @param string $className 系统自动加载时候传入的类名
 */
function autoLoad($className){
  if(substr($className, -5)==='Model' && file_exists(MODELS_PATH.strtolower($className).'.php')){//model
    require_once  MODELS_PATH.strtolower($className).'.php';
  }elseif (file_exists(LIBRARIES_PATH.strtolower($className).'.php'))//library
  {
    require_once  LIBRARIES_PATH.strtolower($className).'.php';
  }
}

//注册自动加载函数
spl_autoload_register('autoLoad');


/**
 * 获取当前的url数组
 * 
 * @return array
 */
function getCurlUrl()
{
  $uri = $_SERVER['REQUEST_URI'];
  if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
  {
    $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
  }
  elseif (dirname($_SERVER['SCRIPT_NAME'])!='/' && strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
  {
    $uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
  }
  
  //获取参数数组，去除第一个/
  $uriArray = @explode('/', $uri);
  array_splice($uriArray, 0,1);
  return $uriArray;
}

/**
 * 路由函数
 * 
 * @author Coly Zhang
 * @version $ID 2014-6-19 $
 */
function router()
{
  //赋值控制器，方法
  @list($controllerName,$methodName,$param) = getCurlUrl();
  //方法和第一参数中，去除get的值，后期get直接获取
  $controllerName = preg_replace('/\?+.*/', '', $controllerName);
  $methodName = preg_replace('/\?+.*/', '', $methodName);
  $param = preg_replace('/\?+.*/', '', $param);
  
  //路由设置
  $routArray = $GLOBALS['config']['route'];
  //默认控制器和方法
  $controllerName = $controllerName ? $controllerName : $routArray['default'];
  $methodName = $methodName ? $methodName : $routArray['defaultMethod'];
  if(file_exists($controllerPath = CONTROLLERS_PATH.strtolower($controllerName).'.php'))
  {
    require_once CONTROLLERS_PATH.'base.php';
    require_once $controllerPath;
    //实例化
    if(method_exists($controllerName, $methodName))
    {
      $controller = new $controllerName();
      $controller->$methodName();
    }else
    {
      template('404');
      exit;
    }
  }else
  {
    template('404');
    exit;
  }
}
router();


/**
 * 向模板中注入变量
 *
 * @param string   $varName 变量名
 * @param mutiple $value      变量的值
 */
function templateAssign($varName,$value)
{
  global $templateAssigns;
  $templateAssigns[$varName] = $value;
}

/**
 * 向模板中注入变量（数组）
 *
 * @param array   $varArray 变量数组
 */
function templateAssignArray($varArray)
{
  global $templateAssigns;
  foreach ((array)$varArray as $key=>$value)
  {
    $templateAssigns[$key] = $value;
  }
}

/**
 * 获取根控制器（保证唯一性）
 * 
 * @return Base
 */
function &get_instance()
{
  return Base::get_instance();
}

/**
 * 消息提示中转
 * 
 * @param string $msg        消息
 * @param int      $type        类型，1成功，2消息提示，3警告，4危险
 * @param string $url           要跳转的url
 * @param int       $seconds 跳转等待秒数 
 */
function showNotice($msg='',$type = 1,$url = '',$seconds=2)
{
  if($msg)
  {
    templateAssign('msg', $msg);
    templateAssign('url', $url);
    $noticeClass = '';
    switch ($type)
    {
      case 1:
        $noticeClass = 'alert-success';
        break;
      case 2:
        $noticeClass = 'alert-info';
        break;
      case 3:
        $noticeClass = 'alert-warning';
        break;
      case 4:
        $noticeClass = 'alert-danger';
        break;
      default:
        $noticeClass = 'alert-success';
        break;
    }
    templateAssign('noticeClass', $noticeClass);
    templateAssign('seconds', $seconds);
    template('notice');
    exit;
  }elseif ($url)
  {
    header('Location:'.$url);
  }
  
}


?>
<?php
header('Content-type:text/html;charset=utf8');
//设置时区
date_default_timezone_set('Asia/Shanghai');

session_start();

/*
 * 运行环境，根据此参数设置错误级别和配置文件引入
*
* development为开发环境，错误显示级别为E_ALL
* production为正式环境，错误显示级别为0
*/
define('ENVIRONMENT', 'development');

//根目录
define('ROOT_PATH', __DIR__.'/');

//根据环境设置错误显示级别以及配置文件
if (defined('ENVIRONMENT'))
{
  switch (ENVIRONMENT)
  {
    case 'development':
      error_reporting(E_ALL);
      require_once ROOT_PATH.'config/config.php.local';
      break;
    case 'testing':
    case 'production':
      error_reporting(0);
      require_once ROOT_PATH.'config/config.php';
      break;
    default:
      error_reporting(E_ALL);
  }
}
require_once CORE_PATH.'loader.php';
require_once CORE_PATH.'core.php';
?>
<?php

/**
 * HTTP请求数据接收类
 * 
 * @author Coly Zhang  <zw1232002@gmail.com>
 * @version $ID 2014-7-8 $
 */
class Input
{
  
  public function __construct()
  {
    
  }
  
  /**
   * 获取post数据
   */
  public function post($key='')
  {
    if($key) return @$_POST[$key];
    return $_POST;
  }
  
  /**
   * 获取get数据
   */
  public function get($key='')
  {
    if($key) return @$_GET[$key];
    return $_GET;
  }
}


?>
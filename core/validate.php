<?php

/**
 * 表单验证类
 * 
 * 
 * @author Coly Zhang  <zw1232002@gmail.com>
 * @version $ID 2014-6-24 $
 */
class Validate
{
  //验证规则数组
  public static $rulesArray = array();
  //错误说明数组
  public static  $errorArray = array();
  //验证是否成功
  public static  $status;
  //表单验证之后的数据数组
  public static  $dataArray = array();
  
  
  /**
   * 验证表单数据(可支持多个规则文件合并)
   * 
   * @param string|array $rulesFileName 规则文件名
   * @return boolean
   */
  public static function ValidateData($rulesFileName)
  {
    $rulesArray = array();
    //如果是数组，合并多个规则文件
    if(is_array($rulesFileName))
    {
      foreach ($rulesFileName as $value)
      {
        $rulesPath= RULES_PATH.$value.'.php';
        if(file_exists($rulesPath))
        {
          $rulesArray = array_merge(include_once $rulesPath,$rulesArray);
        } 
      }
    }else 
    {
      $rulesPath = RULES_PATH.$rulesFileName.'.php';
      if(!file_exists($rulesPath)) exit('规则文件不存在！');
      $rulesArray = include_once $rulesPath;
    } 
    self::$status = true;
    foreach ($rulesArray as $value)
    {
      self::execute($value);
    }
    return self::$status;
  }
  
  
  /**
   * 
   * 执行规则验证
   * 
   * @param array $rules 每条规则数组
   */
  public static function execute($rules)
  {
    self::$dataArray = array_merge($_POST,$_GET);
    $fieldName = $rules['field'];//字段名
    $fieldChName = $rules['label'];//字段显示中文名
    $allRules = explode('|', $rules['rules']);//规则列表
    
    //必填
    if(in_array('required', $allRules) && self::is_empty(self::$dataArray[$fieldName]))
    {
      self::$errorArray[$fieldName] = $fieldChName.'为必填！';
      self::$status = false;
    } 
    //trim
    if(in_array('trim', $allRules)) @self::$dataArray[$fieldName] = trim(self::$dataArray[$fieldName]);
    //htmlspecialchars
    if(in_array('htmlspecialchars', $allRules)) @self::$dataArray[$fieldName] = htmlspecialchars(self::$dataArray[$fieldName]);
    //min_length
    if(preg_match('/min_length\[(\d+)\]/', $rules['rules'],$matches) && strlen(self::$dataArray[$fieldName])<$matches[1])
    {
      self::$errorArray[$fieldName] = $fieldChName.'最小'.$matches[1].'个字符！';
      self::$status = false;
    }  
    //max_length
    if(preg_match('/max_length\[(\d+)\]/', $rules['rules'],$matches) && strlen(self::$dataArray[$fieldName])>$matches[1])
    {
      self::$errorArray[$fieldName] = $fieldChName.'最大'.$matches[1].'个字符！';
      self::$status = false;
    }  
    //numeric
    if(in_array('numeric', $allRules) && !is_numeric(self::$dataArray[$fieldName]))
    {
      self::$errorArray[$fieldName] = $fieldChName.'必须为数字！';
      self::$status = false;
    } 
    //int
    if(in_array('integer', $allRules) && !self::integer(self::$dataArray[$fieldName]))
    {
      self::$errorArray[$fieldName] = $fieldChName.'必须为整型！';
      self::$status = false;
    } 
  }
  

  /**
   * 判断是否为整型
   *
   * @access  public
   * @param string
   * @return  bool
   */
  public static function integer($str)
  {
    return (bool) preg_match('/^[\-+]?[0-9]+$/', $str);
  }
  
  /**
   * 判断是否为空，0不为空
   * 
   * @param string $str
   * @return boolean
   */
  public static function is_empty($str)
  {
    return (bool) !preg_match('/^0$/', $str) && empty($str);
  }
  
  
  
}

?>
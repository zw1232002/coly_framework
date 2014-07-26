<?php

/**
 * 加载器类
 * 将类文件加载成为控制器的方法，可以在控制器中像调用方法一样调用model等
 * 
 * @author Coly Zhang  <zw1232002@gmail.com>
 * @version $ID 2014-6-19 $
 */
class Loader
{
  //要加载到的父类控制器
  protected $parent;
  
  /**
   * construct
   * 
   * @param obj $parent 要加载到的父类控制器
   */
  public function __construct(&$parent)
  {
    $this->parent = $parent;
  }
  
  /**
   * 加载model
   * 
   * @param string|array $modelName model名
   */
  public function model($modelName)
  {
    if(!$modelName) return ;
    if(is_array($modelName))
    {
      foreach ($modelName as $value)
      {
        $this->model($value);
      }
    }else 
    {
      if($this->_is_loaded($modelName)===true) return ;
      $model = ucfirst(str_replace(array('model','Model'), array('Model','Model'), $modelName));
      $this->parent->$modelName = new $model;
    }
    
  }
  
  /**
   * 加载library
   * 
   * @param string|array $libraryName library名
   */
  public function library($libraryName)
  {
    if(!$libraryName) return ;
    if(is_array($libraryName))
    {
      foreach ($libraryName as $value)
      {
        $this->library($value);
      }
    }else
    {
      if($this->_is_loaded($libraryName)===true) return ;
      $arguments = func_get_args();
      $library = ucfirst(array_shift($arguments));
      $class = new ReflectionClass($library);
      $this->parent->$libraryName = $class->newInstanceArgs($arguments);
    }
  }
  
  
  /**
   * 判断一个要加载的文件是否已经加载过
   * 
   * @param string $name
   * @return boolean
   */
  private function _is_loaded($name)
  {
    return property_exists($this->parent, $name) || method_exists($this->parent, $name) ? true :  false;
  }
  
}


?>
<?php

/**
 * 数据库连接和操作类
 * 使用PDO进行连接
 * 
 * @author Coly Zhang  <zw1232002@gmail.com>
 * @version $ID 2014-6-19 $
 */
class Db
{
  
  //pdo单例
  protected static $pdo;
  
  //配置信息数组
  protected  static $dbConfig;
  
  // Prepare结果
  protected static $pre;
  
  //错误
  protected  static $error;
  
  /**
   * 初始化参数
   * 
   * @param array $dbConfig 数据库配置信息数组
   */
  public static function initialize($dbConfig)
  {
    self::$dbConfig = $dbConfig;
  }
  
  
  /**
   * 数据库快速查询方法
   * 
   * @param string $tableName    要查询的表名
   * @param array  $whereArray  where条件数组
   * @param array  $likeArray      like条件数组
   * @param array  $sortArray     排序条件数组
   * @param int      $limitStart      数据起始
   * @param int      $length          要取得数据数量
   * @param array $columnArray 要取得字段名，不填为* 
   * @param array $leftJoinArray leftjoin的数组  ，格式为array('ios_offer AS B'=>'A.offer_id=B.offer_id')
   * @return array|boolean           成功返回查询结果集，失败返回false
   */
  public static function select($tableName,$whereArray = array(),$likeArray = array(),$sortArray = array(),$limitStart = '',$length = '',$columnArray = array(),$leftJoinArray = array())
  {
    $query = 'SELECT ';
    $wheres = $sorts = $leftJoin = '';
    $addOn  = $leftJoinArray ? 'A.' : '';//有leftjoin数组时，附加A.
    //将值设置加引号
    //如果是只获取指定的字段的话
    $columns = $columnArray ?  @implode(',', $columnArray) : '*';
    $query.=$columns.' FROM '.$tableName.' ';
    if($leftJoinArray) $query.=' AS A ';
    //left join
    if($leftJoinArray)
    {
      foreach ($leftJoinArray as $key=>$value)
      {
        $leftJoin .= ' LEFT JOIN  '.$key.' ON '.$value.' ';
      }
    }
    //where条件
    if($whereArray)
    {
      $wheres .= ' WHERE ';
      foreach ($whereArray as $key=>$value)
      {
        if(preg_match('/<|>|!/', $key))//大于，小于，不等于情况
        {
          $addOn = preg_match('/[^A]\./', $key) ? '' : $addOn;
          $wheres .= $addOn.$key."'".$value."' AND ";
        }else 
        {
          $addOn = preg_match('/[^A]\./', $key) ? '' : $addOn;
          $wheres .= $addOn.$key.' = '."'".$value."' AND ";
        }
      }
    }
    //like 
    if($likeArray)
    {
      foreach ($likeArray as $key=>$value)
      {
        $addOn = preg_match('/[^A]\./', $key) ? '' : $addOn;
        $wheres .= $addOn.$key.' LIKE '."'%".$value."%' AND ";
      }
    }
    $wheres = preg_replace('/AND\s$/', '', $wheres);
    //order
    if($sortArray)
    {
      $sorts .= ' ORDER BY ';
      foreach ($sortArray as $key=>$value)
      {
        $addOn = preg_match('/[^A]\./', $key) ? '' : $addOn;
        $sorts .= $addOn.$key." ".$value." , ";
      }
      $sorts = substr($sorts, 0,strlen($sorts)-2);
    }
    //语句集合
    $query.=$leftJoin.$wheres.$sorts;
    if(isset($limitStart) && isset($length) && !empty($length)) $query.=' LIMIT '.$limitStart.','.$length.' ';
    $queryObj = self::query($query);
    if ($queryObj === false) return false;
    return $queryObj->fetchAll();
  }
  
  /**
   * 获取一条记录的详细信息
   *
   * @param string $tableName    要查询的表名
   * @param array  $whereArray  where条件数组
   * @param array  $likeArray      like条件数组
   * @param array $columnArray 要取得字段名，不填为*
   * @return array|boolean           成功返回查询结果数组，失败返回false
   */
  public static function getDetail($tableName,$whereArray = array(),$likeArray = array(),$columnArray = array())
  {
    $detail = self::select($tableName,$whereArray,$likeArray,array(),'','',$columnArray);
    return count($detail)>0 ? $detail[0] : false;
  }
  
  /**
   * 取总数
   *
   * @param string $tableName    要查询的表名
   * @param array  $whereArray  where条件数组
   * @param array  $likeArray      like条件数组
   * @return array|boolean           成功返回查询结果，失败返回false
   */
  public static function count($tableName,$whereArray = array(),$likeArray = array())
  {
    $query = 'SELECT ';
    $wheres = $sorts = '';
    //将值设置加引号
    //如果是只获取指定的字段的话
    $columns =  'count(*)';
    $query.=$columns.' FROM '.$tableName.' ';
    //where条件
      if($whereArray)
    {
      $wheres .= ' WHERE ';
      foreach ($whereArray as $key=>$value)
      {
        if(preg_match('/<|>|!/', $key))//大于，小于，不等于情况
        {
          $wheres .= $key."'".$value."' AND ";
        }else 
        {
          $wheres .= $key.' = '."'".$value."' AND ";
        }
      }
    }
    //like
    if($likeArray)
    {
      foreach ($likeArray as $key=>$value)
      {
        $wheres .= $key.' LIKE '."'%".$value."%' AND ";
      }
    }
    $wheres = preg_replace('/AND\s$/', '', $wheres);
    $query.=$wheres.$sorts;
    $queryObj = self::query($query);
    if ($queryObj === false) return false;
    return $queryObj->fetchColumn();
  }
  
  /**
   * 插入数据
   * @param string $table
   * @param array $data
   * @return int|boolean 成功返回最后一条插入的ID，失败返回false
   */
  public static function insert ($table, $data) {
    $keys = $pars = $vals = array();
    foreach ($data as $k => $v) {
      $keys[] = "`{$k}`";
      $pars[] = "?";
      $vals[] = $v;
    }
    $sql = "INSERT INTO `{$table}` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $pars) . ")";
    $rowCount = self::execute($sql, $vals);
    if ($rowCount === false) return false;
    return self::lastInsertId() ? self::lastInsertId() : $rowCount;
  }
  
  /**
   * 更新数据
   * @param string $table
   * @param array  $updateArray
   * @param array  $whereArray
   * @return int|boolean 成功返回受影响的记录数，失败返回false
   */
  public static function update ($table, $updateArray, $whereArray = array()) {
    $kps = $vals = array();
    foreach ($updateArray as $k => $v) {
      if (preg_match('%^\s*@\s*\+\s*(.*)$%', $v, $m)) {
        $kps[] = "`{$k}` = `{$k}` + ?";
        $vals[] = $m[1];
      } else {
        $kps[] = "`{$k}` = ?";
        $vals[] = $v;
      }
    }
    $sql = "UPDATE `{$table}` SET " . implode(', ', $kps);
    $wheres = ' WHERE ';
    foreach ($whereArray as $key=>$value)
    {
      $wheres .= $key.' = '."'".$value."' AND ";
    }
    $wheres = preg_replace('/AND\s$/', '', $wheres);
    $sql .= $wheres;
    return self::execute($sql, $vals);
  }
  
  /**
   * 删除记录
   * @param string $table
   * @param array  $whereArray
   * @return int|boolean 成功返回删除的行数，失败返回false
   */
  public static function delete ($table, $whereArray) {
    $sql = "DELETE FROM `{$table}`";
    $wheres = ' WHERE ';
    foreach ($whereArray as $key=>$value)
    {
      $wheres .= $key.' = '."'".$value."' AND ";
    }
    $wheres = preg_replace('/AND\s$/', '', $wheres);
    $sql .= $wheres;
    return self::execute($sql);
  }
  
  /**
   * 获取PDO连接实例
   *
   * @return PDO
   */
  public static function instance()
  {
    if (!self::$pdo) {
      self::$pdo = new PDO('mysql:host='.self::$dbConfig['hostname'].';dbname='.self::$dbConfig['database'].';', self::$dbConfig['username'], self::$dbConfig['password'], array(
          PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '" . self::$dbConfig['charset'] . "'"
      ));
      // 设置数据库查询返回的列的默认key为字段名F
      self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    return self::$pdo;
  }
  
  
  /**
   * 获取错误信息
   * @return array
   */
  public static function getError () {
    return self::$error;
  }
  
  /**
   * 开始事务
   * @return bool
   */
  public static function beginTrans () {
    return self::instance()->beginTransaction();
  }
  
  /**
   * 提交事务
   * @return bool
   */
  public static function commit () {
    return self::instance()->commit();
  }
  
  /**
   * 回滚事务
   * @return bool
   */
  public static function rollBack () {
    return self::instance()->rollBack();
  }
  
  /**
   * 执行查询
   * @return PDOStatement
   */
  public static function query ($sql, $data = array()) {
    if (!isset(self::$pre[$sql])) { // 未Prepare过
      $pdo = self::instance();
      self::$pre[$sql] = $pdo->prepare($sql);
      if (self::$pre[$sql] === false) { // Prepare失败
        self::$error = $pdo->errorInfo();
        self::$error['sql'] = $sql;
        self::$error['data'] = $data;
        return false;
      }
    }
    if (self::$pre[$sql]->execute($data) === false) { // 执行失败
      self::$error = self::$pre[$sql]->errorInfo();
      self::$error['sql'] = $sql;
      self::$error['data'] = $data;
      return false;
    }
    return self::$pre[$sql];
  }
  

  /**
   * 执行SQL，并返回受影响的条数
   * @param string $sql 要执行的SQL
   * @param array $data SQL中的数据
   * @return int|boolean 成功时返回受影响的行数，失败返回false
   */
  public static function execute ($sql, $data = array()) {
    $query = self::query($sql, $data);
    if ($query === false) return false;
    return $query->rowCount();
  }
  
  /**
   * 执行查询并获取第一行数据的指定列
   * @return array
   */
  public static function fetchColumn ($sql, $data = array(), $num = 0) {
    $query = self::query($sql, $data);
    if ($query === false) return false;
    $rs = $query->fetchColumn($num);
    if ($rs === false) return null;
    return $rs;
  }
  
  /**
   * 执行查询并获取第一行数据
   * @return array
   */
  public static function fetch ($sql, $data = array()) {
    $query = self::query($sql, $data);
    if ($query === false) return false;
    $rs = $query->fetch();
    if ($rs === false) return null;
    return $rs;
  }
  
  /**
   * 执行查询并获取所有数据
   * @return array
   */
  public static function fetchAll ($sql, $data = array()) {
    $query = self::query($sql, $data);
    if ($query === false) return false;
    return $query->fetchAll();
  }
  

  
  /**
   * 获取最后插入的ID
   * @return int
   */
  public static function lastInsertId () {
    $pdo = self::instance();
    return intval($pdo->lastInsertId());
  }
}


?>
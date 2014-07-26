<?php

/**
 * 将状态值转换成对应的文字
 * 
 * @param int      $status                 状态值
 * @param string $statusTextArray 状态键值对数组
 * @return mixed
 */
function showStatusText($status,$statusTextArray)
{
  return $statusTextArray[$status];
}

/**
 * 字符截取
 * 
 * @param string  $string  要截取的字符
 * @param int       $length  截取的长度
 * @param string  $etc       省略字符
 * @return string
 */
function mb_truncate($string, $length = 80, $etc = '..')
{
  if ($length == 0)
    return '';

  if (strlen($string) > $length) {
    $length -= min($length, strlen($etc));
    return mb_substr($string, 0, $length,'utf-8') . $etc;
  } else {
    return $string;
  }
}


?>
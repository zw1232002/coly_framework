<?php
/**
 * 广告主管理Model
 *
 * @author Coly Zhang  <zw1232002@gmail.com>
 * @version $ID 2014-6-19 $
 */
class AdvModel extends BaseModel
{
  
  public function __construct()
  {
    parent::__construct();
    $this->tableName = 'ios_adv';
  }
  
  
  /**
   * (non-PHPdoc)
   * @see BaseModel::detail()
   */
  public function detail($whereArray,$likeArray = array(),$columnArray = array())
  {
    $detail = parent::detail($whereArray,$likeArray);
    $detail['clickNotifyUrl'] = @json_decode($detail['info'])->clickNotifyUrl;
    return $detail;
  }
  
  
  /**
   * 格式化参数，转成每个表格需要的格式
   *
   * @param array     $paramsArray  参数数组
   * @param boolean $isEdit             是否是编辑
   */
  public  function resetParams($paramsArray,$isEdit = false)
  {
    $params = array();
    if(isset($paramsArray['name'])) $params['name'] = $paramsArray['name'];
    if(isset($paramsArray['need_click_callback'])) $params['need_click_callback'] = $paramsArray['need_click_callback'];
    if(isset($paramsArray['clickNotifyUrl']))
    {
      $params['info'] = array('clickNotifyUrl'=>$paramsArray['clickNotifyUrl']);
    }
    if(isset($paramsArray['info']))
    {
      $params['info'] = json_encode(array_merge($params['info'],json_decode($paramsArray['info'],true)));
    }
    if(!isset($paramsArray['create_time']) && $isEdit===false) $params['create_time'] = date('Y-m-d H:i:s');
    if(!isset($paramsArray['update_time']) && $isEdit===true) $params['update_time'] = date('Y-m-d H:i:s');
    return $params;
  }

}


?>
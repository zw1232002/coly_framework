<?php

/**
 * 广告主管理
 * 
 * @author Coly Zhang  <zw1232002@gmail.com>
 * @version $ID 2014-6-19 $
 */
class Adv extends Base
{
  
  public function __construct()
  {
    parent::__construct(__CLASS__);
    $this->load->model(array('advModel'));
    $this->curModuleName = '广告主';
    templateAssign('curModuleName', $this->curModuleName);
  }
  
  
  /**
   * 广告主列表
   */
  public function index()
  {
    $this->privilegeModel->hasPrivilege(__METHOD__);
    templateAssign('curTitle', '广告主列表');
    templateAssignArray($this->advModel->getAllPaged());
    templateAssign('pagination', $this->advModel->getPageLink(SITE_URL.$this->curClassName.'/'));
    template($this->curClassName.'/list');
  }
  
  /**
   * 添加广告主
   */
  public function insert()
  {
    $this->privilegeModel->hasPrivilege(__METHOD__);
    if(Tools::is_post())
    {
      //开始表单验证
      $postArray = parent::validate('insert');
      if($this->advModel->insert($postArray))
      {
        showNotice('添加成功！',1,$this->curModuleBaseUrl);
      }else 
      {
        showNotice('添加失败！',3);
      }
    }else 
    {
      templateAssign('curTitle', '新增广告主');
      template($this->curClassName.'/insert');
    }
  }
  
  /**
   * 编辑广告主
   */
  public function edit()
  {
    $this->privilegeModel->hasPrivilege(__METHOD__);
    //开始表单验证
    $getArray = parent::validate('../common_id');
    templateAssign('curTitle', '修改广告主');
    templateAssignArray($this->advModel->detail($getArray));
    template($this->curClassName.'/edit');
  }
  
  /**
   * 修改广告主（实际执行）
   */
  public function update()
  {
    $this->privilegeModel->hasPrivilege(__METHOD__);
    if(Tools::is_post())
    {
      //开始表单验证
      $postArray = parent::validate(array($this->curClassName.'/insert','common_id'));
      if($this->advModel->update($postArray))
      {
        showNotice('修改成功！',1,$this->curModuleBaseUrl);
      }else
      {
        showNotice('修改失败！',3);
      }
    }else
    {
      templateAssign('curTitle', '修改广告主');
      template($this->curClassName.'/edit');
    }
  }
  
  /**
   * 报表管理
   */
  public function report()
  {
    $this->privilegeModel->hasPrivilege(__METHOD__);
    $defaultDay = date('Y-m-d',strtotime('-15 days'));//默认15天前
    $_GET['startDate'] = isset($_GET['startDate']) ? $_GET['startDate'] : $defaultDay;
    $_GET['endDate']   = isset($_GET['endDate']) ? $_GET['endDate'] : date('Y-m-d');
    //所有广告主
    $allAdvs = $this->advModel->getAll(array(),array(),array(),array('id','name'));
    $_GET['adv_id']   = isset($_GET['adv_id']) ? $_GET['adv_id'] : $allAdvs[0]['id'];
    $_GET['quick_select']   = isset($_GET['quick_select']) ? $_GET['quick_select'] : '过去15天';
    if($_GET)
    {
      //开始表单验证
      $getArray = parent::validate('../common_search');
      templateAssign('adv_id', $getArray['adv_id']);
      templateAssign('startDate', $getArray['startDate']);
      templateAssign('endDate', $getArray['endDate']);
      templateAssign('quick_select', @$getArray['quick_select']);
      templateAssignArray($this->advModel->report($getArray,'adv_id',$getArray['adv_id']));
    }
    //获取广告主
    templateAssign('allAdvs', $allAdvs);
    templateAssign('curTitle', '广告主报表');
    template($this->curClassName.'/report');
  }
  
  
  
  
}

?>
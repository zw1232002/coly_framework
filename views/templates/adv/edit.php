<?php include_once $TEMPLATES_PATH.'funcs.php';?>
<?php include_once $TEMPLATES_PATH.'public/header.php';?>
<?php include_once $TEMPLATES_PATH.'public/nav.php';?>
  <div class="marketing">
    <?php include_once $TEMPLATES_PATH.'public/position.php';?>
    <div class="panel panel-default">
      <div class="panel-heading clearfix"><?php echo $curTitle;?>（带<strong class="text-danger">*</strong>为必填）<a href="<?php echo $curModuleBaseUrl?>" class="pull-right">返回<?php echo $curModuleName?>列表</a></div>
      <div class="inner_panel">
        <form role="form" action="<?php echo $curModuleBaseUrl?>update" method="POST">
          <div class="form-group">
            <label for="exampleInputEmail1">广告主名称<strong class="text-danger">*</strong></label>
            <input type="text" class="form-control" id="exampleInputEmail1" name="name" placeholder="请输入广告主名称" value="<?php echo $name;?>" required>
          </div>
          <div class="form-group ">
            <label style="display: block;">是否需要通知对方点击<strong class="text-danger">*</strong></label>
            <label><input type="radio" name="need_click_callback" id="optionsRadios1" value="1"  <?php if($need_click_callback==1) echo 'checked';?> >是</label>
            <label><input type="radio" name="need_click_callback" id="optionsRadios1" value="0"  <?php if($need_click_callback==0) echo 'checked';?> >否</label>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">点击通知地址<strong class="text-danger">*</strong></label>
            <input type="text" class="form-control" id="" name="clickNotifyUrl" value="<?php echo $clickNotifyUrl;?>" placeholder="收到点击时，通知广告主的点击地址">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1" >附加信息</label>
            <textarea class="form-control" rows="3" name="info"><?php echo $info;?></textarea>
            <span class="help-block">附加信息，JSON格式，非必填</span>
          </div>
          <input type="hidden" value="<?php echo $id?>" name="id">
          <button type="submit" class="btn btn-primary">确认修改</button>
          <button type="button" class="btn btn-default" onclick="javascript:history.back();">返回</button>
        </form>
      </div>
    </div>
  </div>
<?php include_once $TEMPLATES_PATH.'public/footer.php';?>
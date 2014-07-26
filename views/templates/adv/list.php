<?php include_once $TEMPLATES_PATH.'funcs.php';?>
<?php include_once $TEMPLATES_PATH.'public/header.php';?>
<?php include_once $TEMPLATES_PATH.'public/nav.php';?>
  <div class="marketing">
    <?php include_once $TEMPLATES_PATH.'public/position.php';?>
    <div class="panel panel-default">
      <div class="panel-heading clearfix"><?php echo $curTitle;?>（<span class=""><?php echo $count?></span>）<a href="<?php echo $curModuleBaseUrl?>insert" class="pull-right">新增<?php echo $curModuleName?></a></div>
      <div class="table-responsive">
        <table class="table table-bordered  table-hover">
          <tr><th>id</th><th>广告主名称</th><th>是否需要通知对方点击</th><th>创建时间</th><th>修改时间</th><th>操作</th></tr>
          <?php 
            foreach ($item as $value)
            {
              echo '<tr>';
              echo '<td>'.$value['id'].'</td>';
              echo '<td>'.$value['name'].'</td>';
              echo '<td><span class="label label-'.showStatusText($value['need_click_callback'], array(0=>'default',1=>'success')).'">'.showStatusText($value['need_click_callback'], array(0=>'否',1=>'是')).'</span></td>';
              echo '<td>'.$value['create_time'].'</td>';
              echo '<td>'.$value['update_time'].'</td>';
              echo '<td><a href="'.$curModuleBaseUrl.'edit?id='.$value['id'].'" class="btn btn-primary btn-xs" role="button"><span class="glyphicon glyphicon-pencil"></span>&nbsp;编辑</a>&nbsp;&nbsp;<a href="'.$curModuleBaseUrl.'delete?id='.$value['id'].'" onclick="return confirmDelete();" id="" class="btn btn-danger btn-xs" role="button"><span class="glyphicon glyphicon-remove"></span>&nbsp;删除</a></td>';
              echo '</tr>';
            }
          ?>
        </table>
      </div>
    </div>
  </div>
<?php include_once $TEMPLATES_PATH.'public/footer.php';?>
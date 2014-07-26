<?php include_once $TEMPLATES_PATH.'funcs.php';?>
<?php include_once $TEMPLATES_PATH.'public/header.php';?>
<?php include_once $TEMPLATES_PATH.'public/nav.php';?>
<link href="<?php echo $STATIC_PATH;?>js/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="<?php echo $STATIC_PATH;?>js/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
<script src="<?php echo $STATIC_PATH;?>js/bootstrap-datetimepicker-master/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script src="<?php echo $STATIC_PATH;?>js/date.js" charset="UTF-8"></script>
<script src="<?php echo $STATIC_PATH;?>js/report.js" charset="UTF-8"></script>
  <div class="marketing">
    <?php include_once $TEMPLATES_PATH.'public/position.php';?>
    <div class=" search_condition clearfix">
    <form action="" method="GET">
      <div class="pull-left">
        <strong class="text_notice">请选择广告主：</strong>
      </div>
      <div class="col-xs-2">
        <select class="form-control" name="adv_id">
            <?php 
            foreach ($allAdvs as $value)
            {
              $string = $value['id']==$adv_id ? 'selected=selected' : '';
              echo '<option value="'.$value['id'].'" '.$string.'>'.$value['name'].'</option>';
            }
          ?>
          </select>
      </div>
      <div class="pull-left">
        <strong class="text_notice">开始日期：</strong>
      </div>
      <div class="col-xs-2">
        <input type="text" class="form-control" placeholder="开始日期" id="startDate" name="startDate" value="<?php echo $startDate;?>" required>
      </div>
      <div class="pull-left">
        <strong class="text_notice">结束日期：</strong>
      </div>
      <div class="col-xs-2">
        <input type="text" class="form-control" placeholder="结束日期" id="endDate" name="endDate" value="<?php echo $endDate;?>" required>
      </div>
      <div class="btn-group pull-left" id="btn_group">
        <button data-toggle="dropdown" class="btn btn-info dropdown-toggle" type="button" id="dropdown_btn" ><?php if($quick_select){ echo $quick_select;}else{echo '快速选择';}?> <span class="caret"></span></button>
        <ul role="menu" class="dropdown-menu">
          <li><a href="javascript:void(0);" onclick="setDate.call(this,'today');">今日</a></li>
          <li><a href="javascript:void(0);" onclick="setDate.call(this,'yesterday');">昨日</a></li>
          <li><a href="javascript:void(0);" onclick="setDate.call(this,'last7days');">过去7天</a></li>
          <li><a href="javascript:void(0);" onclick="setDate.call(this,'last15days');">过去15天</a></li>
          <li><a href="javascript:void(0);" onclick="setDate.call(this,'last30days');">过去30天</a></li>
          <li><a href="javascript:void(0);" onclick="setDate.call(this,'last60days');">过去60天</a></li>
          <li><a href="javascript:void(0);" onclick="setDate.call(this,'curMonth');">本月</a></li>
          <li><a href="javascript:void(0);" onclick="setDate.call(this,'lastweek');">上周</a></li>
          <li><a href="javascript:void(0);" onclick="setDate.call(this,'lastmonth');">上一月</a></li>
        </ul>
      </div>
      <input type="hidden" name="quick_select" value="<?php echo $quick_select;?>" id="quick_select">
      <button type="submit" class="btn btn-primary pull-left" onclick="return checkForm();">确定</button>
      </form>
    </div>
    <?php if($adv_id){?>
    <div class="panel panel-default">
      <div class="panel-heading clearfix"><?php echo $curTitle;?></div>
      <div class="table-responsive">
        <table class="table table-bordered  table-hover">
          <tr><th>日期</th><th>点击数</th><th>激活数</th><th>激活/点击比率</th><th>金额（元）</th></tr>
          <?php
            foreach ($item as $value)
            {
              echo '<tr>';
              echo '<td>'.$value['date'].'</td>';
              echo '<td>'.($value['click_count'] ? $value['click_count'] : 0).'</td>';
              echo '<td>'.($value['active_count'] ? $value['active_count'] : 0).'</td>';
              echo '<td>'.(100*round($value['active_count']/$value['click_count'],4)).'%'.'</td>';
              echo '<td class="red b">'.($value['revenue']/100).'</td>';
              echo '</tr>';
            }
          ?>
          <tr class="danger"><td>总计：</td><td><?php echo $sum_click;?></td><td><?php echo $sum_active_count;?></td><td><?php echo 100*round($sum_active_count/$sum_click,4).'%';?></td><td><?php echo ($revenue/100);?></td></tr>
        </table>
      </div>
    </div>
    <?php }?>
  </div>
<?php include_once $TEMPLATES_PATH.'public/footer.php';?>
<?php include_once $TEMPLATES_PATH.'funcs.php';?>
<?php include_once $TEMPLATES_PATH.'public/header.php';?>
<?php include_once $TEMPLATES_PATH.'public/nav.php';?>
  <div class="marketing">
    <div class="panel panel-default">
      <div class="inner_panel">
        <div class="alert <?php echo $noticeClass;?>"><?php echo $msg;?></div>
        <div id="">该页面将在<strong id="go_label"><?php echo $seconds;?></strong>秒后跳转</div>
        <div><a href="<?php if($url){echo $url;}else{echo 'javascript:history.back();';};?>">立即跳转</a></div>
      </div>
    </div>
    <script type="text/javascript">
      jQuery(function (){
        var timeLabel = $("#go_label");
        setInterval(function (){
          var curVal = parseInt(timeLabel.html())-1;
          timeLabel.html(curVal);
          if(curVal<=0){
            if("<?php echo $url;?>"){
              location.href="<?php echo $url;?>";
            }else{
              history.back();
            }
          } 
        },1000);
      });
    </script>
  </div>
<?php include_once $TEMPLATES_PATH.'public/footer.php';?>
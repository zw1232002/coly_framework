   <div class="navbar navbar-default navbar-fixed-top " role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo $SITE_URL;?>">茂博IOS API管理平台</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
              <li class="dropdown ">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">广告主管理 <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo $SITE_URL;?>adv/index">广告主列表</a></li>
                  <li><a href="<?php echo $SITE_URL;?>adv/insert">新增广告主</a></li>
                  <li><a href="<?php echo $SITE_URL;?>adv/report">报表管理</a></li>
                </ul>
              </li>
              <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">广告管理 <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo $SITE_URL;?>offer/index">广告列表</a></li>
                  <li><a href="<?php echo $SITE_URL;?>offer/insert">新增广告</a></li>
                  <li><a href="<?php echo $SITE_URL;?>offer/report">报表管理</a></li>
                </ul>
              </li>
              <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">渠道管理 <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo $SITE_URL;?>app/index">渠道列表</a></li>
                  <li><a href="<?php echo $SITE_URL;?>appprice">渠道价格管理</a></li>
                  <li><a href="<?php echo $SITE_URL;?>app/insert">新增渠道</a></li>
                  <li><a href="<?php echo $SITE_URL;?>app/report">报表管理</a></li>
                </ul>
              </li>
              <li class="<?php if($curController=='api') echo 'cur';?>"><a href="<?php echo $SITE_URL;?>api">接口地址生成</a></li>
              <li class="<?php if($curController=='report') echo 'cur';?>"><a href="<?php echo $SITE_URL;?>report">每日报表</a></li>
              <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">查询管理<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo $SITE_URL;?>active">激活查询</a></li>
                  <li><a href="<?php echo $SITE_URL;?>click">点击查询</a></li>
                  <li><a href="<?php echo $SITE_URL;?>platclick">渠道通知点击查询</a></li>
                  <li><a href="<?php echo $SITE_URL;?>click/join">综合查询</a></li>
                </ul>
              </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><span class="show_text">欢迎回来 ：<strong><?php echo $login_user_name;?></strong></span></li>
            <li class="dropdown">
              <a data-toggle="dropdown" class="dropdown-toggle" href="#">用户管理<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo $SITE_URL;?>user/index">用户管理</a></li>
                <li><a href="<?php echo $SITE_URL;?>group/index">用户组管理</a></li>
                <li><a href="<?php echo $SITE_URL;?>privilege/index">权限管理</a></li>
              </ul>
            </li>
            <li class=""><a href="<?php echo $SITE_URL;?>user/loginOut">退出登录</a></li>
          </ul>
        </div><!--/.nav-collapse -->
    </div>
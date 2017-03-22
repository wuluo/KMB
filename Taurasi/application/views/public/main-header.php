<header class="main-header">
    <!-- Logo -->
    <a href="../../index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>Tas</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Taurasi</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!--<li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">你有4条新消息</li>
              <li>
                <ul class="menu">
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="/static/manage/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        测试组
                        <small><i class="fa fa-clock-o"></i> 5 分钟前</small>
                      </h4>
                      <p>为什么不做工具化测试?</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">查看全部</a></li>
            </ul>
          </li>-->
          <!-- 通知 -->
          <!--<li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">你有10条通知</li>
              <li>
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 今日新增5个注册用户
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">查看全部</a></li>
            </ul>
          </li>-->
          <!-- 用户账户 -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="/static/manage/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo Session::instance()->get('username', null); ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="/static/manage/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                <?php echo Session::instance()->get('username', null); ?> - <?php echo Session::instance()->get('title', null); ?>
                  <small>最新登录时间：<?php echo date("Y-m-d H:i",Session::instance()->get('logintime', null)); ?></small>
                </p>
              </li>
              <!--<li class="user-body">
                <div class="row">
                  <div class="col-xs-6 text-center">
                    <a href="#">粉丝</a>
                  </div>
                  <div class="col-xs-6 text-center">
                    <a href="#">好友</a>
                  </div>
                </div>
              </li>-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="https://github.com/wujing0508" class="btn btn-default btn-flat">作者主页</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo URL::site('author/logout'); ?>" class="btn btn-default btn-flat">退出登录</a>
                </div>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </nav>
  </header>
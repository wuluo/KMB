<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>KManager</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="/static/manage/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/static/manage/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="/static/manage/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/static/manage/dist/css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="/static/manage/dist/css/skins/_all-skins.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="/static/manage/html5shiv.min.js"></script>
  <script src="/static/manage/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
<!--引入顶部栏文件-->
<header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>KM</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>KManager</b></span>
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
          <!-- 用户账户 -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              <img src="/static/manage/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?=$userinfo['title'];?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="/static/manage/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
					<?=$userinfo['username'];?> - <?=$userinfo['title'];?>
                  <small>最新登录时间：<?=$userinfo['logintime'];?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <!--<li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
              </li>-->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="https://github.com/wuluo" class="btn btn-default btn-flat">GitHub主页</a>
                </div>
                <div class="pull-right">
                  <a href="/author/logout" class="btn btn-default btn-flat">退出登录</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
<!-- 引入左侧菜单栏 -->
<aside class="main-sidebar">
    <section class="sidebar">
      <ul class="sidebar-menu">
        <?php foreach ($menus as $k=>$v){ ?>
        <li class="treeview <?php if(!empty($v["child"])){ foreach ($v['child'] as $k2=>$v2){ if($v2['uri'] == $nowUrl){ echo " active";}}} ?>">
          <a href="javascript:;">
            <i class="fa <?=$v['icon']?>"></i> <span><?=$v['name']?></span>
              <?php if(!empty($v['child'])){ ?>
                  <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                  </span>
              <?php } ?>
          </a>
          <?php if(!empty($v['child'])){ ?>
          <ul class="treeview-menu">
            <?php foreach ($v['child'] as $k1=>$v1){ ?>
              <?php if($v1['uri'] == $nowUrl){?>
            <li class="active">
              <?php }else{ ?>
            <li>
            <?php } ?>
              <a href="<?=$v1['uri']?>"><i class="fa <?=$v1['icon']?>"></i><?=$v1['name']?></a>
            </li>
            <?php } ?>
          </ul>
		  <?php } ?>
        </li>
        <?php } ?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
<div class="content-wrapper">
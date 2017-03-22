<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Taurasi管理系统</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="/static/manage/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/static/manage/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="/static/manage/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/static/manage/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="/static/manage/plugins/iCheck/square/blue.css">
  <link rel="stylesheet" href="/static/manage/localcss/login.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="/static/manage/html5shiv.min.js"></script>
  <script src="/static/manage/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Taurasi管理系统</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"><?php
		$env = $_SERVER['ENVIRONMENT'];
		if(strtolower($env) == 'development') {
			echo "当前环境：开发环境";
		} elseif(strtolower($env) == 'staging') {
			echo "当前环境：测试环境";
		} elseif(strtolower($env) == 'production') {
			echo "当前环境：生产环境";
		}
		?></p>
      <form action="<?php echo URL::site('author/login'); ?>" method="post">
      <!--<div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">邮箱：</label>
        <div class="col-sm-10">
          <input type="email" class="form-control input-lg" id="inputEmail3" placeholder="邮箱">
        </div>
      </div>-->
      <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">账号：</label>
          <div class="col-sm-10">
              <input type="username" class="form-control input-lg" id="username" name="username" placeholder="用户名">
          </div>
      </div>
      <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">密码：</label>
        <div class="col-sm-10">
          <input type="password" class="form-control input-lg" id="password" name="password" placeholder="密码">
        </div>
      </div>

      <div class="row" style="margin-top:30px;">
        <div class="col-xs-8" style="padding-left:30px;">
          <div class="checkbox icheck">
            <label>
              <!--<input type="checkbox" name="checkbox">&nbsp;&nbsp;&nbsp;&nbsp;记住密码-->
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4" style="padding-right:30px;">
          <button type="submit" name="submit" class="btn-lg btn-primary btn-block">登&nbsp;&nbsp;&nbsp;&nbsp;录</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="/static/manage/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/static/manage/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="/static/manage/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>

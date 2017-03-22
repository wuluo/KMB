<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Taurasi</title>
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
<?php include Kohana::find_file('views', 'public/main-header') ?>
<!-- 引入左侧菜单栏 -->
<?php include Kohana::find_file('views', 'public/main-sidebar') ?>
<!-- =============================================== -->
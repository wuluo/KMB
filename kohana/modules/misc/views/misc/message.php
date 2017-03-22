<!DOCTYPE html>
<html lang="zh-CN">
<head>
<title>Gome+ GVS</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="/resource/css/bootstrap.css" />
<link rel="stylesheet" href="/resource/css/bootstrap-theme.css" />
<script type="text/javascript" src="/resource/scripts/jquery/jquery.js"></script>
<script type="text/javascript" src="/resource/scripts/jquery/jquery.ui.js"></script>
<script type="text/javascript" src="/resource/scripts/jquery/jquery.Jcrop.js"></script>
<script type="text/javascript" src="/resource/scripts/jquery/jquery.form.js"></script>
<script type="text/javascript" src="/resource/scripts/jquery/jquery.json.js"></script>
<script type="text/javascript" src="/resource/scripts/jquery/jquery.fancybox.js"></script>

<script type="text/javascript" src="/resource/scripts/bootstrap/bootstrap.js"></script>
<script type="text/javascript" src="/resource/scripts/common/common.js"></script>
<script type="text/javascript" src="/resource/scripts/common/swfobject.js"></script>
<script type="text/javascript" src="/resource/scripts/common/form.js"></script>
</head>
<body>
<br/><br/><br/><br/><br/>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="col-md-4 col-md-offset-4">
      <div class="panel panel-default">
        <div class="panel-body center">
          <h3><?php echo $message; ?></h3>
          <p></p>
          <a class="btn btn-warning btn-big"  href="<?php echo URL::site($redirect); ?>"> 返回… </a>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">setTimeout('(function(uri) {location.href = uri;})("<?php echo URL::site($redirect); ?>")', <?php echo $delay * 1000; ?>);</script>
</body>
</html>

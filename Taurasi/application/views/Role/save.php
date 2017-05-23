<?php include Kohana::find_file('views', 'public/header') ?>
  <section class="content-header">
    <h1>
      添加角色
    </h1>
    <ol class="breadcrumb">
      <li><a href="/admin/blog/index"><i class="fa fa-dashboard"></i>角色管理</a></li>
      <li class="active">添加角色</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-primary">
      <form class="" role="form">
        <div class="box-body">
          <div class="form-group">
            <div class="row">
            <div class="col-md-1 text-center">
              <label>角色名称</label>
            </div>
            <div class="col-md-6">
              <input type="text" class="form-control" id="">
            </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-1 text-center">
                <label>角色描述</label>
              </div>
              <div class="col-md-6">
                <textarea class="form-control" rows="3" placeholder="Enter ..."></textarea>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-1 control-label">状态</label>
            <div class="col-sm-2">
              <label>
                <input type="radio" name="r3" class="flat-red" checked> 开启
              </label>
            </div>
            <div class="col-sm-2">
              <label>
                <input type="radio" name="r3" class="flat-red"> 关闭
              </label>
            </div>
            <span class="col-sm-6"></span>
          </div>
        </div>
        <div class="box-footer">
          <button type="submit" class="btn btn-primary">保存</button>
        </div>
      </form>
    </div>
  </section>
<?php include Kohana::find_file('views', 'public/footer') ?>

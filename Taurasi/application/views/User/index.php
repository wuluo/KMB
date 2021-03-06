<?php include Kohana::find_file('views', 'public/header') ?>
  <section class="content-header">
    <h1>
      用户管理
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>用户管理</a></li>
      <li class="active">用户列表</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <div class="col-md-4">
          <a class="btn btn-success btn-flat" href="/user/add">
            <i class="fa fa-plus"></i> 添加用户
          </a>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <a class="btn btn-info btn-flat" href="/user/save">
            <i class="fa fa-plus"></i> 添加管理员
          </a>
        </div>
        <div class="col-md-4">

        </div>
        <div class="col-md-4">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="请输入用户名……">
            <span class="input-group-btn">
            <button type="button" class="btn btn-info btn-flat"><i class="fa fa-fw fa-search"></i>&nbsp;搜索</button>
          </span>
          </div></div>
      </div>
    </div>
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">前台用户</a></li>
        <li><a href="#tab_2" data-toggle="tab">管理员管理</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <table id="example1" class="table table-bordered table-hover text-center ">
            <thead>
            <tr class="gradeX">
              <th>用户ID</th>
              <th>用户名</th>
              <th>昵称</th>
              <th>真实姓名</th>
              <th>状态</th>
              <th>邮箱</th>
              <th>电话</th>
              <th>注册时间</th>
              <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr align="center">
              <td>1</td>
              <td>JavaScript闭包应用示例……</td>
              <td>默认分类</td>
              <th>吴璟</th>
              <th>正常</th>
              <th>wujing01@gomeplus.com</th>
              <th>15120070265</th>
              <td>2017-04-25 10:25:13</td>
              <td>
                <a class="btn btn-xs btn-success btn-flat" href="/admin/blog/save?id=1">
                  <i class="fa fa-edit"></i> 编辑
                </a>&nbsp;&nbsp;
                <a class="btn btn-xs bg-maroon btn-flat" href="/admin/blog/info?id=1">
                  <i class="fa fa-check"></i> 启用
                </a>&nbsp;&nbsp;
                <a class="btn btn-xs bg-orange btn-flat" href="/admin/blog/info?id=1">
                  <i class="fa fa-close"></i> 拉黑
                </a>
              </td>
            </tr>
            </tbody>
          </table>
          <div class="row">
            <div class="col-sm-12">
              <div class="dataTables_paginate paging_simple_numbers">
                <ul class="pagination">
                  <li class="paginate_button previous disabled"><a href="#" aria-controls="example1" data-dt-idx="0" tabindex="0">Previous</a></li>
                  <li class="paginate_button active"><a href="#" aria-controls="example1" data-dt-idx="1" tabindex="0">1</a></li>
                  <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="2" tabindex="0">2</a></li>
                  <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="3" tabindex="0">3</a></li>
                  <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="4" tabindex="0">4</a></li>
                  <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="5" tabindex="0">5</a></li>
                  <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="6" tabindex="0">6</a></li>
                  <li class="paginate_button next" id="example1_next"><a href="#" aria-controls="example1" data-dt-idx="7" tabindex="0">Next</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab_2">
          <table id="example2" class="table table-bordered table-hover text-center ">
            <thead>
            <tr class="gradeX">
              <th>ID</th>
              <th>用户名</th>
              <th>昵称</th>
              <th>真实姓名</th>
              <th>邮箱</th>
              <th>电话</th>
              <th>角色</th>
              <th>注册时间</th>
              <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr align="center">
              <td>1</td>
              <td>JavaScript闭包应用示例……</td>
              <td>默认分类</td>
              <th>吴璟</th>
              <th>wujing01@gomeplus.com</th>
              <th>15120070265</th>
              <th>普通管理员</th>
              <td>2017-04-25 10:25:13</td>
              <td>
                <a class="btn btn-xs bg-maroon btn-flat" href="/admin/blog/info?id=1">
                  <i class="fa fa-check"></i> 启用
                </a>&nbsp;&nbsp;
                <a class="btn btn-xs bg-orange btn-flat" href="/admin/blog/info?id=1">
                  <i class="fa fa-close"></i> 拉黑
                </a>
                <a class="btn btn-xs btn-danger btn-flat" onclick="">
                  <i class="fa fa-trash-o"></i> 删除
                </a>
              </td>
            </tr>
            </tbody>
          </table>
          <div class="row">
            <div class="col-sm-12">
              <div class="dataTables_paginate paging_simple_numbers">
                <ul class="pagination">
                  <li class="paginate_button previous disabled"><a href="#" aria-controls="example1" data-dt-idx="0" tabindex="0">Previous</a></li>
                  <li class="paginate_button active"><a href="#" aria-controls="example1" data-dt-idx="1" tabindex="0">1</a></li>
                  <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="2" tabindex="0">2</a></li>
                  <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="3" tabindex="0">3</a></li>
                  <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="4" tabindex="0">4</a></li>
                  <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="5" tabindex="0">5</a></li>
                  <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="6" tabindex="0">6</a></li>
                  <li class="paginate_button next" id="example1_next"><a href="#" aria-controls="example1" data-dt-idx="7" tabindex="0">Next</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php include Kohana::find_file('views', 'public/footer') ?>

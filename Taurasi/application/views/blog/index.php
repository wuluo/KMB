<?php include Kohana::find_file('views', 'public/header') ?>
  <section class="content-header">
    <h1>
      博文列表
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>博文管理</a></li>
      <li class="active">博文列表</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <div class="col-md-4">
          <a class="btn btn-success btn-flat" href="/admin/blog/save">
            <i class="fa fa-plus"></i> 发布博客
          </a>
        </div>
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
          <div class="input-group">
            <input type="text" class="form-control">
            <span class="input-group-btn">
            <button type="button" class="btn btn-info btn-flat"><i class="fa fa-fw fa-search"></i>&nbsp;搜博文</button>
          </span>
          </div></div>
      </div>
    </div>
    <div class="box box-primary">
      <div class="box-body">
        <div class="row">
          <table id="example1" class="table table-bordered table-hover text-center ">
            <thead>
            <tr class="gradeX">
              <th>博文ID</th>
              <th>标题</th>
              <th>分类</th>
              <th>作者</th>
              <th>状态</th>
              <th>更新时间</th>
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
              <td>2017-04-25 10:25:13</td>
              <td>
                <a class="btn btn-xs btn-success btn-flat" href="/admin/blog/save?id=1">
                  <i class="fa fa-edit"></i> 编辑
                </a>&nbsp;&nbsp;
                <a class="btn btn-xs btn-info btn-flat" href="/admin/blog/info?id=1">
                  <i class="fa fa-television"></i> 预览
                </a>&nbsp;&nbsp;
                <a class="btn btn-xs btn-danger btn-flat" onclick="">
                  <i class="fa fa-trash-o"></i> 删除
                </a>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
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
  </section>
<?php include Kohana::find_file('views', 'public/footer') ?>

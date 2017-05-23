<?php include Kohana::find_file('views', 'public/header') ?>
  <section class="content-header">
    <h1>
      博客管理
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>博客管理</a></li>
      <li class="active">分类列表</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <div class="col-md-4">
          <a class="btn btn-success btn-flat" data-toggle="modal" data-target="#myModal">
            <i class="fa fa-plus"></i> 添加分类
          </a>
        </div>
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
        <div class="input-group">
          <input type="text" class="form-control">
          <span class="input-group-btn">
            <button type="button" class="btn btn-info btn-flat"><i class="fa fa-fw fa-search"></i>&nbsp;搜分类</button>
          </span>
        </div></div>
      </div>
    </div>
    <div class="box box-primary">
      <div class="box-body">
        <div class="row">
        <table id="example1" class="table table-bordered table-hover text-center">
          <thead>
          <tr>
            <th>分类编号</th>
            <th>分类名称</th>
            <th>分类类型</th>
            <th>更新时间</th>
            <th>操作</th>
          </tr>
          </thead>
          <tbody>
          <tr align="center">
            <td>1</td>
            <td>默认分类</td>
            <td>博文</td>
            <td>2017-04-25 10:25:13</td>
            <td>
              <a class="">
                <i class="fa fa-edit"></i> 编辑
              </a>&nbsp;&nbsp;
              <a class="">
                <i class="fa fa-television"></i> 预览
              </a>&nbsp;&nbsp;
              <a class="">
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
    <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">添加分类</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <div class="row">
                <div class="col-md-2 text-center center-block">
                  <label>分类名称</label>
                </div>
                <div class="col-md-6">
                  <input type="text" class="form-control" id="">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left btn-flat" data-dismiss="modal">关闭</button>
            <button type="button" class="btn btn-primary btn-flat">保存</button>
          </div>
        </div>
      </div>
    </div>
  </section>

<script type="text/javascript">
    $(function () {
        $('[data-toggle="popover"]').popover()
    })
</script>
<?php include Kohana::find_file('views', 'public/footer') ?>

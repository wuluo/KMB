<?php include Kohana::find_file('views', 'public/header') ?>
<section class="content-header">
    <h1>
      系统日志
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>系统</a></li>
      <li class="active">系统日志</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <div class="col-md-4">
        <div class="input-group">
          <div class="input-group-btn">
             <button type="button" class="btn btn-default disabled">开始时间</button>
          </div>
          <input type="text" class="form-control">
        </div>
        </div>
        <div class="col-md-4">
        <div class="input-group">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default disabled">结束时间</button>
            </div>
            <input type="text" class="form-control">
        </div></div>
        <div class="col-md-4">
        <div class="input-group">
          <input type="text" class="form-control">
          <span class="input-group-btn">
            <button type="button" class="btn btn-info btn-flat"><i class="fa fa-fw fa-search"></i>&nbsp;搜日志</button>
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
            <th>Rendering engine</th>
            <th>Browser</th>
            <th>Platform(s)</th>
            <th>Engine version</th>
            <th>CSS grade</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>Trident</td>
            <td>Internet
              Explorer 4.0
            </td>
            <td>Win 95+</td>
            <td> 4</td>
            <td>X</td>
          </tr>
          <tr>
            <td>Trident</td>
            <td>Internet
              Explorer 5.0
            </td>
            <td>Win 95+</td>
            <td>5</td>
            <td>C</td>
          </tr>
          <tr>
            <td>Gecko</td>
            <td>Netscape 7.2</td>
            <td>Win 95+ / Mac OS 8.6-9.2</td>
            <td>1.7</td>
            <td>A</td>
          </tr>
          <tr>
            <td>Gecko</td>
            <td>Seamonkey 1.1</td>
            <td>Win 98+ / OSX.2+</td>
            <td>1.8</td>
            <td>A</td>
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

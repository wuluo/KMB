<?php include Kohana::find_file('views', 'public/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
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
      <div class="box-header">     
      </div>
      <div class="box-body">
        <div class="form-group col-sm-4">
          <label class="col-sm-4 control-label">起始时间:</label>
          <div class="input-group col-sm-8">
            <div class="input-group-addon">
              <i class="fa fa-clock-o"></i>
            </div>
            <input type="text" class="form-control pull-right" id="reservationtime">
          </div>
        </div>
        <div class="form-group col-sm-4">
          <label class="col-sm-4 control-label">截止时间:</label>
          <div class="input-group col-sm-8">
            <div class="input-group-addon">
              <i class="fa fa-clock-o"></i>
            </div>
            <input type="text" class="form-control pull-right" id="reservationtime">
          </div>
        </div>
        <div class="input-group col-sm-4">
          <input type="text" class="form-control">
          <span class="input-group-btn">
            <button type="button" class="btn btn-info btn-flat"><i class="fa fa-fw fa-search"></i>&nbsp;搜日志</button>
          </span>
        </div>
      </div>
    </div>
    <div class="box box-primary">
      <div class="box-body">
        <div class="row">
        <table id="example1" class="table table-bordered">
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
  <!-- /.content -->
</div>
<?php include Kohana::find_file('views', 'public/footer') ?>
<script>
  $(function () {
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });
  });
</script>

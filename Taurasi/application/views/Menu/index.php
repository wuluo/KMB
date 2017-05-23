<?php include Kohana::find_file('views', 'public/header') ?>
    <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      菜单管理
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>控制面板</a></li>
      <li class="active">菜单管理</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
          <a class="btn btn-success btn-flat" href="/admin/blog/save">
            <i class="fa fa-plus"></i> 添加菜单
          </a>
      </div>
    </div>
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">前台菜单</a></li>
        <li><a href="#tab_2" data-toggle="tab">后台菜单</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <table id="table1" class="table table-bordered table-hover text-center ">
            <thead>
            <tr class="gradeX">
              <th>ID</th>
              <th>菜单名</th>
              <th>URL</th>
              <th>上级菜单</th>
              <th>状态</th>
              <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($fmenus as $k=>$v){ ?>
            <tr align="center">
              <td><?=$v['id']?></td>
              <td><?=$v['name']?></td>
              <td><?=$v['uri']?></td>
              <th><?=$v['parent_name']?></th>
              <th><?php if($v['status'] == 1){echo "启用";}else{echo "禁用";} ?></th>
              <td>
                <a class="btn btn-xs btn-success btn-flat" href="/menu/save?id=<?=$v['id']?>">
                  <i class="fa fa-edit"></i> 编辑
                </a>&nbsp;&nbsp;
				  <?php if($v['status'] == 1){?>
                <a class="btn btn-xs bg-orange btn-flat" href="/menu/off?id=<?=$v['id']?>">
                  <i class="fa fa-close"></i> 禁用
                </a>
                <?php }else{ ?>
                <a class="btn btn-xs bg-maroon btn-flat" href="/menu/on?id=<?=$v['id']?>">
                  <i class="fa fa-check"></i> 启用
                </a>
                <?php } ?>
              </td>
            </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="tab-pane" id="tab_2">
          <table id="table2" class="table table-bordered table-hover text-center ">
            <thead>
            <tr class="gradeX">
              <th>ID</th>
              <th>菜单名</th>
              <th>图标class</th>
              <th>URL</th>
              <th>上级菜单</th>
              <th>状态</th>
              <th>操作</th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ($amenus as $key=>$val){ ?>
                <tr align="center">
                    <td><?=$val['id']?></td>
                    <td><?=$val['name']?></td>
                    <td><?=$val['icon']?></td>
                    <td><?=$val['uri']?></td>
                    <th><?=$val['parent_name']?></th>
                    <th><?php if($val['status'] == 1){echo "启用";}else{echo "禁用";} ?></th>
                    <td>
                        <a class="btn btn-xs btn-success btn-flat" href="/menu/save?id=<?=$val['id']?>">
                            <i class="fa fa-edit"></i> 编辑
                        </a>&nbsp;&nbsp;
						<?php if($val['status'] == 1){?>
                            <a class="btn btn-xs bg-orange btn-flat" href="/menu/off?id=<?=$val['id']?>">
                                <i class="fa fa-close"></i> 禁用
                            </a>
						<?php }else{ ?>
                            <a class="btn btn-xs bg-maroon btn-flat" href="/menu/on?id=<?=$val['id']?>">
                                <i class="fa fa-check"></i> 启用
                            </a>
						<?php } ?>
                    </td>
                </tr>
			<?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
<?php include Kohana::find_file('views', 'public/footer') ?>

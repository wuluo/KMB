<aside class="main-sidebar">
    <section class="sidebar">
      <ul class="sidebar-menu">
        <li class="treeview active">
          <a href="javascript:;">
            <i class="fa fa-dashboard"></i> <span>系统</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="active"><a href="<?php echo URL::site('index/index/'); ?>"><i class="fa fa-circle-o"></i>系统设置</a></li>
            <li><a href="<?php echo URL::site('log/index/'); ?>"><i class="fa fa-circle-o"></i>系统日志</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-files-o"></i>
            <span>一个数字标识</span>
            <span class="pull-right-container">
              <span class="label label-primary pull-right">4</span>
            </span>
          </a>
          <ul class="treeview-menu">
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-share"></i> <span>多级菜单</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-circle-o"></i> 一级 </a></li>
            <li>
              <a href="#"><i class="fa fa-circle-o"></i> 一级
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-circle-o"></i> 二级 </a></li>
                <li>
                  <a href="#"><i class="fa fa-circle-o"></i> 二级 
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> 三级 </a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> 三级 </a></li>
                  </ul>
                </li>
              </ul>
            </li>
            <li><a href="#"><i class="fa fa-circle-o"></i> 一级 </a></li>
          </ul>
        </li>
        <li><a href="#"><i class="fa fa-book"></i> <span>操作文档</span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
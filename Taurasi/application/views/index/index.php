<?php include Kohana::find_file('views', 'public/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      系统设置
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>系统</a></li>
      <li class="active">系统设置</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_1" data-toggle="tab">网站信息</a></li>
          <li><a href="#tab_2" data-toggle="tab">SEO设置</a></li>
          <li><a href="#tab_3" data-toggle="tab">CDN设置</a></li>
          <li><a href="#tab_4" data-toggle="tab">邮件设置</a></li>
          <li><a href="#tab_5" data-toggle="tab">短信设置</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_1">
            <div class="box box-info">
              <div class="box-header">
            </div>
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">网站名称</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="网站名称" value="通用后台管理系统">
                  </div>
                  <span class="col-sm-5">网站名称不能为空</span>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">站长邮箱</label>
                  <div class="col-sm-5">
                    <input type="email" class="form-control" id="inputEmail3" placeholder="站长邮箱" value="">
                  </div>
                  <span class="col-sm-5">站长邮箱不能为空</span>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">备案信息</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="备案信息" value="">
                  </div>
                  <span class="col-sm-5">备案信息不能为空</span>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">统计代码</label>
                  <div class="col-sm-5">
                    <textarea class="form-control" rows="3" placeholder="Enter ..."></textarea>
                  </div>
                  <span class="col-sm-5">统计代码不能为空</span>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">版权信息</label>
                  <div class="col-sm-5">
                    <textarea class="form-control" rows="3" placeholder="Enter ..."></textarea>
                  </div>
                  <span class="col-sm-5">版权信息不能为空</span>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-2">
                  <button type="submit" class="btn btn-block btn-info">保存</button>
                </div>
                <div class="col-sm-1">
                </div>
                <div class="col-sm-2">
                  <button type="submit" class="btn btn-block btn-default pull-right">取消</button>
                </div>
                <div class="col-sm-5">
                </div>
              </div>
              <!-- /.box-footer -->
              </form>
            </div>
          </div>
          <div class="tab-pane" id="tab_2">
            <div class="box box-info">
              <div class="box-header">
            </div>
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">SEO标题</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="SEO标题" value="通用后台管理系统">
                  </div>
                  <span class="col-sm-5">SEO标题不能为空</span>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">SEO关键字</label>
                  <div class="col-sm-5">
                    <input type="email" class="form-control" id="inputEmail3" placeholder="SEO关键字" value="">
                  </div>
                  <span class="col-sm-5">SEO关键字不能为空</span>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">SEO描述</label>
                  <div class="col-sm-5">
                    <textarea class="form-control" rows="3" placeholder="Enter ..."></textarea>
                  </div>
                  <span class="col-sm-5">SEO描述不能为空</span>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-2">
                  <button type="submit" class="btn btn-block btn-info">保存</button>
                </div>
                <div class="col-sm-1">
                </div>
                <div class="col-sm-2">
                  <button type="submit" class="btn btn-block btn-default pull-right">取消</button>
                </div>
                <div class="col-sm-5">
                </div>
              </div>
              <!-- /.box-footer -->
              </form>
            </div>
          </div>
          <div class="tab-pane" id="tab_3">
            <div class="box box-info">
              <div class="box-header">
            </div>
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">静态资源CDN地址</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="SEO标题" value="通用后台管理系统">
                  </div>
                  <span class="col-sm-5"></span>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <div class="col-sm-8">
                    <span>不能以/结尾；设置这个地址后，请将ThinkCMF下的静态资源文件放在其下面；
ThinkCMF下的静态资源文件大致包含以下(如果你自定义后，请自行增加)：
admin/themes/simplebootx/Public/assets
public
themes/simplebootx/Public/assets
例如未设置cdn前：jquery的访问地址是/public/js/jquery.js, 设置cdn是后它的访问地址就是：静态资源cdn地址+/public/js/jquery.js</span>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-2">
                  <button type="submit" class="btn btn-block btn-info">保存</button>
                </div>
                <div class="col-sm-1">
                </div>
                <div class="col-sm-2">
                  <button type="submit" class="btn btn-block btn-default pull-right">取消</button>
                </div>
                <div class="col-sm-5">
                </div>
              </div>
              <!-- /.box-footer -->
              </form>
            </div> 
          </div>
          <div class="tab-pane" id="tab_4">
            <div class="box box-info">
              <div class="box-header">
            </div>
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">邮件发送模式</label>
                  <div class="col-sm-5">
                    <select class="form-control">
                      <option>SMTP</option>
                      <option>POP3</option>
                    </select>
                  </div>
                  <span class="col-sm-5"></span>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">SMTP服务器</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="SMTP服务器" value="">
                  </div>
                  <span class="col-sm-5"></span>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">SMTP端口</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="SMTP端口" value="">
                  </div>
                  <span class="col-sm-5"></span>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">邮箱账号</label>
                  <div class="col-sm-5">
                    <input type="email" class="form-control" id="inputEmail3" placeholder="邮箱账号" value="">
                  </div>
                  <span class="col-sm-5"></span>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">邮箱密码</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="邮箱密码" value="">
                  </div>
                  <span class="col-sm-5"></span>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">收件箱地址</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="收件箱地址" value="">
                  </div>
                  <span class="col-sm-5"></span>
                </div>
                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-2">
                  <button type="submit" class="btn btn-block btn-info">保存</button>
                </div>
                <div class="col-sm-1">
                </div>
                <div class="col-sm-2">
                  <button type="submit" class="btn btn-block btn-default pull-right">取消</button>
                </div>
                <div class="col-sm-5">
                </div>
              </div>
              <!-- /.box-footer -->
              </form>
            </div>
          </div>
          <div class="tab-pane" id="tab_5">
            <div class="box box-info">
              <div class="box-header">
            </div>
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">是否开启</label>
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
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">接口地址</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="接口地址" value="接口地址">
                  </div>
                  <span class="col-sm-5"></span>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">内容编码</label>
                  <div class="col-sm-5">
                    <select class="form-control">
                      <option>UTF-8</option>
                      <option>GB2312</option>
                    </select>
                  </div>
                  <span class="col-sm-5"></span>
                </div>
                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">接收号码参数名</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="接收号码参数名" value="">
                  </div>
                  <span class="col-sm-5"></span>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">多号码分隔符</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="多号码分隔符" value="">
                  </div>
                  <span class="col-sm-5"></span>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">短信内容参数名 </label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="短信内容参数名  " value="">
                  </div>
                  <span class="col-sm-5"></span>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">其他参数</label>
                  <div class="col-sm-5">
                    <input type="email" class="form-control" id="inputEmail3" placeholder="邮箱密码" value="">
                  </div>
                  <span class="col-sm-5"></span>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-2">
                  <button type="submit" class="btn btn-block btn-info">保存</button>
                </div>
                <div class="col-sm-1">
                </div>
                <div class="col-sm-2">
                  <button type="submit" class="btn btn-block btn-default pull-right">取消</button>
                </div>
                <div class="col-sm-5">
                </div>
              </div>
              <!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
        <!-- /.tab-content -->
      </div>
    </div>
    
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include Kohana::find_file('views', 'public/footer') ?>

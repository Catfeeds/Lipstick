<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<script src="/addons/hc_pdd/template/js/color.js"></script>
<ul class="nav nav-tabs">
	<li><a href="<?php  echo $this->createWebUrl('setting')?>">基础设置</a></li>
	<li><a href="<?php  echo $this->createWebUrl('guanzhu')?>">关注设置</a></li>
	<li><a href="<?php  echo $this->createWebUrl('shenhe')?>">审核设置</a></li>
	<li class="active"><a href="<?php  echo $this->createWebUrl('ad')?>">流量主</a></li>
	<li><a href="<?php  echo $this->createWebUrl('poster')?>">海报设置</a></li>
	<li><a href="<?php  echo $this->createWebUrl('message')?>">模板消息</a></li>
	<li><a href="<?php  echo $this->createWebUrl('signin')?>">签到设置</a></li>
</ul>
<div class="clearfixcon">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" id="form1" style="display: block">
		<div class="panel panel-default con" id="tab3">
			<div class="panel-heading">
				流量主设置
			</div>
			<div class="panel-body">
				<div class="tab-pane active" id="tab_param2">
				        <div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">首页流量主ID</label>
							<div class="col-sm-8">
								<input type="text" name="adunit" class="form-control" value="<?php  echo $info['adunit'];?>">								
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">商品详情流量主ID</label>
							<div class="col-sm-8">
								<input type="text" name="adunit2" class="form-control" value="<?php  echo $info['adunit2'];?>">								
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">个人中心流量主ID</label>
							<div class="col-sm-8">
								<input type="text" name="adunit3" class="form-control" value="<?php  echo $info['adunit3'];?>">								
							</div>
						</div>                   
				</div>
			</div>
		</div>
		<div class="form-group col-sm-12">
			<input name="submit" type="submit" value="提交" class="btn btn-primary col-lg-1" <?php  if($update_status == 1) { ?>disabled<?php  } ?>>
			<input type="hidden" name="act" value="add" />
		</div>
	</form>
</div>


<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
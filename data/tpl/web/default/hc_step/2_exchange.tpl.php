<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li class="active"><a href="<?php  echo $this->createWebUrl('exchange')?>">商品兑换记录</a></li>
    <!--
	<li><a href="<?php  echo $this->createWebUrl('win_exchange')?>">奖品兑换记录</a></li>
	<li><a href="<?php  echo $this->createWebUrl('coin_exchange')?>">步数兑换记录</a></li>
    <li><a href="<?php  echo $this->createWebUrl('xuni')?>">虚拟兑换记录</a></li>
    <li><a href="<?php  echo $this->createWebUrl('xuni_post')?>">添加虚拟兑换记录</a></li>
    -->
</ul>
<div class="panel panel-default">
        <div class="panel-heading">筛选</div>
        <div class="panel-body">
            <form action="" method="post" class="form-horizontal" role="form" id="form">
                <div class="form-group">
                    <div class="col-md-4 mathyts" >
                       <select  name="order_status" class="form-control form-control_s" >                        
                           <option value="1" <?php  if($order_status==1) { ?>selected<?php  } ?>>UID</option>
                           <option value="2" <?php  if($order_status==2) { ?>selected<?php  } ?>>兑换人</option>
                           <option value="3" <?php  if($order_status==3) { ?>selected<?php  } ?>>手机号</option>
                       </select>
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="keyword" id="keyword" value="<?php  echo $keyword;?>">
                    </div>
                    <div class="pull-right col-md-2">
                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
                    </div>
                </div>
            </form>
        </div>
</div>
<div class="panel panel-default">
    <div class="panel-body">
       <form action="" method="post" class="form-horizontal form">
       <div class="alert alert-info url_div hide" role="alert">
       </div>
            <input type="hidden" name="storeid" value="">
            <div class="table-responsive panel-body">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th>id</th>
                        <th>uid</th>
                        <th>昵称</th>
                        <th>兑换人</th>
                        <th>头像</th>
                        <th>省</th>
                        <th>市</th>
                        <th>区</th>
                        <th>详细地址</th>
                        <th>邮编</th>
                        <th>联系方式</th>
                        <th>商品名</th>
                        <th>兑换时间</th>
                        <th>支付方式</th>
                        <th>状态</th>
                        <th style="text-align:right;">操作</th>
                    </tr>
                    </thead>
                    <tbody id="level-list">
                    <?php  if(is_array($list)) { foreach($list as $item) { ?>
                    <tr>
                         <td><div class="type-parent"><?php  echo $item['id'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['user_id'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['nick_name'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['userName'];?></div></td>
                         <td><div class="type-parent"><img src="<?php  echo tomedia($item['head_pic']);?>" width="50" height="50" /></div></td>
                         <td><div class="type-parent"><?php  echo $item['provinceName'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['cityName'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['countyName'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['detailInfo'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['nationalCode'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['telNumber'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['goods_name'];?></div></td>
                         <td><div class="type-parent"><?php  echo $item['time'];?></div></td>
                         <td>
                         <?php  if($item['type'] == 0) { ?>
                            <span class="label label-primary">原价购买</span>
                         <?php  } else if($item['type'] ==1) { ?>
                            <span class="label label-primary">币加钱</span>
                         <?php  } else if($item['type'] ==2) { ?>
                            <span class="label label-primary">步数币</span>
                         <?php  } else { ?>
                            <span class="label label-primary">邀请好友</span>
                         <?php  } ?>
                         </td>
                         <td>
                         <?php  if($item['status'] == 1) { ?>
                            <span class="label label-success">已发货</span>
                            <?php  } else { ?>
                            <span class="label label-default">待发货</span>
                         <?php  } ?>
                         </td>
                         <td style="text-align:right;">
                             <a class="btn btn-primary btn-sm" href="<?php  echo $this->createWebUrl('fahuo', array('op' => 'shangpin','id' => $item['id']))?>">发货</a>
                         </td>
                    </tr>
                    <?php  } } ?>
                    <?php  if(empty($list) ) { ?>
	                <tr ng-if="!wechats">
	                <td colspan="15" class="text-center">暂无数据</td>
	                </tr>
	                <?php  } ?>
                    <tr>
                    <td colspan="15" style="text-align:right"><?php  echo $page;?></td>
                    </tr>     
                    </tbody>
                </table> 
            </div>
        </form>
    </div>
</div>
<style type="text/css">
    .hide{display: none}
</style>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
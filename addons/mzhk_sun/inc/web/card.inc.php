<?php

global $_GPC, $_W;

$GLOBALS['frames'] = $this->getMainMenu();
$where=" WHERE   lid=4 and  uniacid=".$_W['uniacid'];
if($_GPC['keywords']){
    $keywords=$_GPC['keywords'];
    $where.=" and gname LIKE  '%$keywords%'";
}
if($_GPC['status']){
    $where.=" and status={$_GPC['status']} ";

}
if(!empty($_GPC['time'])){
    $start=strtotime($_GPC['time']['start']);
    $end=strtotime($_GPC['time']['end']);
    $where.=" and ime >={$start} and time<={$end}";

}
$status=$_GPC['status'];

$type=isset($_GPC['type'])?$_GPC['type']:'all';
$data[':uniacid']=$_W['uniacid'];

$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$sql="select * from " . tablename("mzhk_sun_goods") ." ".$where." order by gid desc ";
$total=pdo_fetchcolumn("select count(*) as wname from " . tablename("mzhk_sun_goods") . " " .$where." order by gid desc ",$data);
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list=pdo_fetchall($select_sql,$data);
$pager = pagination($total, $pageindex, $pagesize);

if($_GPC['op']=='delete'){

    $res=pdo_delete('mzhk_sun_goods',array('gid'=>$_GPC['gid'],'uniacid'=>$_W['uniacid']));

    if($res){
        message('删除成功！', $this->createWebUrl('card'), 'success');
    }else{
        message('删除失败！','','error');
    }
}
if($_GPC['op']=='open'){
    $res=pdo_update('mzhk_sun_goods',array('isshelf'=>intval($_GPC["isshelf"])),array('gid'=>$_GPC['gid'],'uniacid'=>$_W['uniacid']));
    if($res){
        message('成功！', $this->createWebUrl('card'), 'success');
    }else{
        message('失败！','','error');
    }
}
if($_GPC['op']=='tg'){
    $res=pdo_update('mzhk_sun_goods',array('status'=>2),array('gid'=>$_GPC['gid'],'uniacid'=>$_W['uniacid']));
    if($res){
        message('通过成功！', $this->createWebUrl('card'), 'success');
    }else{
        message('通过失败！','','error');
    }
}
if($_GPC['op']=='jj'){
    $res=pdo_update('mzhk_sun_goods',array('status'=>3),array('gid'=>$_GPC['gid'],'uniacid'=>$_W['uniacid']));
    if($res){
        message('拒绝成功！', $this->createWebUrl('card'), 'success');
    }else{
        message('拒绝失败！','','error');
    }
}


include $this->template('web/card');
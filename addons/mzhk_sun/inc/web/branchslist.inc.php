<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();

$info = pdo_getall('mzhk_sun_branch',array('uniacid'=>$_W['uniacid']));

global $_W, $_GPC;

if($_GPC['op']=='delete'){

        $res=pdo_delete('mzhk_sun_branch',array('id'=>$_GPC['id']));
        if($res){
            message('操作成功',$this->createWebUrl('branchslist',array()),'success');
        }else{
            message('操作失败','','error');
        }

}
if($_GPC['op']=='change'){
    $res=pdo_update('mzhk_sun_branch',array('stutes'=>$_GPC['stutes']),array('id'=>$_GPC['id']));
    if($res){
        message('操作成功',$this->createWebUrl('branchslist',array()),'success');
    }else{
        message('操作失败','','error');
    }
}

include $this->template('web/branchslist');
<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$info=pdo_get('mzhk_sun_banner',array('uniacid'=>$_W['uniacid']));
if($info['lb_imgs']){
    $lb_imgs = $info['lb_imgs'];
}
if($info['lb_imgs1']){
    $lb_imgs1 = $info['lb_imgs1'];
}
if($info['lb_imgs2']){
    $lb_imgs2 = $info['lb_imgs2'];
}
if($info['lb_imgs3']){
    $lb_imgs3 = $info['lb_imgs3'];
}
if(checksubmit('submit')){

    $data['bname']=$_GPC['bname'];

    $data['uniacid']=$_W['uniacid'];

    $data['bname1']=$_GPC['bname1'];


    $data['bname2']=$_GPC['bname2'];


    $data['bname3']=$_GPC['bname3'];

    $data['lb_imgs']=$_GPC['lb_imgs'];
    $data['lb_imgs2']=$_GPC['lb_imgs2'];
    $data['lb_imgs1']=$_GPC['lb_imgs1'];
    $data['lb_imgs3']=$_GPC['lb_imgs3'];



    if($_GPC['id']==''){
        $res=pdo_insert('mzhk_sun_tbbanner',$data,array('uniacid'=>$_W['uniacid']));


        if($res){
            message('添加成功',$this->createWebUrl('tbbanner',array()),'success');
        }else{
            message('添加失败','','error');
        }

    }else{

        $res = pdo_update('mzhk_sun_tbbanner', $data, array('id' => $_GPC['id'],'uniacid'=>$_W['uniacid']));

        if($res){
            message('编辑成功',$this->createWebUrl('tbbanner',array()),'success');
        }else{
            message('编辑失败','','error');
        }
    }
}
include $this->template('web/dsabanner');
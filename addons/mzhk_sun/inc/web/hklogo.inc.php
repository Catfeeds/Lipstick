<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$item=pdo_get('mzhk_sun_system',array('uniacid'=>$_W['uniacid']));

if(checksubmit('submit')){


    $data['hk_tubiao']=$_GPC['hk_tubiao'];
    $data['uniacid']=$_W['uniacid'];
    $data['hk_logo']=$_GPC['hk_logo'];
    $data['hk_bgimg']=$_GPC['hk_bgimg'];
    $data['hk_namecolor']=$_GPC['hk_namecolor'];
    $data['hk_userrules']=html_entity_decode($_GPC['hk_userrules']);
    $data['hk_mytitle']=$_GPC['hk_mytitle'];
    $data['hk_mybgimg']=$_GPC['hk_mybgimg'];

    if($_GPC['id']==''){
        $res=pdo_insert('mzhk_sun_system',$data);
        if($res){
            message('添加成功',$this->createWebUrl('hklogo',array()),'success');
        }else{
            message('添加失败','','error');
        }
    }else{
        $res = pdo_update('mzhk_sun_system', $data, array('id' => $_GPC['id']));
        if($res){
            message('编辑成功',$this->createWebUrl('hklogo',array()),'success');
        }else{
            message('编辑失败','','error');
        }
    }
}


include $this->template('web/hklogo');
<?php

global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();

$uniacid = $_W['uniacid'];

if($_GPC['op']=='tobegroup'){
    $orderid = intval($_GPC['orderid']);
    $o_data["is_ok"] = 1;
    $g_data["status"] = 4;
    $res_o=pdo_update('mzhk_sun_ptorders',$o_data,array('id'=>$orderid));
    $res=pdo_update('mzhk_sun_ptgroups',$g_data,array('order_id'=>$orderid,'status'=>3));
    if($res){
        message('设置成团成功！', $this->createWebUrl('orderinfo'), 'success');
    }else{
        message('设置成团失败！','','error');
    }
}

if($_GPC['op']=='sendgoods'){
    $id = intval($_GPC['id']);
    $uniacid = $_W['uniacid'];
    $sincetype = intval($_GPC['sincetype']);
    if($sincetype==3){
        $shipname = $_GPC['shipname'];
        $shipnum = $_GPC['shipnum'];
        if(empty($shipname) || empty($shipnum)){
            message('快递名称或者快递单号不能为空！','','error');
        }
        $sg_data["shipname"] = $shipname;
        $sg_data["shipnum"] = $shipnum;
    }
    $sg_data["status"] = 6;
    $sg_data["shiptime"] = time();

    $res=pdo_update('mzhk_sun_ptgroups',$sg_data,array('id'=>$id));
    if($res){
        message('发货成功！', $this->createWebUrl('orderinfo'), 'success');
    }else{
        message('发货失败！','','error');
    }
}

if($_GPC['op']=='send'){
    $id = intval($_GPC['id']);
    $uniacid = $_W['uniacid'];
    
    $order = pdo_get('mzhk_sun_ptgroups',array('uniacid'=>$uniacid,'id'=>$id),array("money","bid","gid","groupordernum","status"));
    if($order["status"]==5){
        message('该订单已经是完成状态，不需要在完成订单！','','error');
    }
    $res=pdo_update('mzhk_sun_ptgroups',array('status'=>5,"finishtime"=>time()),array('id'=>$id));
    if($res){
        $bid = intval($order['bid']);
        if($bid==0){
            $goods = pdo_get('mzhk_sun_goods',array('uniacid'=>$uniacid,'gid'=>$order['gid']),array("bid"));
            $bid = intval($goods['bid']);
        }
        
        $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$uniacid,'bid'=>$bid),array("totalamount","frozenamount","bname"));
        

        
        $branddata = array();
        $branddata["totalamount"] = $brand["totalamount"]+$order["money"];
        pdo_update('mzhk_sun_brand', $branddata, array('bid' => $bid));
        
        $data = array();
        $data["bid"] = $bid;
        $data["bname"] = $brand['bname'];
        $data["mcd_type"] = 1;
        $data["mcd_memo"] = "拼团订单-订单id：".$id.";订单号：".$order["groupordernum"]."；";
        $data["addtime"] = time();
        $data["money"] = $order["money"];
        $data["order_id"] = $id;
        $data["uniacid"] = $uniacid;
        $data["status"] = 1;
        pdo_insert('mzhk_sun_mercapdetails', $data);

        message('设置成功！', $this->createWebUrl('orderinfo'), 'success');
    }else{
        message('设置失败！','','error');
    }
}

if($_GPC['op']=='refund'){
    $id = intval($_GPC['id']);
    $uniacid = $_W['uniacid'];
    $isrefund = intval($_GPC['isrefund']);
    if($isrefund==3){
        $ress = pdo_update('mzhk_sun_ptgroups',array("isrefund ="=>3),array('id'=>$id,'uniacid'=>$uniacid));
        if($ress){
            message('拒绝成功！', $this->createWebUrl('orderinfo'), 'success');
        }else{
            message('拒绝失败！','','error');
        }
    }

    
    $order = pdo_get('mzhk_sun_ptgroups',array('uniacid'=>$uniacid,'id'=>$id),array("money","out_trade_no","out_refund_no","gid","paytype","openid"));
    
    if($order["paytype"]==2){
        $money = $order['money'];
        
        $res_user = pdo_update('mzhk_sun_user', array('money +=' => $money), array('openid' => $order['openid']));
        if($res_user){
            $memo = "拼团订单退款，订单id：".$id;
            
            $data = array();
            $data["openid"] = $order['openid'];
            $data["order_id"] = $id;
            $data["money"] = $money;
            $data["addtime"] = time();
            $data["rtype"] = 4;
            $data["memo"] = $memo;
            $data["uniacid"] = $uniacid;
            $res=pdo_insert('mzhk_sun_rechargelogo',$data);
            $result['result_code'] = 'SUCCESS';
        }else{
            $result['result_code'] = 'ERROR';
        }
    }else{

        
        include_once IA_ROOT . '/addons/mzhk_sun/cert/WxPay.Api.php';
        load()->model('account');
        load()->func('communication');
        
        $res=pdo_get('mzhk_sun_system',array('uniacid'=>$uniacid));
        $appid=$res['appid'];
        $wxkey=$res['wxkey'];
        $mchid=$res['mchid'];
        $path_cert = IA_ROOT . "/addons/mzhk_sun/cert/".$res['apiclient_cert'];
        $path_key = IA_ROOT . "/addons/mzhk_sun/cert/".$res['apiclient_key'];
        $out_trade_no=$order['out_trade_no'];
        $fee = $order['money'] * 100;
        $out_refund_no = $order['out_refund_no']?$order['out_trade_no']:$mchid.rand(100,999).time().rand(1000,9999);
        $WxPayApi = new WxPayApi();
        $input = new WxPayRefund();
        $input->SetAppid($appid);
        $input->SetMch_id($mchid);
        $input->SetOp_user_id($mchid);
        $input->SetRefund_fee($fee);
        $input->SetTotal_fee($fee);
        $input->SetOut_refund_no($out_refund_no);
        $input->SetOut_trade_no($out_trade_no);
        $result = $WxPayApi->refund($input, 6, $path_cert, $path_key, $wxkey);
    }
    
    if ($result['result_code'] == 'SUCCESS') {
        pdo_update('mzhk_sun_goods',array("num +="=>1),array('gid'=>$order['gid'],'uniacid'=>$uniacid));
        pdo_update('mzhk_sun_ptgroups',array("isrefund ="=>2,"out_refund_no ="=>$out_refund_no),array('id'=>$id,'uniacid'=>$uniacid));
        message('退款成功！', $this->createWebUrl('orderinfo'), 'success');
    }else{
        pdo_update('mzhk_sun_ptgroups',array("out_refund_no ="=>$out_refund_no),array('id'=>$id,'uniacid'=>$uniacid));
        if($order["paytype"]==2){
            message('退款失败！','error');
        }else{
            message('退款失败！微信'.$result["err_code_des"],'','error');
        }
    }
}

$where = " WHERE  a.uniacid=".$_W['uniacid'];
if(!empty($_GPC['keywords'])){
    $keywords=$_GPC['keywords'];
    $where.=" and a.groupordernum LIKE '%$keywords%'";
}
if(!empty($_GPC['telphone'])){
    $telphone=$_GPC['telphone'];
    $where.=" and a.telnumber LIKE '%$telphone%'";
}
if(!empty($_GPC['nametype'])){
    $nametype = $_GPC['nametype'];
    $key_name=$_GPC['key_name'];
    if($nametype=='key_goods'){
        $where.=" and a.gname LIKE '%$key_name%'";
    }elseif($nametype=='key_bname'){
        $where.=" and a.bname LIKE '%$key_name%'";        
    }elseif($nametype=='key_uname'){
        $where.=" and a.name LIKE '%$key_name%'";        
    }
}
if(!empty($_GPC['shiptype'])){
    $shiptype=$_GPC['shiptype'];
    if($shiptype=="到店消费"){
        $where.=" and (a.sincetype LIKE '%$shiptype%' or a.sincetype LIKE '%上门自提%')";
    }else{
        $where.=" and a.sincetype LIKE '%$shiptype%'";
    }
}

if(!empty($_GPC['statustype'])){
    $statustype = intval($_GPC['statustype']);
    if($statustype==91){
        $where.=" and a.isrefund = 1  ";
    }elseif($statustype==92){
        $where.=" and a.isrefund = 2 ";
    }elseif($statustype==93){
        $where.=" and a.isrefund = 3 ";
    }else{
        $where.=" and a.status = $statustype and (a.isrefund=0 or a.isrefund=3)";
    }
}

if(!empty($_GPC['timetype'])){
    $timetype = $_GPC["timetype"];
    $time_start_end = $_GPC["time_start_end"];
    if($time_start_end){
        $time_start_end_arr = explode(" - ",$time_start_end);
        if($time_start_end_arr){
            $starttime = strtotime($time_start_end_arr[0]);
            $endtime = strtotime($time_start_end_arr[1]);
            if($timetype=="key_addtime"){
                $where.=" and a.addtime >= {$starttime} and a.addtime <= {$endtime} ";
            }elseif($timetype=="key_paytime"){
                $where.=" and a.paytime >= {$starttime} and a.paytime <= {$endtime} ";
            }elseif($timetype=="key_finishtime"){
                $where.=" and a.finishtime >= {$starttime} and a.finishtime <= {$endtime} ";
            }
        }
    }
}

$status=$_GPC['status'];
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$type=isset($_GPC['type'])?$_GPC['type']:'all';

if($type=='all'){
   
}else{
    $where.= " and a.status =$status";
}
$sql = "select a.*,b.id as order_id,b.ordernum from ".tablename('mzhk_sun_ptgroups')."a left join ".tablename('mzhk_sun_ptorders')."b on b.id=a.order_id ".$where;


if($_GPC['op']=='exportorder'){
    $select_sql =$sql." order by a.order_id desc,a.id asc ";
    $orderlist=pdo_fetchall($select_sql,$data);
    $export_title_str = "id,订单号,总团单号,地址,电话,名字,金额(元),运费(元),状态,是否退款,配送方式,下单时间,付款时间,发货时间,完成时间,商家名称,商品名称,数量,快递名称,快递单号,备注";
    $export_title = explode(',',$export_title_str);
    $export_list = array(); 
    $status = array("","取消订单","待支付","已支付","已成团","已完成","待收货");
    $refund = array("否","退款申请中","已退款","拒绝退款");
    $i=1;
    foreach ($orderlist as $k => $v){
        $export_list[$k]["k"] = $v["id"];
        $export_list[$k]["groupordernum"] = $v["groupordernum"]."\t";
        $export_list[$k]["ordernum"] = $v["ordernum"]."\t";
        $export_list[$k]["provincename"] = $v["provincename"].$v["cityname"].$v["countyname"].$v["detailinfo"];
        $export_list[$k]["telnumber"] = $v["telnumber"]."\t";
        $export_list[$k]["name"] = $v["name"];
        $export_list[$k]["money"] = $v["money"];
        $export_list[$k]["deliveryfee"] = $v["deliveryfee"];
        $export_list[$k]["status"] = $status[$v["status"]];
        $export_list[$k]["isrefund"] = $refund[$v["isrefund"]];
        $export_list[$k]["sincetype"] = $v["sincetype"];
        $export_list[$k]["addtime"] = $v["addtime"]?date("Y-m-d H:i:s",$v["addtime"])."\t":" ";
        $export_list[$k]["paytime"] = $v["paytime"]?date("Y-m-d H:i:s",$v["paytime"])."\t":" ";
        $export_list[$k]["shiptime"] = $v["shiptime"]?date("Y-m-d H:i:s",$v["shiptime"])."\t":" ";
        $export_list[$k]["finishtime"] = $v["finishtime"]?date("Y-m-d H:i:s",$v["finishtime"])."\t":" ";
        $export_list[$k]["bname"] = $v["bname"];
        $export_list[$k]["gname"] = $v["gname"];
        $export_list[$k]["num"] = $v["num"];
        $export_list[$k]["shipname"] = $v["shipname"];
        $export_list[$k]["shipnum"] = $v["shipnum"]."\t";
        $export_list[$k]["uremark"] = $v["uremark"];
        $i++;
    } 
    $exporttitle = "拼团订单";

    exportToExcel($exporttitle.'_'.date("YmdHis").'.csv',$export_title,$export_list);
    exit;
}
//本!!模!!块!!来!!自!!说*图&谱&源&*码
$select_sql =$sql." order by a.order_id desc,a.id asc LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$lits=pdo_fetchall($select_sql,$data);
foreach ($lits as $key => $value) {
    if($value["bname"]==''){
        $brand = pdo_fetch("select b.bname from ".tablename('mzhk_sun_brand')." as b left join ".tablename('mzhk_sun_goods')." as g on g.bid=b.bid where g.gid=".intval($value["gid"]));
        $lits[$key]["bname"] = $brand["bname"];
    }
}
$total=pdo_fetchcolumn("select count(*) as wname from ".tablename('mzhk_sun_ptgroups')."a left join ".tablename('mzhk_sun_ptorders')."b on b.id=a.order_id ".$where,$data);
$pager = pagination($total, $pageindex, $pagesize);
if($_GPC['op']=='delete'){
    $res=pdo_delete('mzhk_sun_ptgroups',array('id'=>$_GPC['id']));
    if($res){
        message('删除成功！', $this->createWebUrl('orderinfo'), 'success');
    }else{
        message('删除失败！','','error');
    }
}

include $this->template('web/orderinfo');
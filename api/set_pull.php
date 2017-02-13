<?
//error_reporting(0);
include_once('connect.php');
/*$_REQUEST['data'] = '{"user_id":1,"order_id":"3c3d17d6-30ba-4057-a2e6-90840f069b34","req_time":"2016-04-12 13:59:30","data":[{"item_id":1,"qty":1},{"item_id":8,"qty":10},{"item_id":7,"qty":10}]}';*/

if($_REQUEST['data']!=''){
	$arr = json_decode($_REQUEST['data']);
//print_r( $arr);
//if pull hit again
$res = mysql_query("select * from req_table where order_id = '".$arr->order_id."'");
if(mysql_num_rows($res)>0){
		while($user = mysql_fetch_assoc($res)){
			//echo "update inventory set qty = qty+".$user['qty'].",updated=now() where id = ".$user['item_id']."";
			mysql_query("update inventory set qty = qty+".$user['qty'].",updated=now() where id = ".$user['item_id']."");
		}
		mysql_query("delete from req_table where order_id = '".$arr->order_id."'");
}

//if request time out
$res = mysql_query("select * from req_table where created < DATE_ADD(now(),INTERVAL - 1 minute)");
if(mysql_num_rows($res)>0){
		while($user = mysql_fetch_assoc($res)){
			$res = mysql_query("update inventory set qty = qty+".$user['qty'].",updated=now() where id = ".$user['item_id']."");
		}
		mysql_query("delete from req_table where created < DATE_ADD(now(),INTERVAL - 1 minute)");
}
	$asd = $arr->data;
	$notfound = 0;
	foreach($asd as $val){
	//echo( $val->item_id.' and '.$val->qty);
		$res = mysql_query("select id,qty,if(qty>=".$val->qty.",1,0) as isavailable from inventory where id = '".$val->item_id."'");
		$user = mysql_fetch_assoc($res);
		$row[] = $user;
		
		if($user['isavailable']==0){
			$notfound++;
		}
	}
	if($notfound)
	{
		$r = array('status'=>200,'data'=>$row,'msg'=>'All requested items not available. Please fill correct quantity.');
		//echo 'do nothing';
	}else{
		//echo 'less data';
		foreach($asd as $val){
			$res = mysql_query("update inventory set qty=if(qty-".$val->qty.">=0,qty-".$val->qty.",qty),updated=if(1,now(),updated) where id = '".$val->item_id."'");
			$res = mysql_query("insert into req_table set order_id = '".$arr->order_id."', item_id = '".$val->item_id."', qty = '".$val->qty."', `user` = '".$arr->user_id."', req_time = '".$arr->req_time."',created = now() ");
		}
		$r = array('status'=>200,'data'=>$row,'msg'=>'All requested items are locked in your cart now. your cart will expire within one minute.');
	}
/*
	$res = mysql_query("update inventory set qty=if(qty-".$val->qty.">0,qty-".$val->qty.",qty) where id = '".$val->item_id."'");
	//$res = mysql_query("update inventory set qty = qty-".$val->qty." where id = '".$val->item_id."'");
	$res = mysql_query("insert into req_table set order_id = '".$arr->order_id."', item_id = '".$val->item_id."', qty = '".$val->qty."', user = '".$arr->user_id."', req_time = '".$arr->req_time."',created = now() ");
}
	$res = mysql_query("select req_table.item_id,inventory.qty as available,req_table.qty as reuested from req_table left join inventory on req_table.item_id=inventory.id where order_id = '".$arr->order_id."'");
	while($user = mysql_fetch_assoc($res)){
			$row[] = $user;		*/	
		

}
		
		echo json_encode($r);
?>
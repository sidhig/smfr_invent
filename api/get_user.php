<?
error_reporting(0);
include_once('connect.php');
/*
$res = mysql_query("select * from req_table where created < DATE_ADD(now(),INTERVAL - 1 minute)");
if(mysql_num_rows($res)>0){
		while($user = mysql_fetch_assoc($res)){
			$res = mysql_query("update inventory set qty = qty+".$user['qty'].",updated=now() where id = ".$user['id']."");
		}
		mysql_query("delete from req_table where created < DATE_ADD(now(),INTERVAL - 1 minute)");
}
*/

if($_REQUEST['last_updated']==''){
	$res = mysql_query("select * from tbl_user ");
}else{
	$res = mysql_query("select * from tbl_user where updated > '".$_REQUEST['last_updated']."'");
}
	if(mysql_num_rows($res)>0){
		while($user = mysql_fetch_assoc($res)){
			$row[] = $user;
		}
		$r = array('status'=>200,'data'=>$row);
	}else{
		$r = array('status'=>400,'data'=>'Item not found');
	}
echo json_encode($r);

?>
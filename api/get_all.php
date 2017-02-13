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
	$invent = mysql_query("select * from inventory ");
	$user = mysql_query("select * from tbl_user ");
	$log = mysql_query("select * from log ");
}else{
	$invent = mysql_query("select * from inventory where updated > '".$_REQUEST['last_updated']."'");
	$user = mysql_query("select * from tbl_user where updated > '".$_REQUEST['last_updated']."'");
	$log = mysql_query("select * from log where created > '".$_REQUEST['last_updated']."'");
}
	$type = mysql_query("select * from tbl_type order by `name`");
	$size = mysql_query("select * from tbl_size order by `name`");
$row_invent = array();
$row_user = array();
$row_log = array();
$row_type = array();
$row_size = array();
if(mysql_num_rows($invent)>0){
		while($invent_row = mysql_fetch_assoc($invent)){
			$row_invent[] = $invent_row;
		}
}
if(mysql_num_rows($user)>0){
		while($user_row = mysql_fetch_assoc($user)){
			$row_user[] = $user_row;
		}
}
if(mysql_num_rows($log)>0){
		while($log_row = mysql_fetch_assoc($log)){
			$row_log[] = $log_row;
		}
}
if(mysql_num_rows($type)>0){
		while($type_row = mysql_fetch_assoc($type)){
			$row_type[] = $type_row;
		}
}
if(mysql_num_rows($size)>0){
		while($size_row = mysql_fetch_assoc($size)){
			$row_size[] = $size_row;
		}
}

		$r = array('status'=>200,'date'=> mysql_fetch_assoc(mysql_query("select now()"))['now()'],'invent'=>$row_invent,'user'=>$row_user,'log'=>$row_log,'size'=>$row_size,'type'=>$row_type);

/*	
	}else{
		$r = array('status'=>400,'data'=>'Item not found');
	}*/
echo json_encode($r);

?>
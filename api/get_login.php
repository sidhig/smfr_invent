<?
error_reporting(0);
if($_REQUEST['username']!='' && $_REQUEST['password']!='' ){
include_once('connect.php');
$res = mysql_query("select * from tbl_user where (username='".$_REQUEST['username']."' and password='".md5($_REQUEST['password'])."') or (email='".$_REQUEST['username']."' and password='".md5($_REQUEST['password'])."')");
	if(mysql_num_rows($res)>0){
		$user = mysql_fetch_assoc($res);
		mysql_query("update tbl_user set 
				email='".$_REQUEST['email']."',
				phone='".$_REQUEST['phone']."',
				deviceid='".$_REQUEST['deviceid']."',
				pushid='".$_REQUEST['pushid']."',
				updated='".$_REQUEST['updated']."'
				where id='".$user['id']."'");
		$r = array('status'=>200,'reorder'=>12,'data'=>mysql_fetch_assoc(mysql_query("select * from tbl_user where id='".$user['id']."'")));
	}else{
		$r = array('status'=>400,'data'=>'username with this password not found');
	}
echo json_encode($r);
}else{
	$r = array('status'=>400,'data'=>'username and password can not be blank');
echo json_encode($r);
}





?>
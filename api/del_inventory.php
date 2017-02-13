<?
error_reporting(0);
include_once('connect.php');


if(!empty($_REQUEST)){

if($_REQUEST['id']!=''){
	$res = mysql_query("select * from inventory where id = '".$_POST['id']."'");
	if(mysql_num_rows($res)>0){
		$row = mysql_fetch_assoc($res);
		mysql_query("insert into `log` set `type` ='3', order_id='0',item_id='".$_POST['id']."',user_id='".$_POST['user_id']."',qty='".$row['qty']."',station='',`engine`='',fname='',lname='',deviceType='mobile',req_time=now(),created=now()");
		 //unlink('../'.$row['img_url']);
		  mysql_query("update inventory set isactive = '0',qty='0',updated=now() where id = '".$_POST['id']."'");
		  $r = array('status'=>200,'data'=>'Inventory Deleted successfully.');	
	}else{
		$r = array('status'=>400,'data'=>'Inventory not exist for this id.');	
		}
}
else{ 
	$r = array('status'=>400,'data'=>'Id must be filled');
}
echo json_encode($r);
}
?>
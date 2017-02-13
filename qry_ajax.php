<?
session_start();
include_once'connect.php';

// error_reporting(0);
//  $user_name = "root";
// $password = "";
// $database = "smfr_invent";
// $server = "localhost";

// $db_handle = mysql_connect($server, $user_name, $password);
// $db_found = mysql_select_db($database, $db_handle);
//if pull hit again
/*$res = mysql_query("select * from req_table where order_id = '".$_POST['order_id']."'");
if(mysql_num_rows($res)>0){
		while($user = mysql_fetch_assoc($res)){
			//echo "update inventory set qty = qty+".$user['qty'].",updated=now() where id = ".$user['item_id']."";
			mysql_query("update inventory set qty = qty+".$user['qty'].",updated=now() where id = ".$user['item_id']."");
		}
		mysql_query("delete from req_table where order_id = '".$_POST['order_id']."'");
}
*/
//if request time out
/*
$res = mysql_query("select * from req_table where created < DATE_ADD(now(),INTERVAL - 1 minute)");
if(mysql_num_rows($res)>0){
		while($user = mysql_fetch_assoc($res)){
			$res = mysql_query("update inventory set qty = qty+".$user['qty'].",updated=now() where id = ".$user['item_id']."");
		}
		mysql_query("delete from req_table where created < DATE_ADD(now(),INTERVAL - 1 minute)");
}	*/

	$res = $conn->query("select id,qty,if(qty>=".$_POST['qty'].",1,0) as isavailable from inventory where id = '".$_POST['item_id']."'");
	$user =$res->fetch_object();
		if($user->isavailable==0){
			echo ('Requested item not available. Please fill correct quantity.');
		}else{

		//echo 'less data';
		$res = $conn->query("update inventory set qty=if(qty-".$_POST['qty'].">=0,qty-".$_POST['qty'].",qty),updated=if(1,now(),updated) where id = '".$_POST['item_id']."'");

		$res = $conn->query("insert into req_table set 
								order_id = '".$_POST['order_id']."', 
								item_id = '".$_POST['item_id']."', 
								qty = '".$_POST['qty']."', 
								`user` = '".$_SESSION['LOGIN_USERID']."', 
								req_time = '".$_POST['req_time']."',
								from_web = 1,
								created = now()");
		echo ('Requested item locked in your cart now. your cart will expire within one minute.');
	}

       //header('location:order_detail.php');
          //unset($_POST);
?>
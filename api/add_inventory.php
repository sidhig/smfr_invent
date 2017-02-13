<?
//error_reporting(0);
include_once('connect.php');

$res = mysql_query("select * from req_table where created < DATE_ADD(now(),INTERVAL - 1 minute)");
if(mysql_num_rows($res)>0){
		while($user = mysql_fetch_assoc($res)){
			$res = mysql_query("update inventory set qty = qty+".$user['qty']." where id = ".$user['id']."");
		}
		mysql_query("delete from req_table where created < DATE_ADD(now(),INTERVAL - 1 minute)");
}

if(!empty($_REQUEST)){
	if($_FILES['uploadimg']['name']!=''){
 $target_path  = "../uploads/";
//$imageFileType = pathinfo($_FILES["uploadimg"]["name"],PATHINFO_EXTENSION);

$image = (explode(".",basename( $_FILES['uploadimg']['name'])));

$target_path = $target_path .time().'.'.$image[1];
	}
	else{ $target_path='';
	}
if($_REQUEST['id']==''){
$res = mysql_query("select * from inventory where description = '".$_POST['desc']."' and size = '".$_POST['size']."'");
if(mysql_num_rows($res)>0){
	$row = mysql_fetch_assoc($res);
		if($row['isactive']==0){
			if($_FILES['uploadimg']['tmp_name']!=''){
				 if(move_uploaded_file($_FILES['uploadimg']['tmp_name'], $target_path)){
					unlink('../'.$row['img_url']);
				 $imgqry = " img_url = '".trim(trim($target_path,'.'),'/')."', ";
				 }
			}else{
		$forimg =  mysql_fetch_assoc(mysql_query("select * from inventory where description = '".$_POST['desc']."' and img_url!=''"));
			$imgqry = " img_url = '".$forimg['img_url']."', ";
			}
			$res = mysql_query("update inventory set       
                              cost = '".$_POST['cost']."',
							  reorder = '".$_POST['reorder']."',
                              invent_type = '".$_POST['invent_type']."',
                              qty = '".$_POST['qty']."',".$imgqry."
                              user = '".$_POST['userid']."',
                              isactive = '1',
							  updated = now() where description = '".$_POST['desc']."' and size = '".$_POST['size']."'");
			$r = array('status'=>200,'data'=>mysql_fetch_assoc(mysql_query("select * from inventory where description = '".$_POST['desc']."' and size = '".$_POST['size']."'")),'msg'=>'Inventory Activated successfully');
		}else{
			$r = array('status'=>400,'msg'=>'Inventory already exist with this Description and size.');
		}
}else{
			 
          move_uploaded_file($_FILES['uploadimg']['tmp_name'], $target_path);
		
	$res = mysql_query("insert into inventory set                            
                              size = '".$_POST['size']."',
                              cost = '".$_POST['cost']."',
                              description = '".$_POST['desc']."',
                              invent_type = '".$_POST['invent_type']."',
                              qty = '".$_POST['qty']."',
							  reorder = '".$_POST['reorder']."',
                              img_url = '".trim(trim($target_path,'.'),'/')."',
                              user = '".$_POST['userid']."',
                              isactive = '1',
							  updated = now(),
                              created = now()");
		$inserted_id= mysql_insert_id();
	$log_res = mysql_query("insert into `log` set 
							  `type` = '1',
							  order_id='0',
							  item_id = '".$inserted_id."',
							  qty = '".$_POST['qty']."',
							  user_id = '".$_POST['userid']."',
							  deviceType = 'Mobile',
							  created = now()");
	$r = array('status'=>200,'data'=>mysql_fetch_assoc(mysql_query("select * from inventory where id='".$inserted_id."'")),'msg'=>'Inventory added successfully');
	}
}else{
	if($_FILES['uploadimg']['tmp_name']!=''){
		 if(move_uploaded_file($_FILES['uploadimg']['tmp_name'], $target_path)){
			$res = mysql_fetch_assoc(mysql_query("select img_url from inventory where id='".$_REQUEST['id']."'"));
			unlink('../'.$res['img_url']);
		 $imgqry = " img_url = '".trim(trim($target_path,'.'),'/')."', ";
		 }
	}else{
		$forimg =  mysql_fetch_assoc(mysql_query("select * from inventory where description = '".$_POST['desc']."' and img_url!=''"));
			$imgqry = " img_url = '".$forimg['img_url']."', ";
			}
	$res = mysql_query("update inventory set                            
                              size = '".$_POST['size']."',
                              cost = '".$_POST['cost']."',
                              description = '".$_POST['desc']."',
                              invent_type = '".$_POST['invent_type']."',
							  reorder = '".$_POST['reorder']."',
                              qty = '".$_POST['qty']."',".$imgqry."
                              user = '".$_POST['userid']."',
                              isactive = '1',
							  updated = now() where id='".$_REQUEST['id']."'");
	$log_res = mysql_query("insert into `log` set 
							  `type` = '2',
							  order_id='0',
							  item_id = '".$_REQUEST['id']."',
							  qty = '".$_POST['qty']."',
							  user_id = '".$_POST['userid']."',
							  deviceType = 'Mobile',
							  created = now()");
							 //echo mysql_error();
					$r = array('status'=>200,'data'=>mysql_fetch_assoc(mysql_query("select * from inventory where id='".$_REQUEST['id']."'")),'msg'=>'Inventory updated successfully');
}
}else{ $r = array('status'=>400,'data'=>'Description must be filled'); }
echo json_encode($r);

?>
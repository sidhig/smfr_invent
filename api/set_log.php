<?
error_reporting(0);

if($_REQUEST['order_id']!='' && $_REQUEST['station']!='' && $_REQUEST['engine']!='' && $_REQUEST['fname']!='' && $_REQUEST['lname']!='' && $_REQUEST['req_time']!=''){
	include_once('connect.php');
	include_once('smtpmail.php');
	
     $res = mysql_query("select * from req_table where  order_id='".$_REQUEST['order_id']."'"); 
    if(mysql_num_rows($res)>0){
    	while($obj =mysql_fetch_assoc($res)){
    		
    		 $log_res = mysql_query("insert into log set 
								`type` = '4',
				    			order_id = '".$obj['order_id']."',
				    			item_id = '".$obj['item_id']."',
								user_id = '".$obj['user']."',
				    			qty = '".$obj['qty']."',
                                station = '".$_REQUEST['station']."',
                                `engine` = '".$_REQUEST['engine']."',
                                fname = '".$_REQUEST['fname']."',
                                lname = '".$_REQUEST['lname']."',
								deviceType = 'Mobile',
                                req_time = '".$_REQUEST['req_time']."',
				    			created=now()");
			$reorder_qry = mysql_query("select *,(select email from setting) as email from inventory where qty <= (select reorder from setting ) and id = '".$obj['item_id']."'");
			while($formail =mysql_fetch_assoc($reorder_qry)){
				mailto($formail['email'],'Reorder Request','Item name: '.$formail['description']." Size: ".$formail['size']." Remaining Quantity : ".$formail['qty']);
			}
    	}

	$res = mysql_query("delete from req_table where order_id='".$_REQUEST['order_id']."'");
    $r = array('status'=>200,'data'=>'Data pull successfully');     
    }else{
	 $r = array('status'=>200,'data'=>'Invalid Request. Requested item not found with this order id.');  

    }

}
else{
$r = array('status'=>400,'data'=>'All field must be filled');
}
echo json_encode($r);
?>
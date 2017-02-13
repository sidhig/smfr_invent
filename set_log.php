<?
include_once'connect.php';
//print_r($_POST);
if($_POST['order_id']!='' && $_POST['station']!='' && $_POST['engine']!='' && $_POST['fname']!='' && $_POST['lname']!=''){

	 $res = $conn->query("select * from req_table where  order_id='".$_POST['order_id']."'"); 
      if($res->num_rows>0){
    	while($obj = $res->fetch_object()){   
      
    		 $log_res = $conn->query("insert into log set 
								`type` = '4',
				    			order_id = '".$obj->order_id."',
				    			item_id = '".$obj->item_id."',
								user_id = '".$obj->user."',
				    			qty = '".$obj->qty."',
                                station = '".$_POST['station']."',
                                `engine` = '".$_POST['engine']."',
                                fname = '".$_POST['fname']."',
                                lname = '".$_POST['lname']."',
								deviceType = 'Web',
                                req_time = '".$_POST['req_time']."',
				    			created=now()");

    	/*	 $reorder_qry = mysql_query("select *,(select email from setting) as email from inventory where qty <= (select reorder from setting ) and id = '".$obj['item_id']."'");
			while($formail =mysql_fetch_assoc($reorder_qry)){
				mailto($formail['email'],'Reorder Request','Item name: '.$formail['description']." Size: ".$formail['size']." Remaining Quantity : ".$formail['qty']);
			}*/
    		}

    		$res = $conn->query("delete from req_table where order_id='".$_POST['order_id']."'");
    	echo ('Data pull successfully'); 
}else{
	 echo ('Invalid Request. Requested item not found with this order id.');  

    }
    	
    }
    	else{
 echo ('All field must be filled');
}
?>
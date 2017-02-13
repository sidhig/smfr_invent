 
<? include_once'connect.php';?>
<? include_once'header.php';?>

<style>
tr{
  text-align: center;
}
</style>
<script>
$(document).ready(function(){
$("#filter_det").on('input',function(){
  filter($("#detail"),$("#filter_det"));

});});
</script>
<? 

if($_POST['detail']!=''){
$result = $conn->query("select order_id,user_id,station,engine,lname,fname,username,req_time from log logt left join tbl_user usert on logt.user_id = usert.id where type = 4 and logt.order_id = '".@$_POST['detail']."' group by order_id")->fetch_object();
?><center>
<table width=80%>
<tr><th>Order Id: <?=$result->order_id?></th>
  <th>Station: <?=$result->station?></th>
  <th>Engine: <?=$result->engine?></th>
  <th>First Name: <?=$result->fname?></th>
  <th>Last Name: <?=$result->lname?></th></tr>
</table></center>
<?


  $obj =$conn->query("select order_id,log.qty,img_url,size,cost ,(log.qty*cost) as totalamount,description from log left join inventory on log.item_id = inventory.id  where order_id ='".@$_POST['detail']."'")->fetch_object();
}else{
    header('location:transaction_report.php');
}
?>
<div id="user_tbl" style="margin-top: 26px;">

   <a href="transaction_report.php" name="close" id="close"  class="btn btn-warning" style="text-align:left;margin-left:50px;">Back</a>
   <!-- <h4 style="color:blue;text-align:left;margin-left:50px;">OrderId:</h4><?=($obj->order_id)?> -->
   <center>
    <strong>Search:</strong>
 <input id='filter_det' type="text" list='desc_list' style="width: 25vw;">
<table class="table table-hover" style="width:90%;border:3px ridge green;margin-top: 1vh;" >
   <tr class="danger" style='color:blue;'>
   	<!-- <th style="text-align:center">ItemCode</th>
 <th style="text-align:center">OrderId</th> -->
      <!--  <input type='hidden' name='detail' value='<?=($obj->order_id)?>'>-->
      <th style="text-align:center">Image</th>
      <th style="text-align:center">Description</th> 
      <th style="text-align:center">Size</th>   
      <th style="text-align:center">Quantity</th>
      <th style="text-align:center">Cost</th>
      <th style="text-align:center">TotalAmount</th>
   	
    	<!--<th>Role</th> -->
   	
  <!--  <th style="text-align:center">Isactive</th> -->
   
     </tr>
<tbody id='detail'>

<? $sql = "select order_id,log.qty,img_url,size,cost ,(log.qty*cost) as totalamount,description from log left join inventory on log.item_id = inventory.id  where order_id = '".@$_POST['detail']."'";
if ($result = $conn->query($sql)) { 
  $total_cost = 0;
  $total_qty = 0;

        while($obj = $result->fetch_object()){ 
//while ($row = mysql_fetch_array($result)) { ?>

<tr class="success">
   
 <!-- <td><?=$obj->item_code?></td> 
     <td ><?=($obj->order_id)?></td> -->
    <td><? if(file_exists($obj->img_url) && $obj->img_url !="") { ?>
      <a href="#<?=$obj->id?>" rel="modal:open" > 
        <img src = "<?=$obj->img_url?>" style="width:10vw;vertical-align:center:" >
      </a> 
      <? }else{
    echo '<img src = "uploads/default.png" style="width:10vw;">';
      } ?></td>
  
     <td><?=($obj->description)?></td>
     <td><?=($obj->size)?></td>
    <td><? echo $qty=($obj->qty); $total_qty = $total_qty +$qty; ?></td> 
    <td>$<?=number_format($obj->cost,2)?></td>    
    <td>$<? echo $cost = number_format($obj->qty*$obj->cost,2); 
     $total_cost = $total_cost+($obj->qty*$obj->cost); ?></td> 
    </tr>

 <!-- <td>
      <input type='hidden' name='detail' value='<?=($obj->order_id)?>'>
    <form action='#' method='post'>
   <input type='hidden' name='edit_id' value='<?=($obj->order_id)?>'> 
    <input type="submit" class="btn btn-primary" id="detail" value='Detail'>
      </form>
    </td> -->
   
    <? } ?>

<tr class="success">
   
 <!-- <td><?=$obj->item_code?></td>  -->
     <td colspan='3'>
      <strong>Total :</strong> </td>
    <td><strong><?=$total_qty?></strong></td> 
    <td></td>    
    <td><strong>$<?=number_format($total_cost,2)?></strong></td> 
    </tr>


    <? }
?>
    </tbody>
</table>
</div>
</center>
<? include 'footer.php';?>
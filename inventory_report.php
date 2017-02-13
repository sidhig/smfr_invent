 
<? include_once'connect.php';?>
<? include_once'header.php';?>

<style>
tr{
  text-align: center;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
$("#filter_inv").on('input',function(){
  filter($("#inventory_report"),$("#filter_inv"));
});
});
</script>
<center><div id="user_tbl" style="margin-top: 26px;">
  <h1 style="color:blue;"> Inventory Report</h1>
  <strong>Search:</strong>
 <input id='filter_inv' type="text" list='desc_list' style="width: 25vw;">
<table class="table table-hover" style="width:86vw;border:3px ridge green;margin-top: 1vh;" >
   <tr class="danger" style='color:blue;'>
    <th style="text-align:center">Transaction</th> 
   	<th style="text-align:center">Image</th> 
    <th style="text-align:center">Description</th>
    <th style="text-align:center">Size</th> 
    <th style="text-align:center">Quantity</th>
  <!--   <th style="text-align:center">Reorder</th> -->
   	<th style="text-align:center">Cost</th>
     <th style="text-align:center">TotalAmount</th>   	
    	<!--<th>Role</th> -->
    <th style="text-align:center">Username</th> 
    <!-- <th style="text-align:center">DeviceType</th> 
     -->
    <th style="text-align:center">Time</th> 
    
   
     </tr>
<tbody id="inventory_report">
<?  $sql = "select if(type=1,'Quantity Added',if(log.qty<0,'Quantity Removed','Quantity Added')) as transaction,inventory.img_url,inventory.size,inventory.cost,inventory.description,tbl_user.username,abs(log.qty) as qty,log.created from log left join inventory on log.item_id = inventory.id left join tbl_user on log.user_id = tbl_user.id where inventory.isactive = 1 and  type in ('1','2') order by log.created desc";
if ($result = $conn->query($sql)) { 
        while($obj = $result->fetch_object()){ 
//while ($row = mysql_fetch_array($result)) { ?>

<tr class="success">
 <!-- <td><?=$obj->item_code?></td>  -->
 <td><?=($obj->transaction)?></td>
    <td><? if(file_exists($obj->img_url) && $obj->img_url !="") { ?>
      <a href="#<?=$obj->id?>" rel="modal:open" > 
        <img src = "<?=$obj->img_url?>" style="width:10vw;vertical-align:center:" >
      </a> 
      <? }else{
    echo '<img src = "uploads/default.png" style="width:10vw;">';
      } ?></td>
    <td ><?=($obj->description)?></td>
   <!--  <td ><?=($obj->user_id)?></td> -->
    <td><?=($obj->size)?></td>
    <td><? echo $qty=($obj->qty); $total_qty = $total_qty +$qty; ?></td>

    <td>$<?=number_format($obj->cost,2)?></td> 
    <td>$<?echo $cost = number_format($obj->qty*$obj->cost,2); 
     $total_cost = $total_cost+($obj->qty*$obj->cost); ?></td>
    <td><?=ucfirst($obj->username)?></td> 
     <!-- <td><?=($obj->deviceType)?></td> --> 
     
    <td><?$source= $obj->created;
$date = new DateTime($source);
echo $date->format('m-d-Y h:i A')?></td>

 <!-- <td>
  <form action='detail.php' method='post'> 
    <input type='hidden' name='detail' value='<?=($obj->order_id)?>'>
    <a href="detail.php"> <img src="image/Info.png" style="width:35px;"></a>
     <input type="submit" class="btn btn-primary" id="detail" value='Detail'> 
      </form>
    </td> -->
    </tr>
   

  <? } ?>

<tr class="success">
   
 <!-- <td><?=$obj->item_code?></td>  -->
     <td colspan='4'>
      <strong>Total :</strong> </td>
    <td><strong><?=$total_qty?></strong></td> 
    <td></td>    
    <td><strong>$<?=number_format($total_cost,2)?></strong></td> 
    <td></td> 
    <td></td> 
    </tr>


    <? }
?>
    </tbody>
</table>
</div>
</center>
<? include 'footer.php';?>
 
<? include_once'connect.php';?>
<? include_once'header.php';?>

<style>
tr{
  text-align: center;
}
</style>
<script>
$(document).ready(function(){
$("#filter_rep").on('input',function(){
  filter($("#report"),$("#filter_rep"));
});
});
</script>
<center><div id="user_tbl" style="margin-top: 26px;">
  <h1 style="color:blue;"> Transaction Report</h1>
   <strong>Search:</strong>
 <input id='filter_rep' type="text" list='desc_list' style="width: 25vw;">
<table class="table table-hover" style="width:90%;border:3px ridge green;margin-top: 1vh;" >
   <tr class="danger" style='color:blue;'>
   	<!-- <th style="text-align:center">ItemCode</th> -->
    <th style="text-align:center">OrderId</th>
<!--     <th style="text-align:center">UserId</th> -->
    <th style="text-align:center">Station</th>
   	<th style="text-align:center">Enginee</th>   	
    	<!--<th>Role</th> -->
   	<th style="text-align:center">Fname</th>
     <th style="text-align:center">Lname</th>
  <!--  <th style="text-align:center">Isactive</th> -->
    <th style="text-align:center">Username</th> 
    <th style="text-align:center">Request time</th> 
    <th style="text-align:center">Details</th>
   
     </tr>
<tbody id='report'>
<?  $sql = "select order_id,user_id,station,engine,lname,fname,username,DATE_ADD(logt.req_time, interval ".$offset_time." minute) as `req_time` from log logt left join tbl_user usert on logt.user_id = usert.id where type = 4 group by order_id order by logt.created desc";
if ($result = $conn->query($sql)) { 
        while($obj = $result->fetch_object()){ 
//while ($row = mysql_fetch_array($result)) { ?>

<tr class="success">
 <!-- <td><?=$obj->item_code?></td>  -->
    
    <td ><?=($obj->order_id)?></td>
   <!--  <td ><?=($obj->user_id)?></td> -->
    <td><?=($obj->station)?></td>
    <td><?=($obj->engine)?></td>
    <td><?=($obj->fname)?></td>
    <td><?=($obj->lname)?></td> 
    <td><?=ucfirst($obj->username)?></td> 
    <td><?$source= $obj->req_time;
$date = new DateTime($source);
echo $date->format('m-d-Y h:i A')?></td>

 <td>
  <form action='transaction_detail.php' method='post'> 
    <input type='hidden' name='detail' value='<?=($obj->order_id)?>'>
   <!-- <a href="detail.php"> <img src="image/Info.png" style="width:35px;"></a>-->
     <input type="submit" class="btn btn-primary" id="detail" value='Detail'> 
      </form>
    </td>
    </tr>
    <? } }
?>
    </tbody>
</table>
</div>
</center>
<? include 'footer.php';?>
 
<? include_once'connect.php';?>
<? include_once'header.php';?>
<? //print_r($_SESSION);
           if(@$_POST['id']!=''){
$obj = $conn->query("update inventory set isactive = '0',qty='0',updated=now() where id = '".$_POST['id']."'"); 
//unlink($obj->img_url);
//$conn->query("delete from inventory where id = '".$_POST['id']."' ");  
$err = "inventory deleted";
}

?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript">
function getTimeZone() {
    var offset = new Date().getTimezoneOffset(),
        o = Math.abs(offset);
    return (offset < 0 ? "+" : "-") + o;
}



function delformsubmit(id){
 // alert(id);
  if(confirm("Are you sure delete this data")){
    $("#del_"+id).submit();
    //alert('deleted');
  }

}
      $(document).ready(function(){
          $("#filter_desc").on('input',function(){
           filter_table($('#in_type').val(),$("#filter_desc").val(),$("#inventry_tbl"),$('#in_category').val());
          });
          $("#in_type").on('change', function() {
            var intype = $("#in_type").val();
                $.post( "cat_ajax.php",{ tbl_type: intype },function(data) {
                data1='<option value="">All</option>'+data;
                $("#in_category").html(data1);
                });
              
           filter_table($('#in_type').val(),$("#filter_desc").val(),$("#inventry_tbl"),$('#in_category').val());
         });

            $("#in_category").on('change', function() {
           filter_table($('#in_type').val(),$("#filter_desc").val(),$("#inventry_tbl"),$('#in_category').val());
          });

      });
</script>
<?
/* $time_diff ="<script>document.write(getTimeZone())</script>";

echo $time_diff;
if (strpos($time_diff, '+') !== false) {
   $asd = trim($time_diff,'+');
  $time_diff2 = intval($asd); 
}else if (strpos($time_diff, '-') !== false){
  $asd = trim($time_diff,'-');
  $time_diff2 = intval($asd); 
}
var_dump($time_diff2);

$asd = (int)$time_diff;
echo intval($asd); 
var_dump($asd);*/ ?>
<style>
tr{
  text-align: center;
}
.modal {
  padding: 15px 0px;
}
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js" type="text/javascript" charset="utf-8"></script>

  <link rel="stylesheet" href="jquery.modal.css" >
  <script src="jquery.modal.js" ></script>
      <script type="text/javascript">
        $("#print_modal").on("click", function () {
            var divContents = $(".modal").html();
            alert(divContents);
            var printWindow = window.open('', '', 'height=400,width=800');
            printWindow.document.write('<html><head><title>DIV Contents</title>');
            printWindow.document.write('</head><body >');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        });
    </script>
<style>
.modal {
  padding: 18px 0px;
  width: 35vw;
}
.modal a.close-modal {
    position: absolute;
    top: 0.5px;
    right: 0.5px;
    display: block;
    width: 30px;
    height: 30px;}
</style>
<center><div id="user_tbl" style="margin-top: 26px;">
  <h1 style="color:blue;">All Inventory</h1>

<datalist id= 'desc_list' >
  <? $sql = "SELECT replace(replace(replace(replace(inventory.description,'#',' '),'".'"'."',' '),'\r\n',' '),'\n',' ') as description  FROM `inventory` left join tbl_user on tbl_user.id = inventory.user where inventory.isactive = '1' order by inventory.description asc";
if ($result = $conn->query($sql)) { 
        while($obj = $result->fetch_object()){ ?>
          <option value='<?=($obj->description)?>'><?

}}
          ?></datalist>
          <strong>Search:</strong>
 <input id='filter_desc' type="text" list='desc_list' style="width: 15vw; height: 4vh;">
  <strong>Type:</strong>
      <select  id="in_type" name="tbl_type" style="width:10vw;height: 4vh;">
         <option value="">All</option>
            <? $sql = "SELECT name FROM `tbl_type` group by name"; 
              $result = $conn->query($sql); 
            while($row = $result->fetch_object()){?>
            <option value='<?=$row->name?>'><?=$row->name?></option>
           <? } ?>
      </select>
      <strong>Category:</strong>
      <select  id="in_category" name="in_category" style="width:10vw;height: 4vh;">
         <option value="">All</option>
            <? $sq = "(select category from tbl_type)"; 
              $resu= $conn->query($sq); 
            while($cat = $resu->fetch_object()){?>
            <option value='<?=$cat->category?>'><?=$cat->category?></option>
           <? } ?>
      </select>
<table class="table table-hover" style="width:90%;border:3px ridge green;margin-top: 1vh;" >
   <tr class="danger" style='color:blue;'>
   	<!-- <th style="text-align:center">ItemCode</th> -->
    <th style="text-align:center">Image</th>
    <th style="text-align:center">Description</th>
    <th style="text-align:center">Type</th>
    <th style="text-align:center">Category</th>
   	<th style="text-align:center">Size</th>   	
    	<!--<th>Role</th> -->
   	<th style="text-align:center">Cost</th>
     <th style="text-align:center">Quantity</th>
    <th style="text-align:center">TotalAmount</th>
    <th style="text-align:center">Updated On</th> 
    <th style="text-align:center">Edit</th>
    <th style="text-align:center">View</th> 
    <th style="text-align:center">Delete</th> 
     </tr>
<tbody id='inventry_tbl'>
<? $sql = "SELECT  inventory.id,inventory.invent_type,inventory.category,inventory.img_url,inventory.size,inventory.cost,replace(replace(replace(replace(inventory.description,'#',' '),'".'"'."',' '),'\r\n',' '),'\n',' ') as description,inventory.qty,inventory.isactive,DATE_ADD(inventory.updated, interval ".$offset_time." minute) as `updated`,inventory.created,tbl_user.username FROM `inventory` left join tbl_user on tbl_user.id = inventory.user where inventory.isactive = '1' order by inventory.id desc";
if ($result = $conn->query($sql)) { 
  $total_cost = 0;
  $total_qty = 0;
        while($obj = $result->fetch_object()){ 
//while ($row = mysql_fetch_array($result)) { ?>
<div id="<?=$obj->id?>" style="display:none;width:35%;"><center>
  <? if(file_exists($obj->img_url) && $obj->img_url!=''){ ?>
  <img src = "<?=$obj->img_url?>" style="width:10vw;">
  <? }else{
    echo '<img src = "uploads/default.png" style="width:10vw;">';
  } ?></center>
</div>
<tr class="success">
 <!-- <td><?=$obj->item_code?></td>  -->
    <td >
      <? if(file_exists($obj->img_url) && $obj->img_url !="") { ?>
      <a href="#<?=$obj->id?>" rel="modal:open" > 
        <img src = "<?=$obj->img_url?>" style="width:10vw;vertical-align:center:" >
      </a> 
      <? }else{
    echo '<img src = "uploads/default.png" style="width:10vw;">';
      } ?></td>
    <td ><?=($obj->description)?></td>
      <td ><?=($obj->invent_type)?></td>
        <td ><?=($obj->category)?></td>
    <td><?=($obj->size)?></td>
    <td>$<?=number_format($obj->cost,2)?></td>
    <td><? echo $qty=($obj->qty); $total_qty = $total_qty +$qty; ?></td> 
    <!-- <td><?=($obj->isactive)?></td>  -->
     <td>$<? echo $cost = number_format($obj->qty*$obj->cost,2); 
     $total_cost = $total_cost+($obj->qty*$obj->cost); ?></td>
    <td><?$source= $obj->updated;
$date = new DateTime($obj->updated);
echo $date->format('m-d-Y h:i A')?></td>

 <td>
    <form action='edit_invent_new.php' method='post'>
    <input type='hidden' name='edit_id' value='<?=($obj->description)?>'>
    <input type="submit" class="btn btn-primary" id="edit" value='Edit'>
      </form>
    </td>
    <td>
    <a href="phpqrcode/index.php?data=<?=($obj->id)?>,<?=($obj->description)?>,<?=($obj->size)?>&level=L&size=5&id=<?=($obj->id)?>" rel="modal:open" class="btn btn-primary" >View</a>
    
    </td>
 <td><form id='del_<?=($obj->id)?>' action='view_invent.php' method='post'>
  <input type='hidden' name='id' value='<?=($obj->id)?>'>
  <input type="button" onclick='delformsubmit(<?=($obj->id)?>)' class="btn btn-primary" id="delete" value="Delete">
</form>
    </td>
  </tr>
  
<? } }
?>

<tr class="success">
   
 <!-- <td><?=$obj->item_code?></td>  -->
     <td colspan='4'>
     <center><strong> Total :</strong></center> </td>
    <td><strong><?=$total_qty?></strong></td> 
    
    <td><strong>$<?=number_format($total_cost,2)?></strong></td> 
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>

    </tr>


   
</tbody>
</table>
</div>
</center>
<? include 'footer.php';?>

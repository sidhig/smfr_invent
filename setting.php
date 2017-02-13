<?
session_start();?>
<?php include_once'header.php';?>
<?php include_once'connect.php';?>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>


<? 
  if(!empty($_POST)){
       if($_POST['qry_type']=='additem'){ 
 $sql ="update setting set                              
                              email = '".trim($_POST['email'])."',
                              reorder = '".trim($_POST['reorder'])."',
                              updated = now()
                              where id='1'";
                              
                              
          if($conn->query($sql)){
            $err = " Successfully updated";
          }
          else{
            $err = "sql error";
          }
    }       
        
} 
       if($_POST['add_type']=='type'){
     $obj ="insert into  tbl_type set                              
                              name = '".trim($_POST['type_name'])."' ON DUPLICATE KEY UPDATE name=name ";  
      if($conn->query($obj)){
            $err = "Saved Successfully";
          }
          else{
            $err = "sql error";
          }

       }
       else if(@$_POST['type_id']!=''){
         $del = $conn->query("delete from tbl_type where id = '".$_POST['type_id']."' ");  
          $err = "Deleted Successfully";
}

if($_POST['add_size']=='size'){
     $obj ="insert into  tbl_size set                              
                              name = '".trim($_POST['size_name'])."' ON DUPLICATE KEY UPDATE name=name ";  
      if($conn->query($obj)){
            $err = "Saved Successfully ";
          }
          else{
            $err = "sql error";
          }

       }
       else if(@$_POST['size_id']!=''){
         $del = $conn->query("delete from tbl_size where id = '".$_POST['size_id']."' ");  
          $err = "Deleted Successfully";
} 
     
?>
<?
 $obj = $conn->query("SELECT * FROM `setting` where id = '1'")->fetch_object();?>

      <script>
function validateForm(){  

if ($("#email").val().trim() == '')
    {
      alert('Please enter a Email Id.');
       $("#email").focus()
    }
    

     else if ($("#reorder").val().trim() == '')
    {
      alert('Please enter Reorder.'); 
        $("#reorder").focus()
          }
          else if ($("#reorder").val().trim() == '0')
    {
      alert('Please enter Reorder value greater than 0.'); 
        $("#reorder").focus()
          }
           else{
         $("#setting").submit();
      }

    }
    $(document).ready(function() {

    $('#email,#reorder').on('keypress', function (e) {
  if (e.which == 13) {
   validateForm();
    return false;    //<---- Add this line onclick="validateForm()"
  }
});
  $("#reorder").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
   });
 function del_type(type_id){
  if(confirm("Are you sure delete this data")){
    $("#del_"+id).submit();
    //alert('deleted');
  }
}
  function del_size(size_id){
    //alert(size_id);
  if(confirm("Are you sure delete this data")){
    $("#dele_"+id).submit();
    //alert('deleted');
  }

}

</script>
<style>
.remove_btn{
  background: url(image/deny.png) no-repeat;
  background-size: 100% 100%;;
  width: 26%;
  
  border: transparent;
  color: transparent;
}
td{
  padding: 5px;
}
</style>
<center><div style="color:green;font-size:17px;margin-bottom:-13px;"><? echo $err;?></div></ceenter>
	<form  id="setting" name="myForm" role="form" action = "<?=basename($_SERVER['PHP_SELF'])?>" method ="post"  enctype="multipart/form-data">
	<center>	      
	<table border="0" class="demo-table" class="table table-responsive table-hover" style="width:50%;border:3px ridge green;margin-top: 40px;">
   <tr><th style= "text-align: center; color:blue; font-size: 20px;padding-top: 10px;" colspan=2>Setting</th>
    </tr>
		<!-- <tr class="tr"><td><strong>Item Code</strong></td>
		<td><input type = "text" class="demoInputBox" name="itemcode" id="itemcode">
    </td>

		</tr> --><input type='hidden' name='qry_type' value='additem' >
    <input type='hidden' name='date' value='<?=date('Y-m-d H:i:s')?>' >
    <tr class="tr"><td style="padding-right: 2vw;padding-left: 7vw;">Email<span style='color:red;'>*</span></td>
    <td>
    	<input type ="text"  class="demoInputBox"  name="email" id="email" style="width:70%;" value="<?=$obj->email?>">
        </td>
    </tr>
 		
    <tr class="tr"><td style="padding-right: 2vw;padding-left: 7vw;">Reorder<span style='color:red;'>*</span></td>
    <td >

      <input id="reorder" name="reorder" class="demoInputBox" type='text' list='number' style="border:#F0F0F0 1px solid;width:70%;" value="<?=$obj->reorder?>">
    <datalist id='number'> 
        <? for($i=1;$i<=100;$i++){ ?>
              <option value="<?=$i?>">
                <? } ?>
          </datalist></td>
    </tr>
 
        <tr>
		<td  colspan= "2" style= "text-align:center;margin-bottom:5%;height:a5px">
      <input type="button"  value="Save" class="btn btn-primary" onclick = "validateForm()">
         <a href="home.php" name="close" id="close"  class="btn btn-primary" >Close</a> 
        </td>
	</tr>
	 		</table>
</center>
</form>
<center><div style="width:40vw;">
  <div style="float:left;">
<table class="table table-hover" style="width:19vw;border:3px ridge green;">
  <tr class="danger" style="color:blue;">
      <th style="text-align:center" colspan="2">Size</th>
  </tr> 
  <tr class="success">
  <form  method='post' style="margin:0px;">
    <td style="text-align:center;">
      <input type="text" name="size_name" style="width:7vw;">
    </td>
    <td  style="text-align:center;"> 
            
              <input type='hidden' name='add_size' value='size'>
              <input type="submit" class="btn btn-primary"  value='Add'>
            
    </td>
  </form>
</tr>
  <? $sql = "SELECT  * FROM `tbl_size` where 1 order by name";
    if ($result = $conn->query($sql)) { 
            while($size = $result->fetch_object()){ 
     ?>
    <tr class="success">
      <td  style="text-align:center;"><?=$size->name?></td> 
      <td  style="text-align:center;">
        <form id='dele_<?=($size->id)?>'  method='post' style="margin:0px;">
          <input type='hidden' name='size_id' value='<?=($size->id)?>'>
          <input type="submit" onclick='del_size(<?=($size->id)?>)'  class="remove_btn" style="">
        </form>
      </td>
      
    </tr>
<? } }
?>

</table>
</div>
<div style="float:right;">
<table class="table table-hover" style="width:19vw;border:3px ridge green;">
  <tr class="danger" style="color:blue;">
      <th style="text-align:center" colspan="2">Type</th>
      
  </tr> 
  <tr class="success">
  <form method='post' style="margin:0px;">
      <td style="text-align:center;">
        <input type="text" name="type_name" style="width:7vw;">
      </td>
      <td  style="text-align:center;"> 
        <input type='hidden' name='add_type' value='type'>
        <input type="submit" class="btn btn-primary"  value='Add'>
      </td>
  </form>
</tr>
  <? $sql = "SELECT  * FROM `tbl_type` where 1 order by name";
    if ($result = $conn->query($sql)) { 
            while($obj = $result->fetch_object()){ 
     ?>
    <tr class="success">
      <td  style="text-align:center;"><?=$obj->name?></td> 
     <!--  <td  style="text-align:center"> 
        <form action='' method='post'>
          <input type='hidden' name='edit_id' value='<?=($obj->id)?>'>
          <input type="submit" class="btn btn-primary" id="edit" value='Edit'>
        </form>
      </td> -->
      <td  style="text-align:center;">
        <form id='del_<?=($obj->id)?>' method='post' style="margin:0px;">
          <input type='hidden' name='type_id' value='<?=($obj->id)?>'>
          <input type="submit" onclick='del_type(<?=($obj->id)?>)'  class="remove_btn" >
        </form>
      </td>
    </tr>
<? } }
?>

</table>
</div>
</div></center>



<? include 'footer.php';?>
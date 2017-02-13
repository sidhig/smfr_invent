<?
session_start();
//print_r($_POST);
?>
<? include_once'header.php';?>
<? include_once'connect.php';?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="js/size.js"></script>
<script>
$(document).ready(function(){
          
          $("#invent_type").on('change', function() {
            var intype = $("#invent_type").val();
                $.post( "cat_ajax.php",{ tbl_type: intype },function(data) {
                $("#in_category").html(data);
                });
         });

      });
</script>
<?
//error_reporting(1);
if($_POST['qry_type']=='additem'){       
  if($_FILES["fileToUpload"]["name"]!=''){
      $target_dir = "uploads/";
      $target_file = $target_dir . time();
      $uploadOk = 1;
      $imageFileType = pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION);
      $target_file = $target_file .'.'. $imageFileType;
      // Check if image file is a actual image or fake image
      if(isset($_POST["submit"])) {
          $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
          if($check !== false) {
              echo "File is an image - " . $check["mime"] . ".";
              $uploadOk = 1;
          } else {
              echo "File is not an image.";
              $uploadOk = 0;
          }
      }
      // Check if file already exists
      if (file_exists($target_file)) {
          echo "Sorry, file already exists.";
          $uploadOk = 0;
      }
      // Check file size
      if ($_FILES["fileToUpload"]["size"] > 500000) {
          echo "Sorry, your file is too large.";
          $uploadOk = 0;
      }
      // Allow certain file formats
      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif" ) {
          echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
          $uploadOk = 0;
      }
      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
          $err = "Sorry, your file was not uploaded.";
      // if everything is ok, try to upload file
      } else {
          if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
              echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";

         $res = $conn->query("select img_url from inventory where description = '".trim($_POST['desc'])."' and size = '".$_POST['size']."'")->fetch_object(); ;
         unlink($res->img_url);
         $imgqry = " img_url = '".$target_file."', ";
         } } 
      }//upload image code end
else{
  $target_file = '';
  $prev_image = $conn->query("select img_url from inventory where description = '".$_POST['desc']."' and img_url != ''")->fetch_object();
  if($prev_image->img_url!=''){
    $target_file = $prev_image->img_url;
    
  }
}
 $conn->query("update inventory set '".$target_file."',updated = now() where description = '".$_POST['desc']."' and img_url != ''");
$j=0;
foreach($_POST['size'] as $size){
  if($_POST['size'][$j]!='' && $_POST['cost'][$j]!=''){
  $already = $conn->query("select count(*) as rowcount from inventory where description = '".$_POST['desc']."' and size = '".$_POST['size'][$j]."'")->fetch_object();
 if($already->rowcount==1){

    //if($already->isactive==0){

       $update_qry = "update inventory set
                              cost = '".trim($_POST['cost'][$j])."',
                              qty = '".$_POST['qty'][$j]."',
                              reorder = '".$_POST['reorder'][$j]."',
                              img_url = '".$target_file."',
                              invent_type = '".$_POST['tbl_type']."',
                               category = '".$_POST['in_category']."',
                              `user` = '".$_SESSION['LOGIN_USERID']."',
                              isactive = '".$_POST['isactive'][$j]."',
                              updated = now() 
                              where description = '".trim($_POST['desc'])."' and size = '".$_POST['size'][$j]."'";
        $conn->query($update_qry);
        $err="Requested Inventory activated";
    /*}else{
      $err="Inventory already exist with this Description and size";
  }*/
 }else{//fresh insertion

         $sql ="insert into inventory set                             
                              size = '".$_POST['size'][$j]."',
                              cost = '".trim($_POST['cost'][$j])."',
                              description = '".trim(str_replace('"',' ',$_POST['desc']))."',
                              qty = '".$_POST['qty'][$j]."',
                              reorder = '".$_POST['reorder'][$j]."',
                              invent_type = '".$_POST['tbl_type']."',
                              category = '".$_POST['in_category']."',
                              img_url = '".$target_file."',
                              user = '".$_SESSION['LOGIN_USERID']."',
                              isactive = '1',
                              created = now(),
                              updated = now()";                        
         if($conn->query($sql)=== TRUE){
          $inserted_id=  $conn->insert_id;
           $conn->query("insert into `log` set 
         `type` = '1',
         order_id='0',
         item_id = '".$inserted_id."',
         qty = '".$_POST['qty'][$j]."',
         user_id = '".$_SESSION['LOGIN_USERID']."',
         deviceType = 'Web',
         created = now()");   
            
          }    
         
   }  //end fresh insertion
    
} //end size and cost         
  $j++;           
 }///for end 
 $err = "Inventory updated";
 //header('location:view_invent.php');
 echo ("<script>location.href='view_invent.php'</script>");
 
}
?>

<?
  // $objct =$conn->query("SELECT * FROM `inventory` where id = '".@$_POST['edit_id']."'")->fetch_object();
$result = $conn->query("select * from inventory where description = '".$_REQUEST['edit_id']."'")->fetch_object();
?>
<center><div style="color:red;font-size:17px;margin-bottom:-13px;"><? echo $err;?></div>
</center> 
<center>
<form  id="additem" name="myForm" role="form" action = "edit_invent_new.php" method ="post"  enctype="multipart/form-data">

    <table id='tbl_size' border="0" class="demo-table" class="table table-responsive table-hover" style="width: 70%;margin-top: 40px;   margin: 0px;">
        <tr><td colspan= "4" style= "text-align: center; color:blue; font-size: 20px;padding-top: 10px;" colspan=2>Edit Item</td>
               </tr>
    <input type='hidden' name='date' value='<?=date('Y-m-d H:i:s')?>' >
    <tr class="tr" >
      <td colspan="2" style='width: 50%'>Description<span style='color:red;'>*</span></td>
      <td colspan="2" ><textarea rows="3"  style="height:auto;"  name="desc" id="desc"><?=$result->description?></textarea>
        <input type='hidden' name='qry_type' value='edit' >
         <input type='hidden' name='edit_id' value='<?=($obj->id)?>'>
        <!--  <input type='hidden' name='qry_type' value='additem'> -->
        </td>
    </tr>
      <tr class="tr"><td colspan="2">Type<span style='color:red;'>*</span></td>      
    <td colspan="2">
          <select  id="invent_type" name="tbl_type" style="border:#F0F0F0 1px solid;height: 31px;">
            <? $sql = "SELECT name,id FROM `tbl_type` group by name"; 
              $resu = $conn->query($sql); 
            while($row = $resu->fetch_object()){?>
            <option value='<?=$row->name?>' <? if($row->name==$result->invent_type){ echo 'selected';} ?>><?=$row->name?></option>
           <? } ?>
      </select>
        <input type='hidden' name='qry_type' value='additem'></td>

    </tr>

     <tr class="tr">
          <td colspan="2">Category<span style='color:red;'>*</span>
          </td>      
          <td colspan="2">
             <select name="in_category" id="in_category" style="border:#F0F0F0 1px solid;height: 31px;">
                     <? $sq = "SELECT category FROM `tbl_type` where name='".$result->invent_type."' group by category"; 
                        $resu = $conn->query($sq); 
                       while($cat = $resu->fetch_object()){?>
                      <option value='<?=$cat->category?>' <? if($cat->category==$result->category){ echo 'selected';} ?>><?=$cat->category?></option>
                     <? } ?>
             </select>

                <input type='hidden' name='qry_type' value='additem'>
          </td>
        </tr>
  <tr class="tr" id='add_size_row'>
     <td colspan="2">Add Size</td>
    <td id='add_size_col' colspan="2">
        <select id="size" name="size" style="border:#F0F0F0 1px solid;height: 31px;">
<?php 
$res =$conn->query("select *,replace(name,'/','_') as `value` from tbl_size where name not in (select size from inventory where description= '".$_REQUEST['edit_id']."')");
    while($obj = $res->fetch_object()){ ?>
<option value='<?=$obj->value?>'><?=$obj->name?></option>
<?
}
?>
</select>

      <!-- <select id="size" name="size" style="border:#F0F0F0 1px solid;height: 31px;">
              <option value="S">S</option>
              <option value="M">M</option>
              <option value="L">L</option>
              <option value="XL">XL</option>
              <option value="XXL">XXL</option>
      </select> -->
      <input type='button' id='addsize_btn' onclick="nxtsize($('#size').val());" value='Add' > 
     <!--  <img src="image/add.png" id='addsize_btn' style="width:31px;" onclick="nxtsize('0',$('#size').val());">-->
    </td>
    
  </tr>
    <tr class="tr" id="row_head" >
       <td>Size<span style='color:red;'>*</span></td>
       <td>Cost(Per Unit)<span style='color:red;'>*</span></td>
       <td>Quantity<span style='color:red;'>*</span></td>
        <td>Reorder<span style='color:red;'>*</span></td>
        <td></td>
         
    </tr>
    <datalist id='number'> 
        <? for($i=0;$i<=100;$i++){ ?>
              <option value="<?=$i?>">
        <? } ?>
    </datalist>
 <?

if ($result = $conn->query("select *,replace(size,'/','_') as `value` from inventory where description = '".$_REQUEST['edit_id']."'")){
    while($obj = $result->fetch_object()){ 
?>
<tr id='row<?=$obj->value?>' <? if($obj->isactive==0){ ?> style='background-color:lightcoral;' <? } ?> >
  <td><input id="size<?=$obj->value?>" name="size[]" type="text" class="cls_size" readonly  style="border:#F0F0F0 1px solid;height: 31px; width: 40%;" value='<?=$obj->size?>'></td>
  <td>$<input type ="text" style="width:40%;" name="cost[]" id="cost<?=$obj->value?>" class="cls_cost" onkeypress="return isNumberKey(event)" value='<?=$obj->cost?>'></td>
  <td><input id="qty<?=$obj->value?>" name="qty[]" type="text" list="number" style="width:45%;border:#F0F0F0 1px solid;height: 31px;" onkeypress="return isNumber(event)" value='<?=$obj->qty?>'></td>
  <td><input id="reorder<?=$obj->value?>" name="reorder[]" type="text" list="number" style="width:45%;border:#F0F0F0 1px solid;height: 31px;" value='<?=$obj->reorder?>' onkeypress="return isNumber(event)" >
    <? if($obj->isactive==0){ ?>
<span id="del"style='float:right;font-size: 8px;'>Deleted</span>
<?}?>
  </td>
  <td><input id="active<?=$obj->value?>" name="isactive[]" type="hidden" style="width:45%;" value='<?=$obj->isactive?>'>
<? if($obj->isactive==0){ ?>
    <input id="isactive<?=$obj->value?>" type="button"  onclick="$('#row<?=$obj->value?>').css('background-color',''); $('#active<?=$obj->value?>').val('1'); $(this).hide();$('#del').hide();" value='Add Again'> 
<? } ?></td></tr>
<? }
}

$result = $conn->query("select * from inventory where description = '".$_REQUEST['edit_id']."' and img_url!=''")->fetch_object();
?>




</table>
<table border="0" class="demo-table" class="table table-responsive table-hover" style="width: 70%; margin: 0px;">
    <tr class="tr"><td style="padding-bottom: 43px;">Image</td>
    <td><input type="file" name="fileToUpload" id="fileToUpload" style="margin-top:32px;"><? if(file_exists($result->img_url) && $result->img_url!=''){ ?>
  <img src = "<?=$result->img_url?>" style="width:5vw;">
  <? }else{?>
    <img src = "uploads/default.png" style="width:5vw;">
  <?} ?>
        </td>
    </tr>
        <tr>

    <td  colspan= "4" style= "text-align:center;margin-bottom:5%;height:a5px">
      <input type="button"  value="Save" class="btn btn-primary" onclick = "validateForm()">
        <a href="view_invent.php" name="close" id="close"  class="btn btn-primary" >Close</a>
        </td>
  </tr> 
    </table>
 
</form></center>
<? include 'footer.php';?>
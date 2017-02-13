<? 
session_start();
//print_r($_POST);
?>
<?php include_once'header.php';?>
<?php include_once'connect.php';?>
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
if($_POST['qry_type']=='additem')
{
  if($_FILES["fileToUpload"]["name"]!='')
  {
      $target_dir = "uploads/";
      $target_file = $target_dir . time();
      $uploadOk = 1;
      $imageFileType = pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION);
      $target_file = $target_file .'.'. $imageFileType;
      // Check if image file is a actual image or fake image
    if(isset($_POST["submit"]))
      {
          $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
          if($check !== false)
          {
              echo "File is an image - " . $check["mime"] . ".";
              $uploadOk = 1;
          } 
          else 
            {
                echo "File is not an image.";
                $uploadOk = 0;
            }
      }
      
      if (file_exists($target_file))
      {
          echo "Sorry, file already exists.";
          $uploadOk = 0;
      }
      
      if ($_FILES["fileToUpload"]["size"] > 500000)
      {
          echo "Sorry, your file is too large.";
          $uploadOk = 0;
      }
      
      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif" )
      {
          echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
          $uploadOk = 0;
      }
     
      if ($uploadOk == 0)
      {
          $err = "Sorry, your file was not uploaded.";
      
      } 
        else 
        {
          if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
          {
              echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";

               $res = $conn->query("select img_url from inventory where description = '".trim($_POST['desc'])."' and size = '".$_POST['size']."'")->fetch_object(); ;
               unlink($res->img_url);
               $imgqry = " img_url = '".$target_file."', ";
          }
        } 
  }//upload image code end
        else
        {
          $target_file = '';
          $prev_image = $conn->query("select img_url from inventory where description = '".$_POST['desc']."' and img_url != ''")->fetch_object();
          if($prev_image->img_url!='')
          {
            $target_file = $prev_image->img_url;
    
  }
}
    $conn->query("update inventory set '".$target_file."',updated = now() where description = '".$_POST['desc']."' and img_url != ''");
            $j=0;
      foreach($_POST['size'] as $size)
      {
            if($_POST['size'][$j]!='' && $_POST['cost'][$j]!='')
            {
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
                              isactive = '1',
                              updated = now() 
                              where description = '".trim($_POST['desc'])."' and size = '".$_POST['size'][$j]."'";
                      $conn->query($update_qry);
                      $err="Requested Inventory activated";
    
 }
      else
      {//fresh insertion

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
                if($conn->query($sql)=== TRUE)
                {
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
       $j++;   
} //end size and cost         
          
 }///for end 
             $err = "new inventory inserted";
            // header('location:view_invent.php');
             echo ("<script>location.href='view_invent.php'</script>");

}
?>

<center>
  <div style="color:red;font-size:17px;margin-bottom:-13px;"><? echo $err;?>
  </div>
</center> 
<center>
    <form  id="additem" name="myForm" role="form" action = "add_invent.php" method ="post"  enctype="multipart/form-data">

    <table id='tbl_size' border="0" class="demo-table" class="table table-responsive table-hover" style="width: 70%;margin-top: 40px;   margin: 0px;">
        <tr>
          <td colspan= "4" style= "text-align: center; color:blue; font-size: 20px;padding-top: 10px;" colspan=2>Add Item
          </td>
        </tr>
           <input type='hidden' name='date' value='<?=date('Y-m-d H:i:s')?>' >
        <tr class="tr" >
          <td colspan="2" style='width: 50%'>Description<span style='color:red;'>*</span>
          </td>
          <td colspan="2" >
            <textarea rows="3"  style="height:auto;"  name="desc" id="desc"></textarea>
             <input type='hidden' name='qry_type' value='additem'>
          </td>
        </tr>
        <tr class="tr">
          <td colspan="2">Type<span style='color:red;'>*</span>
          </td>      
          <td colspan="2">
             <select name="tbl_type" id="invent_type" style="border:#F0F0F0 1px solid;height: 31px;">
                     <!-- <option value='Select'>Select</option>  -->
                     <? $sql = "SELECT name FROM `tbl_type` group by name"; 
                        $result = $conn->query($sql); 
                       while($row = $result->fetch_object()){?>
                      <option value='<?=$row->name?>'><?=$row->name?></option>
                     <? } ?>
             </select>

                <input type='hidden' name='qry_type' value='additem'>
          </td>
        </tr>
        <tr class="tr">
          <td colspan="2">Category<span style='color:red;'>*</span>
          </td>      
          <td colspan="2">
             <select name="in_category" id="in_category" style="border:#F0F0F0 1px solid;height: 31px;">
                     <? $sq = "SELECT category FROM `tbl_type` where name = 'Clothing' group by category"; 
                        $resu = $conn->query($sq); 
                       while($cat = $resu->fetch_object()){?>
                      <option value='<?=$cat->category?>'><?=$cat->category?></option>
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
                    $res =$conn->query("select *,replace(name,'/','_') as `value` from tbl_size");
                    while($obj = $res->fetch_object()){ ?>
                      <option value='<?=$obj->value?>'><?=$obj->name?></option>
                      <?
                      }
                      ?>
              </select>

                <input type='button' id='addsize_btn' onclick="nxtsize($('#size').val());" value='Add' > 
     <!--  <img src="image/add.png" id='addsize_btn' style="width:31px;" onclick="nxtsize('0',$('#size').val());">-->
          </td>
        </tr>
        <tr class="tr" id="row_head" style="display:none;" >
           <td>Size<span style='color:red;'>*</span></td>
           <td>Cost(Per Unit)<span style='color:red;'>*</span></td>
           <td>Quantity<span style='color:red;'>*</span></td>
           <td>Reorder<span style='color:red;'>*</span></td>
       
         </tr>
        <datalist id='number'> 
            <? for($i=0;$i<=100;$i++){ ?>
                  <option value="<?=$i?>">
            <? } ?>
        </datalist>

</table>
<table border="0" class="demo-table" class="table table-responsive table-hover" style="width: 70%; margin: 0px;">
    <tr class="tr">
      <td  colspan= "2">Image</td>
      <td  colspan= "2"><input type="file" name="fileToUpload" id="fileToUpload" style="width:83%;">
      </td>
    </tr>
    <tr>
      <td  colspan= "4" style= "text-align:center;margin-bottom:5%;height:a5px">
        <input type="button"  value="Submit" class="btn btn-primary" onclick = "validateForm()">
        <a href="home.php" name="close" id="close"  class="btn btn-primary" >Close</a>
      </td>
    </tr> 
</table>
</form>
</center>
<? include 'footer.php';?>
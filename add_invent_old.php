<?
session_start();?>
<?php include_once'header.php';?>
<?php include_once'connect.php';?>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<?
if($_POST['qry_type']=='additem')
{

  $already = $conn->query("select *,count(*) as rowcount from inventory where description = '".$_POST['desc']."' and size = '".$_POST['size']."'")->fetch_object();
 if($already->rowcount==1){
    if($already->isactive==0){
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
      }

        $update_qry = "update inventory set
                              cost = '".trim($_POST['cost'])."',
                              qty = '".$_POST['qty']."',".$imgqry."
                              img_url = '".$target_file."',
                              invent_type = '".$_POST['invent_type']."',
                              `user` = '".$_SESSION['LOGIN_USERID']."',
                              isactive = '1',
                              updated = now() 
                              where description = '".trim($_POST['desc'])."' and size = '".$_POST['size']."'";
        $conn->query($update_qry);
        $err="Requested Inventory activated";
    }else{
      $err="Inventory already exist with this Description and size";
  }
 }else{//fresh insertion
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
   // echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
   // echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $err = "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
   
        } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
}else{
    $target_file = '';
}//if image added
$sql ="insert into inventory set                             
                              size = '".$_POST['size']."',
                              cost = '".trim($_POST['cost'])."',
                              description = '".trim(str_replace('"',' ',$_POST['desc']))."',
                              qty = '".$_POST['qty']."',
                              invent_type = '".$_POST['invent_type']."',
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
						 qty = '".$_POST['qty']."',
						 user_id = '".$_SESSION['LOGIN_USERID']."',
						 deviceType = 'Web',
						 created = now()");          
            $err = "new inventory inserted";
            echo $err;
            header('location:view_invent.php');
          }

 }///insert

}
?>
      <script>
function validateForm(){  

if ($("#cost").val().trim() == '')
    {
      alert('Please enter a valid cost.');
       $("#cost").focus()
    }
    else if (!($.isNumeric($("#cost").val())))
    {
      alert('Please enter a valid cost.');
       $("#cost").focus()
    }
 /*else if ($("#fileToUpload").val().trim() == '')
    {
      alert('Please select an image.');
        $("#itemcode").focus()
          }*/
          else if ($("#desc").val().trim() == '')
    {
      alert('Please enter description.'); 
        $("#desc").focus()
          }
           else if ($("#qty").val().trim() == '')
    {
      alert('Please enter quantity.'); 
        $("#qty").focus()
          }
          else{
         $("#additem").submit();
      }
        
    }

    $(function(){
    $("#desc").on('keyup', function(){
      this.value=this.value.replace(',', "").replace("'", "").replace("#", "").replace('"', "").replace(':', "").replace(';', "").replace('\n', " ").replace('  ', " ").replace('&', "").replace('%', "").replace('/', "");
    });
   $("#qty").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

  });
    $(document).ready(function() {

    $('#desc,#size,#cost,#qty,#fileToUpload').on('keypress', function (e) {
  if (e.which == 13) {
   validateForm();
    return false;    //<---- Add this line onclick="validateForm()"
  }
});
 
        $('#cost').keypress(function (event) {
            return isNumber(event, this)
        });
    });
   
    function isNumber(evt, element) {
        var charCode = (evt.which) ? evt.which : event.keyCode

        if (
            (charCode != 189 || $(element).val().indexOf('-') != -1) &&      
            (charCode != 46 || $(element).val().indexOf('.') != -1) && 
            (charCode < 48 || charCode > 57)){   

            return false;
            }
             var asd = $(element).val().split('.'); 
              //alert(asd);
              if(asd[1].length>1){
                return false;
              }
        return true;
    }

   /* $(document).ready(function(){
    $("#cost").keypress(function (e) { 
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        $("#add_d_phone_errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
 }
   });   
   });*/
 
</script>
<center><div style="color:red;font-size:17px;margin-bottom:-13px;"><? echo $err;?></div></ceenter>
	<form  id="additem" name="myForm" role="form" action = "add_invent.php" method ="post"  enctype="multipart/form-data">
	<center>	      
	<table border="0" class="demo-table" class="table table-responsive table-hover" style="width: 58%;border:3px ridge green;margin-top: 40px;">
   <tr><th style= "text-align: center; color:blue; font-size: 20px;padding-top: 10px;" colspan=2>Add Item</th>
    </tr>
		<!-- <tr class="tr"><td><strong>Item Code</strong></td>
		<td><input type = "text" class="demoInputBox" name="itemcode" id="itemcode">
    <input type='hidden' name='qry_type' value='additem' ></td>

		</tr> -->
    <input type='hidden' name='date' value='<?=date('Y-m-d H:i:s')?>' >
    <tr class="tr"><td>Description<span style='color:red;'>*</span></td>
    <td><textarea rows="3" style="height:auto;" class="demoInputBox" name="desc" id="desc"></textarea>
        </td>
    </tr>
 		<tr class="tr"><td>Type<span style='color:red;'>*</span></td>      
		<td>
          <select id="invent_type" name="invent_type" style="border:#F0F0F0 1px solid;height: 31px;">
              <option>Clothing</option>
              <option>Medical</option>
          </select>
        <input type='hidden' name='qry_type' value='additem'></td>
		</tr>
		<tr class="tr"><td>Size<span style='color:red;'>*</span></td>      
		<td>
          <select id="size" name="size" style="border:#F0F0F0 1px solid;height: 31px;">
              <option value="S">S</option>
              <option value="M">M</option>
              <option value="L">L</option>
              <option value="XL">XL</option>
              <option value="XXL">XXL</option>
          </select>
        <input type='hidden' name='qry_type' value='additem'></td>
		</tr>
		
 		<tr class="tr"><td>Cost(Per Unit)<span style='color:red;'>*</span></td>
 		<td>$<input type ="text"  class="demoInputBox" style="width:66%;" name="cost" id="cost">
      <!-- <span id="add_d_phone_errmsg"></span> --></td>
       </tr> 
 		
        <tr class="tr"><td>Quantity<span style='color:red;'>*</span></td>
    <td>

      <input id="qty" name="qty" type='text' list='number' style="border:#F0F0F0 1px solid;height: 31px;">
      <datalist id='number'> 
        <? for($i=0;$i<=100;$i++){ ?>
              <option value="<?=$i?>">
                <? } ?>
          </datalist>
        </td>
    </tr>
    <tr class="tr"><td>Reorder<span style='color:red;'>*</span></td>
    <td>

      <input id="reorder" name="reorder" type='text' list='number' style="border:#F0F0F0 1px solid;height: 31px;" value="10">
    <datalist id='number'> 
        <? for($i=0;$i<=100;$i++){ ?>
              <option value="<?=$i?>">
                <? } ?>
          </datalist></td>
    </tr>
    <tr class="tr"><td>Image</td>
    <td><input type="file" name="fileToUpload" id="fileToUpload">
        </td>
    </tr>
        <!-- <tr class="tr"><td>Role</td>
        <td><select name="role" id="role" class="demoInputBox" >
            <option value="" >Select</option>
            <option value="Admin" >Admin</option>
            <option value="User" >User</option>        
         </td>
        </select>
        </tr> -->
        
        <tr>
		<td  colspan= "2" style= "text-align:center;margin-bottom:5%;height:a5px">
      <input type="button"  value="Submit" class="btn btn-primary" onclick = "validateForm()">
        <a href="home.php" name="close" id="close"  class="btn btn-primary" >Close</a>
        </td>
	</tr>
	 		</table>
</center>
</form>
<? include 'footer.php';?>
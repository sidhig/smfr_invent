<?php include_once'header.php';?>
<?php include_once'connect.php';?>
<?

      if($_POST['qry_type']=='edit')
      
{
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

   $res = $conn->query("select img_url from inventory where id='".$_REQUEST['edit_id']."'")->fetch_object(); ;
   unlink($res->img_url);
   $imgqry = " img_url = '".$target_file."', ";
   } } }
  $update_qry = "update inventory set                            
                              size = '".$_POST['size']."',
                              cost = '".trim($_POST['cost'])."',
                              description = '".trim($_POST['desc'])."',
                              qty = '".$_POST['qty']."',".$imgqry."
                              `user` = '".$_SESSION['LOGIN_USERID']."',
                              invent_type = '".$_POST['invent_type']."',
                              isactive = '1',
                              updated = now() 
                              where id='".$_REQUEST['edit_id']."'";

         if($conn->query($update_qry)){
          $conn->query("insert into `log` set 
                       `type` = '2',
                       order_id='0',
                       item_id = '".$_REQUEST['edit_id']."',
                       qty = '".$_POST['diff']."',
                       user_id = '".$_SESSION['LOGIN_USERID']."',
                       deviceType = 'Web',
                       created = now()");
            $err = " inventory updated";
            echo $err;
           header('location:view_invent.php');
          }else{
            $err = "Inventory already exist with this description.";
            //echo $err ;
          }
    
}

if($_POST['edit_id']!=''){
  $obj =$conn->query("SELECT * FROM `inventory` where id = '".@$_POST['edit_id']."'")->fetch_object();
}else{
    header('location:view_invent.php');

}
?>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
     
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
         $("#editinvent").submit();
      }
       
    
    }
      $(function(){
    $("#desc").on('keyup', function(){
      this.value=this.value.replace(',', "").replace("'", "")
      .replace("#", "").replace('"', "").replace(':', "").replace(';', "").replace('\n', " ").replace('  ', " ").replace('&', "").replace('%', "").replace('/', "");
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
        $("#qty").on('input', function() {current
$("#diff").val($("#qty").val()-$("#current").val());qty
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
</script>
<center><div style="color:red;font-size:17px;margin-bottom:-13px;"><? echo $err;?></div></ceenter>
  <form  id="editinvent" name="myForm" role="form" action = "edit_invent.php" method ="post"  enctype="multipart/form-data">
  <center>        
  <table border="0" class="demo-table" class="table table-responsive table-hover" style="width: 58%;border:3px ridge green;margin-top: 40px;">
   <tr><th style= "text-align: center; color:blue; font-size: 20px;padding-top: 10px;" colspan=2>Update Inventory</th>
    </tr>
    <!-- <tr class="tr"><td><strong>Item Code</strong></td>
    <td><input type = "text" class="demoInputBox" name="itemcode" id="itemcode">
    </td>

    </tr> -->
    <tr class="tr"><td>Description<span style='color:red;'>*</span></td>
    <td><textarea rows="3" style="height:auto;" class="demoInputBox" name="desc" id="desc" ><?=$obj->description?></textarea>
      <input type='hidden' name='qry_type' value='edit' >
      <input type='hidden' name='edit_id' value='<?=($obj->id)?>'>
        </td>
    </tr>
	<tr class="tr"><td>Type<span style='color:red;'>*</span></td>      
		<td>
          <select id="invent_type" name="invent_type" style="border:#F0F0F0 1px solid;height: 31px;">
              <option <? if($obj->invent_type=='Clothing'){ echo 'selected';} ?>>Clothing</option>
              <option <? if($obj->invent_type=='Medical'){ echo 'selected';} ?>>Medical</option>
          </select>
        <input type='hidden' name='qry_type' value='additem'></td>
	</tr>
    <tr class="tr"><td>Size<span style='color:red;'>*</span></td>      
    <td>
          <select id="size" name="size" style="border:#F0F0F0 1px solid;height: 31px;">
              <option <? if($obj->size=='S'){ echo 'selected';} ?>>S</option>
              <option <? if($obj->size=='M'){ echo 'selected';} ?>>M</option>
              <option <? if($obj->size=='L'){ echo 'selected';} ?>>L</option>
              <option <? if($obj->size=='XL'){ echo 'selected';} ?>>XL</option>
              <option <? if($obj->size=='XXL'){ echo 'selected';} ?>>XXL</option>
          </select>
           </tr>
    
    <tr class="tr"><td>Cost(Per Unit)<span style='color:red;'>*</span></td>
    <td>$<input type ="text"  class="demoInputBox" style="width:66%;" name="cost" id="cost" value="<?=$obj->cost?>">
        </td>
    </tr>
        <tr class="tr"><td>Quantity<span style='color:red;'>*</span></td>
    <td>

      <input id="qty" name="qty" type='number' list='number' style="border:#F0F0F0 1px solid;height: 31px; background-color:white;" value='<?=($obj->qty)?>' min='0'>
      <datalist id='number'> 
        <? for($i=0;$i<=100;$i++){ ?>
              <option value="<?=$i?>">
                <? } ?>
          </datalist>
        </td>
    </tr>
    <tr><td></td>
      <td style="padding-top: initial;">
    <i>Current Quantity : <?=($obj->qty)?></i>
    <input id="current" name="qty" type="hidden" style="border:#F0F0F0 1px solid;height: 31px;width:63px; background-color:white;" value='<?=($obj->qty)?>' disabled>
    <input id="diff" name="diff" type="hidden" list="number" > </td></tr>
    <tr class="tr"><td>Reorder<span style='color:red;'>*</span></td>
    <td>

      <input id="reorder" name="reorder" type='text' list='number' style="border:#F0F0F0 1px solid;height: 31px;" value='<?=($obj->reorder)?>'>
      <datalist id='number'> 
        <? for($i=0;$i<=100;$i++){ ?>
              <option value="<?=$i?>">
                <? } ?>
          </datalist>
    </td>
    </tr>
    
    <tr class="tr"><td style="padding-bottom: 43px;">Image</td>
    <td><input type="file" name="fileToUpload" id="fileToUpload" ><? if(file_exists($obj->img_url) && $obj->img_url!=''){ ?>
  <img src = "<?=$obj->img_url?>" style="width:10vw;">
  <? }else{?>
    <img src = "uploads/default.png" style="width:5vw;">
  <?} ?>
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
      <input type="button" value="Save" class="btn btn-primary" onclick="validateForm()">
      <a href="view_invent.php" name="close" id="close"  class="btn btn-primary" >Close</a>
        </td>
	</tr>
	 		</table>
</center>
</form>
<? include 'footer.php';?>
<?php include_once'header.php';?>
<? include 'connect.php';?>

<?if($_POST['edit_id']!=''){
  //echo "SELECT * FROM `tbl_login` where id = '".@$_POST['edit_id']."'";
 $obj = $conn->query("SELECT * FROM `tbl_user` where id = '".@$_POST['edit_id']."'")->fetch_object();
}

?>

  <script >
    
/*function validateEmail(x) {
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
        //alert("Not a valid e-mail address");
        return true;
    }else{
      return false;
    }
}*/
function validateForm(){ 
  if ($("#username").val().trim() == '')
    {
     alert('User Name is a required field. It is empty!');
     $("#username").focus()
    }  
        
   /* else if ($("#password").val().trim() == '')
    {
      alert('Password is a required field. It is empty!');
     $("#password").focus();
    }
     */
    /*else if (validateEmail($("#email").val().trim()))
    {
      alert('Please enter a valid Email');
     $("#email").focus();
    }   */
      else if (!($.isNumeric($("#phone").val().trim()))) {   

       alert("Enter valid numeric Phone number.");
     $("#phone").focus();
      }  
      else if ( $("#phone").val().length!=10) {   
       alert("Enter 10 digit Tracker Phone number.");
     $("#phone").focus();
      }
      else
      {
         $("#updateinfo").submit();
      }
    }
    $(document).ready(function(){
    $('#username,#password,#email,#phone,#role').on('keypress', function (e) {
  if (e.which == 13) {
   validateForm();
    return false;    //<---- Add this line onclick="validateForm()"
  }
});
    });

function AllowOnlyNumbers(e) {

    e = (e) ? e : window.event;
    var key = null;
    var charsKeys = [ 97,65, 99, 67, 118, 86,115,83, 112,80]; 
    
    var specialKeys = [8,9,27,13,35,36,37,39,46,45]; 
     key = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
 
    if (key && key < 48 || key > 57) {

              if ((e.ctrlKey && charsKeys.indexOf(key) != -1) ||
               (navigator.userAgent.indexOf("Firefox") != -1 && ((e.ctrlKey && e.keyCode && e.keyCode > 0 && key >= 112 && key <= 123) || (e.keyCode && e.keyCode > 0 && key && key >= 112 && key <= 123)))) {
            return true
        }
           
        else if (specialKeys.indexOf(key) != -1) {
           
            if ((key == 39 || key == 45 || key == 46)) {
                return (navigator.userAgent.indexOf("Firefox") != -1 && e.keyCode != undefined && e.keyCode > 0);
            }
              
            else if (e.shiftKey && (key == 35 || key == 36 || key == 37)) {
                return false;
            }
            else {
                return true;
            }
        }
        else {
            return false;
        }
    }
    else {
        return true;
       }
    }
     function checkornot() {

                     if (document.getElementById('chk1').checked) {
                          $('#password').attr('type', 'text');
                      } 
                      else
                       {
                   $('#password').attr('type', 'password');
       }
     }  
</script>

<form  id="updateinfo" role="form" name="myForm" action="view_user.php" method ="post" style= "margin-top:60px;">
  <center> 
  <table border="0" class="demo-table" class="table table-responsive table-hover" style="width: 51%;border:3px ridge green;margin-top: 40px;">
     <tr><th style= "text-align: center; color:blue; font-size: 20px;padding-top: 10px;" colspan=2>Update User</th>
    </tr>
      <tr class="tr"><td>User Name<span style='color:red;'>*</span></td>

    <td> <INPUT TYPE="hidden" NAME="edit_id" value="<?=$obj->id?>">
      <INPUT TYPE="hidden" NAME="query_type" value="edit">
      <input type = "text" class="demoInputBox" name="username" id="username" value="<?=$obj->username?>" readonly></td>
    </tr>
    <tr class="tr"><td>Email<span style='color:red;'>*</span></td>
    <td><input type ="text"  class="demoInputBox" name="email" id="email" value="<?=$obj->email?>" readonly>
     </td>
    </tr> 
  <!-- <tr class="tr"><td>Password<span style='color:red;'>*</span></td>
    <td><input type ="password"  class="demoInputBox" name="password" id="password" value=""></td></tr>
    <tr><td></td>
    <td style="padding-top: initial;"><input type="checkbox" name="password" id="chk1" onchange="checkornot();">show password</td>
      </tr>  --> 
    <tr class="tr"><td>Phone<span style='color:red;'>*</span></td>
    <td><input type ="text"  class="demoInputBox" name="phone" id="phone" value="<?=$obj->phone?>" onkeypress="return AllowOnlyNumbers(event);"></td>
    </tr>  
     
    
    <tr class="tr"><td>Role<span style='color:red;'>*</span></td>
    <td>
          <select id="role" name="role" style="border:#F0F0F0 1px solid;height: 31px;">
              <option value='1' <? if($obj->role=='1'){ echo selected;} ?>>User</option>
              <option value='2' <? if($obj->role=='2'){ echo selected;} ?>>Admin</option>            
          </select></td>
    </tr>
    <!-- <tr class="tr"><td>Push Id</td>
    <td><input type ="text"  class="demoInputBox" name="pushid" id="pushid" value="<?=$obj->pushid?>">
        </td>
    </tr>
    <tr class="tr"><td>Device Id</td>
    <td><input type ="text"  class="demoInputBox" name="deviceid" id="deviceid" value="<?=$obj->deviceid?>">
        </td>
    </tr> -->
        <!-- <tr class="tr"><td>Role</td>
        <td><select name="role" id="role" class="demoInputBox" value="<?=$obj->role?>">
            <option value="" >Select</option>
            <option value="1" >Admin</option>
            <option value="0" >User</option>
        </td>
        </select>
        </tr> -->
        <tr>
    <td  colspan= "2" style= "text-align:center;margin-bottom:5%;">
      <input type="button" value="Save" class="btn btn-primary" onclick="validateForm()"></input>
  <a href="view_user.php" name="close" id="submit"  class="btn btn-primary" >Close</a></td>
  </tr>
      </table>
</center>
</form>
<? include 'footer.php';?>

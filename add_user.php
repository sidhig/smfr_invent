<?php include_once'header.php';?>
<?php include_once'connect.php';?>
	<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>-->
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
     <?
     //print_r($_POST);
   if($_POST['qry_type']=='new')
{
  
  $already = $conn->query("select count(*) as rowcount from tbl_user where email = '".$_POST['email']."'")->fetch_object();
 
if($already->rowcount){
$err= "User already exist with this Email";
}
else {
   $sql ="insert into tbl_user set 
              username = '".trim($_POST['username'])."',
              password = '".md5(trim($_POST['password']))."',
              phone = '".trim($_POST['phone'])."',
              email = '".trim($_POST['email'])."',
              role = '".$_POST['role']."',
              created = now(),
              updated = now()";
                              
          if($conn->query($sql)){
            $err = "new user inserted";
            echo $err;
            header('location:view_user.php');
          }else{
            $err = "User already exist.";
            //echo $err;
          }
        }
         } 
          ?>

      <script>
    /**/
    function validateEmail(x) {
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
        //alert("Not a valid e-mail address");
        return true;
    }else{
      return false;
    }
}
function validateForm(){ 
  //alert('test');
  if ($("#username").val().trim() == '')
    {
     alert('User Name is a required field. It is empty!');
     $("#username").focus()
    }  
        
    else if ($("#password").val().trim() == '')
    {
      alert('Password is a required field. It is empty!');
     $("#password").focus();
    }
     /*else if ($("#password").val().length < 6)
    {
      alert('Password must contain at least six characters!!');
     $("#password").focus();
    }
     else if ($("#password").val()!=/^(?=.*[0-9_\W]).+$/)
    {
      alert('Password must contain at least one special character!');
     $("#password").focus();
    }*/
     
    else if (validateEmail($("#email").val().trim()))
    {
      alert('Please enter a valid Email');
     $("#email").focus();
    }   
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
         $("#addsmfr").submit();
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
     /*$(document).ready(function(){*/
     function checkornot() {

                     if (document.getElementById('chk1').checked) {
                          $('#password').attr('type', 'text');
                      } 
                      else
                       {
                   $('#password').attr('type', 'password');
       }
     }  
     
    /* $("#pushid").keypress(function (e) { 
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        $("#add_d_phone_errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
 }
   });   
   });*/                                                                    
   </script>
<center><div style="color:red;font-size:17px;margin-bottom:-13px;"><? echo $err;?></div></ceenter>
	<form  id="addsmfr" name="myForm" role="form" action = "add_user.php" method ="post" style= "margin-top:60px;">
	<center>	      
	<table border="0" class="demo-table" class="table table-responsive table-hover" style="width: 51%;border:3px ridge green;margin-top: 40px;">
   <tr><th style= "text-align: center; color:blue; font-size: 20px;padding-top: 10px;" colspan=2>Add User</th>
    </tr>
		<tr class="tr"><td>Username<span style='color:red;'>*</span></td>
		<td><input type = "text" class="demoInputBox" name="username" id="username" placeholder='Username'>
    <input type='hidden' name='qry_type' value='new' ></td>

		</tr>
 		<tr class="tr"><td>Password<span style='color:red;'>*</span></td>
		<td><input type ="password"  class="demoInputBox" name="password" id="password" placeholder='Password' style="width: 68%;"></td></tr>
    <tr><td></td>
    <td style="padding-top: initial;"><input type="checkbox" name="password" id="chk1" onchange="checkornot();">show password</td>
  		</tr>
    <tr class="tr"><td>Phone<span style='color:red;'>*</span></td>
    <td><input type ="text"  class="demoInputBox" name="phone" id="phone" placeholder='Phone' onkeypress="return AllowOnlyNumbers(event);"></td>
    </tr>
		
 		<tr class="tr"><td>Email<span style='color:red;'>*</span></td>
 		<td><input type ="text"  class="demoInputBox" name="email" id="email" placeholder='Email'>
        </td>
        <tr class="tr"><td>Role<span style='color:red;'>*</span></td>
    <td>
          <select id="role" name="role" style="border:#F0F0F0 1px solid;height: 31px;">
              <option value="1">User</option>
              <option value="2">Admin</option>            
          </select></td>
    </tr>
   <!-- 
 		 </tr>
    <tr class="tr"><td>Push Id</td>
    <td><input type ="text"  class="demoInputBox" name="pushid" id="pushid">
        <span id="add_d_phone_errmsg"></sapn></td>
    </tr>
    <tr class="tr"><td>Device Id</td>
    <td><input type ="text"  class="demoInputBox" name="deviceid" id="deviceid">
        </td>
    </tr> -->
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
      <input type="button" value="Submit" class="btn btn-primary" onclick="validateForm()">
      
       <a href="home.php" name="close" id="submit"  class="btn btn-primary" >Close</a>
     </td>
	</tr>
	 		</table>
</center>
</form>
<? include 'footer.php';?>
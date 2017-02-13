
<?php include_once'connect.php';?>
	<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>-->
   
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
     <?
    // print_r($_GET);
  ?>

      <script>
    
   
function validateForm(){ 

  if ($("#password").val().trim() == '')
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
     
else if ($("#password").val()!= $("#confirmpassword").val())
    {
      alert('Please enter a valid confirmpassword');
     $("#confirmpassword").focus();
    }   
      else
      {
         $("#addsmfr").submit();
      }
    }
    $(document).ready(function(){
    $('#password,#confirmpassword').on('keypress', function (e) {
  if (e.which == 13) {
   validateForm();
    return false;    //<---- Add this line onclick="validateForm()"
  }
});
    });


                                                                        
   </script>
<style>
.modal a.close-modal {
    position: absolute;
    top: -0.5px;
    right: -2.5px;
    display: block;
    width: 30px;
    height: 30px;
  }
</style>
<center><div  style=""></center>
	<form  id="addsmfr" name="myForm" role="form" action = "view_user.php" method ="post" style= "margin-top:60px;">
	<table border="0" class="demo-table" class="table table-responsive table-hover" style="border:3px ridge green;margin-top: -28px;margin-left:1px">
   <tr><th style= "text-align: center; color:blue; font-size: 20px;padding-top: 10px;" colspan=2>Change Password</th>
    </tr>
    
    <tr class="tr"><td>Password<span style='color:red;'>*</span></td><td><input type ="password"  class="demoInputBox" name="password" id="password" placeholder='Password' ></td>
  <input type='hidden' name='qry_type' value='password' >
  <input type='hidden' name='pass_id' value='<?=$_GET['id']?>' ></td>
</tr>
<tr class="tr"><td>Confirm Password<span style='color:red;'>*</span></td>
    <td><input type ="text"  class="demoInputBox" name="confirmpassword" id="confirmpassword" placeholder='Confirm Password' style="width: 68%;"></td></tr>
        </td>
      </tr>
        <tr>
  <td  colspan= "2" style= "text-align:center;margin-bottom:5%;height:a5px">
      <input type="button" value="Save" class="btn btn-primary"  onclick="validateForm()">


        </tr>
  </table>

</form>
</div>
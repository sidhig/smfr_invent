 
<? include_once'connect.php';?>
<? include_once'header.php';?>
<? 

 if($_POST['query_type']=='edit')
{
        if($_POST['edit_id']!='')
    {
  
 $sql ="update tbl_user set 
                              username = '".trim($_POST['username'])."',
                              
                              phone = '".trim($_POST['phone'])."',
                               email = '".$_POST['email']."',
                              role = '".$_POST['role']."',
                              updated = now() 
                              where id='".$_POST["edit_id"]."'";
                              
                              
          if($conn->query($sql)){
            $err = " Successfully updated";
          }
          else{
            $err = "sql error";
          }
           
        }
      }
      else if(@$_POST['id']!=''){
$conn->query("delete from tbl_user where id = '".$_POST['id']."' ");  
$err = "user deleted";
}
     else if(@$_POST['qry_type']=='password' and $_POST['pass_id']!=''){
$conn->query("update tbl_user set 
                              password = '".md5(trim($_POST['password']))."' where id = '".$_POST['pass_id']."' ");  
$err = "user password updated.";
}

?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <link rel="stylesheet" href="jquery.modal.css" >
  <script src="jquery.modal.js" ></script>
<script type="text/javascript" src="js/drop.js"></script>
<script type="text/javascript">
function delformsubmit(id){
  if(confirm("Are you sure delete this data")){
    $("#del_"+id).submit();
    //alert('deleted');
  }

}
$(document).ready(function(){
$("#filter_user").on('input',function(){
  filter($("#user"),$("#filter_user"));
});
});

</script>
<style>
tr{
  text-align: center;
}
</style>
<center><div id="user_tbl" style="margin-top: 26px;">
  <h1 style="color:blue;">All User Record</h1>
  <strong>Search:</strong>
 <input id='filter_user' type="text" list='desc_list' style="width: 25vw;">
<table class="table table-hover" style="width: 85%;border:3px ridge green;margin-top:7px;">
   <tr class="danger" style='color:blue;'>
   	<th style="text-align:center">Username</th>
   	<!--<th style="text-align:center">Password</th> -->
    <th style="text-align:center">Phone</th>
    <th style="text-align:center">Email</th>
    <th style="text-align:center">Role</th>
   <!-- <th style="text-align:center">Pushid</th>
  <th style="text-align:center">Deviceid</th>  	
  	<th>Role</th> -->
   	<th style="text-align:center">Updated On</th>
    <th style="text-align:center"> Password</th>
    <th style="text-align:center">Edit</th>
    <th style="text-align:center">Delete</th>   
   
   </tr>
   <tbody id='user'>

<?  $sql = "SELECT id,username,phone,email,role,DATE_ADD(updated, interval ".$offset_time." minute) as `updated` FROM `tbl_user` where username!='admin' order by id desc";
if ($result = $conn->query($sql)) { 
        while($obj = $result->fetch_object()){ 
//while ($row = mysql_fetch_array($result)) { ?>

<tr class="success">
 <td><?=$obj->username?></td>    
      <!--<td><?=($obj->password)?></td>--> 
      <td><?=($obj->phone)?></td>
      <td><?=($obj->email)?></td>
      <td>
      <? $role=$obj->role;
    if( $role =="2")
    echo "Admin";
    else{
      echo"User";
    }?>
    </td>
       <!--<td><?=($obj->pushid)?></td>
      <td><?=($obj->deviceid)?></td>
         
    <td><?=($obj->role)?></td> -->
    <td><?$source= $obj->updated;
$date = new DateTime($source);
echo $date->format('m-d-Y h:i A')?></td>
 
   <td> <!-- <form action='change_password.php' method='post' rel="modal:open">
    <input type='hidden' name='edit_id' value='<?=($obj->id)?>'>
    <input type="submit" class="btn btn-primary" id="edit" rel="modal:open" value='Password'>
      </form> --><a href="change_password.php?id=<?=($obj->id)?>" rel="modal:open"> <input type="button" class="btn btn-primary" id="<?=$obj->id?>" value="Password"></a>
    </td>
 
   <td> <form action='edit_user.php' method='post'>
    <input type='hidden' name='edit_id' value='<?=($obj->id)?>'>
    <input type="submit" class="btn btn-primary" id="edit" value='Edit'>
      </form>
    </td>
<td><form id='del_<?=($obj->id)?>' action='view_user.php' method='post'>
  <input type='hidden' name='id' value='<?=($obj->id)?>'>
  <input type="button" onclick='delformsubmit(<?=($obj->id)?>)' class="btn btn-primary" id="delete"value="Delete">
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
<? 
include_once'connect.php';?>
<? include_once'header.php';?>
<?  

if($_POST['del_id']!=''){

$res = $conn->query("select * from req_table where id = '".$_POST['del_id']."'");
if($res->num_rows>0){
   $user = $res->fetch_object();
   $res = $conn->query("update inventory set qty = qty+".$user->qty.",updated=now() where id = '".$user->item_id."'");
   $conn->query("delete from req_table where id = '".$_POST['del_id']."'");
}
$err= "<span style='color:green;font-size:1.6rem;' ><b>Deleted Successfully</b></span>"; 
}

if($_POST['order_id']=='' || $_POST['order_id']==null){
  header('location:pull_invent.php'); 
}


?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript">
function getTimeZone() {
    var offset = new Date().getTimezoneOffset(),
        o = Math.abs(offset);
    return (offset < 0 ? "+" : "-") + o;
}

$(document).ready(function(){
$("#filter_desc").on('input',function(){
  filter($("#inventry_tbl"),$("#filter_desc"));
});
});
</script>

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

        function validateForm() {
          //alert("asd");
          if($("#fname").val().trim() == '' || $("#fname").val().trim() == null ) {
                $("#fname").css('border','1px solid red');
                $("#fname").focus();
           }
          else if($("#lname").val().trim() == '' || $("#lname").val().trim() == null ) {
                          $("#lname").css('border','1px solid red'); 
                          $("#lname").focus();
             }

          else if($("#station").val().trim() == '' || $("#station").val().trim() == null ) {
                $("#station").css('border','1px solid red');
                $("#station").focus();
          }
                    
          else if($("#engine").val().trim() == '' || $("#engine").val().trim() == null ) {
                $("#engine").css('border','1px solid red'); 
                $("#engine").focus();
           
            }

  
  else
  {               
    $.ajax({
              url: "set_log.php",
              cache: false,
              type:'post',
              data :$('#set_log').serialize(),
              success: function(data){
               alert(data);
               window.location.href = 'pull_invent.php';
              },
              error: function(err){
               alert(err);
              }
               });
  }
}
    $(document).ready(function(){
    $("#fname").on('input',function(e){
         $("#fname").css('border','1px solid #CCC');
      });
      $("#lname").on('input',function(e){
         $("#lname").css('border','1px solid #CCC');
         
      });
      $("#station").on('input',function(e){
         $("#station").css('border','1px solid #CCC');
         
      });
      $("#engine").on('input',function(e){
         $("#engine").css('border','1px solid #CCC');
         
      });
       });
   
    </script>

    <script type="text/javascript">
function del_key(id){
if(confirm("Are you want to sure to delete?")){
  $("#form_"+id).submit();
  
}
}
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
    height: 30px;
  }
</style>

<center><div id="user_tbl" style="margin-top: 26px;">
  <h1 style="color:blue;">Pull Inventory</h1>
<h4><b>Order Id</b>: <?=$_POST['order_id']?><h4>
<table class="table table-hover" style="width:70%;border:3px ridge green;margin-top: 1vh;" >
   <tr class="danger" style='color:blue;'>
    <th style="text-align:center">Description</th> 
   	<th style="text-align:center">Size</th> 
   	<th style="text-align:center">Cost</th>
     <th style="text-align:center">Quantity</th>
      <th style="text-align:center">Remove</th>
     </tr>
<tbody id='inventry_tbl'>
<? $sql = "select req_table.id,req_table.order_id,req_table.qty, inventory.description,inventory.size,inventory.cost from `req_table` left join `inventory`  on req_table.item_id = inventory.id where order_id= '".$_POST['order_id']."'";

if ($result = $conn->query($sql)) { 
  if( $result->num_rows>0){
    $total_cost = 0;
  $total_qty = 0;

        while($obj = $result->fetch_object()){ ?>
<tr class="success" id="<?=$obj->id?>">
     <td ><?=($obj->description)?></td> 
    <td><?=($obj->size)?></td>
    <td>$<?=number_format($obj->cost,2)?></td>
    <td><? echo $qty=($obj->qty); $total_qty = $total_qty +$qty; ?></td>
    <td>
    <form action="cart.php" method='post' id='form_<?=$obj->id?>' >
     <input type="hidden" name="del_id" value="<?=$obj->id?>">
     <input type="hidden" name="order_id" value="<?=$obj->order_id?>">
       <input onclick='del_key("<?=$obj->id?>")' type="button" class="btn btn-primary"  value="Remove" >
   </form>
 </td>
  </tr>
  
<? }  }
else{ ?>
<tr class="success">
<td colspan="5" style="color:red;">No item added.</td> </tr>
<?}}?>
</tbody>
</table>
</div>

<form method="post" action="pull_invent.php">
  <input type="hidden" name="order_id" value="<?=$_POST['order_id']?>">
<input type="submit"  class="btn btn-primary" id="cancel" value="Add More Items">
</form>
<form id="set_log">
  <table>
    <tr >
      <td>
        <input type="hidden" name="order_id" value="<?=$_POST['order_id']?>">
        <input type="hidden" name="req_time" value="<?=date('Y-m-d h:i:s')?>">
        <input type = "text" name="fname" id="fname" class="demoInputBox" placeholder='First Name'style="width:90%;margin-top: 15px;">
      </td>
      <td>
        <input type = "text" name="lname" id="lname" class="demoInputBox" placeholder='Last Name'style="width:90%;margin-top: 15px;">
      </td>
    </tr >
    <tr >
      <td>
        <input type = "text" name="station" id="station" class="demoInputBox" placeholder='Station' style="width:90%;margin-top: 15px;">
      </td>
      <td>
        <input type = "text" name="engine" id="engine" class="demoInputBox" placeholder='Engine' style="width:90%;margin-top: 15px;">
      </td>
    </tr>
    </table>
    <input type="button" onclick='validateForm();' class="btn btn-primary" id="pull" value="Pull" style="margin-top: 15px;">
    <a href="pull_invent.php"><input type="button"  class="btn btn-primary" id="cancel" value="Cancel" style="margin-top: 15px;"></a>
</center>
</form>
<? include 'footer.php';?>

 
<? include_once'connect.php';
include_once'header.php';
 //print_r($_SESSION);
if($_POST['order_id']==''){ 
  $res = $conn->query("select * from req_table where from_web = '1'");
  if( $res->num_rows>0){
      while($user = $res->fetch_object()){
        $conn->query("update inventory set qty = qty+".$user->qty.",updated=now() where id = ".$user->item_id."");

      }
      $conn->query("delete from req_table where from_web = '1'");
  }
}
?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script type="text/javascript">
      


        function getTimeZone() {
            var offset = new Date().getTimezoneOffset(),
                o = Math.abs(offset);
            return (offset < 0 ? "+" : "-") + o;
          }
      function pull(id){       
     if(parseInt($('#qty_'+id).val()) > parseInt($('#stock_'+id).val()) || $('#qty_'+id).val().trim() == '' || $('#qty_'+id).val().trim()==null || $('#qty_'+id).val() == '0'){ 
            alert("Please fill Quantity correctly.");
          // $('#qty_'+id).val('');
       }
         else{
                    var d = new Date();
                        d = d.getFullYear() + "-" + ('0' + (d.getMonth() + 1)).slice(-2) + "-" + ('0' + d.getDate()).slice(-2) + " " + ('0' + d.getHours()).slice(-2) + ":" + ('0' + d.getMinutes()).slice(-2) + ":" + ('0' + d.getSeconds()).slice(-2);
  $.ajax({
        url: "qry_ajax.php",
        cache: false,
        type:'post',
        data : 'order_id='+$('#order_id').val()+'&item_id='+id+'&qty='+$('#qty_'+id).val()+'&req_time='+d,
        success: function(data){
          alert(data);
              $('#pull_'+id).attr('disabled',true);
              $('#qty_'+id).attr('disabled',true);
          
              var asd = $('#cart').val().split(' ');
              asd[1]++;
              $('#cart').val('Cart '+asd[1]);
                
            },
            error: function(err){
             alert(err);
            }
               });
           
      }
    }

      $(document).ready(function(){
        $("#filter_desc").on('input',function(){
           filter_table($('#in_type').val(),$("#filter_desc").val(),$("#inventry_tbl"),$('#in_category').val());
          });
          $("#in_type").on('change', function() {
            var intype = $("#in_type").val();
                $.post( "cat_ajax.php",{ tbl_type: intype },function(data) {
                data1='<option value="">All</option>'+data;
                $("#in_category").html(data1);
                });
              
           filter_table($('#in_type').val(),$("#filter_desc").val(),$("#inventry_tbl"),$('#in_category').val());
         });

            $("#in_category").on('change', function() {
           filter_table($('#in_type').val(),$("#filter_desc").val(),$("#inventry_tbl"),$('#in_category').val());
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
        function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
          return true;
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
  /*#cart{
  background: url(image/cart.jpg);
  background-size: 100% 100%;
  width:42px;
  height: 42px;
  border:none;
  
}*/
</style>

<center>
  <div id="user_tbl" style="margin-top: 26px;">
  <h1 style="color:blue;">All Inventory</h1>
  <datalist id= 'desc_list' >
  <? $sql = "SELECT replace(replace(replace(replace(inventory.description,'#',' '),'".'"'."',' '),'\r\n',' '),'\n',' ') as description  FROM `inventory` left join tbl_user on tbl_user.id = inventory.user where inventory.isactive = '1' order by inventory.description asc";
        if ($result = $conn->query($sql)) { 
            while($obj = $result->fetch_object()){ ?>
               <option value='<?=($obj->description)?>'><?

      }}
          ?>
  </datalist>
  <div >
    <strong>Search:</strong>
      <input id='filter_desc' type="text" list='desc_list' style="width:15vw; height: 4vh;">
    <strong>Type:</strong>
      <select  id="in_type" name="tbl_type" style="width:10vw;height: 4vh;">
         <option value="">All</option>
            <? $sql = "SELECT name FROM `tbl_type` group by name"; 
              $result = $conn->query($sql); 
            while($row = $result->fetch_object()){?>
            <option value='<?=$row->name?>'><?=$row->name?></option>
           <? } ?>
      </select>
       <strong>Category:</strong>
      <select  id="in_category" name="in_category" style="width:10vw;height: 4vh;">
         <option value="">All</option>
            <? $sq = "(select category from tbl_type)"; 
              $resu= $conn->query($sq); 
            while($cat = $resu->fetch_object()){?>
            <option value='<?=$cat->category?>'><?=$cat->category?></option>
           <? } ?>
      </select>
    </div>

  <form action="cart.php" method="post">
      <? if($_POST['order_id']!=''){ 

$sql = "select * from req_table where order_id = '".$_POST['order_id']."'";

$result = $conn->query($sql);
$nr = $result->num_rows;
        //while($obj = $result->fetch_object()){ 

}
$order = $conn->query("select max(order_id+1) 'order_id' from `log` ")->fetch_object();
 $orderid = $order->order_id;
        ?>
      <input type='hidden' id='order_id' name="order_id" <? if($_POST['order_id']!=''){ ?> value="<?=$_POST['order_id']?>" <? }else{ ?> value="<?=$orderid?>" <? } ?> >
        <input type="submit" id="cart" class="btn btn-primary" style="margin-right:-28px; margin-top: 15px;width:9vw;text-align:left;" value="<?='Cart '.$nr?>" >
    </form>
      <table class="table table-hover" style="width:90%;border:3px ridge green;margin-top: 1vh;" >
        <tr class="danger" style='color:blue;'>
   	<!-- <th style="text-align:center">ItemCode</th> -->
            <th style="text-align:center">Image</th>
            <th style="text-align:center">Description</th> 
            <th style="text-align:center">Type</th>
             <th style="text-align:center">Category</th> 
          	<th style="text-align:center">Size</th>   	
    	       <!--<th>Role</th> -->
   	        <th style="text-align:center">Cost</th>
            <th style="text-align:center">Quantity</th>
            <th style="text-align:center">Quantity in Stock</th>
            <th style="text-align:center">Order</th> 
        </tr>
      <tbody id='inventry_tbl'>
            <? $sql = "SELECT  inventory.id,inventory.img_url,inventory.size,inventory.invent_type,inventory.category,inventory.cost,replace(replace(replace(replace(inventory.description,'#',' '),'".'"'."',' '),'\r\n',' '),'\n',' ') as description,inventory.qty,inventory.isactive,DATE_ADD(inventory.updated, interval ".$offset_time." minute) as `updated`,inventory.created,tbl_user.username FROM `inventory` left join tbl_user on tbl_user.id = inventory.user where inventory.isactive = '1' and inventory.qty != '0' order by inventory.id desc";
            if ($result = $conn->query($sql)) { 
              $total_cost = 0;
              $total_qty = 0;

                    while($obj = $result->fetch_object()){ 
            //while ($row = mysql_fetch_array($result)) { ?>
        <div id="<?=$obj->id?>" style="display:none;width:35%;"><center>
            <? if(file_exists($obj->img_url) && $obj->img_url!=''){ ?>
            <img src = "<?=$obj->img_url?>" style="width:10vw;">
            <? }else{
              echo '<img src = "uploads/default.png" style="width:10vw;">';
            } ?></center>
        </div>
<tr class="success" >
 <!-- <td><?=$obj->item_code?></td>  -->
      <td >
            <? if(file_exists($obj->img_url) && $obj->img_url !="") { ?>
            <a href="#<?=$obj->id?>" rel="modal:open" > 
              <img src = "<?=$obj->img_url?>" style="width:10vw;vertical-align:center:" >
            </a> 
            <? }else{
          echo '<img src = "uploads/default.png" style="width:10vw;">';
            } ?>
      </td>
      <td ><?=($obj->description)?></td> 
      <td ><?=($obj->invent_type)?></td>
      <td ><?=($obj->category)?></td> 
      <td><?=($obj->size)?></td>
      <td>$<?=number_format($obj->cost,2)?></td>
      <td><input type="text" id="qty_<?=($obj->id)?>"  style="width:5vw;"  onkeypress="return isNumber(event)"> </td>
      <td><input type="text" id="stock_<?=($obj->id)?>"  value="<?=$obj->qty?>" style="width:5vw;" readonly></td> 
      <td>
        <input type="button" onclick='pull(<?=($obj->id)?>);' class="btn btn-primary" id="pull_<?=($obj->id)?>" value="Add">
      </td>
  </tr>  
<? } }
?>
</tbody>
</table>
</div>
</center>
<? include 'footer.php';?>

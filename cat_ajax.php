<? include_once'connect.php';
if( $_POST["tbl_type"]!='' )
{
 $result1 = $conn->query("select category from tbl_type where name = '".$_POST['tbl_type']."'");
  while($value = $result1->fetch_object())
 {
  echo "<option value='".$value->category."'>".$value->category."</option>";
 }
}
else 
{
 $result1 = $conn->query("select category from tbl_type");
  while($value = $result1->fetch_object()) 
 {  
  echo "<option value='".$value->category."'>".$value->category."</option>";
 }
}

?>
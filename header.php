  <?php include_once'connect.php';?>

<?session_start();
if($_SESSION['username']==''){
  header("location:index.php");
}




function get_timezone_offset($remote_tz, $origin_tz = null) {
    if($origin_tz === null) {
        if(!is_string($origin_tz = date_default_timezone_get())) {
            return false; // A UTC timestamp was returned -- bail out!
        }
    }
    $origin_dtz = new DateTimeZone($origin_tz);
    $remote_dtz = new DateTimeZone($remote_tz);
    $origin_dt = new DateTime("now", $origin_dtz);
    $remote_dt = new DateTime("now", $remote_dtz);
    $offset = $remote_dtz->getOffset($remote_dt) - $origin_dtz->getOffset($origin_dt);
 return $offset/60;
}
$offset_time = get_timezone_offset('America/New_York','GMT');



?>

<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css\smfr.css">
  <link rel="image icon" type="image/png" sizes="160x160" href="image/logoimage.png">
</head>
<script>
function capitalize( str )
{
    var pieces = str.split(" ");
    for ( var i = 0; i < pieces.length; i++ )
    {
        var j = pieces[i].charAt(0).toUpperCase();
        pieces[i] = j + pieces[i].substr(1).toLowerCase();
    }
    return pieces.join(" ");
}

function filter_table(data1,text_data,jo_object,data6){
 jo = jo_object.find('tr');
 text_data = text_data.toString();
 if(text_data!= undefined){
  var data2 = capitalize(text_data.trim());
  var data3 = text_data.toLowerCase().trim();
  var data4 = text_data.toUpperCase().trim();
  var data5 = text_data.trim();
 }else{
  var data2 ='';
  var data3 ='';
  var data4 ='';
  var data5 ='';
 }
    jo.hide();
    jo.filter(function (i, v) {
        var $t = $(this);
            if (    ($t.is(":contains('" + data1 + "')")) &&       ( ($t.is(":contains('" + data2 + "')")) || ($t.is(":contains('" + data3 + "')")) || ($t.is(":contains('" + data4 + "')")) || ($t.is(":contains('" + data5 + "')")) ) &&          ($t.is(":contains('" + data6 + "')"))               ) {
                return true;
            }
  
   return false;
  }).show();
}

</script>
<body>
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>   
    </div> 
        <div class="collapse navbar-collapse" id="myNavbar"> 
        <ul class="nav navbar-nav navbar-right">
      <li><a  style="color:white;"><b><? echo "User : ".$_SESSION['username'];?></b></a></li>
      <li><a href="logout.php" style="color:white;">Logout</a></li>
    </ul>   
    <ul class="nav navbar-nav">
      <li><a href="home.php" style="color:white;">Home</a></li>
      <? if($_SESSION["role"] =='2'){ ?> <li><a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color:white;">Manage User</span></a>
        <ul class="dropdown-menu">
      <li><a href="view_user.php">View Users</a></li>
    <li><a href="add_user.php">Add User</a></li>
   
  </ul>
      </li><? }?>
      <li><a href="pull_invent.php" style="color:white;">Pull Inventory Items</a>
         
      </li>
     <!--  <li><a href="#" style="color:white;">Take An Inventory</a></li> -->

      <li class="dropdown"> 
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color:white;">Manage Inventory</span></a>
        <ul class="dropdown-menu">
          <li><a href="view_invent.php">View & Edit Inventory</a>
          </li>
    <li><a href="add_invent.php">Add Item</a></li>
    
  </ul>
      </li>
       <li class="dropdown"> 
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color:white;">Reports</a>
        <ul class="dropdown-menu">
          <li><a href="transaction_report.php">Transaction</a>
          </li>
          <li><a href="inventory_report.php">Inventory</a>
          </li>
        </ul>
      </li>
      <li><a href="setting.php" style="color:white;">Settings</a></li>     
       </ul>
        
  </div>
  </div>
</nav>
	<!-- <div id="hmenu">
    <a class="input" href="home.php">Home</a> 
  <!-- <a id="input" href="view_tblsql.php" id="input">ViewUser</a> 
  <a href="newuser.php"  target="_self">
<a class="input" href="newuser.php">NewUser</a> 
<a class="input"><b><? echo"User: " .$_SESSION['username'];?></b></a>
<a class="input" href="admin_login.php">Logout</a>
</div> -->
<div class="container" style= "height:auto;min-height:83vh;" >



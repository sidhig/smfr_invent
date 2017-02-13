<?
error_reporting(0);
 // include_once('config.php');

$database = "smfr_invent";

$db_handle = mysql_connect("50.62.148.208", 'saurabh', 'password');
$db_found = mysql_select_db($database, $db_handle);

$conn = new mysqli("50.62.148.208","saurabh","password",$database);
if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
}
?>
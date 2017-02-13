<?
require_once "Mail.php";
require_once 'Mail/mime.php';

error_reporting(0);
 
function mailto($usermail,$subject,$body){

  $from = "SMFR<technosoftavik@gmail.com>";
    
        $host = "ssl://smtp.gmail.com";
        $port = "465";
        $username = "technosoftavik@gmail.com";  //<> give errors
        $password = "adminavik";

        $headers = array ('From' => $from,
          'To' => $usermail,
          'Subject' => $subject);
        $smtp = @Mail::factory('smtp',
          array ('host' => $host,
            'port' => $port,
            'auth' => true,
            'username' => $username,
            'password' => $password));
  //for attachment
  //$mime = new Mail_mime($crlf);
  //$mime->setTXTBody($body);
  //$mime->setHTMLBody($body);
  //$str = $_REQUEST["create_for"]."/".$filename;//file link
  //$mime->addAttachment($str, 'text/plain');
  //$body = $mime->get();
  //$headers = $mime->headers($headers);

        $mail = $smtp->send($usermail, $headers, $body);
 
   if (@PEAR::isError($mail)) 
   {
       //   echo($mail->getMessage() );
      } 
   else
   {
  // echo $_REQUEST["filename"];
        // echo("Email successfully sent! to ".$_REQUEST["email"]);
      }
}	 

//mailto('prakhar.a@aviktechnosoft.com','ads','$body');
?>
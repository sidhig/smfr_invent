<? error_reporting(0);
include_once('connect.php'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
 <script>

 function PrintDiv() {    
           var divToPrint = document.getElementById('divToPrint');
           var popupWin = window.open('', '_blank');
           popupWin.document.open();
           popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
            popupWin.document.close();
             
          
                }
  
</script>

<?php    //print_r($_REQUEST);

    
    //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'phpqrcode/temp/';

    include "qrlib.php";    
    
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    
    $filename = $PNG_TEMP_DIR.'test.png';
    
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'Q';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = $_REQUEST['level'];    

   $matrixPointSize = 10;
    if (isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);


    if (isset($_REQUEST['data'])) { 
    
        //it's very important!
        if (trim($_REQUEST['data']) == '')
            die('data cannot be empty! <a href="?">back</a>');
            
        // user data
        $filename = $PNG_TEMP_DIR.'test'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        QRcode::png($_REQUEST['data'], $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        
    } else {    
    
        //default data
        echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>';    
        QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        
    } ?>

    
   <? /* $sql = ("SELECT  inventory.*,tbl_user.username FROM `inventory` left join tbl_user on tbl_user.id = inventory.user order by inventory.id  desc ");*/
  
   $sql = "SELECT * from inventory where id = '".$_REQUEST['id']."'";
        $result = $conn->query($sql);
        // ($result->num_rows > 0) {
        ($obj = $result->fetch_object());?>
   
 <div  style="width:100%;height:40vh;">  
<div style="width:35vw;float:left;">
    <div style="float:left;">
        <span ><? if($obj->img_url !=""  && file_exists('../'.$obj->img_url)) { ?><a ><img src = "<?=$obj->img_url?>" style="width:12vw;margin-left: 1vw;border: 1px solid #EFE3E3;margin-top: 1vh;
         margin-bottom:3vh;" ></a> <? }else{ ?><img src = "uploads/default.png" style="width:12vw;margin-left: 1vw;border: 1px solid #EFE3E3;margin-top: 1vh;
         margin-bottom:3vh;" ><? } ?></span>
     </div><br>
    
    <div style="float:right;width:20vw;margin-top:-2vh;margin-right:.5vw;">
        <b style="font-size:1.6vw;" ><?=($obj->description)?></b><br>
        <b>Size:</b><?=($obj->size)?><br>
        <b>Cost:</b><?=($obj->cost)?><br>
        <b>Quantity:</b><?=($obj->qty)?><br><br>
        <span id="divToPrint"><?echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" style="width:20vw;"/><hr/>'; ?></span> 
    </div><br>
</div></div>
<center>
<div style="width:80%;" >
    <form action='edit_invent.php' method='post'>
       <input type="button" id="print_btn" onclick="PrintDiv();" value="Print" class="btn btn-primary" onclick="">
       <input type='hidden' name='edit_id' value='<?=($obj->id)?>'>
       <input type="submit" class="btn btn-primary" id="edit" value='Edit'>
       <a href="#close-modal" rel="modal:close"><input type="button" value="Close" class="btn btn-primary" ></a>
    </form>
</div></center>

    
     
    
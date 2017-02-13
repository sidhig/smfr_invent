
         
function validateForm(){  
   if ($("#desc").val().trim()=='')
        {
            alert('Please enter description.');
            $("#desc").focus();
              return false;
        }

  var is_sub =  new Array();
  var i = 0;
  var final_sub = 1;
  is_sub[0]=0;
  $( ".cls_size").each(function() {
      is_sub[i]=0;
   var j = $(this).val();
   //alert(j);
   var j = j.replace("/", "_");
     if ($("#cost"+j).val().trim() != '')
        {
          if ($("#reorder"+j).val().trim() == '' || $("#qty"+j).val().trim() == '')
          {
                is_sub[i] = 0;
          }
          else{
            is_sub[i] = 1;
          }
        }
       i++;

  });
  for(var k=0;k<is_sub.length;k++){

      final_sub = final_sub && is_sub[k];
        // alert(is_sub[k-1]);
        // alert(is_sub[k]);
  }
   //alert(final_sub);
    // alert(is_sub);

 
  if(final_sub==1){
    //alert("form submit");
   $("#additem").submit();
  }   else{
    alert("Please fill all the details");
  }
}
          function isNumber(evt) 
          {
              
              var charCode = (evt.which) ? evt.which : evt.keyCode;
              if (charCode > 31 && (charCode < 48 || charCode > 57)) 
              {
                  return false;
              }
                  return true;
          }
             $(document).ready(function()
             {
                $("#desc").on('keyup', function()
                {
                      this.value=this.value.replace(',', "").replace("'", "").replace("#", "").replace('"', "").replace(':', "").replace(';', "").replace('\n', " ").replace('  ', " ").replace('&', "").replace('%', "").replace('/', "");
              });
            });
      function nxtsize(size)
      {
           $('#size option[value="'+size+'"]').remove();
           var size = size.replace("/", "_");
           var asd = size.replace("_", "/");
    //    $("#row"+size).show();
           $("#tbl_size").append('<tr id="row'+size+'" ><td><input id="size'+size+'" name="size[]" type="text" class="cls_size" readonly  style="border:#F0F0F0 1px solid;height: 31px; width: 40%;"></td><td>$<input type ="text" style="width:40%;" name="cost[]" id="cost'+size+'" class="cls_cost" onkeypress="return isNumber(event)" ></td><td><input id="qty'+size+'" name="qty[]" type="text" value="0" list="number" style="width:45%;border:#F0F0F0 1px solid;height: 31px;" onkeypress="return isNumber(event)"></td><td><input id="reorder'+size+'" name="reorder[]" type="text" list="number" style="width:45%;border:#F0F0F0 1px solid;height: 31px;" value="10" onkeypress="return isNumber(event)" ><img src="image/deny.png" style="width:23px;" onclick=delasd("'+size+'");> </td></tr>');

              $("#row_head").show();
              $("#size"+size).val(asd);



          if($('#size').html().trim()=='')
          {
                 $('#add_size_row').hide();
         }
              else
               {
                $('#add_size_row').show();
              }

      }
      function delasd(size_id)
      {    
            var size =  $('#size'+size_id).val();
             $("#size").prepend($('<option></option>').val(size).html(size));
             $('#size'+size_id).val('');
             $("#row"+size_id).remove();
               $('#add_size_row').show();
      }

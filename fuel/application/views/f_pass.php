 
<!--banner-->
<div id="banner_title">
<img src="<?php echo site_url() ?>templates/images/banner_title.jpg">
</div>
<div id="orders">

<div id="user" style="margin-top:40px;">
  <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
 
  <tr>
    <td>      
      請輸入Email帳號：
    </td>
    <td width="326">
      <input type="text" class="form-control" name="cemail" id="cemail" >
      <span id="pwd_error_msg" style="color:red"></span><br/>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="326"><form id="form1" name="form1" method="post" action="">
      <input type="button" name="button" id="pwdButton" uri="<?php echo $forgot_url?>"  value="送出" />
    </form></td>
  </tr>
  
</table>
</div>

</div>

 <script type="text/javascript">

   function ValidEmail(emailtoCheck)
  {
    // 規則: 1.只有一個 "@"
    //       2.網址中, 至少要有一個".", 且不能連續出現
    //       3.不能有空白
    var regExp = /^[^@^\s]+@[^\.@^\s]+(\.[^\.@^\s]+)+$/;
    if ( emailtoCheck.match(regExp) )
      return true;
    else
      return false;
  }

   $(document).ready(function() {
      
       $("#pwdButton").click(function(){
          var url = $("#pwdButton").attr("uri");
          var cemail = $("#cemail").val();
          var chkMail = ValidEmail(cemail);
          $("#pwd_error_msg").html("處理中...");
          if(chkMail === true)
          {
            var postData = {'cemail': cemail};

            $.ajax({
              url: url,
              type: 'POST',
              dataType: 'json',
              data: postData,
              success: function(data)
              {
                console.log(data);
                if(data.status == 1)
                {
                  console.log(data);
                  // $('div#cart_login').fadeOut();
                  // $('div#cart_black').fadeOut();
                  // $('div#cart_forgot_pwd').fadeOut();
                  $("#cemail").val("");
                  alert(data.msg);
                  $("#pwd_error_msg").html(data.msg);
                }
                else
                {
                  $("#pwd_error_msg").html(data.msg);
                }
              }
            });
          }
          else
          {
            $("#pwd_error_msg").html("email格式錯誤");
          }
        });

   });

 </script>
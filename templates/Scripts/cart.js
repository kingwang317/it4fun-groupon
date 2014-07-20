$(document).ready(function(){
	var win_h=$(window).height();
	var win_w=$(window).width();
	$('div#cart_black').height(win_h);
	$('a.step1btn2').on('click',function(){
		if($(".plan_price:checked").size() == 0)
		{
			alert("請選擇方案");
		}
		else
		{
			$('div#cart_login').css({
				'top':(win_h-$('div#cart_login').height())/2,
				'left':(win_w-$('div#cart_login').width())/2,
			});
			$('div#cart_black').fadeIn();
			$('div#cart_login').fadeIn();			
		}

	});

	$('a.forgotBtn').on('click',function(){

		$('div#cart_forgot_pwd').css({
			'top':(win_h-$('div#cart_forgot_pwd').height())/2,
			'left':(win_w-$('div#cart_forgot_pwd').width())/2,
		});

		$('div#cart_login').fadeOut();
		$('div#cart_black').fadeIn();
		$('div#cart_forgot_pwd').fadeIn();			
	});

	$('div#cart_black').on('click',function(){
			$('div#cart_black').fadeOut();
			$('div#cart_login').fadeOut();
			$('div#cart_forgot_pwd').fadeOut();
	});
	$('div#cart_login div.close').on('click',function(){
			$('div#cart_black').fadeOut();
			$('div#cart_login').fadeOut();
	});

	$('div#cart_forgot_pwd div.close').on('click',function(){
			$('div#cart_black').fadeOut();
			$('div#cart_forgot_pwd').fadeOut();
	});

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
            $('div#cart_login').fadeOut();
            $('div#cart_black').fadeOut();
            $('div#cart_forgot_pwd').fadeOut();
            $("#cemail").val("");
            alert(data.msg);
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

    $("#update").click(function(){
      var url = $(this).attr("updateuri");
      var postData = {
        'member_name': $("#member_name").val(),
        'member_account': $("#member_account").val(),
        'member_city': $("#member_city").val(),
        'member_addr': $("#member_addr").val(),
        'member_mobile': $("#member_mobile").val(),
        'vat_number': $("#vat_number").val(),
        'invoice_title': $("#invoice_title").val(),
        'member_pass': $("#member_pass").val(),
        'member_chk_pass': $("#member_chk_pass").val()
      };

      if($("#member_pass").val() != "")
      {
        if($("#member_pass").val() != $("#member_chk_pass").val())
        {
          alert("密碼不一致!!")
          return false;
        }
      }

        $.ajax({
          url: url,
          type: 'POST',
          dataType: 'json',
          data: postData,
          success: function(data)
          {
            if(data.status == 1)
            {
              alert("更新完成");
            }
            else
            {
              alert(data.msg);
            }
          }
        });
    });

    $("a.firstTimeBtn").click(function(){
      sendForm.submit();
    });
});

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
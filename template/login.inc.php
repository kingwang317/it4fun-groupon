<script type="text/javascript">
        $(function(){
            $("#divShow").hide();
            
            $('body').click(function(evt) {
                if($(evt.target).parents("#divShow").length==0 && 
                    evt.target.id != "login_btn" && evt.target.id != "divShow") {
                    $('#divShow').hide();
                }
            });
        });
    </script>
<div id="divShow">
<div id="login_bg" style=" display: block;"></div>
<div id="login_box" style=" display: block;">
    <div onclick="$('#divShow').hide();" style="float:right; top:0px; width:40px; height:20px;">關閉 X</div>
    <form >
      
      <h4>歡迎回來！請輸入帳號密碼： </h4>
      <span  style="color:red">error</span><br>
      電子信箱：<input type="text" class="mail"><br>
      輸入密碼：<input type="password" ><br>
        <a >忘記密碼</a>
        <img src="images/login_btn.png"></div>
    </form>
  </div>
</div>
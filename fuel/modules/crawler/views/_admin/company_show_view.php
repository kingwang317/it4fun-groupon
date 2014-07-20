
<div id="main_top_panel">
	<h2 class="ico ico_blog_settings">關鍵字成效</h2>
</div>
<div class="clear"></div>

<div id="notification" class="notification"></div>

<div id="main_content" class="noaction">
	<div id="main_content_inner">
		<div class="boxshadow">
			<p style="margin-bottom: 5px">請選擇年月</p>
			<p class="LINE"></p>
			<p>
				廠商：<span style="color: #eb1729"><?php echo $company_name;?></span><br /><br />
				請選擇年月：<input type="search" class="date-picker" id="date_picker"/>
			</p>
			<button onclick="ahover('<?php echo $back_url;?>')">返回廠商列表</button>
			<button onclick="chk_val('<?php echo $query_url;?>')">下一步</button>
		</div>
	</div>
</div>
<?php echo js($this->config->item('company_javascript'), 'company')?>
<?php echo css($CI->config->item('company_css'), 'company')?>
<style>
.LINE
{
	border-bottom: solid 1px #ccc;
	width: 450px;
	height: 1px;
}
.boxshadow {
	border: solid 1px #CCC; /* 邊框樣式 */
	-moz-box-shadow: 1px 1px 5px #999; /* Firefox */
	-webkit-box-shadow: 1px 1px 5px #999; /* Safari 和 Chrome */
	box-shadow: 1px 1px 5px #999; /* Opera 10.5 + */
	width: 500px; /* 區塊寬度 */
	height:150px; /* 區塊高度 */
	padding: 10px;
	margin: 100px auto;
}
.ui-datepicker-calendar {
    display: none;
}​
</style>
<script>
	jQuery(document).ready(function($) {
	    $('.date-picker').datepicker( {
		     changeMonth: true,
		     changeYear: true,
		     dateFormat: 'yymm',

		     onClose: function() {
		        var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
		        var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
		        $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
		     },
	    });
	});

	function chk_val(url)
	{
		if($('#date_picker').val() == "")
		{
			alert("請輸入年月");
		}
		else
		{
			url = url + '&this_ym=' + $('#date_picker').val();
			ahover(url);
		}
	}

	function ahover(url)
	{
		location.href = url;
	}
</script>
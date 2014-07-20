
<div id="main_top_panel">
	<h2 class="ico ico_blog_settings">關鍵字成效</h2>
</div>
<div class="clear"></div>

<div id="notification" class="notification"></div>

<div id="main_content" class="noaction">
	<div id="main_content_inner">
<?php 
	$total_price = 0;
	foreach($cost_results as $row){
?>
		<div class="pricebox">
			<p style="margin: 5px 5px; line-height: 18px;">
			<span class="p_title">Target:</span><span class="p_content"><?php echo $row['target']?></span>
			<span class="p_title">Domain:</span><span class="p_content"><?php echo $row['cus_domain']?></span>
			<span class="p_title">Keyword:</span><span class="p_content"><?php echo $row['kw_name']?></span>
			<span class="p_title">Single Price:</span><span class="p_content"><?php echo $row['price']?></span><br />

			<span class="p_title">Run Date:</span><span class="p_content"><?php echo $row['run_date']?></span>
			<span class="p_title">Total Price:</span class="p_content"><span class="p_content"><?php echo $row['total_price']?></span><br />
			<span class="p_title">Page:</span><span class="p_content"><?php echo $row['page']?></span><br />
			<span class="p_title">Report Date:</span><span class="p_content"><?php echo $this_ym?></span>
			</p>
			<br />
			<table class="hovertable">
				<tbody>
<?php
					$total_price += $row["total_price"];
					echo "<tr>";
					for($i = 1 ; $i <= 31 ; $i++){
						$num = str_pad($i, 2,"0", STR_PAD_LEFT);
						echo "<th>".$num."</th>";

					}
					echo "</tr>";
					echo "<tr>";
					for($i = 1 ; $i <= 31 ; $i++){
						$num = str_pad($i, 2,"0", STR_PAD_LEFT);
						if($row["d$num"] <= $row['charge_rank'] && $row["d$num"] > 0){
							$mark = true;
						}else{
							$mark = false;
						}
						echo "<td ".(($mark)?"class='markred'":"")." >".$row["d$num"]."</td>";
					}
					echo "</tr>";
?>
				</tbody>
			</table>
		</div>
<?php 
	}
?>
	<div id="total_price"><?php echo $this_ym;?>總金額：$<?php echo $total_price;?></div>
	</div>
</div>
<?php echo js($this->config->item('company_javascript'), 'company')?>
<?php echo css($CI->config->item('company_css'), 'company')?>
<style>
#total_price
{
	width: 200px;
	height: 20px;
	padding: 5px 0 0 5px;
	background-color: #FDADAD;
	font-size: 14px;
	font-weight: bold;
	color: #666;
	-moz-box-shadow: 1px 1px 5px #999; /* Firefox */
	-webkit-box-shadow: 1px 1px 5px #999; /* Safari 和 Chrome */
	box-shadow: 1px 1px 5px #999; /* Opera 10.5 + */
}
table.hovertable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #a9c6c9;
	border-collapse: collapse;
}
table.hovertable th {
	background-color:#F1F1F1;
}

table.hovertable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #a9c6c9;
	padding: 1px;
}
table.hovertable td.markred {
	background-color:#FD93BA
}
.p_title
{
	font-size: 14px;
	font-weight: bold;
	color: #666;
}
.p_content
{
	font-weight: bold;
	color: #C97474;
	font-size: 12px;
}
.LINE
{
	border-bottom: solid 1px #ccc;
	width: 450px;
	height: 1px;
}
.pricebox {
	border: solid 1px #a9c6c9; /* 邊框樣式 */
	-moz-box-shadow: 1px 1px 5px #999; /* Firefox */
	-webkit-box-shadow: 1px 1px 5px #999; /* Safari 和 Chrome */
	box-shadow: 1px 1px 5px #999; /* Opera 10.5 + */
	width: 1023px; /* 區塊寬度 */
	height:128px; /* 區塊高度 */
	margin-bottom: 20px;
}
</style>
<script>
	jQuery(document).ready(function($) {

	});
</script>
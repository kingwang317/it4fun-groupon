<?php echo js($this->config->item('order_javascript'), 'order')?>
<?php echo css($this->config->item('order_css'), 'order')?>
<script type="text/javascript">
	var $j = jQuery.noConflict(true);

	$j(document).ready(function($) {
		var prod_template = doT.template($j('#prod_template').text());
		var plan_template = doT.template($j('#plan_template').text());

		$j( "select[name='pro_cate']" ).change(function() {
			$j("select[name='pro_id']").children().remove();
			var url = '<?php echo $get_prod_url?>' + $j(this).val();
			//console.log(url);
			$j.get(url, function(data) {
				var parse_data = $j.parseJSON(data);
				//console.log(parse_data);
				if(parse_data.status == 1)
				{
					$j("select[name='pro_id']").append(prod_template(parse_data));
				}
			});
		});

		$j( "select[name='pro_id']" ).change(function() {
			$j("select[name='pro_plan']").children().remove();
			var url = '<?php echo $get_plan_url?>' + $j(this).val();
			console.log(url);
			$j.get(url, function(data) {
				var parse_data = $j.parseJSON(data);
				console.log(parse_data);
				if(parse_data.status == 1)
				{
					$j("select[name='pro_plan']").append(plan_template(parse_data));
				}
			});
		});

		var elem = $(".same_order");
		$(".same_order").click(function(){
			if($(this).is( ":checked" ))
			{
				$("#oa_name").val($("#order_name").val());
				$("#oa_mobile").val($("#order_mobile").val());
				$("#oa_addr").val($("#order_addr").val());
			}
			else
			{
				$("#oa_name").val("");
				$("#oa_mobile").val("");
				$("#oa_addr").val("");
			}
		});
	});

	function aHover(url)
	{
		location.href = url;
	}
</script>
<div id="main_content">
	<div class="row" style="margin:10px 10px">
	    <div class="col-md-2 sheader"><h2>訂單管理</h2></div>
	    <div class="col-md-10 sheader">
	    </div>
	</div>

<div class="row" style="margin:10px 10px">
	<div class="col-md-12">
		<ol class="breadcrumb">
		  <li><a href="<?php echo $back_url?>">訂單管理</a></li>
		  <li class="active"><?php echo $view_name?></li>
		</ol>
	</div>
</div>
<div class="row" style="margin:10px 10px">
	<div class="col-md-12">
		<form method="post" action="<?php echo $submit_url?>" enctype="multipart/form-data" id="addForm">
		<?php
		if(isset($order_results))
		{
		?>
			<table class="table table-bordered">
				<thead>
					<tr>
						<td colspan="2">新增訂單</td>
					</tr>
				</thead>
				<tbody>
					<!--<tr>
						<td>商品類別</td>
						<td>
							<select>
								<option value="">請選擇...</option>
							</select>
						</td>
					</tr>-->
				<!-- 	<tr>
						<td>商品</td>
						<td>
							<?php echo $order_results->pro_name;?>
						</td>
					</tr> -->
					<!-- <tr>
						<td>商品方案</td>
						<td>
							<div class="col-xs-5">
							<select name="plan_id" class="form-control input-sm">
								<option value="">請選擇...</option>
								<?php
									foreach($plan_results as $plan_row)
									{
								?>
									<option value="<?php echo $plan_row->plan_id?>"<?php if($plan_row->plan_id == $order_results->product_plan) echo "selected";?>><?php echo $plan_row->plan_desc?></option>
								<?php
									}
								?>
							</select>
							</div>
						</td>
					</tr> -->
					<tr>
						<td>訂購人姓名</td>
						<td>
							<div class="col-xs-5">
								<input id="order_name" name="order_name" type="text" class="form-control input-sm" value="<?php echo $order_results->order_name?>" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>訂購人email</td>
						<td>
							<div class="col-xs-5">
								<input id="order_email" name="order_email" class="form-control input-sm" value="<?php echo $order_results->order_email?>" type="email" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>訂購人手機</td>
						<td>
							<div class="col-xs-5">
								<input id="order_mobile" name="order_mobile" type="text" class="form-control input-sm" value="<?php echo $order_results->order_mobile?>" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>訂購人地址</td>
						<td>
							<div class="col-xs-8">
								<input id="order_addr" name="order_addr" type="text" class="form-control input-sm" value="<?php echo $order_results->order_addr?>" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>統一編號</td>
						<td>
							<div class="col-xs-5">
								<input id="order_vat_num" name="order_vat_num" type="text" class="form-control input-sm" value="<?php echo $order_results->order_vat_number?>"/>
							</div>
						</td>
					</tr>
					<tr>
						<td>發票抬頭</td>
						<td>
							<div class="col-xs-5">
								<input id="order_inv_title" name="order_inv_title" type="text" class="form-control input-sm" value="<?php echo $order_results->order_invoice_title?>"/>
							</div>
						</td>
					</tr>
					<!-- <tr>
						<td>狀態</td>
						<td>
							<?php if($order_results->RtnCode == 1):?>
								<span style="color:blue">成功</span>
							<?php else:?>
								錯誤碼：<span style="color:red"><?php echo $order_results->RtnCode?></span>
							<?php endif;?>
						</td>
					</tr> -->
					<tr>
						<td>訂單確認</td>
						<td>
							<div class="col-xs-3">
							<select name="order_status" class="form-control input-sm">
								<?php
									foreach($order_status_results as $row)
									{
								?>
										<option value="<?php echo $row->code_key?>" <?php if($order_results->order_status == $row->code_key) echo "SELECTED";?>><?php echo $row->code_name?></option>
								<?php
									}
								?>
							</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>出貨狀態</td>
						<td>
							<div class="col-xs-3">
							<select name="order_ship_status" class="form-control input-sm">
								<?php
									foreach($ship_status_results as $row)
									{
								?>
										<option value="<?php echo $row->code_key?>" <?php if($order_results->order_ship_status == $row->code_key) echo "SELECTED";?>><?php echo $row->code_name?></option>
								<?php
									}
								?>
							</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>發票狀態</td>
						<td>
							<div class="col-xs-3">
							<select name="order_inv_status" class="form-control input-sm">
								<?php
									foreach($inv_status_results as $row)
									{
								?>
										<option value="<?php echo $row->code_key?>" <?php if($order_results->order_inv_status == $row->code_key) echo "SELECTED";?>><?php echo $row->code_name?></option>
								<?php
									}
								?>
							</select>
							</div>
						</td>
					</tr>
					<!-- <tr style="display:none;" >
						<td>送達時間</td>
						<td>
							<div class="col-xs-3">
							<select name="order_ship_time" class="form-control input-sm">
								<?php
									foreach($ship_time_results as $row)
									{
								?>
										<option value="<?php echo $row->code_value1?>" <?php if($order_results->order_ship_time == $row->code_value1) echo "SELECTED";?>><?php echo $row->code_name?></option>
								<?php
									}
								?>
							</select>
							</div>
						</td>
					</tr> -->
					<tr>
						<td>訂單備註</td>
						<td>
							<div class="col-xs-6">
								<textarea name="order_note" class="form-control" rows="5"><?php echo $order_results->order_note?></textarea>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							收件人資料&nbsp;&nbsp;<input type="checkbox" class="same_order"> 同訂購人資料
						</td>
					</tr>
					<tr>
						<td>收件人姓名</td>
						<td>
							<div class="col-xs-5">
								<input id="oa_name" name="oa_name" type="text" class="form-control input-sm" value="<?php echo $order_results->order_addressee_name?>" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>收件人手機</td>
						<td>
							<div class="col-xs-5">
								<input id="oa_mobile" name="oa_mobile" type="text" class="form-control input-sm" value="<?php echo $order_results->order_addressee_mobile?>" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>收件人地址</td>
						<td>
							<div class="col-xs-8">
								<input id="oa_addr" name="oa_addr" type="text" class="form-control input-sm" value="<?php echo $order_results->order_addressee_addr?>" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align:right">
							<button class="btn btn-small btn-primary" type="submit">修改</button>
							<button class="btn btn-small btn-warning" type="button" onclick="aHover('<?php echo $back_url?>')">取消</button>
						</td>
					</tr>
				</tobdy>
			</table>
<?php
		}
?>
		</form>
	</div>
</div>

<div class="row" style="margin:10px 10px">
	<div class="col-md-12">
		<?php if (isset($order_dt_results)): ?> 
			<table class="table table-bordered">
				<thead>
					<tr>
						<td>商品名稱</td>
						<td>數量</td>
						<td>單價</td>
						<td>小計</td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($order_dt_results as $row): ?>
					<tr>
						<td><?php echo $row->pro_name ?></td>
						<td><?php echo $row->num ?></td>
						<td><?php echo $row->amount ?></td>
						<td><?php echo $row->num * $row->amount ?></td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		<?php endif ?>
	</div>
</div>
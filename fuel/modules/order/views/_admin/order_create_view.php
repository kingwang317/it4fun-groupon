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
		  <li class="active">新增訂單</li>
		</ol>
	</div>
</div>
<div class="row" style="margin:10px 10px">
	<div class="col-md-12">
		<form method="post" action="<?php echo $submit_url?>" enctype="multipart/form-data" id="addForm">
			<table class="table table-bordered">
				<thead>
					<tr>
						<td colspan="2">新增訂單</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>商品類別</td>
						<td>
							<div class="col-xs-5">
								<select name="pro_cate" class="form-control input-sm">
									<option value="">請選擇...</option>
									<?php 
										foreach($pro_cate_results as $cate_row)
										{
									?>
										<option value="<?php echo $cate_row->code_key?>"><?php echo $cate_row->code_name?></option>
									<?php 
										}
									?>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>商品</td>
						<td>
							<div class="col-xs-5">
								<select name="pro_id" id="prod" class="form-control input-sm">
									<option value="">請選擇...</option>
									<script id='prod_template'  type="text/x-dot-template">
										<option value="">請選擇...</option>
										{{for(var i=0, l=it.product_results.length; i<l; i++){ }}
											<option value="{{=it.product_results[i].pro_id}}">{{=it.product_results[i].pro_name}}</option>
										{{ } }}
									</script>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>商品方案</td>
						<td>
							<div class="col-xs-5">
								<select name="pro_plan" class="form-control input-sm">
									<option value="">請選擇...</option>
									<script id='plan_template'  type="text/x-dot-template">
										<option value="">請選擇...</option>
										{{for(var i=0, l=it.plan_results.length; i<l; i++){ }}
											<option value="{{=it.plan_results[i].plan_id}}">{{=it.plan_results[i].plan_desc}}</option>
										{{ } }}
									</script>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>訂購人姓名</td>
						<td>
							<div class="col-xs-5">
								<input id="order_name" name="order_name" type="text" class="form-control input-sm" value="" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>訂購人email</td>
						<td>
							<div class="col-xs-5">
								<input id="order_email" name="order_email" class="form-control input-sm" value="" type="email" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>訂購人手機</td>
						<td>
							<div class="col-xs-5">
								<input id="order_mobile" name="order_mobile" type="text" class="form-control input-sm" value="" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>訂購人地址</td>
						<td>
							<div class="col-xs-8">
								<input id="order_addr" name="order_addr" type="text" class="form-control input-sm" value="" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>統一編號</td>
						<td>
							<div class="col-xs-5">
								<input id="order_vat_num" name="order_vat_num" type="text" class="form-control input-sm" value=""/>
							</div>
						</td>
					</tr>
					<tr>
						<td>發票抬頭</td>
						<td>
							<div class="col-xs-5">
								<input id="order_inv_title" name="order_inv_title" type="text" class="form-control input-sm" value=""/>
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
								<input id="oa_name" name="oa_name" type="text" class="form-control input-sm" value="" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>收件人手機</td>
						<td>
							<div class="col-xs-5">
								<input id="oa_mobile" name="oa_mobile" type="text" class="form-control input-sm" value="" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>收件人地址</td>
						<td>
							<div class="col-xs-8">
								<input id="oa_addr" name="oa_addr" type="text" class="form-control input-sm" value="" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align:right">
							<button class="btn btn-sm btn-primary" type="submit">新增</button>
							<button class="btn btn-sm btn-warning" type="button" onclick="aHover('<?php echo $back_url?>')">取消</button>
						</td>
					</tr>
				</tobdy>
			</table>
		</form>
	</div>
</div>
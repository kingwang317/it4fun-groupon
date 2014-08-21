<?php echo js($this->config->item('order_javascript'), 'order')?>
<?php echo css($this->config->item('order_css'), 'order')?>
<script type="text/javascript">
	var $j = jQuery.noConflict(true);

	$j(document).ready(function($) {
		var prod_template = doT.template($j('#prod_template').text());

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
			var url = '<?php echo $filter_url?>' + $j(this).val();
			$("#filterOk").attr('sendUrl', url);
		});

		$j("#filterOk").click(function(){
			var url = $j(this).attr('sendUrl');

			aHover(url);
		});

		$j("#date_filter").click(function(){
			var url = $j(this).attr('sendUrl');
			var start_time = $j("#start_time").val();
			var end_time = $j("#end_time").val();
			if( start_time != "" && end_time != "")
			{
				url = url + "&start_time=" + start_time + "&end_time=" + end_time;

				aHover(url);				
			}
			else
			{
				alert("請選擇日期");
			}

		});

		$j("#select-all").click(function() {

		   if($j("#select-all").prop("checked"))
		   {
				$j("input[name='order_id[]']").each(function() {
					$j(this).prop("checked", true);
				});
		   }
		   else
		   {
				$j("input[name='order_id[]']").each(function() {
					$j(this).prop("checked", false);
				});     
		   }
		});

		$j("button.delall").click(function(){
			if($j("input[name='order_id[]']:checked").size() > 0)
			{
				var order_ids = [];
				var j = 0;
				var postData = {};
				var api_url = '<?php echo $multi_del_url?>';
				$j("input[name='order_id[]']").each(function(i){
					if($j(this).prop("checked"))
					{
						order_ids[j] = $j(this).attr('orderid');
						j++;
					}
				});

				postData = {'order_ids': order_ids};
				$j( "#dialog-confirm p" ).text('您確定要刪除嗎？');
				$j( "#dialog-confirm" ).dialog({
				  resizable: false,
				  height:150,
				  modal: true,
				  buttons: {
				    "Delete": function() {
						$j.ajax({
							url: api_url,
							type: 'POST',
							async: true,
							crossDomain: false,
							cache: false,
							data: postData,
							success: function(data, textStatus, jqXHR){
								var data_json=jQuery.parseJSON(data);
								//console.log(data_json);
								$j( "#dialog-confirm" ).dialog( "close" );
								if(data_json.status == 1)
								{
									$j(".notify").find("span").text('刪除成功');
									$j(".notify").fadeIn(100).delay(500).fadeOut(1000);
									setTimeout("update_page()", 500);
								}
								else
								{
									$j(".notify").find(".alert").removeClass('alert-success');
									$j(".notify").find(".alert").addClass('alert-danger');
									$j(".notify").find("span").text('刪除失敗');
									$j(".notify").slideDown(500).delay(1000).fadeOut(200);
								}

							},
						});
				    },
				    Cancel: function() {
				      $j( this ).dialog( "close" );
				    }
				  }
				});
			}
			else
			{
				alert("請至少選一個item");
			}
		});

		$j("#oChecked").click(function(){
			if($j("input[name='order_id[]']:checked").size() > 0)
			{
				var act = 'o_checked';
				batch_act(act);
			}
			else
			{
				alert("請至少選一個item");
			}
		});

		$j("#oUnChecked").click(function(){
			if($j("input[name='order_id[]']:checked").size() > 0)
			{
				var act = 'o_un_checked';
				batch_act(act);
			}
			else
			{
				alert("請至少選一個item");
			}
		});

		$j("#oShiped").click(function(){
			if($j("input[name='order_id[]']:checked").size() > 0)
			{
				var act = 'o_shiped';
				batch_act(act);
			}
			else
			{
				alert("請至少選一個item");
			}
		});

		$j("#oUnShiped").click(function(){
			if($j("input[name='order_id[]']:checked").size() > 0)
			{
				var act = 'o_un_shiped';
				batch_act(act);
			}
			else
			{
				alert("請至少選一個item");
			}
		});

		$j("#oInved").click(function(){
			if($j("input[name='order_id[]']:checked").size() > 0)
			{
				var act = 'o_inved';
				batch_act(act);
			}
			else
			{
				alert("請至少選一個item");
			}
		});

		$j("#oUnInved").click(function(){
			if($j("input[name='order_id[]']:checked").size() > 0)
			{
				var act = 'o_un_inved';
				batch_act(act);
			}
			else
			{
				alert("請至少選一個item");
			}
		});

		$j("#export").click(function(){
			//var url = $(this).attr('exurl');
			$("#download_excel_form").submit();
		});

		$j( "#start_time" ).datepicker({
			onClose:function(selectedDate){
				$j("#end_time").datepicker("option", "minDate", selectedDate);
			}
		});
		$j( "#end_time" ).datepicker({
			onClose:function(selectedDate){
				$j("#start_time").datepicker("option", "maxDate", selectedDate);
			}
		});

		$j( "#start_time" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
		$j( "#end_time" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

		$j("#export_report_btn").click(function(){
			$j("#report_excel_form").submit();
		});
	});

	function batch_act(act)
	{
		var order_ids = [];
		var j = 0;
		var postData = {};
		var api_url = '<?php echo $batch_action_url?>';
		$j("input[name='order_id[]']").each(function(i){
			if($j(this).prop("checked"))
			{
				order_ids[j] = $j(this).attr('orderid');
				j++;
			}
		});

		postData = {'order_ids': order_ids, 'act': act};
		
    	$.post(api_url, postData, function(data, textStatus, xhr) {
			var data_json=jQuery.parseJSON(data);
			//console.log(data_json);
			if(data_json.status == 1)
			{
				$j(".notify").find(".alert").removeClass('alert-danger');
				$j(".notify").find(".alert").addClass('alert-success');
				$j(".notify").find("span").text('更新成功');
				$j(".notify").fadeIn(100).delay(500).fadeOut(1000);
				setTimeout("update_page()", 500);
			}
			else
			{
				$j(".notify").find(".alert").removeClass('alert-success');
				$j(".notify").find(".alert").addClass('alert-danger');
				$j(".notify").find("span").text('更新失敗');
				$j(".notify").slideDown(500).delay(1000).fadeOut(200);
			}
    	});
	}

	function aHover(url)
	{
		location.href = url;
	}

	$j("document").ready(function($) {

	});

	function del_order(order_id)
	{
		var	 api_url = '<?php echo $del_url?>' + order_id;
	   
		$j.ajax({
			url: api_url,
			type: 'POST',
			async: true,
			crossDomain: false,
			cache: false,
			success: function(data, textStatus, jqXHR){
				var data_json=jQuery.parseJSON(data);
				//console.log(data_json);
				$j( "#dialog-confirm" ).dialog( "close" );
				if(data_json.status == 1)
				{
					$j("#notification span").text('刪除成功');
					$j("#notify").fadeIn(100).fadeOut(1000);
					setTimeout("update_page()", 500);
				}

			},
		});
	}
	function dialog_chk(order_id)
	{
		$j( "#dialog-confirm p" ).text('您確定要刪除嗎？');
		$j( "#dialog-confirm" ).dialog({
		  resizable: false,
		  height:150,
		  modal: true,
		  buttons: {
		    "Delete": function() {
				del_order(order_id);
		    },
		    Cancel: function() {
		      $j( this ).dialog( "close" );
		    }
		  }
		});
	}
	function update_page()
	{
		location.reload();
	}
</script>
<style>


</style>
<div id="main_content">
	<div id="dialog-confirm" title="刪除確認?">
	  <p></p>
	</div>
	<div class="row" style="margin:10px 10px">
	    <div class="col-md-2 sheader"><h2>訂單管理</h2></div>
	    <div class="col-md-10 sheader">
			<form class="form-inline" role="form">
				<div class="form-group">
					<select class="form-control input-sm" name="search_item" method="GET">
						<option value="order_id" <?php if($search_item == 'order_id') echo "SELECTED"?>>依訂單編號</option>
						<option value="order_name" <?php if($search_item == 'order_name') echo "SELECTED"?>>依訂購人</option>
						<option value="order_email" <?php if($search_item == 'order_email') echo "SELECTED"?>>email</option>
					</select>
				</div>
				<div class="form-group">
					<input type="text" class="form-control input-sm" placeholder="Search..." name="search_txt" value="<?php echo $search_txt?>"/>
				</div>
				<button type="submit" class="btn btn-info btn-sm">Search</button>
			</form>
	    </div>
	</div>

	<div class="row" style="margin:10px 10px">
    	<!--<button class="btn btn-sm btn-info" type="button" onclick="aHover('<?php echo $create_url?>')" style="float:left;">新增訂單</button>-->
		
		<form action="<?php echo $expor_url?>" method="POST" enctype="multipart/form-data" id="download_excel_form" style="float:left; margin-left:5px;">
			<input type="hidden" name="pro_id" value='<?php echo $pro_id?>'>
			<input type="hidden" name="order_filter" value='<?php echo $order_filter?>'>
			<button type="button" class="btn btn-success btn-sm" exurl="" id="export">匯出excel</button>
		</form>
    	
    	<form action="<?php echo $expor_report_url?>" method="POST" enctype="multipart/form-data" id="report_excel_form" style="float:left; margin-left:5px;">
    		<input type="hidden" name="order_filter" value='<?php echo $order_filter?>'>
    		<button type="button" class="btn btn-success btn-sm" id="export_report_btn">匯出報表</button>
    	</form>
	</div>
	<div class="row notify" style="margin:10px 10px; font-size: 12px; display:none;">
	  <div class="alert alert-success col-md-3" style="margin-bottom:0px;">
	    <span>刪除失敗</span>
	  </div>
	</div>
	<div class="row" style="margin:10px 10px">
		<!-- <table class="table table-bordered" style="margin-bottom:5px">
			<tbody>
				<tr>
					<th class="col-xs-2">商品類別</th>
					<td>
						<select class="form-control input-sm" name="pro_cate">
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
					</td>
					<th class="col-xs-2">商品名稱</th>
					<td>
						<select class="form-control input-sm" name="pro_id">
							<option value="">請選擇...</option>
							<script id='prod_template'  type="text/x-dot-template">
								<option value="">請選擇...</option>
								{{for(var i=0, l=it.product_results.length; i<l; i++){ }}
									<option value="{{=it.product_results[i].pro_id}}">{{=it.product_results[i].pro_name}}</option>
								{{ } }}
							</script>
						</select>
					</td>
					<td>
						<button type="button" class="btn btn-info btn-sm" id="filterOk" sendUrl="<?php echo $filter_url?>">確認</button>
					</td>
				</tr>
			<tbody>
		</table>-->

		<table class="table table-bordered" style="margin-bottom:5px">
			<tbody>
				<tr>
					<th class="col-xs-2">從</th>
					<td>
						<input type="text" class="form-control input-sm" name="start_time" id="start_time" readonly='true'/>
					</td>
					<th class="col-xs-2">到</th>
					<td>
						<input type="text" class="form-control input-sm" name="end_time" id="end_time" readonly='true'/>
					</td>
					<td>
						<button type="button" class="btn btn-info btn-sm" id="date_filter" sendUrl="<?php echo $date_filter_url?>">確認</button>
					</td>
				</tr>
			<tbody>
		</table>
		
	</div>
	<div class="row" style="margin:5px 10px">
		<form>
			<table class="table table-bordered" style="margin-bottom:5px">
				<tbody>
					<tr>
						<th class="col-xs-2">批次處理</th>
						<td>
							<div class="btn-toolbar">
								<div class="btn-group">
									<button type="button" class="btn btn-default btn-sm" id="oChecked">已確認</button>
									<button type="button" class="btn btn-default btn-sm" id="oUnChecked">未確認</button>
								</div>
								<div class="btn-group">
									<button type="button" class="btn btn-default btn-sm" id="oShiped">已出貨</button>
									<button type="button" class="btn btn-default btn-sm" id="oUnShiped">未出貨</button>
								</div>
								<div class="btn-group">
									<button type="button" class="btn btn-default btn-sm" id="oInved">已開立</button>
									<button type="button" class="btn btn-default btn-sm" id="oUnInved">未開立</button>
								</div>
								<button class="btn btn-sm btn-danger delall" type="button">刪除</button>
							</div>
						</td>
					</tr>
				<tbody>
			</table>
		</form>
	</div>
	<div class="row" style="margin:10px 10px">
		<table class="table table-bordered">
			<thead>
				<tr>
					<td>
						<div class="checkbox">
							<label>
								<input type="checkbox" id="select-all"/>
							</label>
						</div>
					</td>
					<td>訂單編號</td>
					<!-- <td>訂購商品</td>
					<td>訂購方案</td> -->
					<!-- <td>狀態</td>  -->
					<td>訂購人</td>						
					<td>訂單確認</td>
					<td>出貨狀態</td>
					<td>發票狀態</td>
					<td>訂購時間</td>
					<!--<td>刪除</td>-->
				</tr>
			</thead>
			<tbody>
				<?php
				if(isset($order_results))
				{
					foreach($order_results as $rows)
					{

				?>
				<tr>
					<td>
						<label class="checkbox">
							<input type="checkbox" name="order_id[]" orderid="<?php echo $rows->order_id?>"/>
						</label>
					</td>
					<td><button class="btn btn-link btn-xs" type="button" onclick="aHover('<?php echo $edit_url.$rows->order_id?>')"><?php echo $rows->order_id?></button></td>
					<!-- <td><?php echo $rows->pro_name?></td> -->
					<!-- <td><?php echo $rows->plan_desc?></td> -->
					<!-- <td>
						<?php if($rows->RtnCode == 1):?>
							<span style="color:blue">成功</span>
						<?php else:?>
							<span style="color:red">失敗</span>
						<?php endif;?>
					</td> -->
					<td><?php echo $rows->order_name?></td>
					<td>
						<?php
							if($rows->order_status == "order_status_0002")
							{
								echo "已確認";
							}
							else
							{
								echo "未確認";
							}
						?>
					</td>
					<td>
						<?php
							if($rows->order_ship_status == "ship_status_0002")
							{
								echo "已出貨";
							}
							else
							{
								echo "未出貨";
							}
						?>
					</td>
					<td>
						<?php
							if($rows->order_inv_status == "inv_status_0002")
							{
								echo "已開立";
							}
							else
							{
								echo "未開立";
							}
						?>
					</td>
					<td><?php echo substr($rows->order_time, 0, 10)?></td>
					<!--<td>
						<button class="btn btn-xs btn-danger del" type="button" onclick="dialog_chk(<?php echo $rows->order_id?>)">刪除</button>
					</td>-->
				</tr>
				<?php
					}
				}
				else
				{
				?>
					<tr>
						<td colspan="7">No results.</td>
					</tr>
				<?php
				}
				?>
			</tobdy>
		</table>
	</div>
	<div style="text-align:center">
	  <ul class="pagination">
		<?php echo $pagination?>
	  </ul>
	</div>
</div>
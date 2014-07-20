<?php echo js($this->config->item('product_javascript'), 'product')?>
<?php echo css($this->config->item('product_css'), 'product')?>
<script type="text/javascript">
	var $j = jQuery.noConflict(true);
</script>
<style>


</style>
<div id="main_content">
	<div id="dialog-confirm" title="刪除確認?">
	  <p></p>
	</div>
	<div class="row" style="margin:10px 10px">
	    <div class="col-md-2 sheader"><h2>產品管理</h2></div>
	    <div class="col-md-10 sheader">
			<form class="form-inline" role="form" method="GET">
				<div class="form-group">
					<select class="form-control input-sm" name="search_item">
						<option value="pro_name">依產品名稱</option>
					</select>
				</div>
				<div class="form-group">
					<input type="text" class="form-control input-sm" placeholder="Search..." name="search_txt"/>
				</div>
				<button type="submit" class="btn btn-info btn-sm">Search</button>
			</form>
	    </div>
	</div>

	<div class="row" style="margin:10px 10px">
	    <div class="col-md-3">
	    	<button class="btn btn-sm btn-danger delall" type="button">刪除</button>
	    	<button class="btn btn-sm btn-info" type="button" onclick="aHover('<?php echo $create_url?>')">新增產品</button>
	    </div>
	    <div class="col-md-10"></div>
	</div>
	<div class="row notify" style="margin:10px 10px; font-size: 12px; display:none">
		<div class="bs-docs-example">
		  <div class="alert fade in">
		    <button type="button" class="close" data-dismiss="alert">×</button>
		    <span>刪除失敗</span>
		  </div>
		</div>
	</div>

	<div class="row" style="margin:10px 10px">
	    <div class="col-md-12">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<td>
							<label class="checkbox">
								<input type="checkbox" id="select-all"/>
							</label>
						</td>
						<td>產品名稱</td>
						<td>產品類別</td>
						<td>上架日期</td>
						<td>下架日期</td>
						<td>更新時間</td>
						<td>已銷售數量</td>
						<td>銷售總額</td>
						<td>點擊數</td>
						<td>刪除</td>
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($product_results))
					{
						foreach($product_results as $key=>$rows)
						{

					?>
					<tr>
						<td>
							<label class="checkbox">
								<input type="checkbox" name="pro_id[]" proid="<?php echo $rows->pro_id?>"/>
							</label>
						</td>
						<td><a type="button" href="<?php echo $edit_url.$rows->pro_id?>"><?php echo $rows->pro_name?></a></td>
						<td><?php echo $rows->code_name?></td>
						<td><?php echo substr($rows->pro_add_time, 0, 10)?></td>
						<td><?php echo substr($rows->pro_off_time, 0, 10)?></td>
						<td><?php echo $rows->modi_time?></td>
						<td style="text-align:center"><?php echo $rows->sell_cnt?></td>
						<td style="text-align:right"><?php echo number_format($rows->sell_amt)?>元</td>
						<td><?php echo $rows->click_num;?></td>
						<td>
							<button class="btn btn-xs btn-danger del" type="button" onclick="dialog_chk(<?php echo $rows->pro_id?>)">刪除</button>
						</td>
					</tr>
					<?php
						}
					}
					else
					{
					?>
						<tr>
							<td colspan="9">No results.</td>
						</tr>
					<?php
					}
					?>
				</tobdy>
			</table>
	    </div>
	</div>
	<div style="text-align:center">
	  <ul class="pagination">
	    <?php echo $pagination?>
	  </ul>
	</div>
</div>

<script>
	function aHover(url)
	{
		location.href = url;
	}

	$j("document").ready(function($) {
		$j("#select-all").click(function() {

		   if($j("#select-all").prop("checked"))
		   {
				$j("input[name='pro_id[]']").each(function() {
					$j(this).prop("checked", true);
				});
		   }
		   else
		   {
				$j("input[name='pro_id[]']").each(function() {
					$j(this).prop("checked", false);
				});     
		   }
		});

		$j("button.delall").click(function(){
			var pro_ids = [];
			var j = 0;
			var postData = {};
			var api_url = '<?php echo $multi_del_url?>';
			$j("input[name='pro_id[]']").each(function(i){
				if($j(this).prop("checked"))
				{
					pro_ids[j] = $j(this).attr('proid');
					j++;
				}
			});

			postData = {'pro_ids': pro_ids};
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
							console.log(data_json);
							$j( "#dialog-confirm" ).dialog( "close" );
							if(data_json.status == 1)
							{
								$j(".notify").find("span").text('刪除成功');
								$j(".notify").fadeIn(100).fadeOut(1000);
								setTimeout("update_page()", 500);
							}
							else
							{
								$j(".notify").find(".alert").addClass('alert-error');
								$j(".notify").find(".alert").addClass('alert-block');
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
		});
	});

	function del_member(pro_id)
	{
		var	 api_url = '<?php echo $del_url?>' + pro_id;
	   
		$j.ajax({
			url: api_url,
			type: 'POST',
			async: true,
			crossDomain: false,
			cache: false,
			success: function(data, textStatus, jqXHR){
				var data_json=jQuery.parseJSON(data);
				console.log(data_json);
				$j( "#dialog-confirm" ).dialog( "close" );
				if(data_json.status == 1)
				{
					$j("#notification span").text('刪除成功');
					$j("#notify").fadeIn(100).fadeOut(1000);
					setTimeout("update_page()", 200);
				}

			},
		});
	}
	function dialog_chk(pro_id)
	{
		$j( "#dialog-confirm p" ).text('您確定要刪除嗎？');
		$j( "#dialog-confirm" ).dialog({
		  resizable: false,
		  height:150,
		  modal: true,
		  buttons: {
		    "Delete": function() {
				del_member(pro_id);
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
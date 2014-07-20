<?php echo js($this->config->item('edm_javascript'), 'edm')?>
<?php echo css($this->config->item('edm_css'), 'edm')?>
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
	    <div class="col-md-2 sheader"><h2 style="font-size: 28px">電子報管理</h2></div>
	    <div class="col-md-10 sheader"></div>
	</div>

	<div class="row" style="margin:10px 10px">
	    <div class="col-md-3">
	    	<button class="btn btn-sm btn-danger delall" type="button">刪除</button>
	    	<button class="btn btn-sm btn-info" type="button" onclick="aHover('<?php echo $create_url?>')">新增電子報</button>
	    </div>
	    <div class="col-md-10"></div>
	</div>
	<div class="row notify" style="margin:10px 10px; font-size: 12px; display:none;">
	  <div class="alert alert-success col-md-3" style="margin-bottom:0px;">
	    <span>刪除失敗</span>
	  </div>
	</div>

	<div class="row" style="margin:10px 10px">
	    <div class="col-md-12">
			<table class="table table-bordered">
				<thead>
					<tr>
						<td>
							<label class="checkbox">
								<input type="checkbox" id="select-all"/>
							</label>
						</td>
						<td>主旨</td>
						<td>發送日期</td>
						<td>寄送記綠</td>
						<td>發送</td>
						<td>刪除</td>
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($edm_results))
					{
						foreach($edm_results as $rows)
						{

					?>
					<tr>
						<td>
							<label class="checkbox">
								<input type="checkbox" name="edm_id[]" edmid="<?php echo $rows->edm_id?>"/>
							</label>
						</td>						
						<td><a href="<?php echo $edit_url.$rows->edm_id?>"><?php echo $rows->subject?></a></td>
						<td>
							<?php 
								if($rows->send_time == "0000-00-00 00:00:00")
								{
									echo "尚未發送";
								}
								else
								{
									echo $rows->send_time;
								}
							?>
						</td>
						<td>
							<button class="btn btn-xs btn-info record" type="button" onclick="aHover('<?php echo $log_url.$rows->edm_id?>')">發送記錄</button>
						</td>
						<td>
							<button class="btn btn-xs btn-danger send" type="button" onclick="aHover('<?php echo $send_url.$rows->edm_id?>')">發送</button>
						</td>
						<td>
							<button class="btn btn-xs btn-danger del" type="button" onclick="dialog_chk(<?php echo $rows->edm_id?>)">刪除</button>
						</td>
					</tr>
					<?php
						}
					}
					else
					{
					?>
						<tr>
							<td colspan="6">No results.</td>
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
				$j("input[name='edm_id[]']").each(function() {
					$j(this).prop("checked", true);
				});
		   }
		   else
		   {
				$j("input[name='edm_id[]']").each(function() {
					$j(this).prop("checked", false);
				});     
		   }
		});

		$j("button.delall").click(function(){
			var pro_ids = [];
			var j = 0;
			var postData = {};
			var api_url = '<?php echo $multi_del_url?>';
			$j("input[name='edm_id[]']").each(function(i){
				if($j(this).prop("checked"))
				{
					pro_ids[j] = $j(this).attr('edmid');
					j++;
				}
			});

			postData = {'edm_ids': pro_ids};
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

	function del_edm(edm_id)
	{
		var	 api_url = '<?php echo $del_url?>' + edm_id;
	   
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
					$j(".notify").find("span").text('刪除成功');
					$j(".notify").fadeIn(100).delay(500).fadeOut(1000);
					setTimeout("update_page()", 200);
				}

			},
		});
	}
	function dialog_chk(edm_id)
	{
		$j( "#dialog-confirm p" ).text('您確定要刪除嗎？');
		$j( "#dialog-confirm" ).dialog({
		  resizable: false,
		  height:150,
		  modal: true,
		  buttons: {
		    "Delete": function() {
				del_edm(edm_id);
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
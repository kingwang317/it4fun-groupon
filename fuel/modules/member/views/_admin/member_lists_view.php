<?php echo js($this->config->item('member_javascript'), 'member')?>
<?php echo css($this->config->item('member_css'), 'member')?>
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
	    <div class="col-md-2 sheader"><h2>會員管理</h2></div>
	    <div class="col-md-10 sheader">
			<form class="form-inline" role="form" action="<?php echo $search_url?>" method="POST">
				<div class="form-group">
					<select class="form-control input-sm" name="act">
						<option value="by_name">依會員姓名</option>
						<option value="by_email">email</option>
					</select>
				</div>
				<div class="form-group">
					<input type="text" class="form-control input-sm" placeholder="Search..." name="search_item"/>
				</div>
				<button type="submit" class="btn btn-info btn-sm">Search</button>
			</form>
	    </div>
	</div>

	<div class="row" style="margin:10px 10px">
	    <div class="span3">
	    	<button class="btn btn-sm btn-danger delall" type="button">刪除</button>
	    	<button class="btn btn-sm btn-info" type="button" onclick="aHover('<?php echo $create_url?>')">新增會員</button>
	    </div>
	    <div class="span10"></div>
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
	    <div class="span12">
			<table class="table table-bordered">
				<thead>
					<tr>
						<td>
							<label class="checkbox">
								<input type="checkbox" id="select-all"/>
							</label>
						</td>
						<td>會員帳號</td>
						<td>會員姓名</td>
						<td>會員電話</td>
						<td>註冊日期</td>
						<td>刪除</td>
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($member_results))
					{
						foreach($member_results as $rows)
						{

					?>
					<tr>
						<td>
							<label class="checkbox">
								<input type="checkbox" name="member_id[]" memberid="<?php echo $rows->member_id?>"/>
							</label>
						</td>
						<td><a href="javascript:;" onclick="aHover('<?php echo $edit_url.$rows->member_id?>')"><?php echo $rows->member_account?></a></td>
						<td><?php echo $rows->member_name?></td>
						<td><?php echo $rows->member_mobile?></td>
						<td><?php echo $rows->create_time?></td>
						<td>
							<button class="btn btn-sm btn-danger del" type="button" onclick="dialog_chk(<?php echo $rows->member_id?>)">刪除</button>
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
				$j("input[name='member_id[]']").each(function() {
					$j(this).prop("checked", true);
				});
		   }
		   else
		   {
				$j("input[name='member_id[]']").each(function() {
					$j(this).prop("checked", false);
				});     
		   }
		});

		$j("button.delall").click(function(){
			var member_ids = [];
			var j = 0;
			var postData = {};
			var api_url = '<?php echo $multi_del_url?>';
			$j("input[name='member_id[]']").each(function(i){
				if($j(this).prop("checked"))
				{
					member_ids[j] = $j(this).attr('memberid');
					j++;
				}
			});

			postData = {'member_ids': member_ids};
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

	function del_member(member_id)
	{
		var	 api_url = '<?php echo $del_url?>' + member_id;
	   
		$j.ajax({
			url: api_url,
			type: 'POST',
			async: true,
			crossDomain: false,
			cache: false,
			success: function(data, textStatus, jqXHR){
				var data_json=jQuery.parseJSON(data);
				
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
	function dialog_chk(member_id)
	{
		$j( "#dialog-confirm p" ).text('您確定要刪除嗎？');
		$j( "#dialog-confirm" ).dialog({
		  resizable: false,
		  height:150,
		  modal: true,
		  buttons: {
		    "Delete": function() {
				del_member(member_id);
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
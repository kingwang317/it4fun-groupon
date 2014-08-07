<?php echo css($this->config->item('news_css'), 'news')?> 

 <div id="main_content">
	<div id="dialog-confirm" title="刪除確認?">
	  <p></p>
	</div>

	<div class="row" style="margin:10px 10px">
	    <div class="col-md-2 sheader"><h2>最新消息</h2></div>
	    <div class="col-md-10 sheader">
			<!-- <form class="form-inline" role="form" action="<?php echo $search_url?>" method="POST">
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
			</form> -->
	    </div>
	</div>

	<div class="row" style="margin:10px 10px">
	    <div class="span3">
	    	<button class="btn btn-info" type="button" onClick="aHover('<?php echo $create_url;?>')">新增</button>
			<button type="button" id="donebatch" class="btn btn-info">批次刪除</button>
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
						<th>
							<label class="label_check c_on" for="checkbox-01">
								<input type="checkbox" id="select-all"/>
							</label>
						</th> 
						<th>日期</th>
						<th>標題</th>
						<th>內容</th>
						<th>圖片</th> 
						<th>刪除</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($results))
					{
						foreach($results as $key=>$rows)
						{

					?>
					<tr>
						<td>
							<label class="label_check c_on" for="checkbox-01">
								<input type="checkbox" name="id[]" newsid="<?php echo $rows->id?>"/>
							</label>
						</td>  
						<td style="width:100px">
							<?php 
                             $date = explode(" ", $rows->date); 
                             $date2 = $date[0];
                             echo $date2;

                            ?>
						</td>
						<td><?php echo $rows->title?></td>
						<td><?php echo substr($rows->content,0,100)."..."?></td>
						<td>
							<?php if (isset($rows->img) && !empty($rows->img)): ?>
								<img style="width:150px" src="<?php echo site_url()."assets/".$rows->img?>" />
							<?php endif ?>
							
						</td>
						<!-- <td><?php echo site_url()."assets/".$rows->img?></td> -->
						<td>
							<button class="btn btn-xs btn-primary" type="button" onclick="aHover('<?php echo $edit_url.$rows->id?>')" >更新</button>
							<button class="btn btn-xs btn-danger del" type="button" onclick="dialog_chk('<?php echo $rows->id?>')">刪除</button>
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
		</section>
	</div>
	
	<div style="text-align:center">
	  <ul class="pagination">
		<?php echo $page_jump?>
	  </ul>
	</div>
<?php echo js($this->config->item('news_javascript'), 'news')?>
<script>
	var $j = jQuery.noConflict(true);

	function aHover(url)
	{
		location.href = url;
	}

	$j("document").ready(function($) {
 
		$j("#select-all").click(function() {

		   if($j("#select-all").prop("checked"))
		   {
				$j("input[name='id[]']").each(function() {
					$j(this).prop("checked", true);
				});
		   }
		   else
		   {
				$j("input[name='id[]']").each(function() {
					$j(this).prop("checked", false);
				});     
		   }
		});

		$j("#donebatch").click(function(){
			var ids = [];
			var j = 0;
			var postData = {};
			var api_url = '<?php echo $multi_del_url?>';
			$j("input[name='id[]']").each(function(i){
				if($j(this).prop("checked"))
				{
					ids[j] = $j(this).attr('newsid');
					j++;
				}
			});
			console.log(ids);
			postData = {'ids': ids};
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

	function del(id)
	{
		var	 api_url = '<?php echo $del_url ?>' + id;

		console.log(api_url);
	   
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
					setTimeout("update_page()", 500);
				}

			},
		});
	}
	function dialog_chk(id)
	{
		$j( "#dialog-confirm p" ).text('您確定要刪除嗎？');
		$j( "#dialog-confirm" ).dialog({
		  resizable: false,
		  height:150,
		  modal: true,
		  buttons: {
		    "Delete": function() {
				del(id);
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
 
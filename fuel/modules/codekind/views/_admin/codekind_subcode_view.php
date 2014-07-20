
<div id="main_top_panel">
	<h2 class="ico ico_blog_settings"><?php echo $view_name?></h2>
</div>

<div class="clear"></div>
<div id="action">
	<div id="filters"></div>
	<div class="buttonbar" id="actions">
		<div class="button50" onclick="aHover('<?php echo $back_url?>')">上一層</div>
		<div class="button50" onclick="aHover('<?php echo $create_url?>')">新增</div>
	</div>
</div>

<div id="notification" class="notification"><span></span></div>
<div id="dialog-confirm" title="刪除確認?">
  <p></p>
</div>
<style>
	table.data tbody tr:nth-child(even) { background-color: #f3f3f3; }
	#main_content table tbody a{
		color: #4679bd;
	}
	.content_header
	{
		margin: 10px 0 0 20px;
	}
	#notification span
	{
		display: none;
		color: #ff2600;
		margin: 0 0 0 20px;
		line-height: 20px;
	}
</style>

<div id="main_content">
	<div id="list_container">
		<!-- list view -->
		<div id="data_table_container">
<?php
		if(isset($code_results))
		{
?>
			<table cellpadding="0" cellspacing="0" id="data_table" class="data">
				<thead>
					<tr>
						<th class="col1">
							<a href="#" onclick="">Code Name</a></th>
						<th class="col2">
							<a href="#" onclick="">Codekind Name</a></th>
						<th class="col3">
							<a href="#" onclick="">Code Value1</a></th>
						<th class="col4">
							<a href="#" onclick="">Second</a></th>
						<th class="col5">
							<a href="#" onclick="">Modi Time</a></th>
						<th class="col6"><span>&nbsp;</span></th>
					</tr>
				</thead>
				<tbody>
<?php
				foreach($code_results as $row)
				{
?>
					<tr id="data_table_row2" class="rowaction">
						<td class="col1 first"><?php echo $row->code_name?></td>
						<td class="col2"><?php echo $row->codekind_name?></td>
						<td class="col3"><?php echo $row->code_value1?></td>
						<td class="col4"><a href="<?php echo $row->id?>">下一層</a></td>
						<td class="col5"><?php echo $row->modi_time?></td>
						<td class="col6 actions"><a href="<?php echo $edit_url.$row->id?>">EDIT</a>&nbsp; |  &nbsp;<a href="javascript:;" onclick="dialog_chk('<?php echo $row->id?>'); return false;">DELETE</a></td>
					</tr>
<?php
				}
?>
				</tbody>
			</table>
<?php
		}
		else
		{
?>
			<div class="nodata">
				No data to display.<br /><br />
				<div class="button50" onclick="aHover('<?php echo $back_url?>')">上一層</div>
			</div>
<?php
		}
?>
		</div>
		<div class="loader" id="table_loader" style="display: none;"></div>
	</div>
</div>
<?php echo js($this->config->item('codekind_javascript'), 'codekind')?>
<?php echo css($CI->config->item('codekind_css'), 'codekind')?>

<script>
	function aHover(url)
	{
		location.href = url;
	}

	function del_code(code_id)
	{
		var	 api_url = '<?php echo $del_url?>' + code_id;
	   
		$.ajax({
			url: api_url,
			type: 'POST',
			async: true,
			crossDomain: false,
			cache: false,
			success: function(data, textStatus, jqXHR){
				var data_json=jQuery.parseJSON(data);
				console.log(data_json);
				$( "#dialog-confirm" ).dialog( "close" );
				if(data_json.status == 1)
				{
					$("#notification span").text('刪除成功');
					$("#notification span").fadeIn(100).fadeOut(1000);
					setTimeout("update_page()", 500);
				}

			},
		});
	}
	function dialog_chk(code_id)
	{
		$( "#dialog-confirm p" ).text('您確定要刪除嗎？');
		$( "#dialog-confirm" ).dialog({
		  resizable: false,
		  height:150,
		  modal: true,
		  buttons: {
		    "Delete": function() {
				del_code(code_id);
		    },
		    Cancel: function() {
		      $( this ).dialog( "close" );
		    }
		  }
		});
	}
	function update_page()
	{
		location.reload();
	}
	jQuery(document).ready(function($) {

	});
</script>
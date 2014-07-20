<div id="action">
	<div id="filters"></div>
	<div class="buttonbar" id="actions">
		
	</div>
</div>

<div id="notification" class="notification"><span></span></div>
<div id="main_content_inner">

	<p class="instructions"><?php echo $view_name?></p>

	<form method="post" action="<?php echo $submit_url?>" enctype="multipart/form-data" id="addForm">
		<table summary="services">
			<tbody>
			<tr class="odd">
				<td scope="row" class="column1" colspan="2"><strong style="font-size:14px"><?php echo $view_name?></strong></td>
			</tr>
			<tr>
				<th scope="row" class="column1">* 群組名稱</th>
				<td>
					<select name="codekind_key">
						<?php
							foreach($codekind_results as $kind_row)
							{
						?>
							<option value="<?php echo $kind_row->codekind_key?>" <?php if($kind_row->codekind_key == $codekind_key) echo "SELECTED"; ?>><?php echo $kind_row->codekind_name?></option>
						<?php
							}
						?>
					</select>
				</td>
			</tr>
			<tr class="odd">
				<th scope="row" class="column1">父層</th>	
				<td>
					<?php echo $parent_name?>
					<input type="hidden" name="parent_id" value="<?php echo $parent_id?>" /> 
				</td>
			</tr>
			<tr class="odd">
				<th scope="row" class="column1">Code Name</th>	
				<td>
					<input type="text" name="code_name" value="" /> 
				</td>
			</tr>
			<tr>
				<th scope="row" class="column1">Code Key</th>
				<td>
					<input type="text" name="code_key" value="" /> 
				</td>
			</tr>	
			<tr class="odd">
				<th scope="row" class="column1">Code Value1</th>	
				<td>
					<input type="text" name="code_value1" value=""> 
				</td>
			</tr>
			<tr>
				<th scope="row" class="column1">Code Value2</th> 
				<td>
					<input type="text" name="code_value2" value=""> 
				</td>
			</tr> 		
			<tr>
				<th scope="row" class="column1">Code Value3</th> 
				<td>
					<input type="text" name="code_value3" value=""> 
				</td>
			</tr>
			<tr>
				<th scope="row" class="column1">Lang Code</th> 
				<td>
					<input type="text" name="lang_code" value=""> 
				</td>
			</tr> 
			<tr>
				<td colspan="2" style="text-align:right">
				<div class="button20" onclick="form_submit()">Save</div>
				<div class="button20" onclick="">Cancel</div>
				</td>
			</tr>
			</tbody>
		</table>
	</form>
</div>
<?php echo js($this->config->item('codekind_javascript'), 'codekind')?>
<?php echo css($CI->config->item('codekind_css'), 'codekind')?>
<script>
	function aHover(url)
	{
		location.href = url;

		return false;
	}

	function form_submit()
	{
		$("#addForm").submit();
		return false;
	}
	jQuery(document).ready(function($) {

	});
</script>
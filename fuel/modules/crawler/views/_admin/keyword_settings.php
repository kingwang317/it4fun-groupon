
<div id="main_top_panel">
	<h2 class="ico ico_blog_settings">Keyword Settings</h2>
</div>
<div class="clear"></div>

<div id="notification" class="notification">

</div>
<div id="main_content" class="noaction">
	<div id="main_content_inner">

		<p class="instructions">
			關鍵字設定:
		</p>
		<br />
		<table summary="services">
			<tbody>
				<tr class="odd">
					<td scope="row" class="column1" colspan="2"><strong>廠商資料維護</strong></td>
				</tr>
				<?php
				foreach ($customers as $key => $value) {
				?>
				<tr>
					<th scope="row" class="column1"><?php echo $value->cus_domain?></th>
					<td>
						<select name="cus_kw" id="cus_kw<?php echo $value->cus_id?>" multiple="" class="multiselect" style="display: none;">
						<?php
						foreach ($keyword as $key => $row) {
							if($row->cus_id == $value->cus_id):
						?>
							<option value="<?php echo $row->kw_name?>" selected="selected"><?php echo $row->kw_name?></option>
						<?php
							endif;
						}
						?>
						</select>
					</td>
				</tr>
				<?php
				}
				?>
				<tr class="odd">
					<td scope="row" class="column1" colspan="2"><a href="/fuel/company/posts">返回廠商列表</a></td>
				</tr>
			</tbody>
		</table>
		
		<!--
			<select name='cus_kw' id='cus_kw' class="multiselect">
				<?php 
				foreach ($keyword as $key => $rows)
				{
				?>
					<option value='<?php echo $rows->kw_name?>' selected='selected'><?php echo $rows->kw_name?></option>
				<?php 
				}
				?>
			</select>-->
		
	</div>
</div>
<?php echo js($this->config->item('company_javascript'), 'company')?>
<?php echo css($CI->config->item('company_css'), 'company')?>
<script>
	var $j = jQuery.noConflict();
	jQuery(document).ready(function($) {

	<?php
	foreach ($customers as $key => $value) {
	?>
		$j("#cus_kw<?php echo $value->cus_id?>").multiselect();
	<?php
	}
	?>
	});
</script>
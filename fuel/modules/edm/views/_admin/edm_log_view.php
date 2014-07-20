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
		<div class="col-md-12">
			<ol class="breadcrumb">
			  <li><a href="<?php echo $back_url?>">電子報列表</a></li>
			  <li class="active"><?php echo $view_name;?></li>
			</ol>
		</div>
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
						<td>會員姓名</td>
						<td>email</td>
						<td>寄送時間</td>
						<td>發送狀態</td>
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($edm_log_result))
					{
						foreach($edm_log_result as $rows)
						{

					?>
					<tr>
						<td>
							<label class="checkbox">
								<input type="checkbox" name="edm_log_id[]" edmid="<?php echo $rows->edm_log_id?>"/>
							</label>
						</td>						
						<td><?php echo $rows->member_name?></td>
						<td>
							<?php echo $rows->target?>
						</td>
						<td><?php echo $rows->run_date;?></td>
						<td>
							<?php 
								if($rows->has_send == 0)
								{
									echo "尚未發送";
								}
								else
								{
									echo "已發送";
								}
							?>
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
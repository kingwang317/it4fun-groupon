<?php if ($this->fuel_auth->has_permission('company/view_company')) : ?>
<div class="dashboard_pod" style="width: 400px;">
	<h3><?=lang('dashboard_latest_news')?></h3>
	<ul class="nobullets">
		<?php foreach($latest_company as $k => $val) : ?>
		<li><strong><?=english_date($val['update_time'], false)?>:</strong> [<?=$val['com_name']?>] - <?=$val['com_name']?></li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>

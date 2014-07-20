<?php if ($this->fuel_auth->has_permission('codekind/view_codekind')) : ?>
<div class="dashboard_pod" style="width: 400px;">
	<h3><?=lang('dashboard_latest_news')?></h3>
	<ul class="nobullets">
		<?php foreach($latest_company as $k => $val) : ?>
		<li><strong><?=english_date($val['modi_time'], false)?>:</strong> [<?=$val['codekind_name']?>] - <?=$val['codekind_name']?></li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>

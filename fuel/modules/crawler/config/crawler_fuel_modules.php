<?php
// included in the main config/MY_fuel_modules.php

$config['modules']['crawler_lists'] = array(
		'module_name' => '排程管理',
		'module_uri' => 'crawler/lists',
		'model_name' => 'crawler_lists_model',
		'model_location' => 'crawler',
		'permission' => 'crawler/lists',
		'nav_selected' => 'crawler/lists',
		'archivable' => TRUE,
		'instructions' => '新增/修改排程'
	);
?>
<?php
// included in the main config/MY_fuel_modules.php

$config['modules']['news_manage'] = array(
		'module_name' => '上稿管理',
		'model_name' => 'news_manage_model',
		'module_uri' => 'news/lists',
		'model_location' => 'news',
		'permission' => 'news',
		'nav_selected' => 'news/lists',
		'archivable' => TRUE,
		'instructions' => '新增/修改'
	);
?>
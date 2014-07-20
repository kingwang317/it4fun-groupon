<?php
// included in the main config/MY_fuel_modules.php

$config['modules']['order_manage'] = array(
		'module_name' => '訂單管理',
		'model_name' => 'order_manage_model',
		'module_uri' => 'order/lists',
		'model_location' => 'order',
		'permission' => 'order/manage',
		'nav_selected' => 'order/lists',
		'archivable' => TRUE,
		'instructions' => '新增/修改訂單'
	);
?>
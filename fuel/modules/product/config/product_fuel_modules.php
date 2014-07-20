<?php
// included in the main config/MY_fuel_modules.php

$config['modules']['product_manage'] = array(
		'module_name' => '產品管理',
		'model_name' => 'product_manage_model',
		'module_uri' => 'product/lists',
		'model_location' => 'product',
		'permission' => 'product/manage',
		'nav_selected' => 'product/lists',
		'archivable' => TRUE,
		'instructions' => '新增/修改產品'
	);

?>
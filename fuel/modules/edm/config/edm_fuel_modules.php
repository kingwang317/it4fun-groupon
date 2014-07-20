<?php
// included in the main config/MY_fuel_modules.php

$config['modules']['edm_manage'] = array(
		'module_name' => '產品管理',
		'model_name' => 'edm_manage_model',
		'module_uri' => 'edm/lists',
		'model_location' => 'edm',
		'permission' => 'edm/manage',
		'nav_selected' => 'edm/lists',
		'archivable' => TRUE,
		'instructions' => '新增/修改電子報'
	);

?>
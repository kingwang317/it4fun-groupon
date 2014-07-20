<?php
// included in the main config/MY_fuel_modules.php

$config['modules']['member_manage'] = array(
		'module_name' => '會員管理',
		'model_name' => 'member_manage_model',
		'module_uri' => 'member/lists',
		'model_location' => 'member',
		'permission' => 'member/manage',
		'nav_selected' => 'member/lists',
		'archivable' => TRUE,
		'instructions' => '新增/修改會員'
	);
?>
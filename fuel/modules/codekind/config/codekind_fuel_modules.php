<?php
// included in the main config/MY_fuel_modules.php

$config['modules']['codekind_lists'] = array(
		'module_name' => '代碼管理',
		'module_uri' => 'codekind/lists',
		'model_name' => 'codekind_lists_model',
		'model_location' => 'codekind',
		'permission' => 'codekind/lists',
		'nav_selected' => 'codekind/lists',
		'table_headers' => array(
			'id', 
			'codekind_name', 
			'codekind_value1', 
			'modi_time' 
		),
		'default_col' => 'modi_time',
		'default_order' => 'desc',
		'archivable' => TRUE,
		'instructions' => '新增/修改群組'
	);

$config['modules']['codekind_codelists'] = array(
		'module_name' => '代碼列表',
		'module_uri' => 'codekind/codelists',
		'model_name' => 'codekind_codelists_model',
		'table_headers' => array(
			'id', 
			'code_name', 
			'codekind_name', 
			'code_value1',
			'second',
			'modi_time' 
		),
		'display_field' => 'code_name',
		'model_location' => 'codekind',
		'permission' => 'codekind/codelists',
		'nav_selected' => 'codekind/codelists',
		'archivable' => TRUE,
		'default_col' => 'modi_time',
		'default_order' => 'desc',
		'configuration' => array('codekind' => 'codekind'),
		'instructions' => '新增/修改代碼'
	);

$config['modules']['codekind_subcodelists'] = array(
		'module_name' => '代碼列表',
		'module_uri' => 'codekind/subcodelists',
		'model_name' => 'codekind_subcodelists_model',
		'table_headers' => array(
			'id', 
			'code_name', 
			'codekind_name', 
			'code_value1',
			'second',
			'modi_time' 
		),
		'display_field' => 'code_name',
		'model_location' => 'codekind',
		'permission' => 'codekind/subcodelists',
		'nav_selected' => 'codekind/subcodelists',
		'archivable' => TRUE,
		'default_col' => 'modi_time',
		'default_order' => 'desc',
		'configuration' => array('codekind' => 'codekind'),
		'instructions' => '新增/修改代碼'
	);

$config['modules']['codekind_subcode'] = array(
	'module_name' => '子代碼列表',
	'module_uri' => 'codekind/keywords',
	'model_name' => 'codekind_subcode_model',
	'model_location' => 'codekind',
	'permission' => 'codekind/subcodelists',
	'instructions' => '子代碼列表',
	'archivable' => TRUE,
	'nav_selected' => 'codekind/subcodelists',
);
?>
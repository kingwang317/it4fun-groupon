<?php 
/*
|--------------------------------------------------------------------------
| MY Custom Modules
|--------------------------------------------------------------------------
|
| Specifies the module controller (key) and the name (value) for fuel
*/


/*********************** EXAMPLE ***********************************

$config['modules']['quotes'] = array(
	'preview_path' => 'about/what-they-say',
);

$config['modules']['projects'] = array(
	'preview_path' => 'showcase/project/{slug}',
	'sanitize_images' => FALSE // to prevent false positives with xss_clean image sanitation
);

*********************** EXAMPLE ***********************************/

/*
$config['modules']['firm'] = array(
   'model_location' => 'firm',
   'module_name' => '廠商管理',
   'module_uri' => 'firm',
   'model_name' => 'firm_model',
   'permission' => 'modules/firm',
   'nav_selected' => 'firm'
   );*/
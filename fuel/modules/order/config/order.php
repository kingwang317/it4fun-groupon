<?php
/*
|--------------------------------------------------------------------------
| FUEL NAVIGATION: An array of navigation items for the left menu
|--------------------------------------------------------------------------
*/
$config['nav']['order'] = array(
'order/lists'		=> '訂單列表'
);

// deterines whether to use this configuration below or the database for controlling the blogs behavior
$config['crawleruse_db_table_settings'] = TRUE;

// the cache folder to hold blog cache files
$config['order_cache_group'] = 'order';

$config['tables']['mod_order'] = 'mod_order';


$config['order_javascript'] = array(
	'jquery.min.js',
	'jquery-ui.min.js',
	'doT.min.js'
);

$config['order_css'] = array(
	'bootstrap.min.css',
	'admin_style.css',
	'jquery-ui.css'
);

?>
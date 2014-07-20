<?php
/*
|--------------------------------------------------------------------------
| FUEL NAVIGATION: An array of navigation items for the left menu
|--------------------------------------------------------------------------
*/
$config['nav']['edm'] = array(
'edm/lists'		=> '電子報列表'

);

// deterines whether to use this configuration below or the database for controlling the blogs behavior
$config['crawleruse_db_table_settings'] = TRUE;

// the cache folder to hold blog cache files
$config['product_cache_group'] = 'edm';

$config['tables']['mod_edm'] = 'mod_edm';


$config['edm_javascript'] = array(
	'jquery.min.js',
	'jquery-ui.min.js',
	'jquery.colorbox-min.js',
	'doT.min.js'
);

$config['ck_js'] = array(
	'ckeditor.js',
	'adapters/jquery.js'
);

$config['edm_css'] = array(
	'bootstrap.min.css',
	'admin_style.css',
	'jquery-ui.css',
	'colorbox.css'
);
/*
$config['rich_css'] = array(
	'rich_editor.css',
);
*/
?>
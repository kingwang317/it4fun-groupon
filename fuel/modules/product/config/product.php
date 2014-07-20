<?php
/*
|--------------------------------------------------------------------------
| FUEL NAVIGATION: An array of navigation items for the left menu
|--------------------------------------------------------------------------
*/
$config['nav']['product'] = array(
'product/lists'		=> '產品列表'
);

// deterines whether to use this configuration below or the database for controlling the blogs behavior
$config['crawleruse_db_table_settings'] = TRUE;

// the cache folder to hold blog cache files
$config['product_cache_group'] = 'product';

$config['tables']['mod_product'] = 'mod_product';


$config['product_javascript'] = array(
	'jquery.min.js',
	'jquery-ui.min.js',
	'jquery.colorbox-min.js',
	'doT.min.js',
	'jquery.numeric.js',
	'jquery.validate.min.js'
);

$config['ck_js'] = array(
	'ckeditor.js',
	'adapters/jquery.js'
);

$config['product_css'] = array(
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
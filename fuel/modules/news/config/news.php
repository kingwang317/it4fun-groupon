<?php
/*
|--------------------------------------------------------------------------
| FUEL NAVIGATION: An array of navigation items for the left menu
|--------------------------------------------------------------------------
*/
$config['nav']['news'] = array(
'news/lists'		=> '最新消息'
);

// deterines whether to use this configuration below or the database for controlling the blogs behavior
$config['crawleruse_db_table_settings'] = TRUE;

// the cache folder to hold blog cache files
$config['news'] = 'news';

$config['tables']['mod_news'] = 'mod_news';


$config['news_javascript'] = array(
    'jquery.min.js',
	'jquery-ui.min.js'
);

$config['news_css'] = array(
	'bootstrap.min.css',
	'admin_style.css',
	'jquery-ui.css'
);

?>
<?php
/*
|--------------------------------------------------------------------------
| FUEL NAVIGATION: An array of navigation items for the left menu
|--------------------------------------------------------------------------
*/
$config['nav']['member'] = array(
'member/lists'		=> '會員列表'
);

// deterines whether to use this configuration below or the database for controlling the blogs behavior
$config['crawleruse_db_table_settings'] = TRUE;

// the cache folder to hold blog cache files
$config['member_cache_group'] = 'member';

$config['tables']['mod_member'] = 'mod_member';


$config['member_javascript'] = array(
	'jquery.min.js',
	'jquery-ui.min.js'
);

$config['member_css'] = array(
	'bootstrap.min.css',
	'admin_style.css',
	'jquery-ui.css'
);

?>
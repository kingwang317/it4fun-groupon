<?php
/*
|--------------------------------------------------------------------------
| FUEL NAVIGATION: An array of navigation items for the left menu
|--------------------------------------------------------------------------
*/
$config['nav']['codekind'] = array(
'codekind/lists'		=> '代碼群組列表',
'codekind/codelists'	=> '代碼列表'
);

// deterines whether to use this configuration below or the database for controlling the blogs behavior
$config['crawleruse_db_table_settings'] = TRUE;

// the cache folder to hold blog cache files
$config['codekind_cache_group'] = 'codekind';

$config['tables']['mod_codekind'] = 'mod_codekind';
$config['tables']['mod_code'] = 'mod_code';

$config['codekind_javascript'] = array(
	'jquery-ui.min.js',
	'ui.multiselect.js',
	'jquery.localisation-min.js',
	'jquery.scrollTo-min.js'
);

$config['codekind_css'] = array(
	'jquery-ui.css',
	'ui.multiselect.css',
	'common.css'
);

?>
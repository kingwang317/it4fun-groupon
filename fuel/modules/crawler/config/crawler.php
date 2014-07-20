<?php
/*
|--------------------------------------------------------------------------
| FUEL NAVIGATION: An array of navigation items for the left menu
|--------------------------------------------------------------------------
*/
$config['nav']['crawler'] = array(
'crawler/lists' => '排程列表1'
);

// deterines whether to use this configuration below or the database for controlling the blogs behavior
$config['crawleruse_db_table_settings'] = TRUE;

// the cache folder to hold blog cache files
$config['crawler_cache_group'] = 'crawler';

$config['tables']['crawler_lists'] = 'crawler';


?>
<?php 
//link the controller to the nav link


$route[FUEL_ROUTE.'news/lists'] 			= NEWS_FOLDER.'/news_manage/lists';
$route[FUEL_ROUTE.'news/lists/(:num)'] 		= NEWS_FOLDER.'/news_manage/lists/$1';
$route[FUEL_ROUTE.'news/create'] 			= NEWS_FOLDER.'/news_manage/create';
$route[FUEL_ROUTE.'news/edit/(:num)'] 		= NEWS_FOLDER.'/news_manage/edit/$1';
$route[FUEL_ROUTE.'news/del/(:num)'] 		= NEWS_FOLDER.'/news_manage/do_del/$1';
$route[FUEL_ROUTE.'news/do_create'] 		= NEWS_FOLDER.'/news_manage/do_create';
$route[FUEL_ROUTE.'news/do_edit/(:num)'] 	= NEWS_FOLDER.'/news_manage/do_edit/$1';
$route[FUEL_ROUTE.'news/do_multi_del'] 		= NEWS_FOLDER.'/news_manage/do_multi_del';
$route[FUEL_ROUTE.'news/area/(:num)'] 		= NEWS_FOLDER.'/news_manage/getArea/$1';

 
<?php 
//link the controller to the nav link


$route[FUEL_ROUTE.'edm/lists'] 					= EDM_FOLDER.'/edm_manage/lists';
$route[FUEL_ROUTE.'edm/lists/(:num)'] 			= EDM_FOLDER.'/edm_manage/lists/$1';
$route[FUEL_ROUTE.'edm/create'] 				= EDM_FOLDER.'/edm_manage/create';
$route[FUEL_ROUTE.'edm/edit/(:num)'] 			= EDM_FOLDER.'/edm_manage/edit/$1';
$route[FUEL_ROUTE.'edm/del/(:num)'] 			= EDM_FOLDER.'/edm_manage/do_del/$1';
$route[FUEL_ROUTE.'edm/do_create'] 				= EDM_FOLDER.'/edm_manage/do_create';
$route[FUEL_ROUTE.'edm/do_edit/(:num)'] 		= EDM_FOLDER.'/edm_manage/do_edit/$1';
$route[FUEL_ROUTE.'edm/do_send'] 				= EDM_FOLDER.'/edm_manage/do_send';
$route[FUEL_ROUTE.'edm/do_multi_del'] 			= EDM_FOLDER.'/edm_manage/do_multi_del';
$route[FUEL_ROUTE.'edm/loglists/(:num)']		= EDM_FOLDER.'/edm_manage/edm_log_list/$1';
$route[FUEL_ROUTE.'edm/loglists/(:num)/(:num)']	= EDM_FOLDER.'/edm_manage/edm_log_list/$1/$2';
$route[FUEL_ROUTE.'edm/send/(:num)']			= EDM_FOLDER.'/edm_manage/do_send_by_id/$1';

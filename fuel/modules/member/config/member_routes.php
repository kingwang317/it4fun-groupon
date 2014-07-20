<?php 
//link the controller to the nav link


$route[FUEL_ROUTE.'member/lists'] 			= MEMBER_FOLDER.'/member_manage/lists';
$route[FUEL_ROUTE.'member/lists/(:num)'] 	= MEMBER_FOLDER.'/member_manage/lists/$1';
$route[FUEL_ROUTE.'member/create'] 			= MEMBER_FOLDER.'/member_manage/create';
$route[FUEL_ROUTE.'member/edit/(:num)'] 	= MEMBER_FOLDER.'/member_manage/edit/$1';
$route[FUEL_ROUTE.'member/del/(:num)'] 		= MEMBER_FOLDER.'/member_manage/do_del/$1';
$route[FUEL_ROUTE.'member/do_create'] 		= MEMBER_FOLDER.'/member_manage/do_create';
$route[FUEL_ROUTE.'member/do_edit/(:num)'] 	= MEMBER_FOLDER.'/member_manage/do_edit/$1';
$route[FUEL_ROUTE.'member/do_multi_del'] 	= MEMBER_FOLDER.'/member_manage/do_multi_del';
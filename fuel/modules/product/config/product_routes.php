<?php 
//link the controller to the nav link


$route[FUEL_ROUTE.'product/lists'] 			= PRODUCT_FOLDER.'/product_manage/lists';
$route[FUEL_ROUTE.'product/lists/(:num)'] 			= PRODUCT_FOLDER.'/product_manage/lists/$1';
$route[FUEL_ROUTE.'product/create'] 		= PRODUCT_FOLDER.'/product_manage/create';
$route[FUEL_ROUTE.'product/edit/(:num)'] 	= PRODUCT_FOLDER.'/product_manage/edit/$1';
$route[FUEL_ROUTE.'product/del/(:num)'] 	= PRODUCT_FOLDER.'/product_manage/do_del/$1';
$route[FUEL_ROUTE.'product/do_create'] 		= PRODUCT_FOLDER.'/product_manage/do_create';
$route[FUEL_ROUTE.'product/do_edit/(:num)'] = PRODUCT_FOLDER.'/product_manage/do_edit/$1';
$route[FUEL_ROUTE.'product/do_multi_del'] 	= PRODUCT_FOLDER.'/product_manage/do_multi_del';
$route[FUEL_ROUTE.'product/upload_files'] 	= PRODUCT_FOLDER.'/product_manage/upload_files';
$route[FUEL_ROUTE.'product/del_photos'] 	= PRODUCT_FOLDER.'/product_manage/del_photos';
$route[FUEL_ROUTE.'product/update_photo_data'] 	= PRODUCT_FOLDER.'/product_manage/update_photo_data';
$route[FUEL_ROUTE.'product/get_photo_data'] 	= PRODUCT_FOLDER.'/product_manage/get_photo_data';
$route[FUEL_ROUTE.'product/add_plan'] 	= PRODUCT_FOLDER.'/product_manage/add_plan';
$route[FUEL_ROUTE.'product/update_plan/(:num)'] 	= PRODUCT_FOLDER.'/product_manage/update_plan/$1';
$route[FUEL_ROUTE.'product/plan_detail/(:num)'] 	= PRODUCT_FOLDER.'/product_manage/plan_detail/$1';
$route[FUEL_ROUTE.'product/del_plan'] 	= PRODUCT_FOLDER.'/product_manage/del_plan';
$route[FUEL_ROUTE.'product/chk_plan'] 	= PRODUCT_FOLDER.'/product_manage/chk_plan';
$route[FUEL_ROUTE.'product/chk_plan/(:num)'] 	= PRODUCT_FOLDER.'/product_manage/chk_plan/$1';
$route[FUEL_ROUTE.'product/ckphoto/(:num)'] 	= PRODUCT_FOLDER.'/product_manage/get_photo_data_to_ck/$1';
<?php 
//link the controller to the nav link


$route[FUEL_ROUTE.'order/lists'] = ORDER_FOLDER.'/order_manage/lists';
$route[FUEL_ROUTE.'order/lists/(:num)'] = ORDER_FOLDER.'/order_manage/lists/$1';
$route[FUEL_ROUTE.'order/lists/(:num)/(:num)'] = ORDER_FOLDER.'/order_manage/lists/$1/$2';
$route[FUEL_ROUTE.'order/create'] = ORDER_FOLDER.'/order_manage/create';
$route[FUEL_ROUTE.'order/edit/(:num)'] = ORDER_FOLDER.'/order_manage/edit/$1';
$route[FUEL_ROUTE.'order/del/(:num)'] = ORDER_FOLDER.'/order_manage/do_del/$1';
$route[FUEL_ROUTE.'order/do_create'] = ORDER_FOLDER.'/order_manage/do_create';
$route[FUEL_ROUTE.'order/do_edit/(:num)'] = ORDER_FOLDER.'/order_manage/do_edit/$1';
$route[FUEL_ROUTE.'order/do_multi_del'] = ORDER_FOLDER.'/order_manage/do_multi_del';
$route[FUEL_ROUTE.'order/get/prod/lists/(:any)'] = ORDER_FOLDER.'/order_manage/get_prod_list/$1';
$route[FUEL_ROUTE.'order/get/plan/lists/(:num)'] = ORDER_FOLDER.'/order_manage/get_plan_list/$1';
$route[FUEL_ROUTE.'order/batch/action'] = ORDER_FOLDER.'/order_manage/batch_action';
$route[FUEL_ROUTE.'order/export/excel'] = ORDER_FOLDER.'/order_manage/download_excel';
$route[FUEL_ROUTE.'order/export/report'] = ORDER_FOLDER.'/order_manage/export_report';

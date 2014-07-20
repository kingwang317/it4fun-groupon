<?php 
//link the controller to the nav link

$crawler_controllers = array('lists');

foreach($crawler_controllers as $c)
{
	$route[FUEL_ROUTE.'crawler/'.$c] = FUEL_FOLDER.'/module';
	$route[FUEL_ROUTE.'crawler/'.$c.'/(.*)'] = FUEL_FOLDER.'/module/$1';
}
/*
$route[FUEL_ROUTE.'crawler/dashboard'] = COMPANY_FOLDER.'/dashboard';
$route[FUEL_ROUTE.'crawler/keyword_settings/(:num)'] = COMPANY_FOLDER.'/keyword_settings/update/$1';
$route[FUEL_ROUTE.'crawler/show/(:num)'] = COMPANY_FOLDER.'/company_show/show/$1';
$route[FUEL_ROUTE.'crawler/keyword/cost'] = COMPANY_FOLDER.'/company_show/cost';
*/

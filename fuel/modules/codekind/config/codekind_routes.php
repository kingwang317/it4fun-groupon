<?php 
//link the controller to the nav link

$codekind_controllers = array('lists', 'codelists', 'subcodelists');

foreach($codekind_controllers as $c)
{
	$route[FUEL_ROUTE.'codekind/'.$c] = FUEL_FOLDER.'/module';
	$route[FUEL_ROUTE.'codekind/'.$c.'/(.*)'] = FUEL_FOLDER.'/module/$1';
}

$route[FUEL_ROUTE.'codekind/sub/codelists/(:num)'] = CODEKIND_FOLDER.'/codekind_subcode/lists/$1';
$route[FUEL_ROUTE.'codekind/sub/codelists/edit/(:num)'] = CODEKIND_FOLDER.'/codekind_subcode/edit/$1';
$route[FUEL_ROUTE.'codekind/sub/codelists/create/(:num)'] = CODEKIND_FOLDER.'/codekind_subcode/create/$1';
$route[FUEL_ROUTE.'codekind/sub/codelists/do_create'] = CODEKIND_FOLDER.'/codekind_subcode/do_create';
$route[FUEL_ROUTE.'codekind/sub/codelists/do_edit/(:num)'] = CODEKIND_FOLDER.'/codekind_subcode/do_edit/$1';
$route[FUEL_ROUTE.'codekind/sub/codelists/delete/(:num)'] = CODEKIND_FOLDER.'/codekind_subcode/delete/$1';
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There is one reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
*/

$route['default_controller'] = 'home';
$route['404_override'] = 'fuel/page_router';
$route['user/login'] = 'member_about/do_login';
$route['user/logout'] = 'member_about/do_logout';
$route['product/detail/(:num)'] = 'prod/detail/$1';
$route['ordercheck'] = 'member_about/order_check';
$route['ordercheck/(:num)'] = 'member_about/order_check/$1';
$route['old/list'] = 'prod/oldprods';
$route['logincheck'] = 'member_about/is_logined';
$route['payment'] = 'payment/payment_form';
$route['test'] = 'edm_job/edm_send_job';
$route['update_order_num'] = 'order_job/update_order_num';
$route['payment/create'] = 'payment/create_order';
$route['payment/test'] = 'payment/test';
$route['payment/callback'] = 'payment/payment_callback';
$route['update_my_data/(:num)'] = 'member_about/do_update_member/$1';
$route['forgot_pwd'] = 'member_about/send_new_pwd';
$route['chkLogin'] = 'member_about/chk_login';
$route['category/(:num)'] = 'home/category/$1';
$route['category'] = 'home/category/47';
$route['news'] = 'news_front';
$route['news/(:num)'] = 'news_front/index/$1';
$route['addToCart/(:num)/(:num)'] = 'prod/do_set_cart_info/$1/$2';
$route['removeFromCart/(:num)'] = 'prod/do_remove_cart/$1'; 
$route['cart'] = 'prod/cart';


/*	
| Uncomment this line if you want to use the automatically generated sitemap based on your navigation.
| To modify the sitemap.xml, go to the views/sitemap_xml.php file.
*/ 
//$route['sitemap.xml'] = 'sitemap_xml';

include(MODULES_PATH.'/fuel/config/fuel_routes.php');

/* End of file routes.php */
/* Location: ./application/config/routes.php */
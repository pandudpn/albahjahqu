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
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

//variables in (:num), (:any), ([a-zA-Z_-]+)

if($_SERVER['SERVER_NAME'] == 'article.okbabe.co.id')
{
    //ARTIKEL
	$route['(:num)/view'] 		  = 'articles/front/index/$1';
	$route['(:any)'] 		  	  = 'articles/front/index/$1';
	$route['default_controller']  = 'articles/front/index/';
}
else
{
	$route['default_controller']  = 'home';
	$route['login']               = 'user/login';
}   



$route['complaints/messages/(:num)']  = 'complaints/messages/index/$1';
$route['customers/mutation/(:num)']   = 'customers/mutation/index/$1';
$route['customers/balance/(:num)']    = 'customers/balance/index/$1';
$route['outlets/transaction/(:num)']  = 'outlets/transaction/index/$1';

$route['dealers/boxes']               = 'dealers/boxes/main';
$route['dealers/boxes/add']           = 'dealers/boxes/main/add';
$route['dealers/boxes/edit/(:num)']   = 'dealers/boxes/main/edit/$1';
$route['dealers/boxes/save']          = 'dealers/boxes/main/save';
$route['dealers/boxes/delete/(:num)'] = 'dealers/boxes/main/delete/$1';
$route['dealers/boxes/datatables']    = 'dealers/boxes/main/datatables';

$route['dealers/boxes/(:num)/service']               = 'dealers/boxes/service/index/$1';
$route['dealers/boxes/(:num)/service/add']           = 'dealers/boxes/service/add/$1';
$route['dealers/boxes/(:num)/service/edit/(:num)']   = 'dealers/boxes/service/edit/$1/$2';
$route['dealers/boxes/(:num)/service/delete/(:num)'] = 'dealers/boxes/service/delete/$1/$2';
$route['dealers/boxes/service/save']                 = 'dealers/boxes/service/save';
$route['dealers/boxes/(:num)/service/datatables']    = 'dealers/boxes/service/datatables/$1';

$route['dealers/boxes/(:num)/stock']               = 'dealers/boxes/stock/index/$1';
$route['dealers/boxes/(:num)/stock/add']           = 'dealers/boxes/stock/add/$1';
$route['dealers/boxes/(:num)/stock/edit/(:num)']   = 'dealers/boxes/stock/edit/$1/$2';
$route['dealers/boxes/(:num)/stock/delete/(:num)'] = 'dealers/boxes/stock/delete/$1/$2';
$route['dealers/boxes/stock/save']                 = 'dealers/boxes/stock/save';
$route['dealers/boxes/(:num)/stock/datatables']    = 'dealers/boxes/stock/datatables/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */

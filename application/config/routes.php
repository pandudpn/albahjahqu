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
if($_SERVER['SERVER_NAME'] == 'admin.sinergi46.com' || $_SERVER['SERVER_NAME'] == 'localhost'){
    $route['default_controller']  = 'home';
    $route['category/(:num)']     = 'category/index/$1';
    $route['tag/(:num)']          = 'tag/index/$1';
    $route['promo/(:num)']        = 'promo/index/$1';
    $route['product/(:num)']      = 'product/index/$1';
    $route['gallery/(:num)']      = 'gallery/index/$1';
    $route['gallery/image/(:num)']      = 'gallery/image/index/$1';
    $route['gallery/image/(:num)/(:num)']      = 'gallery/image/index/$1/$2';
    $route['user/admin/(:num)']   = 'user/admin/index/$1';
    $route['facebook']            = 'references/facebook_connect';
    $route['google']              = 'references/google_connect';
}elseif($_SERVER['SERVER_NAME'] == 'article.sinergi46.com'){
    $route['default_controller']  = 'front';
    $route['(:any)']              = 'front/index/$1';
}   

/* End of file routes.php */
/* Location: ./application/config/routes.php */

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
| 	example.com/class/method/id/
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
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved
| routes must come before any wildcard or regular expression routes.
|
*/

// front page paging
$route['p'] = "welcome/index/0"; // no page
$route['p/(:num)'] = "welcome/index/$1"; // page

$route['f/(:any)'] = "welcome/index/0/$1"; // filter
$route['f/(:any)/(:any)/(:any)'] = "welcome/index/0/$1/$2/$3"; // filter, order

$route['o/(:any)/(:any)'] = "welcome/index/0/all/$1/$2"; // order
$route['p/(:num)/(:any)'] = "welcome/index/$1/$2"; // page, filter

$route['p/(:num)/(:any)/(:any)'] = "welcome/index/$1/all/$2/$3"; // page, order
$route['p/(:num)/(:any)/(:any)/(:any)'] = "welcome/index/$1/$2/$3/$4"; // page, filter, order

$route['started'] = "welcome/index/0/started/latest/desc"; // startedby no specific user
$route['started/(:any)'] = "welcome/index/0/started/latest/desc/$1"; // startedby
$route['p/(:num)/(:any)/(:any)/(:any)/(:any)'] = "welcome/index/$1/$2/$3/$4/$5"; // page, filter, order, startedby
$route['f/(:any)/(:any)/(:any)/(:any)'] = "welcome/index/0/$1/$2/$3"; // filter, order, startedby



// subject | no paging
$route['thread/(:num)/:any'] = "thread/load/$1/0";

// subject | paging
$route['thread/(:num)/(:any)/p/(:num)'] = "thread/load/$1/$2";

// no subject | paging
$route['thread/(:num)/p/(:num)'] = "thread/load/$1/$2";

// let them pass, they'll be redirected home anyways
$route['thread/(:any)'] = "thread/load/$1";

// new route for finding threads by title
$route['find/(:any)'] = "find";

$route['user/(:any)/(:any)/p/(:num)'] = "user/check/$1/$2/$3";
$route['user/(:any)/(:any)'] = "user/check/$1/$2";
$route['user/(:any)'] = "user/load/$1";


$route['users/(:num)'] = "users/index/$1";
$route['users/(:num)/(:any)'] = "users/index/$1/$2";

$route['message/(:num)'] = "message/load/$1";

$route['buddies/remove/(:num)/(:any)'] = "buddies/remove/$1/$2";
$route['buddies/move/(:any)/(:num)/(:any)'] = "buddies/move/$1/$2/$3";
$route['buddies/error/(:num)'] = "buddies/error/$1";
$route['buddies/(:any)'] = "buddies/index/$1";


$route['default_controller'] = "welcome";
$route['scaffolding_trigger'] = "";


/* End of file routes.php */
/* Location: ./system/application/config/routes.php */

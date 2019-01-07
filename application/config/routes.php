<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/* 
| -------------------------------------------------------------------------
| Backend
| -------------------------------------------------------------------------
*/

$method = $_SERVER['REQUEST_METHOD'];

// Login & Signup
if ($method == 'GET' || $method == 'POST') {
    $route['login'] = 'auth/login';
    $route['signup'] = 'auth/signup';
    $route['forgot'] = 'auth/forgotPassword';
    $route['change_password'] = 'auth/changeAdminPassword';
}
if ($method == 'GET') {
    $route['terms'] = 'auth/terms';
    $route['logout'] = 'auth/logout';
    $route['active/(:any)'] = 'auth/active/$1';
    $route['change/(:any)'] = 'auth/changePassword/$1';
}

// Admin
if ($method == 'GET') {
    $route['dashboard'] = 'admin';

    // Admin Account Management
    $route['admins'] = 'admin/middleware/getAllAdmins';
    // User Account Management
    $route['users'] = 'admin/middleware/getAllUsers';
    
    $route['users/active/(:any)'] = 'admin/middleware1/activeUser/$1';
    $route['users/disable/(:any)'] = 'admin/middleware1/disableUser/$1';
    $route['users/delete/(:any)'] = 'admin/middleware1/deleteUser/$1';
    
    // Reports Management
    $route['appreviews'] = 'admin/middleware/getAllAppReviews';
    $route['nondogfriendlys'] = 'admin/middleware/getAllNonDogFriendlys';
    $route['newlocations'] = 'admin/middleware/getAllNewLocations';

    $route['appreview/delete/(:any)'] = 'admin/middleware1/deleteAppReview/$1';
    $route['nondogfriendly/delete/(:any)'] = 'admin/middleware1/deleteNonDogFriendly/$1';
    $route['newlocation/delete/(:any)'] = 'admin/middleware1/deleteNewLocation/$1';
    
    $route['appreview/(:any)/(:any)'] = 'admin/middleware2/getAppReview/$1/$2';
    $route['nondogfriendly/(:any)/(:any)'] = 'admin/middleware2/getNonDogFriendly/$1/$2';
    $route['newlocation/(:any)/(:any)'] = 'admin/middleware2/getNewLocation/$1/$2';
        
    $route['appreviews/delete/(:any)/(:any)'] = 'admin/middleware2/deleteAppReviews/$1/$2';
    $route['nondogfriendlys/delete/(:any)/(:any)'] = 'admin/middleware2/deleteNonDogFriendlys/$1/$2';
    $route['newlocations/delete/(:any)/(:any)'] = 'admin/middleware2/deleteNewLocations/$1/$2';
}

/* 
| -------------------------------------------------------------------------
| APP API
| -------------------------------------------------------------------------
*/

if ($method == 'POST') {
    $route['api/signup'] = 'api/signup';
    $route['api/login'] = 'api/login';
    $route['api/logout'] = 'api/logout';

    $route['api/email_verify'] = 'api/emailVerify';
    
    $route['api/forgot_password'] = 'api/forgotPassword';
    $route['api/change_password'] = 'api/changePassword';
    
    $route['api/report_app_review'] = 'api/reportAppReview';
    $route['api/report_non_dogfriendly'] = 'api/reportNonDogFriendly';
    $route['api/report_new_location'] = 'api/reportNewLocation';
    
    $route['api/get_app_review'] = 'api/getAppReview';
    $route['api/get_non_dogfriendly'] = 'api/getNonDogFriendly';
}
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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['default_controller'] = 'home';
$route['auth/login']                = 'auth/login';
$route['auth/register']             = 'auth/register';
$route['auth/register_wizard']      = 'auth/register_wizard';
$route['auth/trigger_otp']          = 'auth/trigger_otp';
$route['auth/pending_approval']     = 'auth/pending_approval';
$route['auth/api_save_member_temp'] = 'auth/api_save_member_temp';
$route['auth/get_member_detail_temp'] = 'auth/get_member_detail_temp';
$route['auth/verify_otp']           = 'auth/verify_otp';
$route['auth/resend_otp']           = 'auth/resend_otp';
$route['auth/forgot_password']      = 'auth/forgot_password';
$route['auth/reset_password/(:any)'] = 'auth/reset_password/$1';
$route['auth/logout']               = 'auth/logout';
$route['admin']                     = 'admin/index';
$route['berita']                    = 'home/berita';
$route['berita/(:num)']             = 'home/berita';        // pagination offset
$route['berita/(:any)']             = 'home/berita_detail/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

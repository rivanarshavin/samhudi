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
$route['admin/api_get_forum_details/(:num)'] = 'admin/api_get_forum_details/$1';
$route['admin/forum_comment_delete/(:num)/(:num)'] = 'admin/forum_comment_delete/$1/$2';
$route['berita']                    = 'home/berita';
$route['berita/(:num)']             = 'home/berita';        // pagination offset
$route['berita/(:any)']             = 'home/berita_detail/$1';
$route['profile']                   = 'Profile/index';
$route['profile/update']            = 'Profile/update';

// Linkedin Routes
$route['linkedin']                  = 'linkedin/index';
$route['linkedin/create_job']       = 'linkedin/create_job';
$route['linkedin/get_job/(:num)']   = 'linkedin/get_job_detail/$1';
$route['linkedin/apply_job']        = 'linkedin/apply_job';

// Admin Lowongan Routes
$route['admin/lowongan']                    = 'admin/lowongan';
$route['admin/lowongan_approve/(:num)']     = 'admin/lowongan_approve/$1';
$route['admin/lowongan_reject/(:num)']      = 'admin/lowongan_reject/$1';
$route['admin/lowongan_delete/(:num)']      = 'admin/lowongan_delete/$1';
$route['admin/lowongan_add']                = 'admin/lowongan_add';

// Yayasan Public Routes
$route['yayasan']                           = 'yayasan/index';
$route['yayasan/rekapitulasi']              = 'yayasan/rekapitulasi';
$route['pembina']                           = 'yayasan/rekapitulasi';
$route['yayasan/nominate']                  = 'yayasan/nominate';
$route['yayasan/vote/(:num)']               = 'yayasan/vote/$1';
$route['yayasan/detail/(:num)']             = 'yayasan/detail/$1';

// Admin Yayasan Routes
$route['admin/yayasan']                     = 'admin/yayasan';
$route['admin/yayasan/edit/(:num)']         = 'admin/yayasan_edit/$1';
$route['admin/yayasan/delete/(:num)']       = 'admin/yayasan_delete/$1';
$route['admin/yayasan/status/(:num)/(:any)'] = 'admin/yayasan_update_status/$1/$2';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

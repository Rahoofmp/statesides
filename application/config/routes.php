<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
$route['default_controller'] = 'login/index';
$route['404_override'] 		= 'my404';
$route['translate_uri_dashes'] = true;
$route['jsloader/(:any)'] = "jsloader/file/$1";

$route['referral'] 			= 'referral/index';
$route['logout'] 			= 'login/logout';
$route['customer-login'] 			= 'login/customer-login';
$route['session-out']		= 'login/session-out';
$route['forgot']			= 'login/forgot'; 
$route['reset']			    = 'login/reset'; 
$route['under-maintenance']	= 'login/under-maintenance'; 

$route['network'] 			= 'network/genealogy-tree';
$route['success/(:any)']	= "signup/success/$1";
$route['customer-job-order-confirmation/(:any)']	= "login/customer-job-order-confirmation/$1";
$route['admin-job-order-confirmation/(:any)']	= "login/admin-job-order-confirmation/$1";
$route['customer-forgot']	= "login/customer_forgot";

$route['sub-admin'] = 'supervisor';
$route['sub-admin/(:any)'] = 'supervisor/$1';
$route['sub-admin/(:any)/(:any)'] = 'supervisor/$1/$2';
$route['sub-admin/(:any)/(:any)/(:any)'] = 'supervisor/$1/$2/$3';
$route['sub-admin/(:any)/(:any)/(:any)/(:any)'] = 'supervisor/$1/$2/$3/$4';




// $route['index'] 				= 'website/index';
// $route['contact-us'] 		= 'website/contact';
// $route['about-us.php'] 			= 'website/about';
// $route['sign-up.php'] 			= 'website/register';
// $route['terms-conditions'] 		= 'website/terms_conditions';
// $route['refund-policy'] 		= 'website/refund_policy';
// $route['certificates'] 		= 'website/certificates';
// $route['privacy-policy'] 		= 'website/privacy-policy';
// $route['cookies-policy'] 		= 'website/cookies-policy';
// $route['referral/(:any)'] 		= 'referral/index/$1';
// $route['terms_and_conditions'] 		= 'website/terms_and_conditions';
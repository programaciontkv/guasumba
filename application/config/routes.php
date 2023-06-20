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
// $route['default_controller'] = 'welcome';
$route['default_controller'] = 'Login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['/index.php/inicio'] = "inicio";

$route['factura_empresa1_local1_caja1/(:any)'] = "factura/$1";
$route['factura_empresa1_local1_caja2/(:any)'] = "factura/$1";
$route['factura_empresa1_local1_caja3/(:any)'] = "factura/$1";
$route['factura_empresa1_local1_caja4/(:any)'] = "factura/$1";
$route['factura_empresa1_local1_caja5/(:any)'] = "factura/$1";
$route['factura_empresa1_local1_caja6/(:any)'] = "factura/$1";
$route['factura_empresa1_local1_caja7/(:any)'] = "factura/$1";
$route['factura_empresa1_local1_caja8/(:any)'] = "factura/$1";
$route['factura_empresa1_local1_caja9/(:any)'] = "factura/$1";
$route['factura_empresa1_local1_caja10/(:any)'] = "factura/$1";

$route['factura_empresa1_local2_caja1/(:any)'] = "factura/$1";
$route['factura_empresa1_local2_caja2/(:any)'] = "factura/$1";
$route['factura_empresa1_local2_caja3/(:any)'] = "factura/$1";
$route['factura_empresa1_local2_caja4/(:any)'] = "factura/$1";
$route['factura_empresa1_local2_caja5/(:any)'] = "factura/$1";
$route['factura_empresa1_local2_caja6/(:any)'] = "factura/$1";
$route['factura_empresa1_local2_caja7/(:any)'] = "factura/$1";
$route['factura_empresa1_local2_caja8/(:any)'] = "factura/$1";
$route['factura_empresa1_local2_caja9/(:any)'] = "factura/$1";
$route['factura_empresa1_local2_caja10/(:any)'] = "factura/$1";

$route['factura_empresa1_local3_caja1/(:any)'] = "factura/$1";
$route['factura_empresa1_local3_caja2/(:any)'] = "factura/$1";
$route['factura_empresa1_local3_caja3/(:any)'] = "factura/$1";
$route['factura_empresa1_local3_caja4/(:any)'] = "factura/$1";
$route['factura_empresa1_local3_caja5/(:any)'] = "factura/$1";
$route['factura_empresa1_local3_caja6/(:any)'] = "factura/$1";
$route['factura_empresa1_local3_caja7/(:any)'] = "factura/$1";
$route['factura_empresa1_local3_caja8/(:any)'] = "factura/$1";
$route['factura_empresa1_local3_caja9/(:any)'] = "factura/$1";
$route['factura_empresa1_local3_caja10/(:any)'] = "factura/$1";

$route['factura_empresa1_local4_caja1/(:any)'] = "factura/$1";
$route['factura_empresa1_local4_caja2/(:any)'] = "factura/$1";
$route['factura_empresa1_local4_caja3/(:any)'] = "factura/$1";
$route['factura_empresa1_local4_caja4/(:any)'] = "factura/$1";
$route['factura_empresa1_local4_caja5/(:any)'] = "factura/$1";
$route['factura_empresa1_local4_caja6/(:any)'] = "factura/$1";
$route['factura_empresa1_local4_caja7/(:any)'] = "factura/$1";
$route['factura_empresa1_local4_caja8/(:any)'] = "factura/$1";
$route['factura_empresa1_local4_caja9/(:any)'] = "factura/$1";
$route['factura_empresa1_local4_caja10/(:any)'] = "factura/$1";

$route['factura_empresa1_local5_caja1/(:any)'] = "factura/$1";
$route['factura_empresa1_local5_caja2/(:any)'] = "factura/$1";
$route['factura_empresa1_local5_caja3/(:any)'] = "factura/$1";
$route['factura_empresa1_local5_caja4/(:any)'] = "factura/$1";
$route['factura_empresa1_local5_caja5/(:any)'] = "factura/$1";
$route['factura_empresa1_local5_caja6/(:any)'] = "factura/$1";
$route['factura_empresa1_local5_caja7/(:any)'] = "factura/$1";
$route['factura_empresa1_local5_caja8/(:any)'] = "factura/$1";
$route['factura_empresa1_local5_caja9/(:any)'] = "factura/$1";
$route['factura_empresa1_local5_caja10/(:any)'] = "factura/$1";

$route['factura_empresa1_local6_caja1/(:any)'] = "factura/$1";
$route['factura_empresa1_local6_caja2/(:any)'] = "factura/$1";
$route['factura_empresa1_local6_caja3/(:any)'] = "factura/$1";
$route['factura_empresa1_local6_caja4/(:any)'] = "factura/$1";
$route['factura_empresa1_local6_caja5/(:any)'] = "factura/$1";
$route['factura_empresa1_local6_caja6/(:any)'] = "factura/$1";
$route['factura_empresa1_local6_caja7/(:any)'] = "factura/$1";
$route['factura_empresa1_local6_caja8/(:any)'] = "factura/$1";
$route['factura_empresa1_local6_caja9/(:any)'] = "factura/$1";
$route['factura_empresa1_local6_caja10/(:any)'] = "factura/$1";

$route['factura_empresa1_local7_caja1/(:any)'] = "factura/$1";
$route['factura_empresa1_local7_caja2/(:any)'] = "factura/$1";
$route['factura_empresa1_local7_caja3/(:any)'] = "factura/$1";
$route['factura_empresa1_local7_caja4/(:any)'] = "factura/$1";
$route['factura_empresa1_local7_caja5/(:any)'] = "factura/$1";
$route['factura_empresa1_local7_caja6/(:any)'] = "factura/$1";
$route['factura_empresa1_local7_caja7/(:any)'] = "factura/$1";
$route['factura_empresa1_local7_caja8/(:any)'] = "factura/$1";
$route['factura_empresa1_local7_caja9/(:any)'] = "factura/$1";
$route['factura_empresa1_local7_caja10/(:any)'] = "factura/$1";

$route['factura_empresa1_local8_caja1/(:any)'] = "factura/$1";
$route['factura_empresa1_local8_caja2/(:any)'] = "factura/$1";
$route['factura_empresa1_local8_caja3/(:any)'] = "factura/$1";
$route['factura_empresa1_local8_caja4/(:any)'] = "factura/$1";
$route['factura_empresa1_local8_caja5/(:any)'] = "factura/$1";
$route['factura_empresa1_local8_caja6/(:any)'] = "factura/$1";
$route['factura_empresa1_local8_caja7/(:any)'] = "factura/$1";
$route['factura_empresa1_local8_caja8/(:any)'] = "factura/$1";
$route['factura_empresa1_local8_caja9/(:any)'] = "factura/$1";
$route['factura_empresa1_local8_caja10/(:any)'] = "factura/$1";

$route['factura_empresa1_local9_caja1/(:any)'] = "factura/$1";
$route['factura_empresa1_local9_caja2/(:any)'] = "factura/$1";
$route['factura_empresa1_local9_caja3/(:any)'] = "factura/$1";
$route['factura_empresa1_local9_caja4/(:any)'] = "factura/$1";
$route['factura_empresa1_local9_caja5/(:any)'] = "factura/$1";
$route['factura_empresa1_local9_caja6/(:any)'] = "factura/$1";
$route['factura_empresa1_local9_caja7/(:any)'] = "factura/$1";
$route['factura_empresa1_local9_caja8/(:any)'] = "factura/$1";
$route['factura_empresa1_local9_caja9/(:any)'] = "factura/$1";
$route['factura_empresa1_local9_caja10/(:any)'] = "factura/$1";

$route['factura_empresa1_local10_caja1/(:any)'] = "factura/$1";
$route['factura_empresa1_local10_caja2/(:any)'] = "factura/$1";
$route['factura_empresa1_local10_caja3/(:any)'] = "factura/$1";
$route['factura_empresa1_local10_caja4/(:any)'] = "factura/$1";
$route['factura_empresa1_local10_caja5/(:any)'] = "factura/$1";
$route['factura_empresa1_local10_caja6/(:any)'] = "factura/$1";
$route['factura_empresa1_local10_caja7/(:any)'] = "factura/$1";
$route['factura_empresa1_local10_caja8/(:any)'] = "factura/$1";
$route['factura_empresa1_local10_caja9/(:any)'] = "factura/$1";
$route['factura_empresa1_local10_caja10/(:any)'] = "factura/$1";

$route['factura_empresa2_local1_caja1/(:any)'] = "factura/$1";
$route['factura_empresa2_local1_caja2/(:any)'] = "factura/$1";
$route['factura_empresa2_local1_caja3/(:any)'] = "factura/$1";
$route['factura_empresa2_local1_caja4/(:any)'] = "factura/$1";
$route['factura_empresa2_local1_caja5/(:any)'] = "factura/$1";
$route['factura_empresa2_local1_caja6/(:any)'] = "factura/$1";
$route['factura_empresa2_local1_caja7/(:any)'] = "factura/$1";
$route['factura_empresa2_local1_caja8/(:any)'] = "factura/$1";
$route['factura_empresa2_local1_caja9/(:any)'] = "factura/$1";
$route['factura_empresa2_local1_caja10/(:any)'] = "factura/$1";

$route['factura_empresa2_local2_caja1/(:any)'] = "factura/$1";
$route['factura_empresa2_local2_caja2/(:any)'] = "factura/$1";
$route['factura_empresa2_local2_caja3/(:any)'] = "factura/$1";
$route['factura_empresa2_local2_caja4/(:any)'] = "factura/$1";
$route['factura_empresa2_local2_caja5/(:any)'] = "factura/$1";
$route['factura_empresa2_local2_caja6/(:any)'] = "factura/$1";
$route['factura_empresa2_local2_caja7/(:any)'] = "factura/$1";
$route['factura_empresa2_local2_caja8/(:any)'] = "factura/$1";
$route['factura_empresa2_local2_caja9/(:any)'] = "factura/$1";
$route['factura_empresa2_local2_caja10/(:any)'] = "factura/$1";

$route['factura_empresa2_local3_caja1/(:any)'] = "factura/$1";
$route['factura_empresa2_local3_caja2/(:any)'] = "factura/$1";
$route['factura_empresa2_local3_caja3/(:any)'] = "factura/$1";
$route['factura_empresa2_local3_caja4/(:any)'] = "factura/$1";
$route['factura_empresa2_local3_caja5/(:any)'] = "factura/$1";
$route['factura_empresa2_local3_caja6/(:any)'] = "factura/$1";
$route['factura_empresa2_local3_caja7/(:any)'] = "factura/$1";
$route['factura_empresa2_local3_caja8/(:any)'] = "factura/$1";
$route['factura_empresa2_local3_caja9/(:any)'] = "factura/$1";
$route['factura_empresa2_local3_caja10/(:any)'] = "factura/$1";

$route['factura_empresa2_local4_caja1/(:any)'] = "factura/$1";
$route['factura_empresa2_local4_caja2/(:any)'] = "factura/$1";
$route['factura_empresa2_local4_caja3/(:any)'] = "factura/$1";
$route['factura_empresa2_local4_caja4/(:any)'] = "factura/$1";
$route['factura_empresa2_local4_caja5/(:any)'] = "factura/$1";
$route['factura_empresa2_local4_caja6/(:any)'] = "factura/$1";
$route['factura_empresa2_local4_caja7/(:any)'] = "factura/$1";
$route['factura_empresa2_local4_caja8/(:any)'] = "factura/$1";
$route['factura_empresa2_local4_caja9/(:any)'] = "factura/$1";
$route['factura_empresa2_local4_caja10/(:any)'] = "factura/$1";

$route['factura_empresa2_local5_caja1/(:any)'] = "factura/$1";
$route['factura_empresa2_local5_caja2/(:any)'] = "factura/$1";
$route['factura_empresa2_local5_caja3/(:any)'] = "factura/$1";
$route['factura_empresa2_local5_caja4/(:any)'] = "factura/$1";
$route['factura_empresa2_local5_caja5/(:any)'] = "factura/$1";
$route['factura_empresa2_local5_caja6/(:any)'] = "factura/$1";
$route['factura_empresa2_local5_caja7/(:any)'] = "factura/$1";
$route['factura_empresa2_local5_caja8/(:any)'] = "factura/$1";
$route['factura_empresa2_local5_caja9/(:any)'] = "factura/$1";
$route['factura_empresa2_local5_caja10/(:any)'] = "factura/$1";

$route['factura_empresa2_local6_caja1/(:any)'] = "factura/$1";
$route['factura_empresa2_local6_caja2/(:any)'] = "factura/$1";
$route['factura_empresa2_local6_caja3/(:any)'] = "factura/$1";
$route['factura_empresa2_local6_caja4/(:any)'] = "factura/$1";
$route['factura_empresa2_local6_caja5/(:any)'] = "factura/$1";
$route['factura_empresa2_local6_caja6/(:any)'] = "factura/$1";
$route['factura_empresa2_local6_caja7/(:any)'] = "factura/$1";
$route['factura_empresa2_local6_caja8/(:any)'] = "factura/$1";
$route['factura_empresa2_local6_caja9/(:any)'] = "factura/$1";
$route['factura_empresa2_local6_caja10/(:any)'] = "factura/$1";

$route['factura_empresa2_local7_caja1/(:any)'] = "factura/$1";
$route['factura_empresa2_local7_caja2/(:any)'] = "factura/$1";
$route['factura_empresa2_local7_caja3/(:any)'] = "factura/$1";
$route['factura_empresa2_local7_caja4/(:any)'] = "factura/$1";
$route['factura_empresa2_local7_caja5/(:any)'] = "factura/$1";
$route['factura_empresa2_local7_caja6/(:any)'] = "factura/$1";
$route['factura_empresa2_local7_caja7/(:any)'] = "factura/$1";
$route['factura_empresa2_local7_caja8/(:any)'] = "factura/$1";
$route['factura_empresa2_local7_caja9/(:any)'] = "factura/$1";
$route['factura_empresa2_local7_caja10/(:any)'] = "factura/$1";

$route['factura_empresa2_local8_caja1/(:any)'] = "factura/$1";
$route['factura_empresa2_local8_caja2/(:any)'] = "factura/$1";
$route['factura_empresa2_local8_caja3/(:any)'] = "factura/$1";
$route['factura_empresa2_local8_caja4/(:any)'] = "factura/$1";
$route['factura_empresa2_local8_caja5/(:any)'] = "factura/$1";
$route['factura_empresa2_local8_caja6/(:any)'] = "factura/$1";
$route['factura_empresa2_local8_caja7/(:any)'] = "factura/$1";
$route['factura_empresa2_local8_caja8/(:any)'] = "factura/$1";
$route['factura_empresa2_local8_caja9/(:any)'] = "factura/$1";
$route['factura_empresa2_local8_caja10/(:any)'] = "factura/$1";

$route['factura_empresa2_local9_caja1/(:any)'] = "factura/$1";
$route['factura_empresa2_local9_caja2/(:any)'] = "factura/$1";
$route['factura_empresa2_local9_caja3/(:any)'] = "factura/$1";
$route['factura_empresa2_local9_caja4/(:any)'] = "factura/$1";
$route['factura_empresa2_local9_caja5/(:any)'] = "factura/$1";
$route['factura_empresa2_local9_caja6/(:any)'] = "factura/$1";
$route['factura_empresa2_local9_caja7/(:any)'] = "factura/$1";
$route['factura_empresa2_local9_caja8/(:any)'] = "factura/$1";
$route['factura_empresa2_local9_caja9/(:any)'] = "factura/$1";
$route['factura_empresa2_local9_caja10/(:any)'] = "factura/$1";

$route['factura_empresa2_local10_caja1/(:any)'] = "factura/$1";
$route['factura_empresa2_local10_caja2/(:any)'] = "factura/$1";
$route['factura_empresa2_local10_caja3/(:any)'] = "factura/$1";
$route['factura_empresa2_local10_caja4/(:any)'] = "factura/$1";
$route['factura_empresa2_local10_caja5/(:any)'] = "factura/$1";
$route['factura_empresa2_local10_caja6/(:any)'] = "factura/$1";
$route['factura_empresa2_local10_caja7/(:any)'] = "factura/$1";
$route['factura_empresa2_local10_caja8/(:any)'] = "factura/$1";
$route['factura_empresa2_local10_caja9/(:any)'] = "factura/$1";
$route['factura_empresa2_local10_caja10/(:any)'] = "factura/$1";

$route['factura_empresa3_local1_caja1/(:any)'] = "factura/$1";
$route['factura_empresa3_local1_caja2/(:any)'] = "factura/$1";
$route['factura_empresa3_local1_caja3/(:any)'] = "factura/$1";
$route['factura_empresa3_local1_caja4/(:any)'] = "factura/$1";
$route['factura_empresa3_local1_caja5/(:any)'] = "factura/$1";
$route['factura_empresa3_local1_caja6/(:any)'] = "factura/$1";
$route['factura_empresa3_local1_caja7/(:any)'] = "factura/$1";
$route['factura_empresa3_local1_caja8/(:any)'] = "factura/$1";
$route['factura_empresa3_local1_caja9/(:any)'] = "factura/$1";
$route['factura_empresa3_local1_caja10/(:any)'] = "factura/$1";

$route['factura_empresa3_local2_caja1/(:any)'] = "factura/$1";
$route['factura_empresa3_local2_caja2/(:any)'] = "factura/$1";
$route['factura_empresa3_local2_caja3/(:any)'] = "factura/$1";
$route['factura_empresa3_local2_caja4/(:any)'] = "factura/$1";
$route['factura_empresa3_local2_caja5/(:any)'] = "factura/$1";
$route['factura_empresa3_local2_caja6/(:any)'] = "factura/$1";
$route['factura_empresa3_local2_caja7/(:any)'] = "factura/$1";
$route['factura_empresa3_local2_caja8/(:any)'] = "factura/$1";
$route['factura_empresa3_local2_caja9/(:any)'] = "factura/$1";
$route['factura_empresa3_local2_caja10/(:any)'] = "factura/$1";

$route['factura_empresa3_local3_caja1/(:any)'] = "factura/$1";
$route['factura_empresa3_local3_caja2/(:any)'] = "factura/$1";
$route['factura_empresa3_local3_caja3/(:any)'] = "factura/$1";
$route['factura_empresa3_local3_caja4/(:any)'] = "factura/$1";
$route['factura_empresa3_local3_caja5/(:any)'] = "factura/$1";
$route['factura_empresa3_local3_caja6/(:any)'] = "factura/$1";
$route['factura_empresa3_local3_caja7/(:any)'] = "factura/$1";
$route['factura_empresa3_local3_caja8/(:any)'] = "factura/$1";
$route['factura_empresa3_local3_caja9/(:any)'] = "factura/$1";
$route['factura_empresa3_local3_caja10/(:any)'] = "factura/$1";

$route['factura_empresa3_local4_caja1/(:any)'] = "factura/$1";
$route['factura_empresa3_local4_caja2/(:any)'] = "factura/$1";
$route['factura_empresa3_local4_caja3/(:any)'] = "factura/$1";
$route['factura_empresa3_local4_caja4/(:any)'] = "factura/$1";
$route['factura_empresa3_local4_caja5/(:any)'] = "factura/$1";
$route['factura_empresa3_local4_caja6/(:any)'] = "factura/$1";
$route['factura_empresa3_local4_caja7/(:any)'] = "factura/$1";
$route['factura_empresa3_local4_caja8/(:any)'] = "factura/$1";
$route['factura_empresa3_local4_caja9/(:any)'] = "factura/$1";
$route['factura_empresa3_local4_caja10/(:any)'] = "factura/$1";

$route['factura_empresa3_local5_caja1/(:any)'] = "factura/$1";
$route['factura_empresa3_local5_caja2/(:any)'] = "factura/$1";
$route['factura_empresa3_local5_caja3/(:any)'] = "factura/$1";
$route['factura_empresa3_local5_caja4/(:any)'] = "factura/$1";
$route['factura_empresa3_local5_caja5/(:any)'] = "factura/$1";
$route['factura_empresa3_local5_caja6/(:any)'] = "factura/$1";
$route['factura_empresa3_local5_caja7/(:any)'] = "factura/$1";
$route['factura_empresa3_local5_caja8/(:any)'] = "factura/$1";
$route['factura_empresa3_local5_caja9/(:any)'] = "factura/$1";
$route['factura_empresa3_local5_caja10/(:any)'] = "factura/$1";

$route['factura_empresa3_local6_caja1/(:any)'] = "factura/$1";
$route['factura_empresa3_local6_caja2/(:any)'] = "factura/$1";
$route['factura_empresa3_local6_caja3/(:any)'] = "factura/$1";
$route['factura_empresa3_local6_caja4/(:any)'] = "factura/$1";
$route['factura_empresa3_local6_caja5/(:any)'] = "factura/$1";
$route['factura_empresa3_local6_caja6/(:any)'] = "factura/$1";
$route['factura_empresa3_local6_caja7/(:any)'] = "factura/$1";
$route['factura_empresa3_local6_caja8/(:any)'] = "factura/$1";
$route['factura_empresa3_local6_caja9/(:any)'] = "factura/$1";
$route['factura_empresa3_local6_caja10/(:any)'] = "factura/$1";

$route['factura_empresa3_local7_caja1/(:any)'] = "factura/$1";
$route['factura_empresa3_local7_caja2/(:any)'] = "factura/$1";
$route['factura_empresa3_local7_caja3/(:any)'] = "factura/$1";
$route['factura_empresa3_local7_caja4/(:any)'] = "factura/$1";
$route['factura_empresa3_local7_caja5/(:any)'] = "factura/$1";
$route['factura_empresa3_local7_caja6/(:any)'] = "factura/$1";
$route['factura_empresa3_local7_caja7/(:any)'] = "factura/$1";
$route['factura_empresa3_local7_caja8/(:any)'] = "factura/$1";
$route['factura_empresa3_local7_caja9/(:any)'] = "factura/$1";
$route['factura_empresa3_local7_caja10/(:any)'] = "factura/$1";

$route['factura_empresa3_local8_caja1/(:any)'] = "factura/$1";
$route['factura_empresa3_local8_caja2/(:any)'] = "factura/$1";
$route['factura_empresa3_local8_caja3/(:any)'] = "factura/$1";
$route['factura_empresa3_local8_caja4/(:any)'] = "factura/$1";
$route['factura_empresa3_local8_caja5/(:any)'] = "factura/$1";
$route['factura_empresa3_local8_caja6/(:any)'] = "factura/$1";
$route['factura_empresa3_local8_caja7/(:any)'] = "factura/$1";
$route['factura_empresa3_local8_caja8/(:any)'] = "factura/$1";
$route['factura_empresa3_local8_caja9/(:any)'] = "factura/$1";
$route['factura_empresa3_local8_caja10/(:any)'] = "factura/$1";

$route['factura_empresa3_local9_caja1/(:any)'] = "factura/$1";
$route['factura_empresa3_local9_caja2/(:any)'] = "factura/$1";
$route['factura_empresa3_local9_caja3/(:any)'] = "factura/$1";
$route['factura_empresa3_local9_caja4/(:any)'] = "factura/$1";
$route['factura_empresa3_local9_caja5/(:any)'] = "factura/$1";
$route['factura_empresa3_local9_caja6/(:any)'] = "factura/$1";
$route['factura_empresa3_local9_caja7/(:any)'] = "factura/$1";
$route['factura_empresa3_local9_caja8/(:any)'] = "factura/$1";
$route['factura_empresa3_local9_caja9/(:any)'] = "factura/$1";
$route['factura_empresa3_local9_caja10/(:any)'] = "factura/$1";

$route['factura_empresa3_local10_caja1/(:any)'] = "factura/$1";
$route['factura_empresa3_local10_caja2/(:any)'] = "factura/$1";
$route['factura_empresa3_local10_caja3/(:any)'] = "factura/$1";
$route['factura_empresa3_local10_caja4/(:any)'] = "factura/$1";
$route['factura_empresa3_local10_caja5/(:any)'] = "factura/$1";
$route['factura_empresa3_local10_caja6/(:any)'] = "factura/$1";
$route['factura_empresa3_local10_caja7/(:any)'] = "factura/$1";
$route['factura_empresa3_local10_caja8/(:any)'] = "factura/$1";
$route['factura_empresa3_local10_caja9/(:any)'] = "factura/$1";
$route['factura_empresa3_local10_caja10/(:any)'] = "factura/$1";

$route['factura_empresa4_local1_caja1/(:any)'] = "factura/$1";
$route['factura_empresa4_local1_caja2/(:any)'] = "factura/$1";
$route['factura_empresa4_local1_caja3/(:any)'] = "factura/$1";
$route['factura_empresa4_local1_caja4/(:any)'] = "factura/$1";
$route['factura_empresa4_local1_caja5/(:any)'] = "factura/$1";
$route['factura_empresa4_local1_caja6/(:any)'] = "factura/$1";
$route['factura_empresa4_local1_caja7/(:any)'] = "factura/$1";
$route['factura_empresa4_local1_caja8/(:any)'] = "factura/$1";
$route['factura_empresa4_local1_caja9/(:any)'] = "factura/$1";
$route['factura_empresa4_local1_caja10/(:any)'] = "factura/$1";

$route['factura_empresa4_local2_caja1/(:any)'] = "factura/$1";
$route['factura_empresa4_local2_caja2/(:any)'] = "factura/$1";
$route['factura_empresa4_local2_caja3/(:any)'] = "factura/$1";
$route['factura_empresa4_local2_caja4/(:any)'] = "factura/$1";
$route['factura_empresa4_local2_caja5/(:any)'] = "factura/$1";
$route['factura_empresa4_local2_caja6/(:any)'] = "factura/$1";
$route['factura_empresa4_local2_caja7/(:any)'] = "factura/$1";
$route['factura_empresa4_local2_caja8/(:any)'] = "factura/$1";
$route['factura_empresa4_local2_caja9/(:any)'] = "factura/$1";
$route['factura_empresa4_local2_caja10/(:any)'] = "factura/$1";

$route['factura_empresa4_local3_caja1/(:any)'] = "factura/$1";
$route['factura_empresa4_local3_caja2/(:any)'] = "factura/$1";
$route['factura_empresa4_local3_caja3/(:any)'] = "factura/$1";
$route['factura_empresa4_local3_caja4/(:any)'] = "factura/$1";
$route['factura_empresa4_local3_caja5/(:any)'] = "factura/$1";
$route['factura_empresa4_local3_caja6/(:any)'] = "factura/$1";
$route['factura_empresa4_local3_caja7/(:any)'] = "factura/$1";
$route['factura_empresa4_local3_caja8/(:any)'] = "factura/$1";
$route['factura_empresa4_local3_caja9/(:any)'] = "factura/$1";
$route['factura_empresa4_local3_caja10/(:any)'] = "factura/$1";

$route['factura_empresa4_local4_caja1/(:any)'] = "factura/$1";
$route['factura_empresa4_local4_caja2/(:any)'] = "factura/$1";
$route['factura_empresa4_local4_caja3/(:any)'] = "factura/$1";
$route['factura_empresa4_local4_caja4/(:any)'] = "factura/$1";
$route['factura_empresa4_local4_caja5/(:any)'] = "factura/$1";
$route['factura_empresa4_local4_caja6/(:any)'] = "factura/$1";
$route['factura_empresa4_local4_caja7/(:any)'] = "factura/$1";
$route['factura_empresa4_local4_caja8/(:any)'] = "factura/$1";
$route['factura_empresa4_local4_caja9/(:any)'] = "factura/$1";
$route['factura_empresa4_local4_caja10/(:any)'] = "factura/$1";

$route['factura_empresa4_local5_caja1/(:any)'] = "factura/$1";
$route['factura_empresa4_local5_caja2/(:any)'] = "factura/$1";
$route['factura_empresa4_local5_caja3/(:any)'] = "factura/$1";
$route['factura_empresa4_local5_caja4/(:any)'] = "factura/$1";
$route['factura_empresa4_local5_caja5/(:any)'] = "factura/$1";
$route['factura_empresa4_local5_caja6/(:any)'] = "factura/$1";
$route['factura_empresa4_local5_caja7/(:any)'] = "factura/$1";
$route['factura_empresa4_local5_caja8/(:any)'] = "factura/$1";
$route['factura_empresa4_local5_caja9/(:any)'] = "factura/$1";
$route['factura_empresa4_local5_caja10/(:any)'] = "factura/$1";

$route['factura_empresa4_local6_caja1/(:any)'] = "factura/$1";
$route['factura_empresa4_local6_caja2/(:any)'] = "factura/$1";
$route['factura_empresa4_local6_caja3/(:any)'] = "factura/$1";
$route['factura_empresa4_local6_caja4/(:any)'] = "factura/$1";
$route['factura_empresa4_local6_caja5/(:any)'] = "factura/$1";
$route['factura_empresa4_local6_caja6/(:any)'] = "factura/$1";
$route['factura_empresa4_local6_caja7/(:any)'] = "factura/$1";
$route['factura_empresa4_local6_caja8/(:any)'] = "factura/$1";
$route['factura_empresa4_local6_caja9/(:any)'] = "factura/$1";
$route['factura_empresa4_local6_caja10/(:any)'] = "factura/$1";

$route['factura_empresa4_local7_caja1/(:any)'] = "factura/$1";
$route['factura_empresa4_local7_caja2/(:any)'] = "factura/$1";
$route['factura_empresa4_local7_caja3/(:any)'] = "factura/$1";
$route['factura_empresa4_local7_caja4/(:any)'] = "factura/$1";
$route['factura_empresa4_local7_caja5/(:any)'] = "factura/$1";
$route['factura_empresa4_local7_caja6/(:any)'] = "factura/$1";
$route['factura_empresa4_local7_caja7/(:any)'] = "factura/$1";
$route['factura_empresa4_local7_caja8/(:any)'] = "factura/$1";
$route['factura_empresa4_local7_caja9/(:any)'] = "factura/$1";
$route['factura_empresa4_local7_caja10/(:any)'] = "factura/$1";

$route['factura_empresa4_local8_caja1/(:any)'] = "factura/$1";
$route['factura_empresa4_local8_caja2/(:any)'] = "factura/$1";
$route['factura_empresa4_local8_caja3/(:any)'] = "factura/$1";
$route['factura_empresa4_local8_caja4/(:any)'] = "factura/$1";
$route['factura_empresa4_local8_caja5/(:any)'] = "factura/$1";
$route['factura_empresa4_local8_caja6/(:any)'] = "factura/$1";
$route['factura_empresa4_local8_caja7/(:any)'] = "factura/$1";
$route['factura_empresa4_local8_caja8/(:any)'] = "factura/$1";
$route['factura_empresa4_local8_caja9/(:any)'] = "factura/$1";
$route['factura_empresa4_local8_caja10/(:any)'] = "factura/$1";

$route['factura_empresa4_local9_caja1/(:any)'] = "factura/$1";
$route['factura_empresa4_local9_caja2/(:any)'] = "factura/$1";
$route['factura_empresa4_local9_caja3/(:any)'] = "factura/$1";
$route['factura_empresa4_local9_caja4/(:any)'] = "factura/$1";
$route['factura_empresa4_local9_caja5/(:any)'] = "factura/$1";
$route['factura_empresa4_local9_caja6/(:any)'] = "factura/$1";
$route['factura_empresa4_local9_caja7/(:any)'] = "factura/$1";
$route['factura_empresa4_local9_caja8/(:any)'] = "factura/$1";
$route['factura_empresa4_local9_caja9/(:any)'] = "factura/$1";
$route['factura_empresa4_local9_caja10/(:any)'] = "factura/$1";

$route['factura_empresa4_local10_caja1/(:any)'] = "factura/$1";
$route['factura_empresa4_local10_caja2/(:any)'] = "factura/$1";
$route['factura_empresa4_local10_caja3/(:any)'] = "factura/$1";
$route['factura_empresa4_local10_caja4/(:any)'] = "factura/$1";
$route['factura_empresa4_local10_caja5/(:any)'] = "factura/$1";
$route['factura_empresa4_local10_caja6/(:any)'] = "factura/$1";
$route['factura_empresa4_local10_caja7/(:any)'] = "factura/$1";
$route['factura_empresa4_local10_caja8/(:any)'] = "factura/$1";
$route['factura_empresa4_local10_caja9/(:any)'] = "factura/$1";
$route['factura_empresa4_local10_caja10/(:any)'] = "factura/$1";

$route['factura_empresa5_local1_caja1/(:any)'] = "factura/$1";
$route['factura_empresa5_local1_caja2/(:any)'] = "factura/$1";
$route['factura_empresa5_local1_caja3/(:any)'] = "factura/$1";
$route['factura_empresa5_local1_caja4/(:any)'] = "factura/$1";
$route['factura_empresa5_local1_caja5/(:any)'] = "factura/$1";
$route['factura_empresa5_local1_caja6/(:any)'] = "factura/$1";
$route['factura_empresa5_local1_caja7/(:any)'] = "factura/$1";
$route['factura_empresa5_local1_caja8/(:any)'] = "factura/$1";
$route['factura_empresa5_local1_caja9/(:any)'] = "factura/$1";
$route['factura_empresa5_local1_caja10/(:any)'] = "factura/$1";

$route['factura_empresa5_local2_caja1/(:any)'] = "factura/$1";
$route['factura_empresa5_local2_caja2/(:any)'] = "factura/$1";
$route['factura_empresa5_local2_caja3/(:any)'] = "factura/$1";
$route['factura_empresa5_local2_caja4/(:any)'] = "factura/$1";
$route['factura_empresa5_local2_caja5/(:any)'] = "factura/$1";
$route['factura_empresa5_local2_caja6/(:any)'] = "factura/$1";
$route['factura_empresa5_local2_caja7/(:any)'] = "factura/$1";
$route['factura_empresa5_local2_caja8/(:any)'] = "factura/$1";
$route['factura_empresa5_local2_caja9/(:any)'] = "factura/$1";
$route['factura_empresa5_local2_caja10/(:any)'] = "factura/$1";

$route['factura_empresa5_local3_caja1/(:any)'] = "factura/$1";
$route['factura_empresa5_local3_caja2/(:any)'] = "factura/$1";
$route['factura_empresa5_local3_caja3/(:any)'] = "factura/$1";
$route['factura_empresa5_local3_caja4/(:any)'] = "factura/$1";
$route['factura_empresa5_local3_caja5/(:any)'] = "factura/$1";
$route['factura_empresa5_local3_caja6/(:any)'] = "factura/$1";
$route['factura_empresa5_local3_caja7/(:any)'] = "factura/$1";
$route['factura_empresa5_local3_caja8/(:any)'] = "factura/$1";
$route['factura_empresa5_local3_caja9/(:any)'] = "factura/$1";
$route['factura_empresa5_local3_caja10/(:any)'] = "factura/$1";

$route['factura_empresa5_local4_caja1/(:any)'] = "factura/$1";
$route['factura_empresa5_local4_caja2/(:any)'] = "factura/$1";
$route['factura_empresa5_local4_caja3/(:any)'] = "factura/$1";
$route['factura_empresa5_local4_caja4/(:any)'] = "factura/$1";
$route['factura_empresa5_local4_caja5/(:any)'] = "factura/$1";
$route['factura_empresa5_local4_caja6/(:any)'] = "factura/$1";
$route['factura_empresa5_local4_caja7/(:any)'] = "factura/$1";
$route['factura_empresa5_local4_caja8/(:any)'] = "factura/$1";
$route['factura_empresa5_local4_caja9/(:any)'] = "factura/$1";
$route['factura_empresa5_local4_caja10/(:any)'] = "factura/$1";

$route['factura_empresa5_local5_caja1/(:any)'] = "factura/$1";
$route['factura_empresa5_local5_caja2/(:any)'] = "factura/$1";
$route['factura_empresa5_local5_caja3/(:any)'] = "factura/$1";
$route['factura_empresa5_local5_caja4/(:any)'] = "factura/$1";
$route['factura_empresa5_local5_caja5/(:any)'] = "factura/$1";
$route['factura_empresa5_local5_caja6/(:any)'] = "factura/$1";
$route['factura_empresa5_local5_caja7/(:any)'] = "factura/$1";
$route['factura_empresa5_local5_caja8/(:any)'] = "factura/$1";
$route['factura_empresa5_local5_caja9/(:any)'] = "factura/$1";
$route['factura_empresa5_local5_caja10/(:any)'] = "factura/$1";

$route['factura_empresa5_local6_caja1/(:any)'] = "factura/$1";
$route['factura_empresa5_local6_caja2/(:any)'] = "factura/$1";
$route['factura_empresa5_local6_caja3/(:any)'] = "factura/$1";
$route['factura_empresa5_local6_caja4/(:any)'] = "factura/$1";
$route['factura_empresa5_local6_caja5/(:any)'] = "factura/$1";
$route['factura_empresa5_local6_caja6/(:any)'] = "factura/$1";
$route['factura_empresa5_local6_caja7/(:any)'] = "factura/$1";
$route['factura_empresa5_local6_caja8/(:any)'] = "factura/$1";
$route['factura_empresa5_local6_caja9/(:any)'] = "factura/$1";
$route['factura_empresa5_local6_caja10/(:any)'] = "factura/$1";

$route['factura_empresa5_local7_caja1/(:any)'] = "factura/$1";
$route['factura_empresa5_local7_caja2/(:any)'] = "factura/$1";
$route['factura_empresa5_local7_caja3/(:any)'] = "factura/$1";
$route['factura_empresa5_local7_caja4/(:any)'] = "factura/$1";
$route['factura_empresa5_local7_caja5/(:any)'] = "factura/$1";
$route['factura_empresa5_local7_caja6/(:any)'] = "factura/$1";
$route['factura_empresa5_local7_caja7/(:any)'] = "factura/$1";
$route['factura_empresa5_local7_caja8/(:any)'] = "factura/$1";
$route['factura_empresa5_local7_caja9/(:any)'] = "factura/$1";
$route['factura_empresa5_local7_caja10/(:any)'] = "factura/$1";

$route['factura_empresa5_local8_caja1/(:any)'] = "factura/$1";
$route['factura_empresa5_local8_caja2/(:any)'] = "factura/$1";
$route['factura_empresa5_local8_caja3/(:any)'] = "factura/$1";
$route['factura_empresa5_local8_caja4/(:any)'] = "factura/$1";
$route['factura_empresa5_local8_caja5/(:any)'] = "factura/$1";
$route['factura_empresa5_local8_caja6/(:any)'] = "factura/$1";
$route['factura_empresa5_local8_caja7/(:any)'] = "factura/$1";
$route['factura_empresa5_local8_caja8/(:any)'] = "factura/$1";
$route['factura_empresa5_local8_caja9/(:any)'] = "factura/$1";
$route['factura_empresa5_local8_caja10/(:any)'] = "factura/$1";

$route['factura_empresa5_local9_caja1/(:any)'] = "factura/$1";
$route['factura_empresa5_local9_caja2/(:any)'] = "factura/$1";
$route['factura_empresa5_local9_caja3/(:any)'] = "factura/$1";
$route['factura_empresa5_local9_caja4/(:any)'] = "factura/$1";
$route['factura_empresa5_local9_caja5/(:any)'] = "factura/$1";
$route['factura_empresa5_local9_caja6/(:any)'] = "factura/$1";
$route['factura_empresa5_local9_caja7/(:any)'] = "factura/$1";
$route['factura_empresa5_local9_caja8/(:any)'] = "factura/$1";
$route['factura_empresa5_local9_caja9/(:any)'] = "factura/$1";
$route['factura_empresa5_local9_caja10/(:any)'] = "factura/$1";

$route['factura_empresa5_local10_caja1/(:any)'] = "factura/$1";
$route['factura_empresa5_local10_caja2/(:any)'] = "factura/$1";
$route['factura_empresa5_local10_caja3/(:any)'] = "factura/$1";
$route['factura_empresa5_local10_caja4/(:any)'] = "factura/$1";
$route['factura_empresa5_local10_caja5/(:any)'] = "factura/$1";
$route['factura_empresa5_local10_caja6/(:any)'] = "factura/$1";
$route['factura_empresa5_local10_caja7/(:any)'] = "factura/$1";
$route['factura_empresa5_local10_caja8/(:any)'] = "factura/$1";
$route['factura_empresa5_local10_caja9/(:any)'] = "factura/$1";
$route['factura_empresa5_local10_caja10/(:any)'] = "factura/$1";


$route['factura_masiva_empresa1_local1_caja1/(:any)'] = "factura/$1";

$route['guia_remision_empresa1_local1_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local1_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local1_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local1_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local1_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local1_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local1_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local1_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local1_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local1_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa1_local2_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local2_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local2_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local2_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local2_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local2_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local2_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local2_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local2_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local2_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa1_local3_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local3_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local3_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local3_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local3_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local3_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local3_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local3_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local3_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local3_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa1_local4_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local4_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local4_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local4_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local4_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local4_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local4_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local4_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local4_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local4_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa1_local5_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local5_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local5_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local5_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local5_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local5_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local5_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local5_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local5_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local5_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa1_local6_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local6_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local6_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local6_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local6_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local6_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local6_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local6_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local6_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local6_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa1_local7_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local7_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local7_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local7_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local7_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local7_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local7_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local7_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local7_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local7_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa1_local8_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local8_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local8_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local8_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local8_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local8_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local8_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local8_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local8_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local8_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa1_local9_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local9_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local9_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local9_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local9_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local9_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local9_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local9_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local9_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local9_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa1_local10_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local10_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local10_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local10_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local10_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local10_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local10_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local10_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local10_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa1_local10_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa2_local1_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local1_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local1_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local1_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local1_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local1_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local1_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local1_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local1_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local1_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa2_local2_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local2_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local2_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local2_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local2_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local2_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local2_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local2_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local2_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local2_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa2_local3_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local3_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local3_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local3_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local3_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local3_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local3_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local3_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local3_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local3_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa2_local4_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local4_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local4_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local4_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local4_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local4_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local4_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local4_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local4_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local4_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa2_local5_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local5_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local5_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local5_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local5_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local5_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local5_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local5_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local5_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local5_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa2_local6_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local6_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local6_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local6_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local6_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local6_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local6_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local6_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local6_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local6_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa2_local7_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local7_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local7_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local7_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local7_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local7_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local7_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local7_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local7_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local7_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa2_local8_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local8_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local8_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local8_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local8_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local8_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local8_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local8_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local8_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local8_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa2_local9_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local9_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local9_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local9_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local9_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local9_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local9_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local9_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local9_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local9_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa2_local10_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local10_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local10_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local10_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local10_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local10_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local10_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local10_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local10_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa2_local10_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa3_local1_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local1_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local1_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local1_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local1_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local1_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local1_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local1_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local1_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local1_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa3_local2_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local2_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local2_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local2_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local2_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local2_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local2_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local2_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local2_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local2_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa3_local3_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local3_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local3_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local3_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local3_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local3_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local3_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local3_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local3_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local3_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa3_local4_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local4_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local4_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local4_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local4_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local4_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local4_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local4_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local4_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local4_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa3_local5_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local5_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local5_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local5_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local5_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local5_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local5_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local5_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local5_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local5_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa3_local6_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local6_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local6_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local6_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local6_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local6_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local6_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local6_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local6_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local6_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa3_local7_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local7_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local7_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local7_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local7_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local7_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local7_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local7_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local7_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local7_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa3_local8_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local8_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local8_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local8_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local8_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local8_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local8_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local8_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local8_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local8_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa3_local9_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local9_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local9_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local9_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local9_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local9_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local9_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local9_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local9_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local9_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa3_local10_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local10_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local10_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local10_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local10_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local10_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local10_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local10_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local10_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa3_local10_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa4_local1_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local1_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local1_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local1_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local1_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local1_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local1_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local1_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local1_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local1_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa4_local2_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local2_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local2_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local2_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local2_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local2_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local2_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local2_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local2_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local2_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa4_local3_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local3_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local3_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local3_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local3_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local3_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local3_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local3_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local3_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local3_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa4_local4_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local4_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local4_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local4_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local4_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local4_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local4_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local4_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local4_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local4_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa4_local5_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local5_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local5_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local5_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local5_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local5_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local5_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local5_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local5_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local5_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa4_local6_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local6_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local6_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local6_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local6_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local6_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local6_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local6_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local6_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local6_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa4_local7_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local7_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local7_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local7_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local7_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local7_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local7_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local7_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local7_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local7_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa4_local8_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local8_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local8_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local8_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local8_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local8_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local8_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local8_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local8_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local8_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa4_local9_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local9_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local9_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local9_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local9_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local9_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local9_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local9_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local9_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local9_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa4_local10_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local10_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local10_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local10_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local10_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local10_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local10_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local10_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local10_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa4_local10_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa5_local1_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local1_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local1_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local1_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local1_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local1_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local1_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local1_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local1_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local1_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa5_local2_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local2_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local2_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local2_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local2_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local2_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local2_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local2_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local2_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local2_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa5_local3_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local3_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local3_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local3_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local3_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local3_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local3_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local3_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local3_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local3_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa5_local4_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local4_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local4_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local4_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local4_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local4_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local4_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local4_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local4_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local4_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa5_local5_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local5_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local5_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local5_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local5_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local5_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local5_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local5_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local5_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local5_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa5_local6_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local6_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local6_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local6_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local6_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local6_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local6_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local6_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local6_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local6_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa5_local7_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local7_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local7_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local7_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local7_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local7_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local7_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local7_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local7_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local7_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa5_local8_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local8_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local8_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local8_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local8_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local8_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local8_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local8_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local8_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local8_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa5_local9_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local9_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local9_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local9_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local9_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local9_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local9_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local9_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local9_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local9_caja10/(:any)'] = "guia_remision/$1";

$route['guia_remision_empresa5_local10_caja1/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local10_caja2/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local10_caja3/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local10_caja4/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local10_caja5/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local10_caja6/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local10_caja7/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local10_caja8/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local10_caja9/(:any)'] = "guia_remision/$1";
$route['guia_remision_empresa5_local10_caja10/(:any)'] = "guia_remision/$1";


$route['nota_credito_empresa1_local1_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local1_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local1_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local1_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local1_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local1_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local1_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local1_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local1_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local1_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa1_local2_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local2_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local2_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local2_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local2_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local2_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local2_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local2_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local2_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local2_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa1_local3_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local3_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local3_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local3_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local3_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local3_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local3_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local3_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local3_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local3_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa1_local4_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local4_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local4_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local4_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local4_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local4_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local4_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local4_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local4_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local4_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa1_local5_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local5_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local5_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local5_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local5_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local5_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local5_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local5_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local5_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local5_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa1_local6_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local6_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local6_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local6_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local6_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local6_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local6_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local6_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local6_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local6_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa1_local7_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local7_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local7_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local7_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local7_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local7_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local7_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local7_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local7_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local7_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa1_local8_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local8_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local8_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local8_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local8_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local8_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local8_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local8_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local8_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local8_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa1_local9_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local9_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local9_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local9_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local9_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local9_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local9_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local9_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local9_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local9_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa1_local10_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local10_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local10_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local10_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local10_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local10_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local10_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local10_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local10_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa1_local10_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa2_local1_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local1_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local1_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local1_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local1_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local1_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local1_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local1_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local1_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local1_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa2_local2_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local2_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local2_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local2_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local2_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local2_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local2_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local2_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local2_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local2_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa2_local3_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local3_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local3_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local3_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local3_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local3_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local3_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local3_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local3_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local3_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa2_local4_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local4_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local4_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local4_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local4_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local4_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local4_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local4_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local4_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local4_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa2_local5_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local5_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local5_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local5_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local5_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local5_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local5_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local5_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local5_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local5_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa2_local6_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local6_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local6_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local6_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local6_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local6_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local6_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local6_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local6_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local6_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa2_local7_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local7_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local7_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local7_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local7_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local7_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local7_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local7_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local7_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local7_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa2_local8_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local8_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local8_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local8_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local8_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local8_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local8_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local8_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local8_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local8_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa2_local9_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local9_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local9_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local9_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local9_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local9_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local9_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local9_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local9_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local9_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa2_local10_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local10_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local10_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local10_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local10_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local10_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local10_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local10_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local10_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa2_local10_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa3_local1_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local1_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local1_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local1_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local1_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local1_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local1_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local1_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local1_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local1_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa3_local2_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local2_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local2_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local2_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local2_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local2_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local2_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local2_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local2_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local2_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa3_local3_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local3_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local3_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local3_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local3_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local3_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local3_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local3_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local3_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local3_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa3_local4_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local4_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local4_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local4_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local4_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local4_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local4_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local4_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local4_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local4_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa3_local5_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local5_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local5_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local5_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local5_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local5_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local5_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local5_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local5_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local5_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa3_local6_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local6_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local6_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local6_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local6_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local6_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local6_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local6_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local6_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local6_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa3_local7_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local7_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local7_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local7_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local7_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local7_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local7_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local7_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local7_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local7_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa3_local8_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local8_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local8_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local8_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local8_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local8_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local8_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local8_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local8_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local8_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa3_local9_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local9_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local9_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local9_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local9_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local9_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local9_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local9_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local9_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local9_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa3_local10_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local10_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local10_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local10_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local10_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local10_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local10_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local10_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local10_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa3_local10_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa4_local1_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local1_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local1_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local1_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local1_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local1_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local1_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local1_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local1_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local1_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa4_local2_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local2_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local2_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local2_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local2_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local2_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local2_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local2_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local2_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local2_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa4_local3_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local3_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local3_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local3_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local3_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local3_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local3_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local3_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local3_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local3_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa4_local4_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local4_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local4_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local4_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local4_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local4_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local4_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local4_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local4_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local4_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa4_local5_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local5_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local5_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local5_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local5_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local5_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local5_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local5_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local5_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local5_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa4_local6_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local6_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local6_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local6_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local6_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local6_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local6_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local6_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local6_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local6_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa4_local7_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local7_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local7_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local7_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local7_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local7_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local7_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local7_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local7_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local7_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa4_local8_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local8_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local8_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local8_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local8_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local8_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local8_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local8_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local8_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local8_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa4_local9_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local9_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local9_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local9_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local9_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local9_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local9_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local9_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local9_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local9_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa4_local10_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local10_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local10_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local10_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local10_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local10_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local10_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local10_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local10_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa4_local10_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa5_local1_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local1_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local1_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local1_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local1_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local1_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local1_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local1_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local1_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local1_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa5_local2_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local2_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local2_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local2_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local2_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local2_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local2_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local2_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local2_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local2_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa5_local3_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local3_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local3_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local3_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local3_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local3_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local3_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local3_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local3_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local3_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa5_local4_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local4_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local4_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local4_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local4_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local4_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local4_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local4_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local4_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local4_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa5_local5_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local5_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local5_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local5_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local5_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local5_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local5_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local5_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local5_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local5_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa5_local6_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local6_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local6_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local6_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local6_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local6_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local6_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local6_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local6_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local6_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa5_local7_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local7_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local7_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local7_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local7_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local7_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local7_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local7_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local7_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local7_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa5_local8_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local8_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local8_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local8_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local8_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local8_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local8_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local8_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local8_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local8_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa5_local9_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local9_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local9_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local9_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local9_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local9_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local9_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local9_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local9_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local9_caja10/(:any)'] = "nota_credito/$1";

$route['nota_credito_empresa5_local10_caja1/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local10_caja2/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local10_caja3/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local10_caja4/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local10_caja5/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local10_caja6/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local10_caja7/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local10_caja8/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local10_caja9/(:any)'] = "nota_credito/$1";
$route['nota_credito_empresa5_local10_caja10/(:any)'] = "nota_credito/$1";


$route['retencion_empresa1_local1_caja1/(:any)'] = "retencion/$1";
$route['retencion_empresa2_local1_caja1/(:any)'] = "retencion/$1";
$route['retencion_empresa3_local1_caja1/(:any)'] = "retencion/$1";
$route['retencion_empresa4_local1_caja1/(:any)'] = "retencion/$1";
$route['retencion_empresa5_local1_caja1/(:any)'] = "retencion/$1";
$route['retencion_empresa6_local1_caja1/(:any)'] = "retencion/$1";
$route['retencion_empresa7_local1_caja1/(:any)'] = "retencion/$1";
$route['retencion_empresa8_local1_caja1/(:any)'] = "retencion/$1";
$route['retencion_empresa9_local1_caja1/(:any)'] = "retencion/$1";
$route['retencion_empresa10_local1_caja1/(:any)'] = "retencion/$1";


$route['movimiento_empresa1_local1/(:any)'] = "movimiento/$1";
$route['movimiento_empresa1_local2/(:any)'] = "movimiento/$1";
$route['movimiento_empresa1_local3/(:any)'] = "movimiento/$1";
$route['movimiento_empresa1_local4/(:any)'] = "movimiento/$1";
$route['movimiento_empresa1_local5/(:any)'] = "movimiento/$1";
$route['movimiento_empresa1_local6/(:any)'] = "movimiento/$1";
$route['movimiento_empresa1_local7/(:any)'] = "movimiento/$1";
$route['movimiento_empresa1_local8/(:any)'] = "movimiento/$1";
$route['movimiento_empresa1_local9/(:any)'] = "movimiento/$1";
$route['movimiento_empresa1_local10/(:any)'] = "movimiento/$1";

$route['movimiento_empresa2_local1/(:any)'] = "movimiento/$1";
$route['movimiento_empresa2_local2/(:any)'] = "movimiento/$1";
$route['movimiento_empresa2_local3/(:any)'] = "movimiento/$1";
$route['movimiento_empresa2_local4/(:any)'] = "movimiento/$1";
$route['movimiento_empresa2_local5/(:any)'] = "movimiento/$1";
$route['movimiento_empresa2_local6/(:any)'] = "movimiento/$1";
$route['movimiento_empresa2_local7/(:any)'] = "movimiento/$1";
$route['movimiento_empresa2_local8/(:any)'] = "movimiento/$1";
$route['movimiento_empresa2_local9/(:any)'] = "movimiento/$1";
$route['movimiento_empresa2_local10/(:any)'] = "movimiento/$1";

$route['movimiento_empresa3_local1/(:any)'] = "movimiento/$1";
$route['movimiento_empresa3_local2/(:any)'] = "movimiento/$1";
$route['movimiento_empresa3_local3/(:any)'] = "movimiento/$1";
$route['movimiento_empresa3_local4/(:any)'] = "movimiento/$1";
$route['movimiento_empresa3_local5/(:any)'] = "movimiento/$1";
$route['movimiento_empresa3_local6/(:any)'] = "movimiento/$1";
$route['movimiento_empresa3_local7/(:any)'] = "movimiento/$1";
$route['movimiento_empresa3_local8/(:any)'] = "movimiento/$1";
$route['movimiento_empresa3_local9/(:any)'] = "movimiento/$1";
$route['movimiento_empresa3_local10/(:any)'] = "movimiento/$1";

$route['movimiento_empresa4_local1/(:any)'] = "movimiento/$1";
$route['movimiento_empresa4_local2/(:any)'] = "movimiento/$1";
$route['movimiento_empresa4_local3/(:any)'] = "movimiento/$1";
$route['movimiento_empresa4_local4/(:any)'] = "movimiento/$1";
$route['movimiento_empresa4_local5/(:any)'] = "movimiento/$1";
$route['movimiento_empresa4_local6/(:any)'] = "movimiento/$1";
$route['movimiento_empresa4_local7/(:any)'] = "movimiento/$1";
$route['movimiento_empresa4_local8/(:any)'] = "movimiento/$1";
$route['movimiento_empresa4_local9/(:any)'] = "movimiento/$1";
$route['movimiento_empresa4_local10/(:any)'] = "movimiento/$1";

$route['movimiento_empresa5_local1/(:any)'] = "movimiento/$1";
$route['movimiento_empresa5_local2/(:any)'] = "movimiento/$1";
$route['movimiento_empresa5_local3/(:any)'] = "movimiento/$1";
$route['movimiento_empresa5_local4/(:any)'] = "movimiento/$1";
$route['movimiento_empresa5_local5/(:any)'] = "movimiento/$1";
$route['movimiento_empresa5_local6/(:any)'] = "movimiento/$1";
$route['movimiento_empresa5_local7/(:any)'] = "movimiento/$1";
$route['movimiento_empresa5_local8/(:any)'] = "movimiento/$1";
$route['movimiento_empresa5_local9/(:any)'] = "movimiento/$1";
$route['movimiento_empresa5_local10/(:any)'] = "movimiento/$1";

$route['ingreso_empresa1_local1/(:any)'] = "ingreso/$1";
$route['ingreso_empresa1_local2/(:any)'] = "ingreso/$1";
$route['ingreso_empresa1_local3/(:any)'] = "ingreso/$1";
$route['ingreso_empresa1_local4/(:any)'] = "ingreso/$1";
$route['ingreso_empresa1_local5/(:any)'] = "ingreso/$1";
$route['ingreso_empresa1_local6/(:any)'] = "ingreso/$1";
$route['ingreso_empresa1_local7/(:any)'] = "ingreso/$1";
$route['ingreso_empresa1_local8/(:any)'] = "ingreso/$1";
$route['ingreso_empresa1_local9/(:any)'] = "ingreso/$1";
$route['ingreso_empresa1_local10/(:any)'] = "ingreso/$1";

$route['egreso_empresa1_local1/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local2/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local3/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local4/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local5/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local6/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local7/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local8/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local9/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local10/(:any)'] = "egreso/$1";

$route['ingreso_empresa2_local1/(:any)'] = "ingreso/$1";
$route['ingreso_empresa2_local2/(:any)'] = "ingreso/$1";
$route['ingreso_empresa2_local3/(:any)'] = "ingreso/$1";
$route['ingreso_empresa2_local4/(:any)'] = "ingreso/$1";
$route['ingreso_empresa2_local5/(:any)'] = "ingreso/$1";
$route['ingreso_empresa2_local6/(:any)'] = "ingreso/$1";
$route['ingreso_empresa2_local7/(:any)'] = "ingreso/$1";
$route['ingreso_empresa2_local8/(:any)'] = "ingreso/$1";
$route['ingreso_empresa2_local9/(:any)'] = "ingreso/$1";
$route['ingreso_empresa2_local10/(:any)'] = "ingreso/$1";

$route['ingreso_empresa3_local1/(:any)'] = "ingreso/$1";
$route['ingreso_empresa3_local2/(:any)'] = "ingreso/$1";
$route['ingreso_empresa3_local3/(:any)'] = "ingreso/$1";
$route['ingreso_empresa3_local4/(:any)'] = "ingreso/$1";
$route['ingreso_empresa3_local5/(:any)'] = "ingreso/$1";
$route['ingreso_empresa3_local6/(:any)'] = "ingreso/$1";
$route['ingreso_empresa3_local7/(:any)'] = "ingreso/$1";
$route['ingreso_empresa3_local8/(:any)'] = "ingreso/$1";
$route['ingreso_empresa3_local9/(:any)'] = "ingreso/$1";
$route['ingreso_empresa3_local10/(:any)'] = "ingreso/$1";

$route['ingreso_empresa4_local1/(:any)'] = "ingreso/$1";
$route['ingreso_empresa4_local2/(:any)'] = "ingreso/$1";
$route['ingreso_empresa4_local3/(:any)'] = "ingreso/$1";
$route['ingreso_empresa4_local4/(:any)'] = "ingreso/$1";
$route['ingreso_empresa4_local5/(:any)'] = "ingreso/$1";
$route['ingreso_empresa4_local6/(:any)'] = "ingreso/$1";
$route['ingreso_empresa4_local7/(:any)'] = "ingreso/$1";
$route['ingreso_empresa4_local8/(:any)'] = "ingreso/$1";
$route['ingreso_empresa4_local9/(:any)'] = "ingreso/$1";
$route['ingreso_empresa4_local10/(:any)'] = "ingreso/$1";

$route['ingreso_empresa5_local1/(:any)'] = "ingreso/$1";
$route['ingreso_empresa5_local2/(:any)'] = "ingreso/$1";
$route['ingreso_empresa5_local3/(:any)'] = "ingreso/$1";
$route['ingreso_empresa5_local4/(:any)'] = "ingreso/$1";
$route['ingreso_empresa5_local5/(:any)'] = "ingreso/$1";
$route['ingreso_empresa5_local6/(:any)'] = "ingreso/$1";
$route['ingreso_empresa5_local7/(:any)'] = "ingreso/$1";
$route['ingreso_empresa5_local8/(:any)'] = "ingreso/$1";
$route['ingreso_empresa5_local9/(:any)'] = "ingreso/$1";
$route['ingreso_empresa5_local10/(:any)'] = "ingreso/$1";

$route['egreso_empresa1_local1/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local2/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local3/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local4/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local5/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local6/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local7/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local8/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local9/(:any)'] = "egreso/$1";
$route['egreso_empresa1_local10/(:any)'] = "egreso/$1";

$route['egreso_empresa2_local1/(:any)'] = "egreso/$1";
$route['egreso_empresa2_local2/(:any)'] = "egreso/$1";
$route['egreso_empresa2_local3/(:any)'] = "egreso/$1";
$route['egreso_empresa2_local4/(:any)'] = "egreso/$1";
$route['egreso_empresa2_local5/(:any)'] = "egreso/$1";
$route['egreso_empresa2_local6/(:any)'] = "egreso/$1";
$route['egreso_empresa2_local7/(:any)'] = "egreso/$1";
$route['egreso_empresa2_local8/(:any)'] = "egreso/$1";
$route['egreso_empresa2_local9/(:any)'] = "egreso/$1";
$route['egreso_empresa2_local10/(:any)'] = "egreso/$1";

$route['egreso_empresa3_local1/(:any)'] = "egreso/$1";
$route['egreso_empresa3_local2/(:any)'] = "egreso/$1";
$route['egreso_empresa3_local3/(:any)'] = "egreso/$1";
$route['egreso_empresa3_local4/(:any)'] = "egreso/$1";
$route['egreso_empresa3_local5/(:any)'] = "egreso/$1";
$route['egreso_empresa3_local6/(:any)'] = "egreso/$1";
$route['egreso_empresa3_local7/(:any)'] = "egreso/$1";
$route['egreso_empresa3_local8/(:any)'] = "egreso/$1";
$route['egreso_empresa3_local9/(:any)'] = "egreso/$1";
$route['egreso_empresa3_local10/(:any)'] = "egreso/$1";

$route['egreso_empresa4_local1/(:any)'] = "egreso/$1";
$route['egreso_empresa4_local2/(:any)'] = "egreso/$1";
$route['egreso_empresa4_local3/(:any)'] = "egreso/$1";
$route['egreso_empresa4_local4/(:any)'] = "egreso/$1";
$route['egreso_empresa4_local5/(:any)'] = "egreso/$1";
$route['egreso_empresa4_local6/(:any)'] = "egreso/$1";
$route['egreso_empresa4_local7/(:any)'] = "egreso/$1";
$route['egreso_empresa4_local8/(:any)'] = "egreso/$1";
$route['egreso_empresa4_local9/(:any)'] = "egreso/$1";
$route['egreso_empresa4_local10/(:any)'] = "egreso/$1";

$route['inventario_empresa1_local1/(:any)'] = "inventario/$1";
$route['inventario_empresa1_local2/(:any)'] = "inventario/$1";
$route['inventario_empresa1_local3/(:any)'] = "inventario/$1";
$route['inventario_empresa1_local4/(:any)'] = "inventario/$1";
$route['inventario_empresa1_local5/(:any)'] = "inventario/$1";
$route['inventario_empresa1_local6/(:any)'] = "inventario/$1";
$route['inventario_empresa1_local7/(:any)'] = "inventario/$1";
$route['inventario_empresa1_local8/(:any)'] = "inventario/$1";
$route['inventario_empresa1_local9/(:any)'] = "inventario/$1";
$route['inventario_empresa1_local10/(:any)'] = "inventario/$1";

$route['inventario_empresa2_local1/(:any)'] = "inventario/$1";
$route['inventario_empresa2_local2/(:any)'] = "inventario/$1";
$route['inventario_empresa2_local3/(:any)'] = "inventario/$1";
$route['inventario_empresa2_local4/(:any)'] = "inventario/$1";
$route['inventario_empresa2_local5/(:any)'] = "inventario/$1";
$route['inventario_empresa2_local6/(:any)'] = "inventario/$1";
$route['inventario_empresa2_local7/(:any)'] = "inventario/$1";
$route['inventario_empresa2_local8/(:any)'] = "inventario/$1";
$route['inventario_empresa2_local9/(:any)'] = "inventario/$1";
$route['inventario_empresa2_local10/(:any)'] = "inventario/$1";

$route['inventario_empresa3_local1/(:any)'] = "inventario/$1";
$route['inventario_empresa3_local2/(:any)'] = "inventario/$1";
$route['inventario_empresa3_local3/(:any)'] = "inventario/$1";
$route['inventario_empresa3_local4/(:any)'] = "inventario/$1";
$route['inventario_empresa3_local5/(:any)'] = "inventario/$1";
$route['inventario_empresa3_local6/(:any)'] = "inventario/$1";
$route['inventario_empresa3_local7/(:any)'] = "inventario/$1";
$route['inventario_empresa3_local8/(:any)'] = "inventario/$1";
$route['inventario_empresa3_local9/(:any)'] = "inventario/$1";
$route['inventario_empresa3_local10/(:any)'] = "inventario/$1";

$route['inventario_empresa4_local1/(:any)'] = "inventario/$1";
$route['inventario_empresa4_local2/(:any)'] = "inventario/$1";
$route['inventario_empresa4_local3/(:any)'] = "inventario/$1";
$route['inventario_empresa4_local4/(:any)'] = "inventario/$1";
$route['inventario_empresa4_local5/(:any)'] = "inventario/$1";
$route['inventario_empresa4_local6/(:any)'] = "inventario/$1";
$route['inventario_empresa4_local7/(:any)'] = "inventario/$1";
$route['inventario_empresa4_local8/(:any)'] = "inventario/$1";
$route['inventario_empresa4_local9/(:any)'] = "inventario/$1";
$route['inventario_empresa4_local10/(:any)'] = "inventario/$1";

$route['inventario_empresa5_local1/(:any)'] = "inventario/$1";
$route['inventario_empresa5_local2/(:any)'] = "inventario/$1";
$route['inventario_empresa5_local3/(:any)'] = "inventario/$1";
$route['inventario_empresa5_local4/(:any)'] = "inventario/$1";
$route['inventario_empresa5_local5/(:any)'] = "inventario/$1";
$route['inventario_empresa5_local6/(:any)'] = "inventario/$1";
$route['inventario_empresa5_local7/(:any)'] = "inventario/$1";
$route['inventario_empresa5_local8/(:any)'] = "inventario/$1";
$route['inventario_empresa5_local9/(:any)'] = "inventario/$1";
$route['inventario_empresa5_local10/(:any)'] = "inventario/$1";


$route['kardex_empresa1_local1/(:any)'] = "kardex/$1";
$route['kardex_empresa1_local2/(:any)'] = "kardex/$1";
$route['kardex_empresa1_local3/(:any)'] = "kardex/$1";
$route['kardex_empresa1_local4/(:any)'] = "kardex/$1";
$route['kardex_empresa1_local5/(:any)'] = "kardex/$1";
$route['kardex_empresa1_local6/(:any)'] = "kardex/$1";
$route['kardex_empresa1_local7/(:any)'] = "kardex/$1";
$route['kardex_empresa1_local8/(:any)'] = "kardex/$1";
$route['kardex_empresa1_local9/(:any)'] = "kardex/$1";
$route['kardex_empresa1_local10/(:any)'] = "kardex/$1";

$route['kardex_empresa2_local1/(:any)'] = "kardex/$1";
$route['kardex_empresa2_local2/(:any)'] = "kardex/$1";
$route['kardex_empresa2_local3/(:any)'] = "kardex/$1";
$route['kardex_empresa2_local4/(:any)'] = "kardex/$1";
$route['kardex_empresa2_local5/(:any)'] = "kardex/$1";
$route['kardex_empresa2_local6/(:any)'] = "kardex/$1";
$route['kardex_empresa2_local7/(:any)'] = "kardex/$1";
$route['kardex_empresa2_local8/(:any)'] = "kardex/$1";
$route['kardex_empresa2_local9/(:any)'] = "kardex/$1";
$route['kardex_empresa2_local10/(:any)'] = "kardex/$1";

$route['kardex_empresa3_local1/(:any)'] = "kardex/$1";
$route['kardex_empresa3_local2/(:any)'] = "kardex/$1";
$route['kardex_empresa3_local3/(:any)'] = "kardex/$1";
$route['kardex_empresa3_local4/(:any)'] = "kardex/$1";
$route['kardex_empresa3_local5/(:any)'] = "kardex/$1";
$route['kardex_empresa3_local6/(:any)'] = "kardex/$1";
$route['kardex_empresa3_local7/(:any)'] = "kardex/$1";
$route['kardex_empresa3_local8/(:any)'] = "kardex/$1";
$route['kardex_empresa3_local9/(:any)'] = "kardex/$1";
$route['kardex_empresa3_local10/(:any)'] = "kardex/$1";

$route['kardex_empresa4_local1/(:any)'] = "kardex/$1";
$route['kardex_empresa4_local2/(:any)'] = "kardex/$1";
$route['kardex_empresa4_local3/(:any)'] = "kardex/$1";
$route['kardex_empresa4_local4/(:any)'] = "kardex/$1";
$route['kardex_empresa4_local5/(:any)'] = "kardex/$1";
$route['kardex_empresa4_local6/(:any)'] = "kardex/$1";
$route['kardex_empresa4_local7/(:any)'] = "kardex/$1";
$route['kardex_empresa4_local8/(:any)'] = "kardex/$1";
$route['kardex_empresa4_local9/(:any)'] = "kardex/$1";
$route['kardex_empresa4_local10/(:any)'] = "kardex/$1";

$route['kardex_empresa5_local1/(:any)'] = "kardex/$1";
$route['kardex_empresa5_local2/(:any)'] = "kardex/$1";
$route['kardex_empresa5_local3/(:any)'] = "kardex/$1";
$route['kardex_empresa5_local4/(:any)'] = "kardex/$1";
$route['kardex_empresa5_local5/(:any)'] = "kardex/$1";
$route['kardex_empresa5_local6/(:any)'] = "kardex/$1";
$route['kardex_empresa5_local7/(:any)'] = "kardex/$1";
$route['kardex_empresa5_local8/(:any)'] = "kardex/$1";
$route['kardex_empresa5_local9/(:any)'] = "kardex/$1";
$route['kardex_empresa5_local10/(:any)'] = "kardex/$1";


$route['transferencia_empresa1_local1/(:any)'] = "transferencia/$1";
$route['transferencia_empresa1_local2/(:any)'] = "transferencia/$1";
$route['transferencia_empresa1_local3/(:any)'] = "transferencia/$1";
$route['transferencia_empresa1_local4/(:any)'] = "transferencia/$1";
$route['transferencia_empresa1_local5/(:any)'] = "transferencia/$1";
$route['transferencia_empresa1_local6/(:any)'] = "transferencia/$1";
$route['transferencia_empresa1_local7/(:any)'] = "transferencia/$1";
$route['transferencia_empresa1_local8/(:any)'] = "transferencia/$1";
$route['transferencia_empresa1_local9/(:any)'] = "transferencia/$1";
$route['transferencia_empresa1_local10/(:any)'] = "transferencia/$1";



$route['reg_factura_empresa1/(:any)'] = "reg_factura/$1";
$route['reg_factura_empresa2/(:any)'] = "reg_factura/$1";
$route['reg_factura_empresa3/(:any)'] = "reg_factura/$1";
$route['reg_factura_empresa4/(:any)'] = "reg_factura/$1";
$route['reg_factura_empresa5/(:any)'] = "reg_factura/$1";

$route['reg_nota_credito_empresa1/(:any)'] = "reg_nota_credito/$1";
$route['reg_nota_credito_empresa2/(:any)'] = "reg_nota_credito/$1";
$route['reg_nota_credito_empresa3/(:any)'] = "reg_nota_credito/$1";
$route['reg_nota_credito_empresa4/(:any)'] = "reg_nota_credito/$1";
$route['reg_nota_credito_empresa5/(:any)'] = "reg_nota_credito/$1";

$route['ctasxcobrar_empresa1/(:any)'] = "ctasxcobrar/$1";
$route['ctasxcobrar_empresa2/(:any)'] = "ctasxcobrar/$1";
$route['ctasxcobrar_empresa3/(:any)'] = "ctasxcobrar/$1";
$route['ctasxcobrar_empresa4/(:any)'] = "ctasxcobrar/$1";
$route['ctasxcobrar_empresa5/(:any)'] = "ctasxcobrar/$1";

$route['ctasxpagar_empresa1/(:any)'] = "ctasxpagar/$1";
$route['ctasxpagar_empresa2/(:any)'] = "ctasxpagar/$1";
$route['ctasxpagar_empresa3/(:any)'] = "ctasxpagar/$1";
$route['ctasxpagar_empresa4/(:any)'] = "ctasxpagar/$1";
$route['ctasxpagar_empresa5/(:any)'] = "ctasxpagar/$1";


$route['cheque_empresa1/(:any)'] = "cheque/$1";
$route['cheque_empresa2/(:any)'] = "cheque/$1";
$route['cheque_empresa3/(:any)'] = "cheque/$1";
$route['cheque_empresa4/(:any)'] = "cheque/$1";
$route['cheque_empresa5/(:any)'] = "cheque/$1";


$route['pedido_empresa1/(:any)'] = "pedido/$1";
$route['pedido_empresa2/(:any)'] = "pedido/$1";
$route['pedido_empresa3/(:any)'] = "pedido/$1";
$route['pedido_empresa4/(:any)'] = "pedido/$1";
$route['pedido_empresa5/(:any)'] = "pedido/$1";

$route['pedido_empresa1_local1_caja1/(:any)'] = "pedido/$1";
$route['pedido_empresa1_local1_caja2/(:any)'] = "pedido/$1";

$route['pedido_factura_empresa1_local1_caja1/(:any)'] = "pedido_factura/$1";
$route['pedido_factura_empresa1_local1_caja2/(:any)'] = "pedido_factura/$1";
$route['pedido_factura_empresa1_local1_caja3/(:any)'] = "pedido_factura/$1";
$route['pedido_factura_empresa1_local1_caja4/(:any)'] = "pedido_factura/$1";
$route['pedido_factura_empresa1_local1_caja5/(:any)'] = "pedido_factura/$1";

$route['ctasxcobrar_pagos_empresa1/(:any)'] = "ctasxcobrar_pagos/$1";
$route['ctasxcobrar_pagos_empresa2/(:any)'] = "ctasxcobrar_pagos/$1";
$route['ctasxcobrar_pagos_empresa3/(:any)'] = "ctasxcobrar_pagos/$1";
$route['ctasxcobrar_pagos_empresa4/(:any)'] = "ctasxcobrar_pagos/$1";
$route['ctasxcobrar_pagos_empresa5/(:any)'] = "ctasxcobrar_pagos/$1";

$route['nota_debito_empresa1_local1_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local1_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local1_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local1_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local1_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local1_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local1_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local1_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local1_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local1_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa1_local2_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local2_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local2_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local2_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local2_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local2_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local2_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local2_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local2_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local2_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa1_local3_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local3_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local3_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local3_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local3_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local3_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local3_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local3_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local3_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local3_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa1_local4_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local4_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local4_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local4_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local4_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local4_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local4_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local4_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local4_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local4_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa1_local5_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local5_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local5_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local5_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local5_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local5_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local5_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local5_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local5_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local5_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa1_local6_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local6_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local6_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local6_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local6_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local6_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local6_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local6_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local6_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local6_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa1_local7_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local7_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local7_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local7_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local7_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local7_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local7_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local7_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local7_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local7_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa1_local8_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local8_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local8_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local8_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local8_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local8_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local8_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local8_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local8_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local8_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa1_local9_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local9_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local9_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local9_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local9_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local9_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local9_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local9_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local9_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local9_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa1_local10_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local10_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local10_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local10_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local10_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local10_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local10_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local10_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local10_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa1_local10_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa2_local1_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local1_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local1_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local1_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local1_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local1_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local1_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local1_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local1_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local1_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa2_local2_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local2_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local2_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local2_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local2_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local2_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local2_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local2_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local2_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local2_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa2_local3_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local3_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local3_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local3_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local3_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local3_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local3_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local3_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local3_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local3_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa2_local4_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local4_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local4_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local4_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local4_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local4_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local4_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local4_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local4_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local4_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa2_local5_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local5_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local5_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local5_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local5_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local5_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local5_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local5_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local5_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local5_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa2_local6_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local6_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local6_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local6_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local6_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local6_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local6_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local6_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local6_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local6_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa2_local7_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local7_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local7_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local7_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local7_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local7_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local7_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local7_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local7_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local7_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa2_local8_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local8_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local8_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local8_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local8_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local8_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local8_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local8_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local8_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local8_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa2_local9_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local9_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local9_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local9_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local9_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local9_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local9_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local9_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local9_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local9_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa2_local10_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local10_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local10_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local10_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local10_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local10_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local10_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local10_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local10_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa2_local10_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa3_local1_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local1_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local1_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local1_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local1_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local1_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local1_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local1_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local1_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local1_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa3_local2_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local2_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local2_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local2_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local2_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local2_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local2_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local2_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local2_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local2_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa3_local3_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local3_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local3_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local3_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local3_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local3_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local3_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local3_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local3_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local3_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa3_local4_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local4_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local4_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local4_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local4_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local4_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local4_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local4_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local4_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local4_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa3_local5_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local5_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local5_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local5_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local5_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local5_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local5_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local5_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local5_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local5_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa3_local6_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local6_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local6_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local6_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local6_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local6_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local6_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local6_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local6_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local6_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa3_local7_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local7_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local7_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local7_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local7_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local7_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local7_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local7_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local7_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local7_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa3_local8_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local8_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local8_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local8_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local8_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local8_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local8_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local8_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local8_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local8_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa3_local9_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local9_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local9_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local9_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local9_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local9_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local9_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local9_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local9_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local9_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa3_local10_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local10_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local10_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local10_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local10_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local10_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local10_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local10_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local10_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa3_local10_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa4_local1_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local1_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local1_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local1_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local1_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local1_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local1_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local1_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local1_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local1_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa4_local2_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local2_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local2_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local2_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local2_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local2_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local2_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local2_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local2_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local2_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa4_local3_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local3_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local3_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local3_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local3_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local3_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local3_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local3_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local3_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local3_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa4_local4_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local4_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local4_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local4_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local4_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local4_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local4_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local4_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local4_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local4_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa4_local5_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local5_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local5_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local5_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local5_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local5_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local5_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local5_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local5_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local5_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa4_local6_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local6_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local6_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local6_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local6_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local6_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local6_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local6_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local6_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local6_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa4_local7_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local7_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local7_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local7_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local7_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local7_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local7_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local7_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local7_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local7_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa4_local8_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local8_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local8_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local8_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local8_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local8_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local8_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local8_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local8_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local8_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa4_local9_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local9_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local9_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local9_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local9_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local9_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local9_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local9_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local9_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local9_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa4_local10_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local10_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local10_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local10_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local10_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local10_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local10_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local10_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local10_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa4_local10_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa5_local1_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local1_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local1_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local1_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local1_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local1_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local1_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local1_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local1_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local1_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa5_local2_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local2_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local2_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local2_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local2_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local2_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local2_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local2_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local2_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local2_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa5_local3_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local3_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local3_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local3_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local3_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local3_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local3_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local3_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local3_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local3_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa5_local4_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local4_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local4_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local4_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local4_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local4_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local4_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local4_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local4_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local4_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa5_local5_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local5_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local5_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local5_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local5_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local5_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local5_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local5_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local5_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local5_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa5_local6_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local6_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local6_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local6_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local6_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local6_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local6_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local6_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local6_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local6_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa5_local7_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local7_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local7_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local7_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local7_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local7_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local7_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local7_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local7_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local7_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa5_local8_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local8_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local8_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local8_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local8_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local8_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local8_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local8_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local8_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local8_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa5_local9_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local9_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local9_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local9_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local9_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local9_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local9_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local9_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local9_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local9_caja10/(:any)'] = "nota_debito/$1";

$route['nota_debito_empresa5_local10_caja1/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local10_caja2/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local10_caja3/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local10_caja4/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local10_caja5/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local10_caja6/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local10_caja7/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local10_caja8/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local10_caja9/(:any)'] = "nota_debito/$1";
$route['nota_debito_empresa5_local10_caja10/(:any)'] = "nota_debito/$1";

$route['reg_guia_empresa1/(:any)'] = "reg_guia/$1";
$route['reg_guia_empresa2/(:any)'] = "reg_guia/$1";
$route['reg_guia_empresa3/(:any)'] = "reg_guia/$1";
$route['reg_guia_empresa4/(:any)'] = "reg_guia/$1";
$route['reg_guia_empresa5/(:any)'] = "reg_guia/$1";

$route['reg_nota_debito_empresa1/(:any)'] = "reg_nota_debito/$1";
$route['reg_nota_debito_empresa2/(:any)'] = "reg_nota_debito/$1";
$route['reg_nota_debito_empresa3/(:any)'] = "reg_nota_debito/$1";
$route['reg_nota_debito_empresa4/(:any)'] = "reg_nota_debito/$1";
$route['reg_nota_debito_empresa5/(:any)'] = "reg_nota_debito/$1";
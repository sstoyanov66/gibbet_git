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

!!CodeIgniter reads its routing rules from top to bottom!!

|This was to load welcome controller by default:
$route['default_controller'] = 'welcome';

 
Each rule is a regular expression.CodeIgniter looks for the first match, and calls the appropriate controller and method.
*/

/*THIS IS ROUTING FOR TEST PROJECT NEWS :

$route['news/createnews'] = 'news/createnews';// this is to have access to  createnews() method of News.php controller 
$route['news/(:any)'] = 'news/view/$1';// this is to have direct access to any file via News.php controller and its 'view' method(function)  -  matches any request using the wildcard string (:any). and passes the parameter to the view() method of the controller class.
$route['news/editnews'] = 'news/editnews';// this is to have access to  editnews() method of News.php controller 
//$route['news/editnews/(:any)'] = 'news/editnews/$1';

//$route['news'] = 'news';						// this is to have access to News.php controller - not needed because of above 2 routings
$route['default_controller'] = 'news';// this is to have 1st access to index() method of News.php controller
*/

$route['default_controller'] = 'gibbet';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE; 


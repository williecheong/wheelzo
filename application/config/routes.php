<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
*/

$route['default_controller'] = "main";
$route['404_override'] = 'lost';

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
*/

$route['me'] = "main/index/true";
$route['api/v1/rides/me'] = "api/v1/rides/index/true";
$route['api/v2/rides/me'] = "api/v2/rides/index/true";
$route['api/v2/users/current'] = "api/v2/users/index/true";

/* End of file routes.php */
/* Location: ./application/config/routes.php */
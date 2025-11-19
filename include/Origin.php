<?php
/**	op-unit-app:/include/Origin.php
 *
 * @porting    2025-11-19
 * @version    1.0
 * @package    op-unit-app
 * @author     Tomoaki Nagahara
 * @copyright  Tomoaki Nagahara All right reserved.
 */

/**	namespace
 *
 */
namespace OP\UNIT\APP;

//	...
if( OP()->isCI() ){
	return 'isCI';
}

//	...
$scheme = $_SERVER['REQUEST_SCHEME'] ?? ($_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http');
$domain = $_SERVER['SERVER_NAME']; // HTTP_HOST contains the port number. It may just be PHP Built-in Web Server.
$port   = $_SERVER['SERVER_PORT'] == 80 ? '' : $_SERVER['SERVER_PORT'];

//	...
if( $port ){
	$port = ':' . $port;
}

//	...
return "{$scheme}://{$domain}{$port}";

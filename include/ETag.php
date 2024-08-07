<?php
/** op-unit-app:/include/ETag.php
 *
 * @deprecated
 * @created    2024-08-06
 * @version    1.0
 * @package    op-unit-app
 * @author     Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright  Tomoaki Nagahara All right reserved.
 */

/** Declare strict
 *
 */
declare(strict_types=1);

/** namespace
 *
 */
namespace OP\UNIT\APP;

//	Generate ETag.
$etag = md5(self::$_content);
$etag = substr($etag, 0, 10);

//	If-None-Match
if( $client_etag = $_SERVER['HTTP_IF_NONE_MATCH'] ?? null ){
	//	If is match?
	if( $client_etag === $etag ){
		header('HTTP/1.1 304 Not Modified');
		return true;
	}
}

//	If do not set a cache expiration time, the ETag will expire immediately.
if( $age = OP()->Config('app')['age'] ?? null ){
	//	OK
}else if( \OP\Env::MIME() === 'text/html' ){
	$age = 0;
}else{
	//	Browser will no to request 24 hours.
	$age = 60 * 60 * 24;
}

//	Does not set an ETag.
if( empty($age) ){
	return false;
}

//	Set ETag.
header("ETag: {$etag}");

//	For HTTP/1.0 Cache-Control, But "public" is not support any server.
header("Pragma: public");

//	For HTTP/1.1 Cache-Control.
header("Cache-Control: public, max-age={$age}");

//	For HTTP/1.0 Cache-Control.
$expire = gmdate('D, d M Y H:i:s', time()+$age);
header("Expires: {$expire} GMT");

//	Last-Modified
$modified_last = gmdate('D, d M Y H:i:s', time() - $age) . ' GMT';
header("Last-Modified: {$modified_last}");

//	Last-Modified is expired?
if( isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) and strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= strtotime($modified_last) ){
	header("HTTP/1.1 304 Not Modified");
	return true;
}

//	...
return false;

<?php
/** op-unit-app:/ci/App.php
 *
 * @created    2024-08-18
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
namespace OP;

/* @var $ci \OP\UNIT\CI\CI_Config */
$ci = OP::Unit('CI')::Config();

//	Template
$method = 'Template';
$args   = ['ci.txt'];
$result = 'OK';
$ci->Set($method, $result, $args);

//	Content
$method = 'Content';
$args   =  null;
$result =  null;
$ci->Set($method, $result, $args);

//	ETag
$method = 'ETag';
$args   =  null;
$result =  false;
$ci->Set($method, $result, $args);

//	Title
$method = 'Title';
$args   = 'CI';
if( $title = OP()->Config('app')['title'] ?? null ){
	$title = $args . ' | ' . $title;
}else{
	$title = $args;
}
$result = $title;
$ci->Set($method, $result, $args);

//	...
return $ci->Get();

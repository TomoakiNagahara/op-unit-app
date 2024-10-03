<?php
/**
 * op-unit-app:/index.php
 *
 * @created   2018-04-04
 * @version   1.0
 * @package   op-unit-app
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** Declare strict
 *
 */
declare(strict_types=1);

/** namespace
 *
 * @created   2019-11-21
 */
namespace OP;

/** Include
 *
 */
/*
include(__DIR__.'/App.class.php');
*/
require_once(__DIR__.'/App.class.php');

/** If Content() is not called from anywhere, Content will be output now.
 *
 * @genesis   2009-??-?? op-core-4?
 * @moved     2021-05-05 op-core-7:/function/Content.php
 * @moved     2024-07-08 op-unit-app:/index.php
 */
/*
register_shutdown_function(function(){
	//	...
	if( \OP\UNIT\App::Hash() ){
		Notice::Set("The Layout is not calling `App::Content()`");
	}

	//	...
	\OP\UNIT\App::Content();
	OP()->Unit()->App()->Content();
});
*/

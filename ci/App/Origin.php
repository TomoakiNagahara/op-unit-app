<?php
/**	op-unit-app:/ci/App.php
 *
 * @created    2025-09-22
 * @version    1.0
 * @package    op-unit-app
 * @author     Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright  Tomoaki Nagahara All right reserved.
 */

/**	Declare strict
 *
 */
declare(strict_types=1);

/**	namespace
 *
 */
namespace OP;

/* @var $ci \OP\UNIT\CI\CI_Config */

//	...
$method = 'Origin';
$args   =  null;
$result = 'isCI';
$ci->Set($method, $result, $args);

<?php
/**	op-unit-app:/index.php
 *
 * @created    2018-04-04
 * @version    1.0
 * @package    op-unit-app
 * @author     Tomoaki Nagahara
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

/**	Include
 *
 */
require_once(__DIR__.'/App.class.php');

/**	Memory usage is displayed only for admin.
 *
 */
if( OP()->isAdmin() ){
	register_shutdown_function(function(){
		//	...
		include(__DIR__.'/include/memory_usage.php');
	});
}

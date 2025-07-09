<?php
/**	op-unit-app:/include/memory_usage.php
 *
 * @porting    2025-06-19
 * @version    1.0
 * @package    op-unit-app
 * @author     Tomoaki Nagahara
 * @copyright  Tomoaki Nagahara All right reserved.
 */

/**	namespace
 *
 */
namespace OP\UNIT\APP;

//	Go to op-unit-app's register_shutdown_function();
$time = (microtime ( true ) - _OP_APP_START_);
$diff =(memory_get_usage () - _OP_MEM_USAGE_) / 1000;
$used = memory_get_usage () / 1000;
$peak = memory_get_peak_usage () / 1000;

//	...
$execute_time = "execute time: {$time}";
$memory_usage = "memory usage: {$used}KB (app: {$diff}KB, max: {$peak}KB)";

//	...
switch( OP()->MIME() ){
	case 'text/html':
		echo "<script>console.log('{$execute_time}, {$memory_usage}');</script>";
		break;

	case 'text/plain':
		echo PHP_EOL;
		echo $execute_time . PHP_EOL;
		echo $memory_usage . PHP_EOL;
		echo PHP_EOL;
		break;

	case 'text/css':
	case 'text/javascript':
		echo PHP_EOL;
		echo '/*' . PHP_EOL;
		echo $execute_time . PHP_EOL;
		echo $memory_usage . PHP_EOL;
		echo '*/' . PHP_EOL;
		break;
}

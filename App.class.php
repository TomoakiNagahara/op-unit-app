<?php
/** op-unit-app:/App.class.php
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
 */
namespace OP\UNIT;

/** Used class.
 *
 */
use OP\IF_UNIT;
use OP\IF_APP;
use OP\OP_CORE;
use OP\OP_UNIT;
use OP\OP_SESSION;
use OP\Env;
use OP\Config;
use function OP\Unit;
use function OP\Content;
use function OP\RootPath;
use function OP\CompressPath;

/** App
 *
 * @created   2018-04-04
 * @version   1.0
 * @package   op-unit-app
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class App implements IF_UNIT, IF_APP
{
	/** trait.
	 *
	 */
	use OP_CORE, OP_UNIT, OP_SESSION;

	/** SmartURL Arguments.
	 *
	 * @var array
	 */
	private $_args;

	/** Automatically.
	 *
	 */
	function Auto()
	{
		try{
			//	Get End-Point.
			$endpoint = Unit('Router')->EndPoint();

			//	Is http?
			if( Env::isHttp() ){

				//	Check end-point if not in app directory.
				if( strpos($endpoint, RootPath('app')) !== 0 ){
					//	Overwrite end-point.
					$endpoint = ConvertPath('app:/404.php');
				};

				//	Execute End-Point.
				$hash = Content($endpoint);

				//	ETag
				if( Config::Get('app')['etag'] ?? null ){
					Unit('ETag')->Auto($hash);
				}

				//	Do the Layout.
				Unit('Layout')->Auto();

			}else{
				//	Execute End-Point.
				$hash = Content($endpoint);
			};
		}catch( \Throwable $e ){
			OP()->Notice($e);
		};
	}

	/** Get/Set Title for HTML's title tag.
	 *
	 * @param  string  $title
	 * @param  string  $separator
	 * @return string  $title
	 */
	static function Title($title=null, $separator=' | ') : ?string
	{
		require_once(__DIR__.'/function/Title.php');
		return APP\Title($title, $separator);
	}
}

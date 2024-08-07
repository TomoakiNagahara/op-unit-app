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
use OP\Env;
/*
use OP\Config;
use function OP\Unit;
use function OP\Content;
use function OP\RootPath;
use function OP\ConvertPath;
*/

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
	use OP_CORE;
	use \OP\OP_CI;

	/** Content
	 *
	 * @var string
	 */
	static private $_content;

	/** Automatically.
	 *
	 */
	function Auto()
	{
		try{
			/*
			//	Get End-Point.
			$endpoint = Unit('Router')->EndPoint();

			//	End-Point is not install.
			if(!$endpoint ){
				echo 'End-Point is not install. (index.php)';
				return;
			}

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
			*/

			//	Get End-Point.
			if(!$endpoint = OP()->Router()->EndPoint() ){
				return;
			}

			//	OB is start.
			ob_start();

			//	Save original directory.
			$_original_directory = getcwd();

			//	Change to the end-point directory.
			chdir( dirname($endpoint) );

			//	Execute the end-point.
			require_once($endpoint);

			//	Recovery original directory.
			chdir($_original_directory);

			//	Get and store content, And finished OB.
			self::$_content = ob_get_clean();

			//	ETag returned value is whether matched.
			if( self::Etag() ){
				//	ETag is matched.
				//	Not return content and Layout to client browser.
				self::$_content = null;
				//	...
				return;
			}

		}catch( \Throwable $e ){
			OP()->Notice($e);
		};

		//	...
		try{
			//	...
			if( Env::MIME() === 'text/html' ){
				//	Do Layout.
				OP()->Layout()->Auto();
			}else{
				//	Do no Layout.
				self::Content();
			}
		}catch( \Throwable $e ){
			OP()->Notice($e);
		};
	}

	/** Output content.
	 *
	 *  This method is intended to be called from a Layout.
	 *
	 * @revival  2024-07-08
	 */
	static public function Content()
	{
		echo self::$_content;
		self::$_content = null;
	}

	/** Check if the ETag matches.
	 *
	 *  ETag is save on transfer volume.
	 *
	 * @revival  2024-07-08
	 * @return   bool
	 */
	static private function ETag() : bool
	{
		return include(__DIR__.'/include/ETag.php');
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

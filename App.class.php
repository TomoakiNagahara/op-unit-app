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
use OP\OP_CORE;
use OP\IF_UNIT;
use OP\IF_APP;
use OP\Env;

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
		//	Execute the end-point.
		try{
			//	Get End-Point.
			if(!$endpoint = OP()->Router()->EndPoint() ){
				return;
			}

			//	OB is start.
			ob_start();

			//	Since the full path cannot be used, It is convert to a meta path.
			$endpoint = OP()->MetaPath($endpoint);

			//	Execute the End-Point.
			OP()->Template($endpoint);

			//	Get and store content, And finished OB.
			self::$_content = ob_get_clean();

		}catch( \Throwable $e ){
			OP()->Notice($e);
		};

		//	Execute the Layout.
		try{
			//	...
			if( Env::MIME() === 'text/html' ){
				/** Flexible access methods is a great treasure in onepiece
				 *
				 *  1. Hard code to layout from namespace.
				 *  \OP\UNIT\Layout::Auto();
				 *
				 *  2. OP() function is change to specification. This method is not work.
				 *  OP()->Layout()->Auto();
				 *
				 *  3. Use always an already instantiated object.
				 *  OP()->Unit('Layout')->Auto();
				 *
				 *  4. Returns the normalized object for the interface.
				 *  OP()->Unit()->Layout()->Auto();
				 */
				//	Do Layout.
				OP()->Unit('Layout')->Auto();
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
	 * <pre>
	 *  ETag is save on transfer volume.
	 *  Return true is match ETag.
	 *  "HTTP/1.1 304 Not Modified" header was returned.
	 *  If return false, You output the content.
	 * </pre>
	 *
	 * @revival  2024-07-08
	 * @return   bool
	 */
	static private function ETag(string $etag, int $age=3600) : bool
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

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

	/** SmartURL Arguments.
	 *
	 * @var array
	 */
	private $_args;

	/** Content
	 *
	 * @var string
	 */
	private $_content;

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
			$this->_content = ob_get_clean();

			//	ETag returned value is whether matched.
			if( $this->Etag() ){
				//	ETag is matched.
				//	Not return content and Layout to client browser.
				$this->_content = null;
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
				$this->Content();
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
	public function Content()
	{
		echo $this->_content;
		$this->_content = null;
	}

	/** Check if the ETag matches.
	 *
	 *  ETag is save on transfer volume.
	 *
	 * @revival  2024-07-08
	 * @return   bool
	 */
	private function ETag() : bool
	{
		//	Generate ETag.
		$etag = md5($this->_content);
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
		}else if( Env::MIME() === 'text/html' ){
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

<?php
/**
 * op-unit-app:/APP.trait.php
 *
 * @created   2019-11-28
 * @version   1.0
 * @package   op-unit-app
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @created   2019-11-28
 */
namespace OP;

/** APP
 *
 * @created   2019-11-28
 * @version   1.0
 * @package   op-unit-app
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
trait UNIT_APP
{
	/** Get to transparently GET or POST.
	 *
	 * @updated  2019-05-10  Add $key param.
	 * @moved    2019-11-28  App.class.php
	 * @param    string      $key
	 * @return   array       $request
	 */
	function Request($key=null)
	{
		//	...
		switch( strtoupper($_SERVER['REQUEST_METHOD'] ?? null) ){
			case 'GET':
				return \OP\Encode( ($key ? $_GET [$key] : $_GET ) );

			case 'POST':
				return \OP\Encode( ($key ? $_POST[$key] : $_POST) );
		};
	}

	/** Return Canonical
	 *
	 * @created   2022-09-30
	 * @return    string
	 */
	static function Canonical()
	{
		return Config::Get('app')['canonical'] ?? null;
	}
}

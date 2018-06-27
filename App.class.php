<?php
/**
 * unit-app:/App.class.php
 *
 * @creation  2018-04-04
 * @version   1.0
 * @package   unit-app
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** App
 *
 * @creation  2018-04-04
 * @version   1.0
 * @package   unit-app
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class App
{
	/** trait.
	 *
	 */
	use OP_CORE, OP_SESSION;

	static private $_DISPATCH_	 = 'OP\UNIT\NEWWORLD\Dispatch';
	static private $_LAYOUT_	 = 'OP\UNIT\NEWWORLD\Layout';
	static private $_ROUTER_	 = 'OP\UNIT\NEWWORLD\Router';
	static private $_TEMPLATE_	 = 'OP\UNIT\NEWWORLD\Template';

	/** Automatically run.
	 *
	 */
	static function Auto()
	{
		//	Execute end-point.
		$content = self::$_DISPATCH_::Get();

		//	The content is wrapped in the Layout.
		echo self::$_LAYOUT_::Get($content);
	}

	/** Get SmartURL arguments.
	 *
	 * @return	 array	 $args
	 */
	static function Args()
	{
		return self::$_ROUTER_::Get()['args'];
	}

	/** Template
	 *
	 * @param	 string	 $path
	 * @param	 string	 $args
	 */
	static function Template($path, $args=null)
	{
		if( $args and !is_array($args)){
			$type = is_object($args) ? get_class($args) : gettype($args);
			D("Argument is not array. ($type)");
			return;
		}
		self::$_TEMPLATE_::Run($path, $args);
	}

	/** Layout
	 *
	 * <pre>
	 * App::Layout(true);       // Execute layout.
	 * App::Layout(false);      // Does not execute layout.
	 * App::Layout('name');     // Set layout name.
	 * $layout = App::Layout(); // Get layout name.
	 * </pre>
	 *
	 * @param	 null|boolean|string	 $value
	 */
	static function Layout($name=null)
	{
		//	...
		switch( $type = gettype($name) ){
			case 'boolean':
				//	...
				self::$_LAYOUT_::Execute($name);

				//	...
				if(!$name ){
					self::$_LAYOUT_::Name('');
				}
				break;

			case 'string':
				//	...
				self::$_LAYOUT_::Name($name);

				//	...
				if( $name ){
					self::$_LAYOUT_::Execute(true);
				}
				break;

			default:
		}

		//	...
		return self::$_LAYOUT_::Execute() ? self::$_LAYOUT_::Name() : false;
	}

	/** WebPack
	 *
	 * @param	 string	 $path
	 */
	static function WebPack($path)
	{
		//	...
		if(!class_exists('OP\UNIT\WebPack') ){
			if(!Unit::Load('webpack') ){
				return;
			}
		}

		//	...
		list($path, $ext) = explode('.', $path);

		//	...
		$path = ConvertPath($path);

		//	...
		OP\UNIT\WebPack::Set($ext, $path);
	}

	/** Get/Set title.
	 *
	 * @param	 string	 $title
	 * @param	 string	 $separator
	 * @return	 string	 $title
	 */
	static function Title($title=null)
	{
		static $_titles, $separator=' | ';
		if( $title ){
			$_titles[] = $title;
		}
		return join($separator, array_reverse($_titles));
	}
}

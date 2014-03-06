<?php
namespace framework;
/**
 * Container (DI)
 * 
 * PHP Version 5.5
 * 
 * @author     hgsk
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 */
class Container
{
	public static $container = [];

	/**
	 * @param string $keyName
	 * @return bool
	 */
	public static function exists($keyName){
		if(isset(self::$container[$keyName])){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * @param string $keyName
	 * @param trait $container
	 */
	public static function set($keyName, $container){
		self::$container[$keyName] = $container;
		return true;
	}

	/**
	 * @param string $keyName
	 */
	public static function get($keyName){
		if(isset(self::$container[$keyName])){
			return self::$container[$keyName];
		}else{
			return false;
		}
	}
}

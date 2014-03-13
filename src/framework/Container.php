<?php
namespace framework;
/**
 * DI コンテナ
 * 各種インスタンスを格納する
 * 
 * PHP Version 5.5
 * 
 * @author     hgsk
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 **/
class Container
{
	/* @type array インスタンス登録場所 */
	public static $container = [];

	/**
	 * インスタンスの登録状況を調べる
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
	 * インスタンスを登録する
	 * @param string $keyName
	 * @param trait $container
	 */
	public static function set($keyName, $container){
		self::$container[$keyName] = $container;
		return true;
	}

	/**
	 * インスタンスを取り出す
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

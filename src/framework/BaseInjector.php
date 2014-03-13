<?php
namespace framework;
/*
 * インジェクター基底トレイト
 */
trait BaseInjector
{
	/**
	 * インスタンスをDIコンテナに格納する準備をする
	 * @param string $name
	 * @param object $instance
	 * @return object
	 */
	public static function prepare($name=null,$instance){
		// TODO 例外処理を書く
		if(!isset($name)){
			$name = uniqid();
		}
		$keyName = __TRAIT__ . $name;
		if(!Container::exists($keyName))
		{
			Container::set($keyName, $instance); 
		}
		return Container::get($keyName);
	}
}

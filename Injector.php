<?php
/**
 * Injector trait (DI)
 *
 * Singletonのインスタンスを返します
 * 
 * PHP Version 5.5
 * 
 * @author     hgsk
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 */
trait ViewInjector
{
	/**
	 * @param string $configName
	 * @return \Lib\JsonConfig
	 */
	public function getView($name=null)
	{
		if(!isset($name)){
			$name = uniqid();
		}
		$keyName = __TRAIT__ . $name;
		if(!Container::exists($keyName))
		{
			Container::set($keyName, new View()); 
		}
		return Container::get($keyName);
	}
}
trait NavigationInjector 
{
	/**
	 * @param string $configName
	 * @return \Lib\JsonConfig
	 */
	public function getNavigation($name=null)
	{
		if(!isset($name)){
			$name = uniqid();
		}
		$keyName = __TRAIT__ . $name;
		if(!Container::exists($keyName))
		{
			Container::set($keyName, new Navigation()); 
		}
		return Container::get($keyName);
	}
}
trait ContentInjector 
{
	/**
	 * @param string $configName
	 * @return \Lib\JsonConfig
	 */
	public function getContent($name=null)
	{
		if(!isset($name)){
			$name = uniqid();
		}
		$keyName = __TRAIT__ . $name;
		if(!Container::exists($keyName))
		{
			Container::set($keyName, new Content($name)); 
		}
		return Container::get($keyName);
	}
}
trait UserModelInjector
{
	public function getUserModel($name=null)
	{
		if(!isset($name)){
			$name = uniqid();
		}
		$keyName = __TRAIT__ . $name;
		if(!Container::exists($keyName))
		{
			Container::set($keyName, new UserModel()); 
		}
		return Container::get($keyName);
	}
}


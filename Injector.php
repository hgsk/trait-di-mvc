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
trait BaseInjector
{
	public static function prepare($name=null,$instance){
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
trait ViewInjector
{
	/**
	 * @param string $configName
	 * @return \Lib\JsonConfig
	 */
	public function getView($name=null)
	{
		return BaseInjector::prepare($name, new View());
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
		return BaseInjector::prepare($name, new Navigation());
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
		return BaseInjector::prepare($name, new Content($name));
	}
}
trait UserModelInjector
{
	public function getUserModel($name=null)
	{
		return BaseInjector::prepare($name, new UserModel());
	}
}


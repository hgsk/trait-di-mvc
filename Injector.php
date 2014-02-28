<?php
/**
 * Injector trait (DI)
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
trait ViewInjector
{
	/**
	 * @param string $configName
	 * @return \Lib\JsonConfig
	 */
	public function getView($filename=null)
	{
		return BaseInjector::prepare($filename, new View($filename));
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
		return BaseInjector::prepare($name, new Navigation);
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
trait ModelInjector
{
	public function getModel($name=null)
	{
		$classname = camelize($name);
		return BaseInjector::prepare($name, new $classname);
	}
}

trait ControllerInjector
{
	public function getController($name=null)
	{
		$classname = camelize($name) . 'Controller';
		return BaseInjector::prepare($name, new $classname);
	}
}

function camelize($str){
	return str_replace(' ','',ucwords(str_replace('_',' ',$str)));
}

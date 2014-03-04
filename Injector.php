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
	/**
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

trait ViewInjector
{
	/**
	 * @param string $name
	 * @return View
	 */
	public function getView($filename=null)
	{
		return BaseInjector::prepare($filename, new View($filename));
	}
}

trait NavigationInjector 
{
	/**
	 * @param string $name
	 * @return Navigation
	 */
	public function getNavigation($name=null)
	{
		return BaseInjector::prepare($name, new Navigation);
	}
}

trait ContentInjector 
{
	/**
	 * @param string $name
	 * @return Content
	 */
	public function getContent($name=null)
	{
		return BaseInjector::prepare($name, new Content($name));
	}
}

trait ControllerInjector
{
	/**
	 * @param string $name
	 * @return Controller
	 */
	public function getController($name=null)
	{
		$classname = camelize($name) . 'Controller';
		return BaseInjector::prepare($name, new $classname);
	}
}

function camelize($str){
	return str_replace(' ','',ucwords(str_replace('_',' ',$str)));
}

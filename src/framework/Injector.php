<?php
namespace framework;

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


<?php
namespace framework;
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

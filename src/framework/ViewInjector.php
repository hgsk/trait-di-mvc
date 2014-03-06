<?php
namespace framework;
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

<?php

namespace framework;
/*
 * コントローラインジェクタ
 */
trait ControllerInjector
{
	/**
	 * コントローラインスタンスを取り出す
	 * @param string $name
	 * @return Controller
	 */
	public function getController($name=null)
	{
		$classname = '\\' . \camelize($name) . 'Controller';
		return BaseInjector::prepare($name, new $classname);
	}
}

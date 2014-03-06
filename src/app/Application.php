<?php
/**
 * Application
 */
class Application {
	use \framework\ControllerInjector;
	public function run(){
		// /Controller/Action/param1/value1/param2/value2/...
		$requestURI = explode('/', $_SERVER['REQUEST_URI']);
		$this->getController($requestURI[1])->$requestURI[2](array_slice($requestURI,3));
	}
}

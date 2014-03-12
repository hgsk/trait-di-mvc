<?php
/**
 * Application
 */
class Application {
	const ROOT_CONTROLLER_NAME = 'Welcome';
	use \framework\ControllerInjector;
	public function run(){
		// /Controller/Action/param1/value1/param2/value2/...
		$requestURI = explode('/', $_SERVER['REQUEST_URI']);

		if(!empty($requestURI[1])){
			$this->getController($requestURI[1])->$requestURI[2](array_slice($requestURI,3));
		}elseif(!empty($requestURI[2])){
			$this->getController($requestURI[1])->index();
		}else{
			$this->getController(self::ROOT_CONTROLLER_NAME)->index();
		}
	}
}

<?php
require 'Container.php';
require 'Injector.php';
class View {
	public function show($str){
		echo "<p>$str</p>";
	}
}

class User{
	public function get(){
		return [
			(object)['name'=>'John','password'=>'foo'],
			(object)['name'=>'Mark','password'=>'bar']
		];
	}
}

class Navigation{
	use ViewInjector;
	public function show(){
		$this->getView()->show('li*3');
	}
}

class Content{
	use ViewInjector;
	use ModelInjector;
	private $title;
	public function __construct($title){
		$this->title = $title;
	}
	public function show(){
		$users = $this->getModel('user')->get();
		$this->getView()->show($this->title);
		foreach($users as $user){
			$this->getView()->show($user->name);
		}
	}
}

class PageController {
	use NavigationInjector;
	use ContentInjector;
	public function show(){
		$this->getNavigation()->show();
		$this->getContent('title!')->show();
	}
}

class Application {
	use ControllerInjector;
	public function run(){
		$this->getController('page')->show();
	}
}

$app = new Application;
$app->run();

<?php
require 'Container.php';
require 'Injector.php';
class View {
	public function show($str){
		echo "<p>$str</p>";
	}
}

class  UserModel{
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
	use UserModelInjector;
	private $title;
	public function __construct($title){
		$this->title = $title;
	}
	public function show(){
		$users = $this->getUserModel()->get();
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

$page = new PageController;
$page->show();

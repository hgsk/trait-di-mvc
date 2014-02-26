<?php
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
	private $view;
	public function __construct(){
		$this->view = new View();
	}
	public function show(){
		$this->view->show('li*3');
	}
}

class Content{
	private $title;
	private $view;
	private $userModel;
	public function __construct($title){
		$this->title = $title;
		$this->view = new View();
		$this->userModel = new UserModel();
	}
	public function show(){
		$users = $this->userModel->get();
		$this->view->show($this->title);
		foreach($users as $user){
			$this->view->show($user->name);
		}
	}
}

class PageController {
	public function show(){
		$navigation = new Navigation();
		$content = new Content("title!");
		$navigation->show();
		$content->show();
	}
}


$page = new PageController();
$page->show();

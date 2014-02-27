<?php
require 'Container.php';
require 'Injector.php';

// TODO static makeでテンプレートを呼び出す
// TODO none-static setでテンプレート変数を渡す
class View {
	public function show($str){
		echo "<p>$str</p>";
	}
}

// TODO 各ビジネスモデルはModelを継承するようにする
// TODO Modelはgetter,setter,validationのみを提供する
class User{
	public function get(){
		return [
			(object)['name'=>'John','password'=>'foo'],
			(object)['name'=>'Mark','password'=>'bar']
		];
	}
}
// TODO DataMapperを定義する
// TODO 複数のDataMapperにまたがるトランザクションを実装できるようにする
// http://www.sitepoint.com/forums/showthread.php?593227-DataMapper-Pattern-and-Transactions
// For the first method, you could handle transactions inside each mapper.
// Each mapper checks if a transaction is already started, and if not, starts one itself.
// If a transaction is already started, then it just goes about its business. 

// TODO Decoratorに変更して、View用のModel処理を記述する cf:Draper
// viewで、$user->getName()
class Navigation{
	use ViewInjector;
	public function show(){
		$this->getView()->show('li*3');
	}
}

// TODO Context(DCI)に変更して、DataMapper間の処理を記述する
// TODO Context内でModelにRoleを与えて処理する -> Model内で動的にtraitをuseできるようにしたい $user->use('role')->roleInteraction() <-むりっぽい
class Content{
	use ViewInjector;
	use ModelInjector;
	private $title;
	public function __construct($title){
		$this->title = $title;
	}
	public function show(){
	//	$users = UserMapper::find(1);
		$users = $this->getModel('user')->get();
		$this->getView()->show($this->title);
		foreach($users as $user){
			$this->getView()->show($user->name);
		}
	}
}

// TODO 抽象クラス BaseControllerに共通処理と、個別処理のインターフェースを書く
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

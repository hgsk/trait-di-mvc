<?php
// TODO 抽象クラス BaseControllerに共通処理と、個別処理のインターフェースを書く
/**
 * Register Controller
 */
class RegisterController{
	use \framework\ViewInjector;
	protected $isPost;

	public function index(){
		$this->getView('view/register.html')->render();
	}

	public function register(){
		if(strtoupper($_SERVER['REQUEST_METHOD']) === 'POST'){
			$this->isPost = true;
		}
		if(isset($this->isPost)){
			//新規ユーザーを登録
			$user = new User();
			$user->name = $_POST['username'];
			$user->email_address = $_POST['email_address'];
			// save hash and salt
			$user->password = password_hash($_POST['password'],PASSWORD_BCRYPT);
			$new_user_id = UserMapper::create($user);
		}
		
		// Viewを出力
		$this->getView('view/register.html')->set('registered',true)->render();
	}
}

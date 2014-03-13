<?php
/**
 * 認証を行うコントローラ
 **/
class AuthController {
	use framework\BaseController;
	use framework\ViewInjector;

	/**
	 * ログイン
	 * @return void
	 **/
	function login()
	{
		if(!session_id()){
			session_start();
		}
		$this->getView('view/login.html')->set('message',$_SESSION)->render();
	}

	/**
	 * ログアウト
	 * @return void
	 **/
	function logout(){
		if(session_id()){
			session_destroy();
		}
		$this->getView('view/logout.html')->render();
	}

	/**
	 * 認証
	 * @return void
	 **/
	function authenticate(){
		$_SESSION['signed_in'] = [];

		$this->validate($_POST);

		$user = UserMapper::findByEmailAddress($_POST['email_address']);
		// TODO: Userオブジェクトかどうかvalidationしたい
		if(!empty($user)){
			if(password_verify($_POST['password'],$user->password)){
				$_SESSION['signed_in'] = true;
				$_SESSION['username'] = $user->name;
				header('Location: ' . $_SESSION['redirect']);
			}else{
			$_SESSION['flash'][] = ['Invalid username or password'];
			$_SESSION['signed_in'] = false;
			$_SESSION['username'] = null;
			}
		}
		header('Location: /auth/login');
	}
	protected function validate($form){
		// TODO: Modelにうつしたい
		/*pseudo
		$form = $this->getValidator(new DTO);
		if($form->validate($_POST)){
			flash('error message')
		}
		 */
		// すべてのチェック結果をflashしてreturnしたい
		try{
			$v = $this->validator->notEmpty()->email()->assert($form['email_address']);
		}catch(\InvalidArgumentException $e){
			$_SESSION['flash'][] = $e->findMessages(array('notEmpty'=>'メールアドレスが空白です','email'=>'正しいメールアドレスを入力してください'));
		}
		try{
			$v = $this->validator->notEmpty()->noWhitespace()->assert($form['password']);
		}catch(\InvalidArgumentException $e){
			$_SESSION['flash'][] = $e->findMessages(array('notEmpty'=>'パスワードが空白です','noWhitespace'=>'余計なスペースを入れないでください'));
		}
		return true;
	}
}

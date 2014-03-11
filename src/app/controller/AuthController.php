<?php
class AuthController {
	use framework\BaseController;
	use framework\ViewInjector;

	function login()
	{
		$this->getView('view/login.html')->set('message',$_SESSION)->render();
		$_SESSION['flash'] = [];
	}

	function logout(){
		session_destroy();
		$this->getView('view/logout.html')->render();
	}

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
	function validate($form){
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

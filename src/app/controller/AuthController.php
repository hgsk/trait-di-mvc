<?php
use framework\ViewInjector;
use Auth;
class AuthController{
	use framework\ViewInjector;

	function login()
	{
		$this->getView('view/login.html')->render();
	}

	function authenticate(){
		session_start();
		session_destroy();
		$user = UserMapper::findByEmailAddress($_POST['email_address']);
		if(password_verify($user->password,$user->password)){
			session_start();
			$_SESSION['signed_in'] = true;
			$_SESSION['username'] = $user->username;
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}else{
			$_SESSION['flash']['error'] = 'Invalid username or password';
			$_SESSION['signed_in'] = false;
			$_SESSION['username'] = null;
			header('Location: /auth/index');
		}
	}
}

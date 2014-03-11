<?php
class AuthController {
	use framework\BaseController;
	use framework\ViewInjector;

	function login()
	{
		$this->getView('view/login.html')->set('message',$_SESSION)->render();
	}

	function logout(){
		session_destroy();
		$this->getView('view/logout.html')->render();
	}

	function authenticate(){
		$_SESSION['signed_in'] = [];
		if(empty(trim($_POST['email_address']))){
			$_SESSION['flash'] = 'Enter Mail Address';
		}elseif(empty(trim($_POST['password']))){
			$_SESSION['flash'] = 'Enter Password';
		}else{
			$user = UserMapper::findByEmailAddress($_POST['email_address']);
			if(!empty($user)){
				if(password_verify($_POST['password'],$user->password)){
					$_SESSION['signed_in'] = true;
					$_SESSION['username'] = $user->name;
					header('Location: ' . $_SESSION['redirect']);
				}else{
				$_SESSION['flash'] = 'Invalid username or password';
				$_SESSION['signed_in'] = false;
				$_SESSION['username'] = null;
				}
			}
		}
		header('Location: /auth/login');
	}
}

<?php
// TODO 抽象クラス BaseControllerに共通処理と、個別処理のインターフェースを書く
/**
 * Page Controller
 */
class UserController{
	use \framework\ViewInjector;
	public function all($params){
		//TODO 認証処理をBaseControllerで__callフックする(認証するかどうかは別途設定)
		session_start();
		if(!$_SESSION['signed_in']){
			$_SESSION['flash_error'] =  " Please sign in";
			header("Location: /auth/index");
			exit;
		}
		//すべてのユーザーを取得
		$users = UserMapper::all();
		$user_ids = array_map(function ($user){return $user->id;},$users);
		// Viewを出力
		$this->getView('view/users.html')->set('users',$users)->render();
	}
}

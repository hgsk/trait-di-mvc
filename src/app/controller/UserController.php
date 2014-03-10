<?php
// TODO 抽象クラス BaseControllerに共通処理と、個別処理のインターフェースを書く
/**
 * Page Controller
 */
class UserController{
	use framework\BaseController;
	use framework\ViewInjector;
	public function all($params){
		if(self::isAuthorized()){
			//すべてのユーザーを取得
			$users = UserMapper::all();
			$user_ids = array_map(function ($user){return $user->id;},$users);
			// Viewを出力
			$this->getView('view/users.html')->set('users',$users)->render();
		}
	}
}

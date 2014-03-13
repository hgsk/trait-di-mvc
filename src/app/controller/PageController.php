<?php
// TODO 抽象クラス BaseControllerに共通処理と、個別処理のインターフェースを書く
/**
 * ページを表示するコントローラ サンプル
 */
class PageController {
	use \framework\BaseController;
	use \framework\ViewInjector;

	/**
	 * ページを表示
	 * @return void
	 **/
	public function show($params){
		//新規ユーザーを登録
		$user = new User();
		$user->name = "Jane";
		$user->password = "hoge";
		$new_user_id = UserMapper::create($user);

		//新規ユーザーを修正
		$user = UserMapper::find($new_user_id);
		$user->name = "Sato";
		$user->password = "hoge";
		UserMapper::update($user);

		//すべてのユーザーを取得
		$users = UserMapper::all();
		$user_ids = array_map(function ($user){return $user->id;},$users);

		//すべてのユーザーを削除
		//UserMapper::destroy($user_ids);

		// Viewを出力
		$this->getView('view/view.html')->set('users',$users)->set('foo','bar')->set('hoge','fuga')->render();
		//$view = $this->getView('view.html');
		//$view->set('users',$users);
		//$view->set('foo','bar');
		//$view->set('hoge','fuga');
		//$view->render();
	}
}

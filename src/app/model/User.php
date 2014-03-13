<?php
/**
 * User モデル
 */
// TODO 各ビジネスモデルはModelを継承するようにする
// TODO Modelは__get,__set,validationのみを提供する
class User{
	// TODO カラム名とプロパティ名あわせないといけない問題を解消する
	
	public $id;				// @type int 		ユニークID
	public $name;			// @type string 	ユーザー名
	public $password;		// @type string 	パスワード
	public $email_address;	// @type string 	メールアドレス
	public $create_dt;		// @type timestamp	データ作成日時
	public $update_dt;		// @type timestamp	データ更新日時
}

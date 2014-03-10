<?php
/**
 * User Model
 */
// TODO 各ビジネスモデルはModelを継承するようにする
// TODO Modelは__get,__set,validationのみを提供する
class User{
	// TODO カラム名とプロパティ名あわせないといけない問題を解消する
	public $id;
	public $name;
	public $password;
	public $email_address;
	public $create_dt;
	public $update_dt;
}

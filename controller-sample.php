<?php
require 'Container.php';
require 'Injector.php';

/**
 * View
 */
class View {
	public $variables = [];
	public $content;

	/**
	 * テンプレートを呼び出す
	 * @param string $filename View template filename
	 * @return bool
	 */
	public function __construct($filename){
		$this->filename = $filename;
		return true;
	}

	/**
	 * コンテンツを出力する
	 * @return bool
	 */
	public function render(){
		ob_start();
		extract($this->variables);
		include $this->filename;
		$this->content = ob_get_clean();
		echo $this->content;
		//JsonVIEW
		//header('Content-type: application/json');
		//echo json_encode($this->variables, JSON_PRETTY_PRINT);
		return true;
	}

	/**
	 * テンプレート変数を格納する
	 * @param string $key variable key
	 * @param string $value variable value
	 * @return object $this
	 */
	public function set($name,$value){
		$this->variables[$name] = $value;
		return $this;
	}
}

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
	public $create_dt;
	public $update_dt;
}

/**
 * User DataMapper
 */
// TODO 永続化機構をBaseDataMapperクラスに実装する
// TODO 複数のDataMapperにまたがるトランザクションを実装できるようにする -> Contextに実装？
// http://www.sitepoint.com/forums/showthread.php?593227-DataMapper-Pattern-and-Transactions
// For the first method, you could handle transactions inside each mapper.
// Each mapper checks if a transaction is already started, and if not, starts one itself.
// If a transaction is already started, then it just goes about its business. 
// DBALのnestedTransactionを利用できるかも
require 'vendor/autoload.php';
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
class UserMapper extends DataMapper{
	const MODEL_CLASS = 'User';

	// PDO/DBALに依存している。(SQL直書きなので)
	// TODO MongoやRedisにも対応できるようにする

	/**
	 * 新しい行を追加する
	 * @param Model $model;
	 * @return int ;
	 **/
	static public function create($model){
		$statement = self::getConnection()->prepare('
			INSERT INTO user_tbl(name, password, create_dt, update_dt)
			VALUES (:name,:password, :create_dt, :update_dt) 
		');
		$dt = self::getDateTime();
		$statement->bindParam("name", $model->name, PDO::PARAM_STR);
		$statement->bindParam("password", $model->password, PDO::PARAM_STR);
		$statement->bindParam("create_dt", $dt, PDO::PARAM_STR);
		$statement->bindParam("update_dt", $dt, PDO::PARAM_STR);
		$statement->execute();
		$model->id = self::getConnection()->lastInsertId();
		return $model->id;
	}

	/**
	 * 指定した行を更新する
	 * @param Model $model;
	 * @return Model;
	 **/
	static public function update($model)
	{
		//TODO 指定した行だけUPDATEするようにする
		//TODO 指定したプロパティだけUPDATEするようにする
		$statement = self::getConnection()->prepare('
			UPDATE user_tbl 
			SET name = :name
			,	password = :password
			WHERE id = :id
		');
		$statement->bindValue("name", $model->name, PDO::PARAM_STR);
		$statement->bindValue("password" ,$model->password, PDO::PARAM_STR);
		$statement->bindValue("id" ,$model->id, PDO::PARAM_INT);
		$statement->execute();
	}

	/**
	 * 指定したIDの行をDBから削除する
	 * @param int $id;
	 * @return Model;
	 **/
	static public function destroy($ids){
		$sql = '
			DELETE FROM user_tbl 
			WHERE id in (?)
		';
		$statement = self::getConnection()->executeQuery($sql, [$ids], [Connection::PARAM_INT_ARRAY]);
		return $statement->execute();
	}

	/**
	 * 指定したIDの行を返す
	 * @param int $id;
	 * @return Model;
	 **/
	static public function find($id){
		$statement = self::getConnection()->prepare('
			SELECT *
			FROM user_tbl
			WHERE id = :id
		');
		$statement->bindValue("id", $id, PDO::PARAM_INT);
		$statement->execute();

		return self::decorate($statement)->fetch();
	}

	/**
	 * すべての行を返す
	 * @param int $id;
	 * @return Model $model;
	 **/
	static public function all(){
		$statement = self::getConnection()->query('
			SELECT *
			FROM user_tbl;
		');
		return self::decorate($statement)->fetchAll();
	}
}

abstract class DataMapper
{
	// PDO/DBALに依存している。
	// TODO MongoやRedisにも対応できるようにする
	static protected $connection;
	static public function getConnection($dsn = null,$config = null){
		if(!isset($dsn)){
			$dsn = [
				"driver"=>"pdo_sqlite",
				"path"=>"app.db"
			];
		}
		if(!isset($config)){
			$config = new Configuration([ PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ]);
		}
		if(!isset(self::$connection)){
			self::$connection = DriverManager::getConnection($dsn, $config);
		}
		return self::$connection;
	}

	static protected function decorate($statement)
	{
		$statement->setFetchMode(PDO::FETCH_CLASS, static::MODEL_CLASS);
		return $statement;
	}
	static protected function getDateTime(){
		$dt= new DateTime();
		return $dt->format('c');
	}
}

// TODO Decoratorに変更して、View用のModel処理を記述する cf:Draper
// viewで、$user->getName()
class Navigation{
	use ViewInjector;
	public function show(){
		$this->getView()->set('nav','li*3');
	}
}

// TODO Context(DCI)に変更して、DataMapper間の処理を記述する
// TODO Context内でModelにRoleを与えて処理する -> Model内で動的にtraitをuseできるようにしたい $user->use('role')->roleInteraction() <-むりっぽい
class Content{
	use ViewInjector;
	private $title;
	public function __construct($title){
		$this->title = $title;
	}
	public function show(){
	//	$users = UserMapper::find(1);
		$users = UserMapper::all();
		$this->getView()->set('title',$this->title);
		$this->getView()->set('users',$users)->render();
	}
}

// TODO 抽象クラス BaseControllerに共通処理と、個別処理のインターフェースを書く
/**
 * Page Controller
 */
class PageController {
	use ViewInjector;
	public function show($params){
		//Cookieをセット
		setcookie("cookie","eaten");
		//
		//print_r($_COOKIE);
		//print_r($_GET);
		//print_r($_POST);

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
		UserMapper::destroy($user_ids);

		// Viewを出力
		$this->getView('view.html')->set('users',$users)->set('foo','bar')->set('hoge','fuga')->render();
		//$view = $this->getView('view.html');
		//$view->set('users',$users);
		//$view->set('foo','bar');
		//$view->set('hoge','fuga');
		//$view->render();
	}
}

/**
 * Application
 */
class Application {
	use ControllerInjector;
	public function run(){
		// /Controller/Action/param1/value1/param2/value2/...
		$requestURI = explode('/', $_SERVER['REQUEST_URI']);
		$this->getController($requestURI[1])->$requestURI[2](array_slice($requestURI,3));
	}
}

//////////Entry Point//////////
$app = new Application;
date_default_timezone_set('Asia/Tokyo');
$app->run();

//////////Test//////////
class Test{
	public function testCamelize(){
		echo camelize('hoge')=='Hoge' ? 'passed' : 'failed';
		//echo camelize('hOge')=='Hoge' ? 'passed' : 'failed';
		//echo camelize('hoGe')=='Hoge' ? 'passed' : 'failed';
		//echo camelize('hogE')=='Hoge' ? 'passed' : 'failed';
	}
}

////////Debug Utility////////
function v($variable){
	var_dump($variable);
	return true;
}

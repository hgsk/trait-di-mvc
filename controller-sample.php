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
// TODO Modelはgetter,setter,validationのみを提供する
class User{
	public $name;
	public $password;
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
require 'vendor/autoload.php';
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
class UserMapper extends DataMapper{
	const MODEL_CLASS = 'User';
	static public function insert($data){
		$modelClass = self::MODEL_CLASS;
		$statement = self::getConnection()->prepare('
			INSERT INTO user_tbl(name, password)
			VALUES (?,?,?,?)
		');
		$statement->bindParam(1,$name, PDO::PARAM_STR);
		$statement->bindParam(1,$password, PDO::PARAM_STR);
		if(! is_array($data)){
			$data = array($data);
		}
		foreach($data as $row){
			if(! $row instanceof $modelClass || ! $row->isValid()){
				throw new InvalidArgumentException;
			}
			$name = $row->name;
			$password = $row->password;
			$statement->execute();
			$row->id = self::getConnection()->lastInsertId();
		}
	}

	static public function update($data)
	{
		$modelClass = self::MODEL_CLASS;
		$statement = self::getConnection()->prepare('
			UPDATE user_tbl 
			SET name = ?
			,	password = ?
			WHERE id = ?
		');
		$statement->bindParam(1,$name, PDO::PARAM_STR);
		$statement->bindParam(1,$password, PDO::PARAM_STR);
		$statement->execute();
	}

	static public function delete($data){
		$modelClass = self::MODEL_CLASS;
		$statement = self::getConnection()->prepare('
			DELETE FROM user_tbl 
			WHERE id = ?
		');
		$stamtement->bindParam(1, $id, PDO::PARAM_INT);
		if(! is_array($data)){
			$data = [$data];
		};
		foreach($data as $row){
			if(! $row instanceof $modelClass){
				throw new InvalidArgumentException;
			}
			$id = $row->id;
			$statement = execute();
		}
	}

	function find($id){
		$statement = self::getConnection()->prepare('
			SELECT *
			FROM user_tbl
			WHERE id = ?
		');
		$statement->bindParam(1, $id, PDO::OARAM_INT);
		$statement->execute();

		self::decorate($statement);
		return $statement->fetch();
	}

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

	static protected function decorate(PDOStatement $statement)
	{
		$statement->setFetchMode(PDO::FETCH_CLASS, static::MODEL_CLASS);
		return $statement;
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
	//use DataMapperInjector;
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
	//use DataMapperInjector;
	use ViewInjector;
	public function show(){
		//$this->getView('view.html')->set('foo','bar')->set('hoge','fuga')->render();
		$users = UserMapper::all();
		$view = $this->getView('view.html');
		$view->set('users',$users);
		$view->set('foo','bar');
		$view->set('hoge','fuga');
		$view->render();
	}
}

/**
 * Application
 */
class Application {
	use ControllerInjector;
	public function run(){
		$this->getController('page')->show();
	}
}

//////////Entry Point//////////
$app = new Application;
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

<?php
namespace framework;
use \Whoops\Run;
use \Whoops\Handler\PrettyPageHandler;
use Monolog\Logger;
use Monolog\Handler\ConsoleLogHandler;
use Monolog\Handler\StreamHandler;

// TODO: Modelにうつしたい
use Respect\Validation\Validator as Validator;
/*
 * コントローラ基底トレイト
 */
trait BaseController{
	// @type Handler Whoopsエラーハンドラ
	protected $handler;
	// @type Logger Monologロガー
	protected $logger;
	// @type Validator Respect Validator
	protected $validator;

	/*
	 * コンストラクタ
	 * コントローラは全てこのコンストラクタで初期化される
	 */
	public function __construct(){
		// Error
		$this->handler = new PrettyPageHandler;
		//$this->handler->addDataTableCallback('included_files',get_included_files);
		$this->whoops = new Run;
		$this->whoops->pushHandler($this->handler);
		$this->whoops->register();

		// Logger
		$this->logger = new Logger('warning');
		$this->logger->pushHandler(new ConsoleLogHandler(Logger::WARNING));
		$this->logger->pushHandler(new StreamHandler(SYSPATH . "/log/monolog.log", Logger::WARNING));

		// TODO: Modelにうつしたい
		// Form Validators
		$this->validator = Validator::create();
		
		//session write close
		session_register_shutdown();

		isset($_SESSION['flash'])?: $_SESSION['flash'] = [];
	}
	/*
	 * ユーザーの認証状態を確認する
	 * 認証が必要なコントローラから呼び出す
	 */
	static public function isAuthorized(){
		if(!isset($_SESSION['signed_in'])){
			$_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
			$_SESSION['flash'] =  " Please sign in";
			header("Location: /auth/login");
			exit;
		}else{
			return true;
		}
	}
}

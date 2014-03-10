<?php
namespace framework;
/**
 * View
 */
use Monolog\Logger;
use Monolog\Handler\ConsoleLogHandler;
class View {
	public $variables = [];
	public $content;
	public $logger;

	/**
	 * テンプレートを呼び出す
	 * @param string $filename View template filename
	 * @return bool
	 */
	public function __construct($filename){
		$this->filename = APPPATH .'app/' . $filename;
		$this->logger = new Logger('view');
		$this->logger->pushHandler(new ConsoleLogHandler(Logger::WARNING));
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
		if(!empty($this->variables)){
			$this->logger->addWarning(json_encode($this->variables));
		}
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

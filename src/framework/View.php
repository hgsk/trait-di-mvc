<?php
namespace framework;
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
		$this->filename = APPPATH .'app/' . $filename;
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

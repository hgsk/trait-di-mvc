<?php
////////Debug Utility////////
function v($variable){
	return var_dump($variable);
}
function camelize($str){
	return str_replace(' ','',ucwords(str_replace('_',' ',$str)));
}



function c($dump, $label = null, $trace = false, $trace_options = []){
	if(!is_array($dump)){
		$dump = [$dump];
	}
	if(!empty($label)){
		$dump = [
			$label=>$dump
		];
	}
	$dump['__info']['message'] = 'dump:';

	if($trace){
		if(!isset( $trace_options[0])){
			$trace_options[0] = DEBUG_BACKTRACE_PROVIDE_OBJECT;
		}
		if(!isset($trace_options[1])){
			$trace_options[1] = 0;
		}
		$dump['__info']['trace'] = PHP_VERSION_ID < 50400 ? debug_backtrace($trace_options[0]) : debug_backtrace($trace_options[0], $trace_options[1]);
	}

	$json = json_encode($dump);
	$template = <<<TEMPLATE
<script type="text/javascript">
if(!('console' in window)){
	window.console = {};
	window.console.log = function(str){ return str; };
}
console.log({$json});
</script>
TEMPLATE;
	
	echo $template;
	
}
function e($value){
	throw new Exception(var_export($value,true));
}

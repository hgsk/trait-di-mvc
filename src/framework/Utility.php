<?php
namespace framework;
////////Debug Utility////////
function v($variable){
	var_dump($variable);
	return true;
}
function camelize($str){
	return str_replace(' ','',ucwords(str_replace('_',' ',$str)));
}

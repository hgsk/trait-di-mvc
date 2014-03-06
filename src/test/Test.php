<?php

//////////Test//////////
class Test{
	public function testCamelize(){
		echo camelize('hoge')=='Hoge' ? 'passed' : 'failed';
		//echo camelize('hOge')=='Hoge' ? 'passed' : 'failed';
		//echo camelize('hoGe')=='Hoge' ? 'passed' : 'failed';
		//echo camelize('hogE')=='Hoge' ? 'passed' : 'failed';
	}
}

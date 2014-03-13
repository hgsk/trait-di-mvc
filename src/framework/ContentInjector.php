<?php

/*
 * Contentインジェクタ
 */
trait ContentInjector 
{
	/**
	 * Contentインスタンスを取り出す
	 * @param string $name
	 * @return Content
	 */
	public function getContent($name=null)
	{
		return BaseInjector::prepare($name, new Content($name));
	}
}

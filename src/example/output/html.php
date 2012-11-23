<?php
namespace Example\Output;

use By;

class Html implements By\Output
{
	private $_view;
	public $errors = array();

	function __construct($view)
	{
		$this->_view = $view;
	}

	function reader($view)
	{
		require VIEWPATH . "/{$view}.phtml";
	}

	function onSuccess(By\Input $input, array $actions)
	{
		//查询数据库,转换数据到html
		$this->reader($this->_view);
	}

	function onFailure(By\Input $input, array $errors)
	{
		header('Content-Type: text/html; charset=utf-8');

		foreach ($errors as $error) {
			$this->errors = array_merge($this->errors, $error->getErrors());
		}

		$this->reader('error');
	}

	function onException(\Exception $exception)
	{
		echo $exception;
	}

}

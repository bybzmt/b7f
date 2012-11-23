<?php
namespace Example\Valid;

use By;

class Index implements By\Valid
{
	private $errors = array();

	function isValid(By\Input $input)
	{
		$this->errors = array();

		if (!$input['type']) {
			$this->errors['type'] = "类型不能为空";
		}

		if (!$input['page']) {
			$this->errors['page'] = "分页不能为空";
		}

		return !$this->errors;
	}

	function getErrors()
	{
		return $this->errors;
	}
}

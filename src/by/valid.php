<?php
namespace By;

interface Valid
{
	/**
	 * 验证是否有效
	 *
	 * @return bool
	 */
	function isValid(Input $input);

	function getErrors();
}

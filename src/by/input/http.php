<?php
namespace By\Input;

use By;

/**
 * 输入转换
 */
class Http extends By\Input
{
	/**
	 * 它是一个filter系列函数包装
	 */
	function __construct($type, $definition)
	{
		if (is_int($type)) {
			switch ($type) {
			case INPUT_POST : $input = filter_var_array($_POST, $definition); break;
			case INPUT_GET : $input = filter_var_array($_GET, $definition); break;
			case INPUT_COOKIE : $input = filter_var_array($_COOKIE, $definition); break;
			case INPUT_ENV : $input = filter_var_array($_ENV, $definition); break;
			case INPUT_SERVER : $input = filter_var_array($_SERVER, $definition); break;
			default:
				throw new Exception("未知的type:{$type}");
			}
		}
		else {
			$args = func_get_args();

			$input = array();
			foreach ($args as $arg) {
				$input[$arg[1]] = call_user_func_array('filter_input', $arg);
			}
		}

		parent::__construct($input, \ArrayObject::ARRAY_AS_PROPS);
	}
}

<?php
namespace b7f;

class Loader
{
	/**
	 * 命名空间
	 */
	public $namespace = array();

	public function __construct($namespace)
	{
		$this->namespace = $namespace;
	}

	/**
	 * 载入一个类
	 */
	public function load($classname)
	{
		$space = strtolower($classname);
		$class = '';

		$tmp = explode('\\', $space);

		while ($tmp) {
			if (isset($this->namespace[$space])) {
				require $this->namespace[$space] . $class . '.php'; 
			}

			$class .= DIRECTORY_SEPARATOR . array_pop($tmp);
			$space = implode('\\', $tmp);
		}
	}

	/**
	 * 注册／卸载 自动载入器
	 *
	 * @param bool $flag    注册／卸载
	 * @param bool $throw   当自动载入器无法载入时是否抛出异常
	 * @param bool $prepend 将自动载入器注册为auto stack中的第1个
	 * @return bool
	 */
	public function autoload($flag, $throw=true, $prepend=true)
	{
		if ($flag) {
			return spl_autoload_register(array($this, "load"), $throw, $prepend);
		}
		else {
			return spl_autoload_unregister(array($this, "load"));
		}
	}
}

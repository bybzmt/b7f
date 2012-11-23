<?php
namespace By;

class Loader
{
	/**
	 * 静态加载类
	 */
	public $static;

	/**
	 * 命名空间
	 * array(
	 *     namespace1 => path1
	 *     namespace2 => path2
	 *     ...
	 * )
	 */
	public $namespace = array();

	/**
	 * 替换规则
	 */
	public $search = array('\\');
	public $replace = array(DIRECTORY_SEPARATOR);

	public function __construct()
	{
		$this->namespace['By'] = __DIR__;
	}

	/**
	 * 定位一个类的文件
	 *
	 * @param string $classname 类名
	 * @return 返回文件名
	 */
	public function locate($classname)
	{
		if (isset($this->static[$classname])) {
			return $this->static[$classname];
		}

		foreach ($this->namespace as $namespace => $path) {
			if (strpos($classname, $namespace) === 0) {
				return strtolower($path . str_replace(
					$this->search,
					$this->replace,
					substr($classname, strlen($namespace))
				)) . '.php';
			}
		}

		return strtolower(str_replace($this->search, $this->replace, $classname)) . '.php';
	}

	/**
	 * 载入一个类
	 */
	public function load($classname)
	{
		$file = $this->locate($classname);

		if (file_exists($file)) {
			require $file;
		}
	}

	/**
	 * 测试类文件是否存在
	 */
	public function fileExists($classname)
	{
		return file_exists($this->locate($classname));
	}

	/**
	 * 尝试自动载入然后测试类是否存在
	 */
	public function exists($classname)
	{
		$this->load($classname);

		return class_exists($classname, false);
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

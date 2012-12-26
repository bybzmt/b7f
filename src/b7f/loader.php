<?php
namespace By;

class Loader
{
	/**
	 * 命名空间
	 * array(
	 *     namespace1 => path1
	 *     namespace2 => path2
	 *     namespace3 => array(
	 *        'path3',
	 *        'names3-1' => array(
	 *            'path-3-1',
	 *            'names3-1-1 => 'path3-1-1'
	 *            )
	 *         )
	 *     ...
	 * )
	 */
	public $namespace = array();

	/**
	 * 替换规则
	 */
	public $search = array('\\');
	public $replace = array(DIRECTORY_SEPARATOR);

	public function __construct($namespace)
	{
		$this->namespace = $namespace;
	}

	/**
	 * 定位一个类的文件
	 *
	 * @param string $classname 类名
	 * @return 返回文件名
	 */
	public function locate($classname)
	{
		$classname = strtolower($classname);

		foreach ($this->namespace as $namespace => $path) {
			if (strpos($classname, $namespace) === 0) {
				return $path . str_replace(
					'\\',
					DIRECTORY_SEPARATOR,
					substr($classname, strlen($namespace))
				) . '.php';
			}
		}

		return null;
	}

	public function locate3($classname)
	{
		$space = strtolower($classname);
		$class = '';

		$tmp = explode('\\', $space);

		while ($tmp) {
			if (isset($this->namespace[$space])) {
				return $this->namespace[$space] . $class . '.php'; 
			}

			$class .= DIRECTORY_SEPARATOR . array_pop($tmp);
			$space = implode('\\', $tmp);
		}

		return null;
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

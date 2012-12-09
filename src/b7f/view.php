<?php

namespace b7f;

class View
{
	protected $_vars;
	protected $_file;
	protected $_layout;
	protected $_layout_var;

	public function __construct($view=null)
	{
		$this->_setFile($view);
	}

	/**
	 * 注册一个变量
	 */
	public function __set($key, $val)
	{
		$this->_vars[$key] = $val;
	}

	/**
	 * 批量注册变量
	 */
	public function assgns(array $vars)
	{
		$this->_vars = array_merge($this->_vars, $vars);
	}

	/**
	 * 读取视图文件
	 */
	public function reader($view=null)
	{
		$this->_setFile($view);

		if ($this->_layout) {
			//在有布局时将自身的渲染结果作为布局的一个变量传达
			$this->_layout->{$this->_layout_var} = $this->_reader();
			return $this->_layout->reader();
		}
		else {
			return $this->_reader();
		}
	}

	protected function _setLayout($layout, $var)
	{
		$this->_layout = new self($layout);
		$this->_layout_var = $var;
	}

	protected function _setFile($view)
	{
		if ($view) {
			$this->_file = VIEW_PATH . '/' . $view . VIEW_EXT;
		}
	}

	protected function _reader()
	{
		extract($this->_vars);

		ob_start();
		require $this->_file;
		return ob_get_clean();
	}
}

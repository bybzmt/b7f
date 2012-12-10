<?php

namespace b7f;

class View
{
	/*
	 * 注册的变量
	 */
	protected $_vars = array();

	/*
	 * 视图文件
	 */
	protected $_file;

	/*
	 * 布局文件
	 */
	protected $_layout;

	/*
	 * 当前模板输出注册到布局的变量名
	 */
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

	public function &__get($key)
	{
		return $this->_vars[$key];
	}

	/**
	 * 批量注册变量
	 */
	public function assigns(array $vars)
	{
		$this->_vars = array_merge($this->_vars, $vars);
	}

	/**
	 * 读取视图文件
	 */
	public function reader($view=null)
	{
		$this->_setFile($view);

		$out = $this->_reader();

		if (!$this->_layout) {
			return $out;
		}

		//在有布局时将自身的渲染结果作为布局的一个变量传达
		$this->_layout->{$this->_layout_var} = $out;
		return $this->_layout->reader();
	}

	/**
	 * 设置布局 (外部调用)
	 */
	public function setLayout($layout, $var)
	{
		$this->_layout = new self($layout);
		$this->_layout_var = $var;
	}

	/**
	 * 设置布局 (内部调用)
	 */
	protected function _setLayout($layout, $var)
	{
		if (!$this->_layout) {
			$this->setLayout($layout, $var);
		}
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

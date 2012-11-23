<?php
namespace By;

interface Output
{
	/**
	 * 请求成功时执行
	 */
	function onSuccess(Input $input, array $actions);

	/**
	 * 请求失败时执行
	 */
	function onFailure(Input $input, array $valids);

	/**
	 * 发生异常时执行
	 */
	function onException(\Exception $exception);
}

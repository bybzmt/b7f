<?php
namespace By;

interface Action
{
	/**
	 * 执行动作
	 * (动作要么成功,要么异常)
	 */
	function execute(Input $input);
}

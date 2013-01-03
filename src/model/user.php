<?php

namespace model;

use b7f;
use mapper;

class user
{
	//用户密码密钥
	const USER_SECURITY = 'Aj(Y8R*-z6T u^%Q';

	static private $currentUser;

	/**
	 * 用户登陆
	 *
	 * @param string $user 用户名
	 * @param string $pass 密码
	 *
	 * @return 0 登陆成功
	 *         1 用户不存在
	 *         2 密码错误
	 *         3 用户己被禁用
	 */
	public function login($user, $pass)
	{
		$row = $this->getMapper()->findByUser($user);

		//用户不存在
		if (!$row) {
			return 1;
		}

		//密码错误
		if (md5(self::USER_SECURITY.$row->id.$pass) != $row->pass) {
			return 2;
		}

		if (!$row->access) {
			return 3;
		}

		//记录信息
		$_SESSION['user_id'] = $row->id;

		return 0;
	}

	/**
	 * 判断当前用是否登陆
	 */
	public function isLogin()
	{
		return isset($_SESSION['user_id']);
	}

	/**
	 * 得当前的登陆用户
	 *
	 * @exception 如果失败将抛出异常
	 */
	public function getCurrentUser()
	{
		if (!self::$currentUser) {
			$user = $this->getMapper()->find($_SESSION['user_id']);

			if (!$user) {
				throw new Exception('取得当前用户失败:'.$_SESSION['user_id']);
			}

			self::$currentUser = $user;
		}

		return self::$currentUser;
	}

	/**
	 * 用户登出
	 */
	public function logout()
	{
		unset($_SESSION['user_id']);
	}

	public function getMapper()
	{
		return mapper\user::getInstance();
	}
}

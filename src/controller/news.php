<?php
namespace controller;

use view;
use model;

class news
{
	public function addAction()
	{
		$msg = isset($_GET['msg']) ? $_GET['msg'] : null;

		$token = mt_rand();

		$_SESSION['news_add_token'] = $token;

		$view = new view\news();
		$view->add($token, $msg);
	}

	public function doaddAction()
	{
		$type = isset($_POST['type']) ? (int)$_POST['type'] : 0;
		$title = isset($_POST['title']) ? $_POST['title'] : null;
		$content = isset($_POST['content']) ? $_POST['content'] : null;

		$token = isset($_POST['token']) ? $_POST['token'] : null;
		$s_token = isset($_SESSION['news_add_token']) ? $_SESSION['news_add_token'] : null;

		unset($_SESSION['news_add_token']);

		if (!$token || $token != $s_token) {
			header('Location: '.url('news/add', array('msg'=>'请不要重复提交.')));
			return;
		}

		$model = new model\news();
		$id = $model->add($type, $title, $content);

		header('Location: '.url('news/read', array('id'=>$id)));
	}

	public function listAction()
	{
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 0;

		if ($page < 1) {
			$page = 1;
		}

		$view = new view\news();
		$view->showlist($page);
	}

	public function readAction()
	{
		$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

		$view = new view\news();
		$view->read($id);
	}

	public function editAction()
	{
		$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
		$msg = isset($_GET['msg']) ? trim($_GET['msg']) : null;

		$token = mt_rand();

		$_SESSION['news_add_token'] = $token;

		$view = new view\news();
		$view->edit($id, $token, $msg);
	}

	public function doeditAction()
	{
		$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		$type = isset($_POST['type']) ? (int)$_POST['type'] : 0;
		$title = isset($_POST['title']) ? $_POST['title'] : null;
		$content = isset($_POST['content']) ? $_POST['content'] : null;

		$token = isset($_POST['token']) ? $_POST['token'] : null;
		$s_token = isset($_SESSION['news_add_token']) ? $_SESSION['news_add_token'] : null;

		unset($_SESSION['news_add_token']);

		if (!$token || $token != $s_token) {
			header('Location: '.url('news/edit', array('msg'=>'请不要重复提交.')));
			return;
		}

		$model = new model\news();
		$model->edit($id, $type, $title, $content);

		header('Location: '.url('news/read', array('id'=>$id)));
	}
}

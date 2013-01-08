<?php
namespace controller;

use view;
use model;

class news
{
	public function addAction()
	{
		$msg = empty($_GET['msg']) ? $_GET['msg'] : null;

		$token = mt_rand();

		$_SESSION['news_add_token'] = $token;

		$view = new view\news();
		$view->add($token, $msg);
	}

	public function doaddAction()
	{
		$type = isset($_POST['type']) ? $_POST['type'] : null;
		$title = isset($_POST['title']) ? $_POST['title'] : null;
		$content = isset($_POST['content']) ? $_POST['content'] : null;
		$token = isset($_POST['token']) ? $_POST['token'] : null;

		$s_token = isset($_SESSION['news_add_token']) ? $_SESSION['news_add_token'] : null;

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
			$page = 1
		}

		$view = view\news();
		$view->showlist($page);
	}
}

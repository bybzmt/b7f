<?php

require __DIR__.'/../bootstrap.php';

$to_url = isset($_GET['to_url']) ? $_GET['to_url'] : null;
$msg = isset($_GET['msg']) ? $_GET['msg'] : null;

$view = new b7f\View();
$view->msg = $msg;
$view->to_url = $to_url;

echo $view->reader('login');

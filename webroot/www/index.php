<?php
namespace Example;

use By;

require '../bootstrap.php';

$input = new By\Input\Http(
	INPUT_GET, array(
		'type' => array('filter' => FILTER_SANITIZE_NUMBER_INT),
		'page' => array('filter' => FILTER_SANITIZE_NUMBER_INT),
	)
);

$controller = new By\Controller($input, new Output\Html('index'));

$controller->addValid('valid', new Valid\Index());

$controller->addAction('action', new Action\Index());

$controller->run();

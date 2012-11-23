<?php
namespace By;

class Controller
{
	protected $input;
	protected $output;
	protected $valids = array();
	protected $actions = array();

	function __construct(Input $input, Output $output)
	{
		$this->input = $input;
		$this->output = $output;

		set_exception_handler(array($output, 'onException'));
	}

	function addValid($name, Valid $valid)
	{
			$this->valids[$name] = $valid;
	}

	function addAction($name, Action $action)
	{
		$this->actions[$name] = $action;
	}

	function run()
	{
		$errors = false;
		foreach ($this->valids as $name => $valid) {
			if (!$valid->isValid($this->input)) {
				$errors = true;
			}
		}
		if ($errors) {
			return $this->output->onFailure($this->input, $this->valids);
		}

		foreach ($this->actions as $name => $action) {
			$action->execute($this->input);
		}

		$this->output->onSuccess($this->input, $this->actions);
	}
}

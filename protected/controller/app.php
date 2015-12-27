<?php

class app
{
	private $route;

	public function __construct($path) {
		$this->route = explode('/', $path);
		$this->run();
	}
	private function run() {
		$url = array_shift($this->route);
		if (!preg_match('#^[a-zA-Z0-9.,-]*$#', $url))
			throw new Exception('Invalid path');
		
		$ctrlName = 'ctrl' . ucfirst($url);
		if (!file_exists('protected/controller/'.$ctrlName.'.php') || empty($url)) {
			array_unshift($this->route, $url);
			$this->runController('ctrlIndex');
		} else {
			$this->runController($ctrlName);
		}
	}

	private function runController($ctrlName) {
		include "protected/controller/" . $ctrlName . ".php";
		$ctrl = new $ctrlName();

		if (empty($this->route[0])) $ctrl->index();
		else {
			$method = array_shift($this->route);
			if (method_exists($ctrl, $method)) {
				if (empty($this->route[0]))
				$ctrl->$method();
				else
					call_user_func_array (array($ctrl,$method), $this->route);
			} else
				throw new Exception('Error 404');
		}
	}

}

?>
<?php
abstract class ctrl {
	protected $db;
	protected $tpl;
	protected $isAdmin = FALSE;
	
	public function __construct() {
		$this->db = db::getInstance();
// 		if (isset($_SESSION['user'])) {
// 			$this->user = true;
// 			if ($_SESSION['user']['user_type'] == 'admin') $this->admin = true;
// 		} 
	}

	public function out($tplname,$nested=false) {
	    
		if (!$nested) {
			$this->tpl = $tplname;
			include "protected/tpl/main.php";
		} else
			
			include "protected/tpl/" . $tplname;
	}

	
	public function isAdmin(){
		return $this->isAdmin;
	}
	
	public function logout() {
	    session_destroy();
 	    unset($_SERVER['PHP_AUTH_PW']);
 	    unset($_SERVER['PHP_AUTH_USER']);
	    header("Location: /?index");
	    
	}
	
}
?>
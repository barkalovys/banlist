<?php

 class db {


	private $mysqli;

	static private $instance = NULL;

    private $host = 'localhost';
    private $user = 'root';
    private $pass = 'pass';
    private $db = 'banlist';


	private function __construct(){

		$this->mysqli = new mysqli($this->host, $this->user, $this->pass, $this->db);

		if ($this->mysqli->connect_errno)
			throw new Exception('Database connection error');
	}


	private function __clone(){}



	static public function getInstance(){
		if (!self::$instance){
			self::$instance = new self;
		}
			return self::$instance;

	}
 	public function query($sql) {
		// $db->query("SELECT * FROM sdfd WHERE id = ?",$id);

		$args = func_get_args();

		$sql = array_shift($args);
		$link = $this->mysqli;

		$args = array_map(function ($param) use ($link) {
			return "'".$link->escape_string($param)."'";
		},$args);

		$sql = str_replace(array('%','?'), array('%%','%s'), $sql);

		array_unshift($args, $sql);

		$sql = call_user_func_array('sprintf', $args);


		$this->last = $this->mysqli->query($sql);
		if ($this->last === false) throw new Exception('Database error: '.$this->mysqli->error);

		return $this;
	}

 	public function assoc() {
		return $this->last->fetch_assoc();
	}

	public function insert_id() {
	    return $this->mysqli->insert_id;
	}

	public function all() {
		$result = array();
		while ($row = $this->last->fetch_assoc()) $result[] = $row;
		return $result;
	}
}

?>

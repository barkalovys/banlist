<?php

class log
{
    private $filepath = 'log.txt';
    private $handler = NULL;
    private static $instance = NULL;
    
    private function __construct()
    {
        if (!$this->handler = fopen($this->filepath, 'a'))
            throw new Exception('Failed to open log file');
    }
    
    private function __clone() {}
    
    public static function getInstance(){
        if (!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }
    
    public function write($msg){
        $d = new DateTime();
        $d = $d->format('d.m.y H:i:s');
        return fwrite($this->handler, $d . ' '. $msg . "\n\r");
    }
    
    public function close(){
        return fclose($this->handler);
    }
}

?>
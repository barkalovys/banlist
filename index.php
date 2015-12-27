<?php
include_once 'array2json.php';

function __autoload($className){
    $className = strtolower($className);
    @include_once("protected/library/{$className}.class.php");
    @include_once("protected/controller/{$className}.php");
}

try {
	session_start();
	$app = new app(substr($_SERVER['REQUEST_URI'],2));
}
catch (Exception $e) {

	echo $e->getMessage();

}


<?php

namespace LogMon;

// project entity
class Project
{
	
	protected $properties = array(
		'id' => null, 
		'name' => null, 
		'codeName' => null, 
		'logConfig' => null
	);
	

	public function __construct() {
	}
	
	public function __set($name, $value) {
		// TODO: will be implemented
	}

	public function __get($name) {
		// TODO: will be implemented
	}

}
?>

<?php
namespace LogMon\Manager;

class DBResult
{
	public $success;

	public $message;

	public $code;

	public function __construct($success = null, $message = null, $code = null) {
		$this->success = $success;
		$this->message = $message;
		$this->code = $code;
	}
}

?>

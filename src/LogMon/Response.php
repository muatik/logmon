<?php
namespace LogMon;

class Response implements \JsonSerializable
{
	/**
	 * statusCode 
	 * 1 = successful
	 * 0 = error
	 * 
	 * @var float
	 * @access private
	 */
	private $statusCode = 1;
	private $statusMessage = 'successful';
	private $data;

	public function __construct() 
	{
		$this->data = new \stdClass();
	}

	public function setStatusCode($code)
	{
		$this->statusCode = $code;
	}

	public function setStatusMessage($message)
	{
		$this->statusMessage = $message;
	}

	public function setStatus($code, $message)
	{
		$this->setStatusCode($code);
		$this->setStatusMessage($message);
	}

	public function getStatusMessage()
	{
		return $this->statusMessage;
	}


	/**
	 * assings the given data to the variable specified selector
	 *
	 * Example:
	 * $a = new Response();
	 * $a->setData('user->city->name', 'izmit');
	 * $a->setData('user->city->id', '41');
	 * $a->setData('user->emai', 'muatik@gmail.com');
	 * $a->setData('user->emai->gmail', 'muatik@gmail.com');
	 * $a->setData('user->order->home->address', 'mecidiyeköy');
	 * $a->setData('user->order->home->postcode', '14');
	 * $a->setData('user->books', ['ml', 'cs', 'rs']);
	 * $a->setData('user->wallet', new \stdClass());
	 * print_r(json_decode(json_encode($a)));
	 *
	 *
	 * @param mixed $selector 
	 * @param mixed $data 
	 * @access public
	 * @return void
	 */
	public function setData($selector, $data)
	{
		$this->createDataNode($this->data, $selector, $data);
	}

	private function createDataNode($parent, $selector, $data)
	{
		$nodes = preg_split('/->/', $selector, -1, PREG_SPLIT_NO_EMPTY);
		$current = array_shift($nodes);


		if (count($nodes) > 0) {
			if (!isset($parent->$current))
				$parent->$current = new \stdClass();

			$parent->$current = $this->createDataNode(
				$parent->$current, implode('->', $nodes), $data
			);
		} else {
			$parent->$current = $data;
		}

		return $parent;

	}

	public function set($code, $message, $data)
	{
		$this->setStatus($code, $message);
		$this->setData($data);
	}

	public function jsonSerialize()
	{
		$o = new \stdClass();
		$o->status = new \stdClass();
		$o->status->code = $this->statusCode;
		$o->status->message = $this->statusMessage;
		$o->data = $this->data;
		return $o;
	}

}


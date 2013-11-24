<?php
namespace LogMon\LogReader;

abstract class Entries implements \SeekableIterator, \Countable
{
	private $position = 0;
	
	protected $cursor;

	public function __construct($cursor)
	{
		$this->cursor = $cursor;
		$this->position = 0;
		$this->rewind();
	}

	public function __toString()
	{
		return sprintf("This is a mysql result cursor which holds %d records, "
			. "and the cursor has been seeked at %d",
			$this->count(), $this->key());
	}
}


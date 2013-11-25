<?php
namespace LogMon\LogReader;

use LogMon\LogConfig\IFieldMapping;

abstract class Entries implements \SeekableIterator, \Countable
{
	private $position = 0;
	
	protected $cursor;
	
	protected $fieldMapping;

	public function __construct($cursor, IFieldMapping $fieldMapping)
	{
		$this->fieldMapping = $fieldMapping;
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


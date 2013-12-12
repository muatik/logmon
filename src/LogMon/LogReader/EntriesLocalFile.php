<?php
namespace LogMon\LogReader;

use LogMon\LogConfig\IFieldMapper;

/**
 * Entries Iterator for text file resource
 *
 * @uses Entries
 */
class EntriesLocalFile extends Entries
{
	public function __construct($cursor, IFieldMapper $fieldMapper)
	{
		parent::__construct($cursor, $fieldMapper);
	}

	public function current ()
	{
		$line = fgets($this->cursor);
		$mapped = $this->mapper->mapLine($line);
		$this->position++;
		return $mapped;
	}

	public function rewind ()
	{
		$this->position = 0;
		rewind($this->cursor);
	}

	public function key ()
	{
		return $this->position;
	}

	public function next ()
	{
		// jumping to the next line
		$line = fgets($this->cursor);
		++$this->position;
	}

	public function valid ()
	{
		return feof($this->cursor);
	}

	public function seek ($position)
	{
		return false;
	}

	public function count ()
	{
		return -1;
	}

}


<?php
namespace LogMon\LogReader;

use LogMon\LogConfig\IFieldMapping;

/**
 * Entris Iterator for MySQL Results 
 *
 * WARNING: This class uses PDO statment as a cursor which does not 
 * support seekable/scrollable cursor for MySQL database. Because of this,
 * the constructer will be overriden to make cursor a simple array.
 * So, this might be inefficient for large data results.
 * see: http://stackoverflow.com/questions/278259/is-it-possible-to-rewind-a-pdo-result
 *
 * @uses Entries
 */
class EntriesMysql extends Entries
{
	public function __construct($cursor, IFieldMapping $fieldMapping)
	{
		parent::__construct($cursor, $fieldMapping);
		$this->cursor = $cursor->fetchAll();
	}

	public function current ()
	{
		return $this->fieldMapping->map($this->cursor[$this->position]);
	}

	public function rewind ()
	{
		$this->position = 0;
	}

	public function key ()
	{
		return $this->position;
	}

	public function next ()
	{
		++$this->position;
	}

	public function valid ()
	{
		return isset($this->cursor[$this->position]);
	}

	public function seek ($position)
	{
		if (!isset($this->cursor[$position]))
			throw new OutOfBoundsException("invalid seek position ($position)");

		$this->position = $position;
	}

	public function count ()
	{
		return count($this->cursor);
	}

}

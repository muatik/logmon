<?php
namespace LogMon\LogReader;
require 'Entries.php';
class EntriesMysql extends Entries
{
	public function current ()
	{
		$this->data->data_seek($this->position);
		return $this->data->fetch_array();
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
		return $this->data->data_seek($this->position);
	}

	public function seek ($position)
	{
		if (!$this->data->data_seek($position))
			throw new OutOfBoundsException("invalid seek position ($position)");

		$this->position = $position;
	}

	public function count ()
	{
		return $this->data->num_rows;
	}



}

$a = new EntriesMysql(null);

<?php
namespace LogMon\LogReader;

class EntriesMongodb extends Entries
{

	public function current()
	{
		return $this->cursor->getNext();
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
		return $this->cursor->count() > $this->position;
	}

	public function seek ($position)
	{
		$this->cursor->reset();
		$this->cursor->skip($position);
	}

	public function count ()
	{
		return $this->cursor->count();
	}


}

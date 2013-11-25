<?php
namespace LogMon\LogReader;

class EntriesMongodb extends Entries
{

	public function current()
	{
		return $this->fieldMapping->map($this->cursor->current);
	}

	public function seek ($position)
	{
		$this->cursor->skip($position+1);
	}

	public function count ()
	{
		return $this->cursor->count();
	}


}

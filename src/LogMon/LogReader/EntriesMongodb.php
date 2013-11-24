<?php
namespace LogMon\LogReader;
require 'Entries.php';
class EntriesMongodb extends Entries
{

	public function current()
	{
		return $this->cursor->current();
	}

	public function rewind()
	{
		$this->cursor->rewind();
	}

	public function key()
	{
		return $this->cursor->key();
	}

	public function next()
	{
		return $this->cursor->next();
	}

	public function valid ()
	{
		return $this->cursor->valid();
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
$c = new \Mongo('mongodb://localhost/test');
$db = $c->selectDB('test');
$cursor = $db->tweets->find();
$a = new EntriesMongodb($cursor);

$r  = $a->current();
print_r($r);

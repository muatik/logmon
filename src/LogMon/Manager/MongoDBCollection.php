<?php
namespace LogMon\Manager;

use LogMon\Manager\IDBCollection;
use LogMon\Manager\DBResult;

class MongoDBCollection implements IDBCollection
{
	private $db;

	private $collection;
	
	private $save = false;

	public function __construct($db) {
		$this->db = $db;
	}

	public function setCollection($collection){
		$this->collection = $this->db->selectCollection($collection);
	}

	public function insert(&$object) {
		$result = new DBResult();

		try {
			$this->collection->insert(
				$object, 
				array('safe' => $this->safe)
			);
			$result->success = true;
		} catch (\MongoCursorException $e) {
			$result->success = false;
			$result->message = $e->getMessage();
		}

		// TODO: change the following section with a class
		return $result;
	}

	public function update(Array $criteria, $object) {
		$result = new DBResult();
		try {
			$this->collection->update(
				$criteria, 
				$object,
				array('w' => $this->safe)
			);
			$result->success = true;
		} catch (\MongoCursorException $e) {
			$result->success = false;
			$result->message = $e->getMessage();
		}
		return $result;
	}

	public function delete(Array $criteria) {
		$result = new DBResult();
		try {
			$this->collection->remove($criteria);
			$result->success = true;
		} catch (\MongoCursorException $e) {
			$result->success = false;
			$result->message = $e->getMessage();
		}
		return $result;
	}

	public function find(
		array $criteria = array(), 
		$projection = '*', 
		$limit = null, 
		$skip = null) {

		$cursor= $this->collection->find(
			$criteria
		);

		if ($projection != '*')
			$cursor->projection = $projection;

		if ($limit != null)
			$cursor->limit($limit);

		if ($skip != null)
			$cursor->skip($limit);

		return $cursor;
	}
}

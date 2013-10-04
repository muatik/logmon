<?php
namespace LogMon\Manager;

use LogMon\Manager\IDBCollection;
use LogMon\Manager\DBResult;

class MysqlDBCollection implements IDBCollection
{
	private $db;

	private $collection;
	
	private $save = false;

	public function __construct($db) 
	{
		$this->db = $db;
	}

	public function setCollection($collection)
	{
		$this->collection = $collection;
	}
	
	/**
	 * converts the given object's values for mysql database.
	 * For example, all object and array values will be serialized.
	 * 
	 * @param mixed $object 
	 * @access private
	 * @return void
	 */
	private function convertObjectForQuery($object) 
	{
		$new = array();
		foreach($object as $k => $v) {
			if (is_object($v) || is_array($v))
				$v = serialize($v);
			$new[$k] = $v;
		}
		return $new;
	}


	private function generateSqlFromCriteria(Array $criteria, $conditionType = 'and')
	{
		$sql = array();
		foreach ($criteria as $k => $v)
			if (is_array($v)) 
				$sql[] = " $k in ('".implode($v, "', '")."')";
			else
				$sql[] = "$k = '$v'";

		return implode($sql, $conditionType);
	}

	public function insert(&$object) 
	{
		$result = new DBResult();
		$db = $this->db;

		try {
			$fields = $this->convertObjectForQuery(array_keys($object));
			
			$sql = sprintf('insert into %s (%s) values("%s")',
				$this->collection,
				implode($fields, ', '),
				implode($object, '", "')
			);
			
			$db->query($sql);

			if ($db->affected_rows < 1) {
				$result->success = false;
				$result->message = $db->error;
			} else {
				$result->success = true;
				$object['_id'] = $this->db->insert_id;
			}
		} catch (\Exception $e) {
			$result->success = false;
			$result->message = $e->getMessage();
		}

		return $result;
	}

	public function update(Array $criteria, $object)
	{
		$result = new DBResult();
		try {
			$fields = $this->convertObjectForQuery(array_keys($object));
			$sql = 'update '.$this->collection.' set ';
			foreach($object as $k=>$v)
				$sql .= "$k = '$v', ";

			$sql .= ' where '.$this->generateSqlFromCriteria($criteria);
			echo $sql;
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
			$sql = sprintf(
				'delete from %s where %s',
				$this->collection,
				$this->generateSqlFromCriteria($criteria)
			);
			
			$this->db->query($sql);
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

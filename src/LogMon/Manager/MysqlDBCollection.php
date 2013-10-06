<?php
namespace LogMon\Manager;

use LogMon\Manager\IDBCollection;
use LogMon\Manager\DBResult;
use Doctrine\ORM\Query;

class MysqlDBCollection implements IDBCollection
{
	private $db;

	/**
	 * the active collection
	 * 
	 * @var string
	 * @access private
	 */
	private $collection;


	/**
	 * makes the given criteria fit tomysql's query language.
	 * Each criteria element will be added the given querybuilder 
	 * like the following statement:
	 * $queryBuilder->eq('firstname', 'John');
	 * 
	 * @param \Doctrine\ORM\Query\Builder $queryBuilder 
	 * @param Array $criteria 
	 * @static
	 * @access private
	 * @return \Doctrine\ORM\Query\Expr
	 */
	private static function criteriaToDoctrineExp(Array $criteria)
	{
		// TODO: null elements require implementation
		$expressions = array(
			'eq' => 'equals',
			'neg' => 'notEqual',
			'gt' => 'gt',
			'gte' => 'gte',
			'lt' => 'lt',
			'lte' => 'lte',
			'isNull' => 'exists',
			'isNotNull' => null,
			'in' => 'in',
			'notIn' => 'notIn',
			'like' => null,
			'notLike' => null
		);

		$e = new \Doctrine\ORM\Query\Expr();
		$conditions = array();
		foreach($criteria as $expr) {
			if (!isset($expr[0]))
				throw new \Exception(sprintf('The expression "%s" is not valid for MongoDB', $expr[0]));

			$field = $expr[1];
			$value = $expr[2];
			$expr = $expr[0];

			$conditions[] = call_user_func_array(
				array($e, $expr), 
				array($field, $value)
			);
		}
		
		return call_user_func_array(array($e, 'andX'), $conditions);
	}

	
	/**
	 * __construct 
	 * The db parameter must be either \Doctrine\DBAL\Connection 
	 * or \Doctrine\MongoDB\Database
	 *
	 * @param \Doctrine\DBAL\Connection $db 
	 * @access public
	 * @return void
	 */
	public function __construct($db) 
	{
		$this->db = $db;
	}

	/**
	 * sets the active collection
	 * 
	 * @param string $collection 
	 * @access public
	 * @return void
	 */
	public function setCollection($collection)
	{
		$this->collection = $collection;
	}
	
	/**
	 * inserts the object into the active collection
	 * 
	 * @param mixed $object 
	 * @access public
	 * @return DBResult
	 */
	public function insert(&$object) 
	{
		$result = new DBResult();
		$db = $this->db;

		try {
			$affectedRows = $db->insert($this->collection, $object);
			if ($affectedRows < 1) {
				$result->success = false;
				$result->message = $db->error;
			} else {
				$result->success = true;
				$object['_id'] = $db->lastInsertId();
			}
		} catch (\Exception $e) {
			$result->success = false;
			$result->message = $e->getMessage();
		}

		return $result;
	}

	/**
	 * updates collection entries matching the criteria
	 * 
	 * @param Array $criteria 
	 * @param mixed $object 
	 * @access public
	 * @return DBResult
	 */
	public function update(Array $criteria, $object)
	{
		$result = new DBResult();
		try {
			$queryBuilder = $this->db->createQueryBuilder();
			$queryBuilder->update($this->collection, 'c');
			
			foreach($object as $k => $v)
				$queryBuilder->set($k, ':'.$k);
			
			$queryBuilder->setParameters($object);
			
			$expr = self::criteriaToDoctrineExp($criteria);
			$queryBuilder->add('where', $expr);

			$affectedRows = $queryBuilder->execute();

			if ($affectedRows < 1) {
				$result->success = false;
			} else {
				$result->success = true;
			}
				
		} catch (\MongoCursorException $e) {
			$result->success = false;
			$result->message = $e->getMessage();
		}
		return $result;
	}


	/**
	 * deletes collection entries matching the criteria
	 * 
	 * @param Array $criteria 
	 * @access public
	 * @return DBResult
	 */
	public function delete(Array $criteria)
	{
		$result = new DBResult();

		try {
			$queryBuilder = $this->db->createQueryBuilder();
			$queryBuilder->delete($this->collection);
			
			$expr = self::criteriaToDoctrineExp($criteria);
			$queryBuilder->add('where', $expr);
			
			$affectedRows = $queryBuilder->execute();
			if ($affectedRows < 1) {
				$result->success = false;
			} else {
				$result->success = true;
			}
		} catch (\Exception $e) {
			$result->success = false;
			$result->message = $e->getMessage();
		}
		return $result;
	}

	/**
	 * find and returns collection entries matching the criteria
	 *
	 * @param Array $criteria 
	 * @access public
	 * @return MongoCursor
	 */
	public function find(
		array $criteria = array(), 
		$projection = '*', 
		$limit = null, 
		$skip = null
	) {

		$queryBuilder = $this->db->createQueryBuilder();
		$queryBuilder
			->select($projection)
			->from($this->collection, 'c');

		if (count($criteria) > 0) {
			$expr = self::criteriaToDoctrineExp($criteria);
			$queryBuilder->add('where', $expr);
		}
		
		$cursor = $queryBuilder->execute();
		return $cursor;
	}
}

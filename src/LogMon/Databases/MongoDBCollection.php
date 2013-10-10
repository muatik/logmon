<?php
namespace LogMon\Databases;

use Doctrine\ORM\Query;

class MongoDBCollection implements IDBCollection
{
	// TODO: write documentation for the members of this class
	private $db;

	/**
	 * the active collection
	 * 
	 * @var string
	 * @access private
	 */
	private $collection;

	private $isSafe = true;

	/**
	 * makes the given criteria fit to mongodb's query language.
	 * Each criteria element will be added the given querybuilder 
	 * like the following statement:
	 * $queryBuilder->field('firstname')->equals('John');
	 * 
	 * @param mixed $queryBuilder 
	 * @param Array $criteria 
	 * @static
	 * @access private
	 * @return void
	 */
	private static function criteriaToDoctrineExp(&$queryBuilder, Array $criteria)
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

		// an expr looks something like array('eq', 'firstname', 'John')
		foreach($criteria as $expr) {
			if (!isset($expr[0]))
				throw new \Exception(sprintf('The expression "%s" is not valid for MongoDB', $expr[0]));

			$field = $expr[1];
			$value = $expr[2];
			$expr = $expressions[$expr[0]];

			if ($field == '_id')
				$value = new \MongoID($value);

			$queryBuilder->field($field)->$expr($value);
		}
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
		$this->collection = $this->db->selectCollection($collection);
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

		try {
			$this->collection->insert(
				$object, 
				array('safe' => $this->isSafe)
			);
			$result->success = true;
		} catch (\MongoCursorException $e) {
			$result->success = false;
			$result->message = $e->getMessage();
		}

		// TODO: change the following section with a class
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
	public function update(Array $criteria, $object) {
		$result = new DBResult();
		
		try {
			$queryBuilder = $this->collection->createQueryBuilder();
			$queryBuilder->update();

			foreach($object as $k => $v)
				$queryBuilder->field($k)->set($v);

			self::criteriaToDoctrineExp($queryBuilder, $criteria);
			$q = $queryBuilder->getQuery();
			
			$q->execute();
			$result->success = true;
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
	public function delete(Array $criteria) {
		$result = new DBResult();
		try {
			$queryBuilder = $this->collection->createQueryBuilder();
			$queryBuilder->remove();

			self::criteriaToDoctrineExp($queryBuilder, $criteria);
			$q = $queryBuilder->getQuery();
			$q->execute();
			$result->success = true;
		} catch (\MongoCursorException $e) {
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
		$skip = null) {
		
		$queryBuilder = $this->collection->createQueryBuilder();
		
		self::criteriaToDoctrineExp($queryBuilder, $criteria);

		if ($projection != '*')
			$queryBuilder->select($projection);

		if ($limit != null)
			$queryBuilder->limit($limit);

		if ($skip != null)
			$queryBuilder->skip($limit);
		
		$query= $queryBuilder->getQuery();
		$cursor = $query->execute();
		return $cursor;
	}
}

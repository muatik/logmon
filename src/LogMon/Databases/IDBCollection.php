<?php
namespace LogMon\Databases;

interface IDBCollection
{

	/**
	 * __construct 
	 * the db parameter is either \Doctrine\DBAL\Connection 
	 * or \Doctrine\MongoDB\Database
	 * 
	 * @param mixed $db 
	 * @access public
	 * @return void
	 */
	public function __construct($db);

	public function setCollection($collection);
	
	public function insert(&$object);
	
	public function update(Array $criteria, $object);

	public function delete(Array $criteria);

	/**
	 * Expression List
	 * Each criteria element must be one of the following expressions.
	 * You have to write expressions like doctrine's mysql expressions.
	 * You had better have a look at this reference: http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/query-builder.html#the-expr-class
	 *
	 * <code>
	 * 	$criteria = array(
	 * 	  'eq' => array('firsname' => 'John'),
	 * 	  'lt' => array('age' => 25)
	 * 	);
	 * 	The above criteria will be interpreted like this: 
	 * 	  firstname = 'John' and age < 25
	 * </code>
	 *
	 * Mysql Expression, MongoDB Expression
 	 * eq, equals
	 * neg, notEqual
  	 * lt, lt
	 * gt, gt
	 * lte, lte
	 * gte, gte
	 * isNull
	 * isNotNull
	 * exists, exists
	 * in, in
	 * notIn, notIn
	 * like, --
	 * notLike, --
	 */
	public function find(
		Array $criteria, 
		$projection = '*', 
		$limit = null, 
		$skip = null);
}
?>

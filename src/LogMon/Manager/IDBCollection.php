<?php
namespace LogMon\Manager;

interface IDBCollection
{

	public function __construct($db);

	public function setCollection($collection);
	
	public function insert(&$object);
	
	public function update(Array $criteria, $object);

	public function delete(Array $criteria);

	/**
	 * criteria = array(
	 *  'field1' => 'value'; // f=value
	 *  'field2' => '*value'; // f=*value
	 *  'field3' => '*value*'; // f=*value*
	 *  'field4' => array(
	 *    'value1', 'value2'
	 *   )'*value*'; // f=value1 or f=value2
	 * )
	 */
	public function find(
		Array $criteria, 
		$projection = '*', 
		$limit = null, 
		$skip = null);
}
?>

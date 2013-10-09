<?php

namespace LogMon\Projects;

use LogMon\Databases\IDBCollection;
use LogMon\LogConfig;

/**
 * The class Projects manages CRUD operations for projects.
 * 
 * @author Tobias Schlitt <toby@php.net> 
 */
class Manager
{
	
	/**
	 * the name of database collection/table
	 * 
	 * @var string
	 * @access private
	 */
	private $collection = 'projects';
	
	/**
	 * database connection service
	 * 
	 * @var IDBCollection
	 * @access private
	 */
	private $db;

	/**
	 * the dependency container object
	 * 
	 * @var \Silex\Application $app
	 * @access private
	 */
	private $app;


	public function __construct(\Silex\Application $app, IDBCollection $db) 
	{
		$this->app = $app;
		$db->setCollection($this->collection);
		$this->db = $db;
	}

	/**
	 * serialize the parameters of the log configuration to be convenient 
	 * for different supported storage types. So, while reading 
	 * from the storage, this field must be converted to a logConfig object.
	 * 
	 * @param \LogMon\LogConfig\IConfig $logConfig 
	 * @access private
	 * @return json
	 */
	private function serializeLogConfig(\LogMon\LogConfig\IConfig $logConfig)
	{
		return json_encode($logConfig);
	}

	/**
	 * Register a new project permanently.
	 * 
	 * @param Project $project 
	 * @access public
	 * @return boolean
	 */
	public function register(Project $project) {
		
		try {
			$project->validate();
		} catch (\Exception $e) {
			throw $e;
		}

		$object = $project->getProperties();
		if ($object['_id'] == null)
			unset($object['_id']);
		
		$object['logConfig'] = $this->serializeLogConfig($object['logConfig']);

		$result = $this->db->insert($object);
		if ($result->success != true)
			throw new \Exception('The project could not be saved. Because: '
				.$result->message);

		$project->_id = $object['_id'];

		return true;
	}

	/**
	 * Updates the project in the database.
	 * This method deletes records whose id value matches the id of 
	 * the given project.
	 * 
	 * @param Project $project 
	 * @access public
	 * @return boolean
	 */
	public function update(Project $project) {
		try {
			$project->validate();
		} catch (\Exception $e) {
			throw $e;
		}

		$object = $project->getProperties();
		$id = $object['_id'];
		unset($object['_id']);
		
		$object['logConfig'] = $this->serializeLogConfig($object['logConfig']);

		$criteria = array(
			array('eq', '_id', $id)
		);

		$result = $this->db->update($criteria, $object);

		if ($result->success != true)
			throw new \Exception($result->message);

		return true;
	}

	/**
	 * Deletes the given project permanently.
	 * This method deletes records whose id value matches the id of 
	 * the given project.
	 * 
	 * @param Project $project 
	 * @access public
	 * @return boolean
	 */
	public function delete(Project $project) {
		return $this->deleteById($project->_id);
	}

	/**
	 * deletes the project associated with the given id. 
	 * 
	 * @param mixed $id 
	 * @access public
	 * @return boolean
	 */
	public function deleteById($id) {
		$criteria = array(
			array('eq', '_id', $id)
		);

		$result = $this->db->delete($criteria);

		if ($result->success != true)
			throw new \Exception($result->message);

		return true;
	}

	/**
	 * Fetchs all projects from the database and returns them.
	 * Each element in the result array is an instance of the project class.
	 *
	 * @access public
	 * @return array
	 */
	public function getAll() {
		
		$criteria = array(
			/*array("eq", "_id", "3"),
			array("eq", "name", '"mustafa"')*/
		);
		$records = $this->db->find($criteria);
		$projects = array();

		foreach($records as $i) {
			
			$p = new Project();
			foreach($i as $key => $value)
				$p->$key = $value;

			// in view of storing the logConfig as a json string, we need to 
			// create an appropriate logConfig object from this json.
			// this is something like waking up the logConfig object.
			$p->logConfig = \LogMon\LogConfig\Manager::build(
				$this->app, 
				json_decode($p->logConfig)
			);

			$projects[] = $p;
		}

		return $projects;
	}
}


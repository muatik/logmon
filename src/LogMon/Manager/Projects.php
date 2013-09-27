<?php

namespace LogMon\Manager;

use LogMon\Model\Project;

class Projects
{
	
	/**
	 * the name of database collection/table
	 * 
	 * @var string
	 * @access private
	 */
	private $collection = 'projects';
	
	private $db;
	
	public function __construct(IDBCollection $db) {
		$db->setCollection($this->collection);
		$db->safe = true;
		$this->db = $db;
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
			$project->isValid();
		} catch (\Exception $e) {
			throw new \Exception('dd');
		}

		$object = $project->getProperties();
		if ($object['_id'] == null)
			unset($object['_id']);

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
		$result = $this->db->update(
			array('_id' => $project->_id),
			$project
		);

		if ($result->success != true)
			throw new Exception($result->error->message);

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
		$result = $this->db->delete(
			array('_id' => $project->_id)
		);

		if ($result->success != true)
			throw new Exception($result->error->message);

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
		$records = $this->db->find();
		$projects = array();

		foreach($records as $i) {
			
			$p = new Project();
			foreach($i as $key => $value)
				$p->$key = $value;

			$projects[] = $p;
		}

		return $projects;
	}
}

?>

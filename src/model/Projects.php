<?php

namespace LogMon;

class Projects
{
	
	/**
	 * the name of database collection/table
	 * 
	 * @var string
	 * @access protected
	 */
	protected $collection = 'projects';
	
	protected $db;

	public function createFromJSON(string $json) {
		$object = json_encode($json);
		$project = new Project();

		try {
			$project->name = $object->name;
			$project->codeName= $object->codeName;
			$project->logConfig = $object->logConfig;
		} catch (Exception $e) {
			return $e;
		}

		return $project;
	}

	public function insert(Project $project) {
		$result = $this->db->insert($project);
		if ($result->success != true)
			throw new Exception($result->error->message);

		return true;
	}

	public function update(Project $project) {
		$result = $this->db->update($project->id, $project);
		if ($result->success != true)
			throw new Exception($result->error->message);

		return true;
	}

	public function delete(Project $project) {
		$result = $this->db->delete($project->id);
		if ($result->success != true)
			throw new Exception($result->error->message);

		return true;
	}

	public function getAll() {
		$records = $this->db->find();
		$projects = array();
		foreach($records as $i)
			$projects[] = new Project($i);

		return $projects;
	}
}

?>

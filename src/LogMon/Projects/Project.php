<?php
namespace LogMon\Projects;

/**
 * The Project Entity which represents an individual, user defined project.
 * 
 * @package LogMon\Model
 * @version $id$
 * @author Mustafa Atik<muatik@gmail.com> 
 * @license Apache 2.0
 */
class Project
{
	
	/**
	 * the properties of the project.
	 * 
	 * @var array
	 * @access protected
	 */
	protected $properties = array(
		'_id' => null, 
		'name' => null, 
		'codeName' => null, 
		'logConfig' => null
	);
	

	
	/**
	 * the setter method which handles the properties of the project.
	 * 
	 * @param string $name 
	 * @param mixed $value 
	 * @access public
	 * @return void
	 */
	public function __set($name, $value) 
	{
		
		if (array_key_exists($name, $this->properties)) {
			$method = 'set'.ucfirst($name);
			try {
				call_user_func(array($this, $method), $value);
			} catch(InvalidArgumentException $e) {
				throw $e;
			}
		} 
		
	}
	
	/**
	 * the getter which handles the properties of the project.
	 * 
	 * @param string $name 
	 * @access public
	 * @return void
	 */
	public function __get($name) 
	{
		if (isset($this->properties[$name])) {
			return $this->properties[$name];
		}
		
		throw new \InvalidArgumentException(
			sprintf('Property of project "%s" is not defined.', $name)
		);
	}

	/**
	 * sets the id of the project. 
	 * 
	 * @param string $id 
	 * @access private
	 * @return boolean
	 */
	private function set_Id($id) 
	{
		if (!empty($id)) {
			$this->properties['_id'] = $id;
			return true;
		}

		throw new \InvalidArgumentException('The project id cannot be empty.');
	}

	/**
	 * sets the name of the project.  
	 * 
	 * @param string $name 
	 * @access private
	 * @return boolean
	 */
	private function setName($name) 
	{
		if (mb_strlen(trim($name)) > 1) {
			$this->properties['name'] = $name;
			return true;
		}

		throw new \InvalidArgumentException(
			'The project\'s name must be at least 2 letters. It was: '. $name);
	}

	/**
	 * sets the code name of the project.
	 * 
	 * @param string $codeName 
	 * @access private
	 * @return boolean
	 */
	private function setCodeName($codeName)
	{
		if (mb_strlen(trim($codeName)) > 1) {
			$this->properties['codeName'] = $codeName;
			return true;
		}

		throw new \InvalidArgumentException(
			'The project\'s code name must be at leat 2 letters. It was: '.$codeName);
	}
	
	/**
	 * sets the log configuration of the project.
	 * 
	 * @param mixed $logConfig 
	 * @access private
	 * @return boolean
	 */
	private function setLogConfig($logConfig)
	{
		// TODO: logConfig must be validated before assignment
		$this->properties['logConfig'] = $logConfig;
	}
	
	/**
	 * checks whether the project is valid or not 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function validate() {
		$properties = $this->properties;
		unset($properties['_id']);

		foreach($properties as $k => $i) {
			if (empty($i))
				throw new \Exception(
					sprintf('The value of "%s" is null. This is not acceptable.', $k)
				);
		}

		return true;
	}


	/**
	 * returns the properties of the project as an array.
	 * 
	 * @access public
	 * @return array
	 */
	public function getProperties() {
		return $this->properties;
	}


	/**
	 * Fiils the object with the given raw data. This method travers the given
	 * json data and matched elements with the properties will be assigned.
	 *
	 * $rawObject= stdClass(
	 *   '_id' = '23ad12', 
	 *   'name' = 'projectname', 
	 *   'codeName' = 'codename',
	 *   'logConfig' = Logmon\LogConfig\IConfig
	 * )
	 * 
	 * @param stdClass $rawObject
	 * @access public
	 * @return boolean
	 */
	public function initFromObject($rawObject)
	{
		foreach($rawObject as $key => $value)
			if (array_key_exists($key, $this->properties))
				$this->$key = $value;

		return true;
	}

}

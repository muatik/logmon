<?php
namespace LogMon\LogConfig;

/**
 * The base class of log config classes
 * 
 * @abstract
 * @package LogMong\LogConfig;
 */
abstract class BaseLogConfig
{
	/**
	 * storage type 
	 * This variable will be overriden in derived classes.
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $storageType;

	/**
	 * the configuration properties
	 * 
	 * @var array
	 * @access protected
	 */
	protected $properties = array(
		'fieldMapping' => null // will be initialized in __construct()
	);


	public function __construct($data = null) 
	{
		$this->properties['fieldMapping'] = $this->createFieldMapping();
		if ($data != null)
			$this->fromJson($data);
	}


	/**
	 * instantiates a field mapper object
	 * 
	 * @access protected
	 * @return FieldMapper
	 */
	protected function createFieldMapping() 
	{
		return new FieldMapper();
	}


	/**
	 * returns the value of the storage type
	 * 
	 * @access public
	 * @return string
	 */
	public function getStorageType()
	{
		return $this->storageType;
	}

	/**
	 * validates the configuration
	 * If not valid, an exception will be thrown.
	 *
	 * @access public
	 * @return boolean
	 * @throws \Exception
	 */
	public function validate()
	{
		foreach($this->properties as $k => $i) {
			if ($i === null)
				throw new \Exception(
					sprintf('The value of "%s" is null. This is not acceptable.', $k)
				);
		}

		$isMappingValid = $this->properties['fieldMapping']->validate();
		return $isMappingValid;
	}

	/**
	 * checks whether the log source is accesssible or not
	 * If not, an exception will be thrown. Otherwise it returns true.
	 * 
	 * @access public
	 * @return boolean
	 */
	public function test() 
	{
		return $this->getConnection();
	}


	/**
	 * the setter method which handles the properties of the project.
	 * 
	 * @param string $name 
	 * @param mixed $value 
	 * @access public
	 * @return void
	 * @throws \Exception
	 */
	public function __set($name, $value) 
	{
		if (array_key_exists($name, $this->properties)) {
			$method = 'set'.ucfirst($name);
			try {
				call_user_func(array($this, $method), $value);
			} catch(\InvalidArgumentException $e) {
				throw $e;
			}
		} 
	}

	/**
	 * 
	 * @param string $parameter
	 * @access protected
	 * @return void
	 * @throws \Exception
	 */
	protected function setParameter($parameter, $value)
	{
		if (mb_strlen($value) == 0)
			throw new \InvalidArgumentException(
				sprintf('The config parameter "%s" cannot be empty.', $parameter)
			);

		$this->properties[$parameter] = $value;
	}


	public function setFieldMapping(Array $mappings)
	{
		$fieldMapping = $this->createFieldMapping();
		foreach ($mapping as $field => $mapping)
			$fieldmapping->setFieldMapping($field, $mapping);
		
		$this->properties['fieldMapping'] = $fieldMapping;
	}

	/**
	 * the getter which handles the properties of the project.
	 * 
	 * @param string $name 
	 * @access public
	 * @return void
	 * @throw \InvalidArgumentException
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
	 * returns a readable form of the logConfig object 
	 * 
	 * @access public
	 * @return string
	 */
	public function __toString() 
	{
		return $this->toJson($this);
	}
	
	/**
	 * exports the object's properties 
	 * 
	 * @access public
	 * @return json
	 */
	public function toJson($encode = true)
	{
		$data = $this->properties;
		$data['storageType'] = $this->storageType;
		if (is_object($data['fieldMapping']))
			$data['fieldMapping'] = json_decode(
				$this->properties['fieldMapping']->toJson());

		return ($encode ? json_encode($data) : $data);
	}

	/**
	 * loads this object's properties from the json.
	 * This is useful, for example, when you need wake up this object
	 * from json string. It is legiimate to call this method with the value 
	 * returned from __toString(). 
	 *
	 * <code>
	 * 	$jsonObject = (string) $logConfig(); // calls __toString()
	 * 	$newLogConfig->loadFromJson($jsonObject);
	 * </code>
	 * 
	 * @param string $jsonObject 
	 * @access public
	 * @return void
	 * @throws \InvalidArgumentException If the json object does not include required parameters.
	 */
	public function fromJson($jsonObject)
	{
		$jsonObject = (!is_object($jsonObject) ? json_decode($jsonObject) : $jsonObject);
		foreach ($this->properties as $parameter => $value) {
			if (!isset($jsonObject->$parameter))
				throw new \InvalidArgumentException(sprintf(
					"The given configuration does not include the required prarameter '%s'",
				   	$parameter
				));
			
			if ($parameter == 'fieldMapping') {
				$fieldMapping = $this->createFieldMapping();
				$fieldMapping->fromJson($jsonObject->$parameter);
				$this->properties[$parameter] = $fieldMapping;
			} else {
				$this->properties[$parameter] = $jsonObject->$parameter;
			}
		}
	}
}

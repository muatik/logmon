<?php
namespace LogMon\LogConfig;

class FieldMapping
{
	/**
	 * the list of the field.
	 * 
	 * @var array
	 * @access protected
	 */
	protected $fields = array(
		'unique' => array('fieldName' => null, 'regex' => '.*'),
		'date' => array('fieldName' => null, 'regex' => '.*'),
		'type' => array('fieldName' => null, 'regex' => '.*'),
		'message' => array('fieldName' => null, 'regex' => '.*'),
	);

	public function __construct()
	{
		$defaultMapping = new \stdClass();
		$defaultMapping->fieldName = null;
		$defaultMapping->regex = '*';
		foreach ($this->fields as $field => $val)
			$this->fields[$field] = $defaultMapping;
	}

	public function __get($name)
	{
		if (isset($this->fields[$name])) {
			return $this->fields[$name];
		}
		
		throw new \InvalidArgumentException(
			sprintf('Property of project "%s" is not defined.', $name)
		);
	}
	
	/**
	 * checks whether the mapping is valid or not.
	 * 
	 * @param object $mapping 
	 * @access public
	 * @return boolean
	 */
	public function isMappingValid($mapping)
	{
		return isset($mapping->fieldName, $mapping->regex);
	}
	
	/**
	 * sets the given mapping to the field
	 * 
	 * @param string $field 
	 * @param object $mapping 
	 * @access public
	 * @return void
	 * @throws if the field is unknown or the mapping is not valid.
	 */
	public function setFieldMapping($field, $mapping)
	{
		if (!isset($this->fields[$field]))
			throw new \InvalidArgumentException(sprintf(
				"The field '%s' is unknown.", $field));
		
		if (!$this->isMappingValid($mapping))
			throw new \InvalidArgumentException(sprintf("The mapping of the '.
			'field '%s' must have both fieldName and regex.", $field));

		$this->fields[$field] = $mapping;
	}
	
	/**
	 * loads the field mapping from the given json 
	 * 
	 * @param object|string $jsonObject 
	 * @access public
	 * @return void
	 * @throws if any required field does not exists
	 */
	public function loadFromJson($jsonObject)
	{
		if (!is_object($jsonObject))
			$jsonObject = json_decode($jsonObject);

		foreach ($this->fields as $parameter => $value) {
			if (!isset($jsonObject->$parameter))
				throw new \InvalidArgumentException(sprintf(
					"The given field mapping does not include the required prarameter '%s'",
				   	$parameter
				));
			
			$this->setFieldMapping($parameter, $jsonObject->$parameter);
		}
	}

	/**
	 * validates the field mapping
	 * 
	 * @access public
	 * @return boolean
	 * @throws if any mapping is invalid
	 */
	public function validate()
	{
		foreach ($this->fields as $field => $mapping) 
			if (!$this->isMappingValid($mapping))
				throw new \InvalidArgumentException(sprintf(
					"The mapping of the field '%s' is invalid", $field));
		
		return true;
	}

	/**
	 * exports the data of the field mapping
	 * 
	 * @access public
	 * @return array
	 */
	public function export()
	{
		return $this->fields;
	}
}

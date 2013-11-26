<?php
namespace LogMon\LogConfig;

abstract class BaseFieldMapping implements IFieldMapping
{
	/**
	 * the list of the field.
	 * 
	 * @var array
	 * @access protected
	 */
	protected $fields = array();

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
	 * @implements IFieldMapping
	 */
	public function isMappingValid($mapping)
	{
		return isset($mapping->fieldName, $mapping->regex);
	}
	
	/**
	 * @implements IFieldMapping
	 */
	public function setFieldMapping($field, $mapping)
	{
		if (!isset($this->fields[$field]))
			throw new \InvalidArgumentException(sprintf(
				"The field '%s' is unknown.", $field));
		
		if (!$this->isMappingValid($mapping))
			throw new \InvalidArgumentException(sprintf("The mapping of the ".
			"field '%s' must have both fieldName and regex.", $field));

		$this->fields[$field] = $mapping;
	}
	

	/**
	 * @implements IFieldMapping
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
	 * maps the given array data
	 * 
	 * @param Array $data 
	 * @access public
	 * @return Array
	 */
	public function map(Array $data)
	{
		$mapped = array();
		foreach($this->fields as $field => $mapping) {
			$statement = $data[$mapping->fieldName];
			preg_match('/'.$mapping->regex.'/i', $statement, $matches);
			if (isset($matches[1])) {
				$mapped[$field] = $matches[1];
			} else {
				$mapped[$field] = '(ERROR: not matched)';
			}
		}
		return $mapped;
	}


	/**
	 * @implements IFieldMapping
	 */
	public function fromJson($jsonObject)
	{
		if (is_string($jsonObject))
			$jsonObject = json_decode($jsonObject);

		foreach ($this->fields as $parameter => $value) {
			if (!isset($jsonObject->$parameter))
				throw new \InvalidArgumentException(sprintf(
					"The given field mapping does not include the required prarameter '%s'",
				   	$parameter
				));
			
			$this->setFieldMapping($parameter, $jsonObject->$parameter);
		}
		return true;
	}


	/**
	 * @implements IFieldMapping
	 */
	public function toJson()
	{
		return json_encode($this->fields);
	}


}

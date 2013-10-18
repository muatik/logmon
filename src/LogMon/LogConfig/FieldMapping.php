<?php
namespace LogMon\LogConfig;

class FieldMapping
{
	protected $fields = array(
		'unique' => array('fieldName' => null, 'regex' => '*'),
		'date' => array('fieldName' => null, 'regex' => '*'),
		'type' => array('fieldName' => null, 'regex' => '*'),
		'message' => array('fieldName' => null, 'regex' => '*'),
	);

	public function __get($name)
	{
		if (isset($this->fields[$name])) {
			return $this->fields[$name];
		}
		
		throw new \InvalidArgumentException(
			sprintf('Property of project "%s" is not defined.', $name)
		);
	}
	
	public function isMappingValid($mapping)
	{
		return isset($mapping['fieldName'], $mapping['regex']);
	}
	
	public function setFieldMapping($field, $mapping)
	{
		if (!isset($this->fields[$field]))
			throw new \InvalidArgumentException(sprintf(
				"The field '%s' is unexpected.", $field));
		
		if (!$this->isMappingValid())
			throw new \InvalidArgumentException(sprintf("The mapping of the '.
				'field '%s' must have both fieldName and regex.", $field));

		$this->fields[$field] = $mapping;
	}

	public function validate()
	{
		foreach ($this->fields as $field => $mapping) 
			if (!$this->isMappingValid($mapping))
				throw new \InvalidArgumentException(sprintf(
					"The mapping of the field '%s' is invalid"
				));
		
		return true;
	}

	public function export()
	{
		return $this->fields;
	}
}

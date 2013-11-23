<?php
namespace LogMon\LogConfig;

/**
 * The field mapping class specialized for text files
 * 
 * @uses BaseFieldMapping
 */
class FieldMappingTextFile extends BaseFieldMapping
{
	/**
	 * the list of the field.
	 * 
	 * @var array
	 * @access protected
	 * @overrides
	 */
	protected $fields = array(
		'unique' => array('regex' => '.*'),
		'date' => array('regex' => '.*'),
		'type' => array('regex' => '.*'),
		'message' => array('regex' => '.*'),
	);

	public function __construct()
	{
		$defaultMapping = new \stdClass();
		$defaultMapping->regex = '*';
		foreach ($this->fields as $field => $val)
			$this->fields[$field] = $defaultMapping;
	}

	/**
	 * @implements IFieldMapping
	 */
	public function isMappingValid($mapping)
	{
		return isset($mapping->regex);
	}
	

}


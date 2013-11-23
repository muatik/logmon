<?php
namespace LogMon\LogConfig;

/**
 * The field mapping class specialized for Mysql
 * 
 * @uses BaseFieldMapping
 */
class FieldMappingMysql extends BaseFieldMapping
{
	/**
	 * the list of the field.
	 * 
	 * @var array
	 * @access protected
	 * @overrides
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
	
}

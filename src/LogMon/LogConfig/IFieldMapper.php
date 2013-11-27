<?php
namespace LogMon\LogConfig;

interface IFieldMapper
{
	
	/**
	 * checks whether the mapping is valid or not.
	 * 
	 * @param object $mapping 
	 * @access public
	 * @return boolean
	 */
	public function isMappingValid($mapping);
	
	/**
	 * sets the given mapping to the field
	 * 
	 * @param string $field 
	 * @param object $mapping 
	 * @access public
	 * @return void
	 * @throws if the field is unknown or the mapping is not valid.
	 */
	public function setFieldMapping($field, $mapping);
	

	/**
	 * validates the field mapping
	 * 
	 * @access public
	 * @return boolean
	 * @throws if any mapping is invalid
	 */
	public function validate();


	/**
	 * maps the given data
	 * 
	 * @param Array $data 
	 * @access public
	 * @return Array
	 */
	public function map(Array $data);

	/**
	 * loads the field mapping from the given json 
	 * 
	 * @param object|string $jsonObject 
	 * @access public
	 * @return void
	 * @throws if any required field does not exists
	 */
	public function fromJson($jsonObject);


	/**
	 * exports the data of the field mapping
	 * 
	 * @access public
	 * @return array
	 */
	public function toJson();
}

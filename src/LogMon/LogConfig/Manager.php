<?php

class LogConfigManager
{
	public static function build(Project $project)
	{
		// projenin konfiğini okur, doğrular ve işlemi yapar.
	}
}


abstract class LogConfigBase
{
	private $storageType;

	public function validate() 
	{
		// entity validation
	}

	/**
	 * checks whether the log source is readable or not.
	 * If not, an exception will be thrown. Otherwise it returns true.
	 * 
	 * @access public
	 * @return boolean
	 */
	public function test() 
	{
		// will be implemented in each individual config type class.
	}


	/**
	 * the setter method which handles the properties of the project.
	 * 
	 * @param string $name 
	 * @param mixed $value 
	 * @access public
	 * @return void
	 */
	public function __set($name, $value) {
		
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
	public function __get($name) {
		if (isset($this->properties[$name])) {
			return $this->properties[$name];
		}
		
		throw new InvalidArgumentException(
			sprintf('Property of project "%s" is not defined.', $name)
		);
	}
}

class LogConfigMysql extends LogConfigBase
{

}

class LogConfigMongoDB extends LogConfigBase
{

}

class LogConfigText extends LogConfigBase
{
	/**
	 * the file system path of the log
	 * 
	 * @var string
	 * @access private
	 */
	private $properties = array(
		'filePath' => ''
	);
	
	/**
	 * checks whether the log file does exists and is readable.
	 * If not, an exception will be thrwon.
	 *
	 * @access public
	 * @return boolean
	 */
	public function test() 
	{
		$this->validate();
		if (file_exists($this->filePath))
			throw new \Exception(
				sprintf('The file "%s" does not exists.', $this->filePath)
			);
		
		if (is_readable($this->filePath))
			throw new \Exception(
				sprintf('The file "%s" is not readable.', $this->filePath)
			);

		return $true;
	}
}

?>

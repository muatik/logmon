<?php
namespace LogMon\LogConfig;

/**
 * Base 
 * 
 * @abstract
 * @package LogMong\LogConfig;
 */
abstract class Base
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
	protected $properties = array();
	
	/**
	 * the dependency container object 
	 *
	 * @var Silex\Application
	 * @access protected
	 */
	protected $app;
	
	public function __construct(\Silex\Application $app) 
	{
		$this->app = $app;
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
			if (empty($i))
				throw new \Exception(
					sprintf('The value of "%s" is null. This is not acceptable.', $k)
				);
		}
		return true;
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
		
		throw new InvalidArgumentException(
			sprintf('Property of project "%s" is not defined.', $name)
		);
	}

	/**
	 * returns a readable form of the logConfig object 
	 * 
	 * @access public
	 * @return string
	 */
	public function __toString() {
		return json_encode($this->properties);
	}
}

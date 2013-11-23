<?php
namespace LogMon\LogConfig;

class Manager
{
	/**
	 * builds a logConfig object from raw object
	 * 
	 * @param \Silex\Application $app 
	 * @param object $project 
	 * @static
	 * @access public
	 * @return LogMon\LogConfig\IConfig
	 * @trows If storage type is unknown or logConfig cannot be created.
	 */
	public static function build(\Silex\Application $app, $rawConfig)
	{
		if (!is_object($rawConfig)) 
			throw new \InvalidArgumentException(sprintf(
				"The log config cannot be built because the argument does not seem like an object. It is:'%s'",
				$rawConfig
			));
		
		switch ($rawConfig->storageType) {
			case 'localFile':
				$logConfig = new ConfigLocalFile($app);
				break;
			case 'mongodb':
				$logConfig = new ConfigMongodb($app);
				break;
			case 'mysql':
				$logConfig = new ConfigMysql($app);
				break;
			default:
				throw new \Exception(
					sprintf("The storage type is unknown: '%s'", 
						$rawConfig->storageType)
				);
		}

		/**
		 * from now we do not need storage type in the class 
		 * beucase the logconfig class itself knows storage type.
		 */
		unset($rawConfig->storageType);
		$logConfig->fromJson($rawConfig);

		try{
			$logConfig->test();
			return $logConfig;
		} catch (\Exception $e) {
			throw new \Exception('The logconfig is not valid. ' . $e->getMessage());
		}
	}
}

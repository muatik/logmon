<?php
namespace LogMon\LogConfig;

class ConfigMongodb extends ConfigBase
{
	private $storageType = 'mongodb';

	/**
	 * the file system path of the log
	 * 
	 * @var array
	 * @access private
	 */
	private $properties = array(
		'host' => '',
		'port' => '27017',
		'username' => '',
		'password' => '',
		'databaseName' => '',
		'collectionName' => ''
	);

	private function setHost($host) 
	{
		$this->setParameter('host', $host);
	}

	private function setPort($host) 
	{
		$this->setParameter('port', $host);
	}

	private function setUsername($host) 
	{
		$this->setParameter('username', $host);
	}

	private function setPassword($host) 
	{
		$this->setParameter('password', $host);
	}

	private function setDatabaseName($host) 
	{
		$this->setParameter('databaseName', $host);
	}

	private function setCollectionName($host) 
	{
		$this->setParameter('collectionName', $host);
	}

	/**
	 * sets the file system path of the log.
	 * If the given path is not valid, an exception will be thrown.
	 * 
	 * @param string $filePath 
	 * @access private
	 * @return void
	 */
	private function setParameter($parameter, $value)
	{
		if (mb_strlen($value) == 0)
			throw new InvalidArgumentException(
				sprintf('The config parameter %s cannot be empty.', $value)
			);

		$this->properties[$parameter] = $value;
	}

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
		$conf = $this->properties;
		$connParams = array(
			'host' => $conf['host'],
			'port' => $conf['port'],
			'username' => $conf['username'],
			'password' => $conf['password'],
			'database' => $conf['database']
		);

		// test connectivity through the doctrine's dbal
		$conn = $this->app['db.mongodb.getConnection']($connParams);
		return $conn->isConnected();
	}
}

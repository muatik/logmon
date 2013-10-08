<?php
namespace LogMon\LogConfig;

/**
 * The class ConfigMysql manages log configurations on mysql database.
 * 
 * @uses Base
 * @package LogMon\LogConfig
 */
class ConfigMysql
   	extends Base
	implements IConfig
{

	/**
	 * storage type
	 *
	 * @overrides
	 * @var string
	 * @access protected
	 */
	protected $storageType = 'mysql';

	/**
	 * the configuration of mysql connection
	 * 
	 * @var array
	 * @access protected
	 */
	protected $properties = array(
		'host' => '',
		'port' => '3306',
		'username' => '',
		'password' => '',
		'databaseName' => '',
		'collectionName' => '' // table name
	);


	/**
	 * sets host address
	 * 
	 * @param string $host 
	 * @access protected
	 * @return void
	 */
	protected function setHost($host) 
	{
		$this->setParameter('host', $host);
	}

	/**
	 * sets port number
	 * 
	 * @param int $port 
	 * @access protected
	 * @return void
	 */
	protected function setPort($port) 
	{
		$this->setParameter('port', $port);
	}

	/**
	 * sets username
	 * 
	 * @param string $username
	 * @access protected
	 * @return void
	 */
	protected function setUsername($username) 
	{
		$this->setParameter('username', $username);
	}

	/**
	 * sets password
	 * 
	 * @param string $password
	 * @access protected
	 * @return void
	 */
	protected function setPassword($password) 
	{
		$this->setParameter('password', $password);
	}

	/**
	 * sets database name
	 * 
	 * @param string $databaseName
	 * @access protected
	 * @return void
	 */
	protected function setDatabaseName($databaseName) 
	{
		$this->setParameter('databaseName', $databaseName);
	}

	/**
	 * sets collection/table name
	 * 
	 * @param string $collectionName
	 * @access protected
	 * @return void
	 */
	protected function setCollectionName($collectionName) 
	{
		$this->setParameter('collectionName', $collectionName);
	}


	/**
	 * sets the file system path of the log.
	 * If the given path is not valid, an exception will be thrown.
	 * 
	 * @param string $filePath 
	 * @access private
	 * @return void
	 * @throws \Exception
	 */
	private function setParameter($parameter, $value)
	{
		if (mb_strlen($value) == 0)
			throw new \InvalidArgumentException(
				sprintf('The config parameter "%s" cannot be empty.', $parameter)
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
			'user' => $conf['username'],
			'password' => $conf['password'],
			'dbname' => $conf['databaseName'],
			'driver' => 'pdo_mysql'
		);

		// test connectivity through the doctrine's dbal
		$conn = $this->app['db.mysql.getConnection']($connParams);
		$conn->connect();

		$qb = $conn->createQueryBuilder();
		$qb->select('*')
			->from($conf['collectionName'], 't')
			->setMaxResults(1);
		$qb->execute();
		return true;
	}
}

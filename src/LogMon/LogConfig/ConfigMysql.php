<?php
namespace LogMon\LogConfig;

/**
 * The class ConfigMysql manages log configurations on mysql database.
 * 
 * @uses Base
 * @package LogMon\LogConfig
 */
class ConfigMysql
   	extends BaseLogConfig
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
	 * mysql connection factory
	 * 
	 * @var \Closure
	 * @access protected
	 */
	protected $connectionFactory;

	public function __construct(\Closure $mysqlConnectionFactory, $data = null) 
	{
		parent::__construct($data);
		$this->connectionFactory = $mysqlConnectionFactory;
	}

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
		'collectionName' => '', // table name
		'charset' => 'utf8',
		'fieldMapping' => null
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
	 * returns a connection resource of the storage
	 * If fails, an exception will be thrwon.
	 *
	 * @access public
	 * @return Doctrine\DBAL\Connection
	 */
	public function getConnection() 
	{
		$this->validate();
		$conf = $this->properties;
		$connParams = array(
			'host' => $conf['host'],
			'port' => $conf['port'],
			'user' => $conf['username'],
			'password' => $conf['password'],
			'dbname' => $conf['databaseName'],
			'charset' => $conf['charset'], // TODO: add to API
			'driver' => 'pdo_mysql'
		);

		// test connectivity through the doctrine's dbal
		$factory = $this->connectionFactory;
		$conn = $factory($connParams);
		$conn->connect();
		return $conn;
	}

	/**
	 * checks whether the log source is accesssible or not
	 * additionally, checks whether the table does exists or not.
	 * If not, an exception will be thrown. Otherwise it returns true.
	 * 
	 * @overrides 
	 * @access public
	 * @return void
	 */
	public function test() 
	{
		$conn = parent::test();
		$qb = $conn->createQueryBuilder();
		$qb->select('*')
			->from($this->properties['collectionName'], 't')
			->setMaxResults(1);
		$qb->execute();
		return true;
	}
}

<?php
namespace LogMon\LogReader;

class ReaderMongodb
	extends Reader
	implements IReader
{

	public function __construct(LogConfig\IConfig $logConfig)
	{
		$this->logConfig= $logConfig;
	}
	
	public function initialize()
	{
		$this->connection = $this->config->getConnection();
	}

	public function fetch()
	{
		if (!$this->isInitialized)
			$this->initialize();
		
		$conf = $this->logConfig;
		$queryBuilder =  $this->connection->createQueryBuilder();
		$queryBuilder
			->select('c.*')
			->from($conf->collectionName, 'c')
			->setMaxResults($this->limit);

		$query = $queryBuilder->getQuery();
		$cursor = $query->execute();
		return $cursor;
	}
}

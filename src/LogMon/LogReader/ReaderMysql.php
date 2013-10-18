<?php
namespace LogMon\LogReader;

class ReaderMysql
	extends Reader
	implements IReader
{

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

		$cursor = $queryBuilder->execute();
		return $cursor;
	}
}

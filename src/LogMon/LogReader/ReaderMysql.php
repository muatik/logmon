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

		$fieldMapping= $this->logConfig->fieldMapping;
		
		$queryBuilder =  $this->connection->createQueryBuilder();
		$queryBuilder
			->from($this->logConfig->collectionName, 'c')
			->setMaxResults($this->limit);

		$queryBuilder->addSelect(array(
			'c.'.$fieldMapping->unique->fieldName,
			'c.'.$fieldMapping->type->fieldName,
			'c.'.$fieldMapping->message->fieldName,
			'c.'.$fieldMapping->date->fieldName
		));
		
		$cursor = $queryBuilder->execute();
		return new EntriesMysql($cursor, $fieldMapping);
	}
}

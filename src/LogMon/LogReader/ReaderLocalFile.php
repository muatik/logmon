<?php
namespace LogMon\LogReader;

class ReaderLocalFile
	extends Reader
	implements IReader
{

	private function generateQuery()
	{
		/*		
		$collectionName = $this->logConfig->collectionName;
		$fieldMapper = $this->logConfig->fieldMapper;
		$fields = $fieldMapper->toJson();

		$fieldNames = implode(', ', array_map(function($field){
			return '`'.$field->fieldName.'`';
		}, $fields)); 


		$filters = $this->filters;
		$filterParts = array();

		if ($filters['search'] != null)
			$filterParts[] = '`'.$fields['message']->fieldName.'` like \'%'.$filters['search'] . '%\'';

		if ($filters['level'] != null)
			$filterParts[] = '`'.$fields['level']->fieldName.'` like \'%' . $filters['level'] .'%\'';

		if ($filters['date']['greaterThan'] != null)
			$filterParts[] = '`'.$fields['date']->fieldName.'` > \''. $filters['date']['greaterThan'] .'\'';

		if ($filters['date']['lowerThan'] != null)
			$filterParts[] = '`'.$fields['date']->fieldName.'` > \'' . $filters['date']['lowerThan'] . '\'';

		if (count($filterParts) > 0)
			$filterSQL = "where " . implode(' and ', $filterParts);
		else
			$filterSQL = null;


		$sql = "select $fieldNames from $collectionName $filterSQL limit $this->limit";
		return $sql;
		*/
	}

	public function fetch()
	{
		$conn = $this->logConfig->getConnection();
		return new EntriesLocalFile($conn, $this->logConfig->fieldMapper);
	}

}


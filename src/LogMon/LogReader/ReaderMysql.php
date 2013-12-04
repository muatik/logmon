<?php
namespace LogMon\LogReader;

class ReaderMysql
	extends Reader
	implements IReader
{

	private function generateQuery()
	{
		/*here is the template of generated queries. 
		The inner sql statement provides a temporary table which has
		all required fields; unique, level, text and date.
		The upper statement returns everything coming from the inner one 
		and passing through the filters, if there are any.*/

		/*
		WARNING :
		Mysql does not give any functionality to extract substrings in field 
		selection section, for example there is not such a statement like 
			select (substring_with_regex(fieldX, regex) as fieldName

		Because of this, the system cannot support field extraction too.
		User have to tell an exact name of table column for each required fields.
		*/

		//select __FIELDNAMES__ From __COLLECTION_NAME__ where __FILTERS__

		
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
	}

	public function fetch()
	{
		$conn = $this->logConfig->getConnection();
		$sql = $this->generateQuery();
		$cursor = $conn->query($sql);
		return new EntriesMysql($cursor, $this->logConfig->fieldMapper);
	}

}

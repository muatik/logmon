<?php
namespace LogMon\LogReader;

class ReaderMongodb
	extends Reader
	implements IReader
{

	public function generateQuery()
	{
		$conn = $this->logConfig->getConnection();
		$query = $conn->createQueryBuilder();
		$fields = $this->logConfig->fieldMapper->toJson();
		$filters = $this->filters;

		$fieldNames = array_map(function($field){
			return $field->fieldName;
		}, $fields); 

		$query->select($fieldNames);

		if ($filters['search'] != null)
			$query->field($fields['message']->fieldName)->equals(
				new \MongoRegex('/.*' . $filters['search'] . '.*/i'));

		if ($filters['level'] != null)
			$query->field($fields['level']->fieldName)->equals(
				new \MongoRegex('/.*' . $filters['level'] . '.*/i'));


		if ($filters['date']['greaterThan'] != null)
			$query->field($fields['date']->fieldName)->gt($filters['date']['greaterThan']);

		if ($filters['date']['lowerThan'] != null)
			$query->field($fields['date']->fieldName)->lt($filters['date']['greaterThan']);


		$query->limit($this->limit);

		return $query;
	}

	public function fetch()
	{
		$conf = $this->logConfig;
		$queryBuilder =  $this->generateQuery();
		$query = $queryBuilder->getQuery();
		$cursor = $query->execute();
		return new EntriesMongodb($cursor, $this->logConfig->fieldMapper);
	}
}

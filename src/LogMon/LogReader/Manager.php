<?php
namespace LogMon\LogReader;

class Manager
{
	/**
	 * build a log reader special to the project's log storage type.
	 * 
	 * @param LogMon\Projects\Project $project 
	 * @static
	 * @access public
	 * @return \LogMon\LogReader\IReader
	 * @throws \Exception if the storage type of the project is unknown.
	 */
	public static function buildReader(Project $project)
	{
		switch($project->logConfig->storageType) {
			case 'textFile':
				$readerClass = 'ReaderTextFile';
				break;
			case 'mongodb':
				$readerClass = 'ReaderMongodb';
				break;
			case 'mysql':
				$readerClass = 'ReaderMysql';
				break;
			default:
				throw new \Exception(sprintf(
					"The storage type of the project '%s' is unknown: %s",
					$project->codeName, $project->logConfig->storageType
				));
		}

		$reader = new $readerClass($project->logConfig);
		return $reader;
	}
}


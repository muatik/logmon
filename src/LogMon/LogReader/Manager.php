<?php
namespace LogMon\LogReader;

class Manager
{
	/**
	 * build a log reader special to the project's log storage type.
	 * 
	 * @param LogMon\LogConfig\IConfig $logConfig
	 * @static
	 * @access public
	 * @return \LogMon\LogReader\IReader
	 * @throws \Exception if the storage type of the project is unknown.
	 */
	public static function buildReader(\LogMon\LogConfig\IConfig $logConfig)
	{
		switch($logConfig->getStorageType()) {
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
					"The storage type is unknown: %s",
					$logConfig->storageType
				));
		}
		
		$readerClass  = 'LogMon\LogReader\\' . $readerClass;
		$reader = new $readerClass($logConfig);
		return $reader;
	}
}


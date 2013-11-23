<?php
namespace LogMon\LogConfig;

/**
 * The class ConfigLocalFile manages log configurations in plain files.
 * 
 * @uses Base
 * @package LogMon\LogConfig
 */
class ConfigLocalFile
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
	protected $storageType = 'localFile';

	/**
	 * the file system path of the log
	 * 
	 * @var array
	 * @access protected
	 */
	protected $properties = array(
		'filePath' => ''
	);


	/**
	 * @overrides
	 */
	protected function createFieldMapping() 
	{
		return new FieldMappingTextFile();
	}


	/**
	 * sets the file system path of the log.
	 * If the given path is not valid, an exception will be thrown.
	 * 
	 * @param string $filePath 
	 * @access public
	 * @return void
	 * @thrwos \Exception
	 */
	public function setFilePath($filePath)
	{
		if (mb_strlen($filePath) == 0)
			throw new \InvalidArgumentException('The file path cannot be empty.');

		$this->properties['filePath'] = $filePath;
	}

	/**
	 * creates a connection resource of the file.
	 * If fails, an exception will be thrwon.
	 *
	 * @access public
	 * @return boolean
	 * @throws \Exception if the file does not exists or is inacce
	 */
	public function getConnection() 
	{
		$this->validate();
		$filePath = $this->properties['filePath'];
		
		if (!file_exists($filePath))
			throw new \Exception(
				sprintf('The file "%s" does not exists.', $filePath)
			);
		
		if (!is_file($filePath))
			throw new \Exception(
				sprintf('"%s" does not look like a file ', $filePath)
			);

		if (!is_readable($filePath))
			throw new \Exception(
				sprintf('The file "%s" is not readable.', $filePath)
			);
		
		return fopen($filePath, 'r');
	}

	/**
	 * checks whether the log file does exists and is readable.
	 * additionally, try to open the file in read(r) mode.
	 * If fails, an exception will be thrown.
	 * 
	 * @access public
	 * @return void
	 * @overrides
	 */
	public function test()
	{
		$conn = parent::test();
		fclose($conn);
		return true;
	}

}

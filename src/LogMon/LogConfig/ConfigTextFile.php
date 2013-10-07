<?php
namespace LogMon\LogConfig;

class LogConfigText extends ConfigBase
{
	private $storageType = 'localFile';

	/**
	 * the file system path of the log
	 * 
	 * @var array
	 * @access private
	 */
	private $properties = array(
		'filePath' => ''
	);

	/**
	 * sets the file system path of the log.
	 * If the given path is not valid, an exception will be thrown.
	 * 
	 * @param string $filePath 
	 * @access private
	 * @return void
	 */
	private function setFilePath($filePath)
	{
		if (mb_strlen($filePath) == 0)
			throw new InvalidArgumentException('The file path cannot be empty.');

		$this->properties['filePath'] = $filePath;
	}

	/**
	 * checks whether the log file does exists and is readable.
	 * If not, an exception will be thrwon.
	 *
	 * @access public
	 * @return boolean
	 */
	public function test() 
	{
		$this->validate();
		if (file_exists($this->filePath))
			throw new \Exception(
				sprintf('The file "%s" does not exists.', $this->filePath)
			);
		
		if (is_readable($this->filePath))
			throw new \Exception(
				sprintf('The file "%s" is not readable.', $this->filePath)
			);

		return $true;
	}
}

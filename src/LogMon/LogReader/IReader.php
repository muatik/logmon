<?php
namespace LogMon\LogReader;

interface IReader
{
	public function __construct(\LogMon\LogConfig\IConfig $logConfig);
	public function initialize();
	public function fetch();
}

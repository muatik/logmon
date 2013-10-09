<?php
namespace LogMon\LogReader;

interface IReader
{
	public function __construct(LogConfig\IConfig $logConfig);
	public function initialize();
	public function fetch();
}

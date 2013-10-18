<?php
namespace LogMon\LogConfig;

interface IConfig
{
	public function __construct(\Silex\Application $app);
	public function validate();
	public function getConnection();
	public function test();
	public function __toString();
	public function loadFromJson($json);
	public function getStorageType();
}

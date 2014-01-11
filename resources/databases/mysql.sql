-- phpMyAdmin SQL Dump
-- version 3.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 05, 2013 at 03:28 PM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `logmon`
--

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
	  `_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
	  `codeName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	  `logConfig` text COLLATE utf8_unicode_ci NOT NULL,
	  PRIMARY KEY (`_id`),
	  UNIQUE KEY `codeName` (`codeName`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

	--
	-- Dumping data for table `projects`
	--

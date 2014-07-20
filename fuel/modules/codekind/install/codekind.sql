-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jun 27, 2010 at 02:53 PM
-- Server version: 5.0.45
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `fuel`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `mod_codekind`
-- 

CREATE TABLE `mod_codekind` (
  `codekind_id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `codekind_name` varchar(20) DEFAULT NULL,
  `codekind_key` varchar(20) DEFAULT NULL,
  `codekind_desc` varchar(20) DEFAULT NULL,
  `codekind_value1` varchar(20) DEFAULT NULL,
  `codekind_value2` varchar(20) DEFAULT NULL,
  `codekind_value3` varchar(20) DEFAULT NULL,
  `modi_time` datetime DEFAULT NULL,
  `lang_code` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`codekind_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `mod_code`
-- 

CREATE TABLE `mod_code` (
  `code_id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `codekind_key` varchar(20) DEFAULT NULL,
  `code_name` varchar(20) DEFAULT NULL,
  `code_key` varchar(20) DEFAULT NULL,
  `code_value1` varchar(20) DEFAULT NULL,
  `code_value2` varchar(20) DEFAULT NULL,
  `code_value3` varchar(20) DEFAULT NULL,
  `parent_id` bigint(10) DEFAULT NULL,
  `modi_time` datetime DEFAULT NULL,
  `lang_code` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`code_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



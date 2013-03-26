-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 23, 2012 at 07:24 PM
-- Server version: 5.0.51a
-- PHP Version: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mvc`
--

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE IF NOT EXISTS `person` (
  `id` int(11) NOT NULL auto_increment,
  `fName` text collate macce_bin,
  `lName` text collate macce_bin,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=macce COLLATE=macce_bin AUTO_INCREMENT=6 ;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`id`, `fName`, `lName`) VALUES
(1, 'fsdf', '1212'),
(2, 'fsdf', '1212'),
(3, 'fsdf', '000'),
(4, 'fsdf', '000'),
(5, 'kot', 'zgubek');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

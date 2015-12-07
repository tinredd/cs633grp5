-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 07, 2015 at 06:20 AM
-- Server version: 5.5.45-cll-lve
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cs633`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee_education`
--

CREATE TABLE IF NOT EXISTS `employee_education` (
  `employee_education_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) unsigned NOT NULL,
  `degree` varchar(15) DEFAULT NULL,
  `school_name` varchar(100) NOT NULL DEFAULT '',
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `concentration` varchar(100) DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`employee_education_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `employee_skill`
--

CREATE TABLE IF NOT EXISTS `employee_skill` (
  `employee_skill_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) unsigned NOT NULL,
  `skill_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`employee_skill_id`),
  KEY `skill_id` (`skill_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;

--
-- Dumping data for table `employee_skill`
--

INSERT INTO `employee_skill` (`employee_skill_id`, `employee_id`, `skill_id`) VALUES
(7, 8435443, 5),
(8, 8435443, 4),
(9, 8435443, 2),
(19, 3435332, 5),
(32, 3435332, 3),
(38, 4546476, 10),
(39, 4546476, 11),
(40, 4546476, 7),
(41, 4546476, 12),
(42, 4546476, 3),
(43, 6543512, 1),
(44, 6543512, 2),
(45, 6543512, 13),
(46, 6543512, 3),
(47, 6543512, 5),
(48, 6543512, 7),
(49, 6543512, 14),
(50, 6543512, 4),
(51, 6543512, 15),
(52, 3435332, 16);

-- --------------------------------------------------------

--
-- Table structure for table `employee_work`
--

CREATE TABLE IF NOT EXISTS `employee_work` (
  `employee_work_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) unsigned NOT NULL,
  `work_title` varchar(150) DEFAULT NULL,
  `location` varchar(100) NOT NULL DEFAULT '',
  `start_date` date DEFAULT NULL,
  `end_date` date NOT NULL,
  `notes` text,
  PRIMARY KEY (`employee_work_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE IF NOT EXISTS `job` (
  `job_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `office_id` int(11) unsigned NOT NULL,
  `degree` varchar(30) DEFAULT NULL,
  `job_title` varchar(100) NOT NULL DEFAULT '',
  `salary` decimal(8,2) unsigned DEFAULT NULL,
  `notes` text,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive',
  `years_experience` decimal(4,2) unsigned DEFAULT NULL,
  PRIMARY KEY (`job_id`),
  KEY `office_id` (`office_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `job`
--

INSERT INTO `job` (`job_id`, `office_id`, `degree`, `job_title`, `salary`, `notes`, `status`, `years_experience`) VALUES
(1, 2, '2', 'Web designer', '65000.00', 'Internal only', 1, '2.50'),
(2, 1, '1', 'JavaScript Expert', '50000.00', '', 1, '2.50'),
(3, 2, '4', 'Test Analyst (modify)', '8000.00', '', 1, '7.50'),
(4, 4, '4', 'NO MATCH', '60000.00', '', 1, '1.00');

-- --------------------------------------------------------

--
-- Table structure for table `job_skill`
--

CREATE TABLE IF NOT EXISTS `job_skill` (
  `job_skill_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(11) unsigned NOT NULL,
  `skill_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`job_skill_id`),
  KEY `job_id` (`job_id`),
  KEY `skill_id` (`skill_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `job_skill`
--

INSERT INTO `job_skill` (`job_skill_id`, `job_id`, `skill_id`) VALUES
(1, 1, 3),
(2, 1, 4),
(3, 1, 5),
(4, 2, 5),
(5, 2, 7),
(6, 1, 7),
(7, 2, 2),
(9, 1, 6),
(10, 3, 3),
(11, 3, 1),
(12, 3, 5),
(13, 3, 7),
(14, 3, 2),
(17, 4, 7);

-- --------------------------------------------------------

--
-- Table structure for table `office`
--

CREATE TABLE IF NOT EXISTS `office` (
  `office_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `office_name` varchar(100) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `state` char(2) NOT NULL DEFAULT '',
  `address1` varchar(75) NOT NULL DEFAULT '',
  `address2` varchar(100) DEFAULT NULL,
  `zip` varchar(15) NOT NULL DEFAULT '',
  `contact_name` varchar(100) NOT NULL DEFAULT '',
  `contact_phone` varchar(25) NOT NULL DEFAULT '',
  `contact_email` varchar(75) NOT NULL DEFAULT '',
  `notes` text,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  PRIMARY KEY (`office_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `office`
--

INSERT INTO `office` (`office_id`, `office_name`, `city`, `state`, `address1`, `address2`, `zip`, `contact_name`, `contact_phone`, `contact_email`, `notes`, `status`) VALUES
(1, 'East Coast Office', 'Boston', 'MA', '31415 Town Sq.', 'Ste 92', '02116', 'Tracy Smith', '617-415-2222', 'tracy.smith@company.com', NULL, 1),
(2, 'Midwest Office', 'Indianapolis', 'IN', '27182 American Blvd.', NULL, '46205', 'Gary Johnson', '317-845-9913', 'gary.johnson@company.com', 'HR Manager', 1),
(3, 'West Coast Office', 'Seattle', 'WA', '1618 Quincy Rd', NULL, '98117', 'Linda Hernandez', '206-315-9845', 'linda.hernandez.@company.com', NULL, 1),
(4, 'Southern Office', 'Dallas', 'TX', '6283 Grand St.', 'Ste 100', '75209', 'Michael Spaulding', '469-843-9217', 'michael.spaulding@company.com', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `skill`
--

CREATE TABLE IF NOT EXISTS `skill` (
  `skill_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `skill_name` varchar(75) DEFAULT NULL,
  `added_employee_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'employee the skill was added by; 0 for HR',
  `skill_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1=active,0=inactive,2=employee_added',
  PRIMARY KEY (`skill_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `skill`
--

INSERT INTO `skill` (`skill_id`, `skill_name`, `added_employee_id`, `skill_status`) VALUES
(1, 'Java', 0, 1),
(2, 'PHP', 0, 1),
(3, 'CSS', 0, 1),
(4, 'HTML 5', 0, 1),
(5, 'JavaScript', 0, 1),
(6, 'UML', 0, 1),
(7, 'jQuery', 0, 1),
(8, 'Code''s', 8435443, 2),
(9, 'Process Mapping', 8435443, 2),
(10, 'AAA', 4546476, 2),
(11, 'BBB', 4546476, 2),
(12, 'CCC', 4546476, 2),
(13, 'Test', 6543512, 2),
(14, 'Test2', 6543512, 2),
(15, 'Test3', 6543512, 2),
(16, 'Test', 3435332, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `employee_id` int(11) unsigned NOT NULL,
  `office_id` int(11) unsigned NOT NULL,
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `email_address` varchar(75) NOT NULL DEFAULT '',
  `password` varchar(100) DEFAULT NULL,
  `office_phone` varchar(25) DEFAULT NULL,
  `user_type` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '1=HR, 2=employee',
  `job_title` varchar(100) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `notes` text,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1=Active,0=Inactive',
  `hr_contact` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1=Yes, 0=No',
  `employee_contact` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Yes, 0=No',
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`employee_id`),
  KEY `office_id` (`office_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`employee_id`, `office_id`, `first_name`, `last_name`, `email_address`, `password`, `office_phone`, `user_type`, `job_title`, `hire_date`, `notes`, `status`, `hr_contact`, `employee_contact`, `last_updated`) VALUES
(3242342, 1, 'Donald', 'Henderson', 'donald.henderson@company.com', 'YX·jõá\0¥)…!Þ“', '', 2, '', '2015-11-16', NULL, 1, 1, 1, '2015-11-24 18:12:14'),
(3244543, 2, 'Michael', 'Boccafola', 'michael.boccafola@company.com', 'YX·jõá\0¥)…!Þ“', '', 2, 'Senior Application Developer', '2012-11-01', NULL, 1, 1, 1, '2015-11-24 18:12:21'),
(3281209, 4, 'DO NOT', 'MATCH', 'notest@company.com', 'YX·jõá\0¥)…!Þ“', '', 2, '', '1972-01-01', NULL, 1, 1, 1, '2015-12-05 12:38:49'),
(3435332, 1, 'Tracy', 'Smith', 'tracy.smith@company.com', 'YX·jõá\0¥)…!Þ“', '617-415-2222 x432', 1, 'HR Partner', '1972-04-05', 'My notes.', 1, 0, 0, '2015-12-05 19:57:35'),
(4542132, 2, 'Alex', 'Elentukh', 'alex.elentukh@company.com', 'YX·jõá\0¥)…!Þ“', '', 2, 'Technology Specialist', '2005-06-15', NULL, 1, 1, 1, '2015-11-20 19:38:36'),
(4546476, 2, 'Gary', 'Johnson', 'gary.johnson@company.com', 'YX·jõá\0¥)…!Þ“', '', 1, '', NULL, '', 1, 1, 1, '2015-12-05 13:14:37'),
(5943023, 1, 'Test', 'Test', 'test.test@company.com', 'YX·jõá\0¥)…!Þ“', '', 2, '', '2015-08-01', NULL, 1, 1, 1, '2015-12-05 19:52:18'),
(6465443, 3, 'Linda', 'Hernandez', 'linda.hernandez@company.com', 'YX·jõá\0¥)…!Þ“', NULL, 1, NULL, NULL, NULL, 1, 1, 1, NULL),
(6543512, 1, 'Tina', 'Redd', 'tina.redd@company.com', 'YX·jõá\0¥)…!Þ“', '', 2, 'Database Admin', '2009-02-09', NULL, 1, 1, 1, '2015-11-20 19:37:37'),
(6730912, 3, 'Hello', 'Team', 'hello.team@company.com', 'YX·jõá\0¥)…!Þ“', '', 2, '', '2015-05-01', NULL, 0, 1, 1, '2015-12-06 02:12:58'),
(8435443, 2, 'Lawrence', 'Johnson', 'larry.johnson@company.com', 'YX·jõá\0¥)…!Þ“', '', 2, 'Application Developer', '1996-08-21', NULL, 1, 1, 1, '2015-11-20 19:38:54'),
(8436743, 4, 'Mark', 'Faetanini', 'mark.faetanini@company.com', 'YX·jõá\0¥)…!Þ“', '', 2, 'Project Manager', '2014-01-07', NULL, 1, 1, 1, '2015-11-22 09:40:58'),
(8686232, 4, 'Michael', 'Spaulding', 'michael.spaulding@company.com', 'YX·jõá\0¥)…!Þ“', NULL, 1, NULL, NULL, NULL, 1, 1, 1, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee_skill`
--
ALTER TABLE `employee_skill`
  ADD CONSTRAINT `employee_skill_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`skill_id`);

--
-- Constraints for table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `job_ibfk_1` FOREIGN KEY (`office_id`) REFERENCES `office` (`office_id`);

--
-- Constraints for table `job_skill`
--
ALTER TABLE `job_skill`
  ADD CONSTRAINT `job_skill_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `job` (`job_id`),
  ADD CONSTRAINT `job_skill_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`skill_id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`office_id`) REFERENCES `office` (`office_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

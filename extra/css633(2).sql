# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.5.42)
# Database: cs633
# Generation Time: 2015-11-22 00:03:16 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table employee_education
# ------------------------------------------------------------

DROP TABLE IF EXISTS `employee_education`;

CREATE TABLE `employee_education` (
  `employee_education_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) unsigned NOT NULL,
  `degree` varchar(15) DEFAULT NULL,
  `school_name` varchar(100) NOT NULL DEFAULT '',
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `concentration` varchar(100) DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`employee_education_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table employee_skill
# ------------------------------------------------------------

DROP TABLE IF EXISTS `employee_skill`;

CREATE TABLE `employee_skill` (
  `employee_skill_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) unsigned NOT NULL,
  `skill_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`employee_skill_id`),
  KEY `skill_id` (`skill_id`),
  CONSTRAINT `employee_skill_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`skill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `employee_skill` WRITE;
/*!40000 ALTER TABLE `employee_skill` DISABLE KEYS */;

INSERT INTO `employee_skill` (`employee_skill_id`, `employee_id`, `skill_id`)
VALUES
	(6,6543512,4),
	(7,8435443,5),
	(8,8435443,4),
	(9,8435443,2);

/*!40000 ALTER TABLE `employee_skill` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table employee_work
# ------------------------------------------------------------

DROP TABLE IF EXISTS `employee_work`;

CREATE TABLE `employee_work` (
  `employee_work_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) unsigned NOT NULL,
  `work_title` varchar(150) DEFAULT NULL,
  `location` varchar(100) NOT NULL DEFAULT '',
  `start_date` date DEFAULT NULL,
  `end_date` date NOT NULL,
  `notes` text,
  PRIMARY KEY (`employee_work_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table job
# ------------------------------------------------------------

DROP TABLE IF EXISTS `job`;

CREATE TABLE `job` (
  `job_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `office_id` int(11) unsigned NOT NULL,
  `degree` varchar(30) DEFAULT NULL,
  `job_title` varchar(100) NOT NULL DEFAULT '',
  `salary` decimal(8,2) unsigned DEFAULT NULL,
  `notes` text,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive',
  `years_experience` decimal(4,2) unsigned DEFAULT NULL,
  PRIMARY KEY (`job_id`),
  KEY `office_id` (`office_id`),
  CONSTRAINT `job_ibfk_1` FOREIGN KEY (`office_id`) REFERENCES `office` (`office_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `job` WRITE;
/*!40000 ALTER TABLE `job` DISABLE KEYS */;

INSERT INTO `job` (`job_id`, `office_id`, `degree`, `job_title`, `salary`, `notes`, `status`, `years_experience`)
VALUES
	(1,4,'Associate\'s degree','Web designer',40000.00,'Internal only',1,2.50);

/*!40000 ALTER TABLE `job` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table job_skill
# ------------------------------------------------------------

DROP TABLE IF EXISTS `job_skill`;

CREATE TABLE `job_skill` (
  `job_skill_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(11) unsigned NOT NULL,
  `skill_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`job_skill_id`),
  KEY `job_id` (`job_id`),
  KEY `skill_id` (`skill_id`),
  CONSTRAINT `job_skill_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`skill_id`),
  CONSTRAINT `job_skill_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `job` (`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `job_skill` WRITE;
/*!40000 ALTER TABLE `job_skill` DISABLE KEYS */;

INSERT INTO `job_skill` (`job_skill_id`, `job_id`, `skill_id`)
VALUES
	(1,1,3),
	(2,1,4),
	(3,1,5);

/*!40000 ALTER TABLE `job_skill` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table office
# ------------------------------------------------------------

DROP TABLE IF EXISTS `office`;

CREATE TABLE `office` (
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
  PRIMARY KEY (`office_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `office` WRITE;
/*!40000 ALTER TABLE `office` DISABLE KEYS */;

INSERT INTO `office` (`office_id`, `office_name`, `city`, `state`, `address1`, `address2`, `zip`, `contact_name`, `contact_phone`, `contact_email`, `notes`)
VALUES
	(1,'East Coast Office','Boston','MA','31415 Town Sq.','Ste 92','02116','Tracy Smith','617-415-2222','tracy.smith@company.com',NULL),
	(2,'Midwest Office','Indianapolis','IN','27182 American Blvd.',NULL,'46205','Gary Johnson','317-845-9913','gary.johnson@company.com','HR Manager'),
	(3,'West Coast Office','Seattle','WA','1618 Quincy Rd',NULL,'98117','Linda Hernandez','206-315-9845','linda.hernandez.@company.com',NULL),
	(4,'Southern Office','Dallas','TX','6283 Grand St.','Ste 100','75209','Michael Spaulding','469-843-9217','michael.spaulding@company.com',NULL);

/*!40000 ALTER TABLE `office` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table skill
# ------------------------------------------------------------

DROP TABLE IF EXISTS `skill`;

CREATE TABLE `skill` (
  `skill_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `skill_name` varchar(75) DEFAULT NULL,
  `skill_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1=active,0=inactive',
  PRIMARY KEY (`skill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `skill` WRITE;
/*!40000 ALTER TABLE `skill` DISABLE KEYS */;

INSERT INTO `skill` (`skill_id`, `skill_name`, `skill_status`)
VALUES
	(1,'Java',1),
	(2,'PHP',1),
	(3,'CSS',1),
	(4,'HTML 5',1),
	(5,'JavaScript',1),
	(6,'UML',1);

/*!40000 ALTER TABLE `skill` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
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
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`employee_id`),
  KEY `office_id` (`office_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`office_id`) REFERENCES `office` (`office_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`employee_id`, `office_id`, `first_name`, `last_name`, `email_address`, `password`, `office_phone`, `user_type`, `job_title`, `hire_date`, `notes`, `status`, `last_updated`)
VALUES
	(3242342,3,'Donald','Henderson','donald.henderson@company.com','','',2,'','2015-11-16',NULL,1,'2015-11-21 17:23:13'),
	(3244543,4,'Michael','Boccafola','michael.boccafola@company.com','','',2,'Senior Application Developer','2012-11-01',NULL,1,'2015-11-20 19:37:25'),
	(3435332,1,'Tracy','Smith','tracy.smith@company.com','','617-415-2222 x432',1,'HR Partner',NULL,'My notes.',1,'2015-11-20 13:28:14'),
	(4542132,2,'Alex','Elentukh','alex.elentukh@company.com','','',2,'Technology Specialist','2005-06-15',NULL,1,'2015-11-20 19:38:36'),
	(4546476,2,'Gary','Johnson','gary.johnson@company.com','',NULL,1,NULL,NULL,NULL,1,NULL),
	(6465443,3,'Linda','Hernandez','linda.hernandez@company.com','',NULL,1,NULL,NULL,NULL,1,NULL),
	(6543512,1,'Tina','Redd','tina.redd@company.com','','',2,'Database Admin','2009-02-09',NULL,1,'2015-11-20 19:37:37'),
	(8435443,2,'Lawrence','Johnson','larry.johnson@company.com','','',2,'Application Developer','1996-08-21',NULL,1,'2015-11-20 19:38:54'),
	(8436743,3,'Mark','Faetanini','mark.faetanini@company.com','','',2,'Project Manager','2014-01-07',NULL,1,'2015-11-20 19:39:13'),
	(8686232,4,'Michael','Spaulding','michael.spaulding@company.com','',NULL,1,NULL,NULL,NULL,1,NULL);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

UPDATE users SET password=AES_ENCRYPT('cs633grp5','4034ewewfejiooi3');

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

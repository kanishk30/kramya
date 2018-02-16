-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 05, 2015 at 06:45 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kramya`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE IF NOT EXISTS `booking` (
  `booking_index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` varchar(15) NOT NULL,
  `user_id` varchar(15) NOT NULL,
  `hospital_id` varchar(15) NOT NULL,
  `booking_date` datetime NOT NULL,
  `payement` double NOT NULL,
  `payment_success` enum('0','1') NOT NULL DEFAULT '0',
  `payement_MODE` enum('CREDIT CARD','DEBIT CARD','NET BANKING') NOT NULL,
  `booking_ip` varchar(15) NOT NULL,
  `revert_back` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`booking_id`),
  UNIQUE KEY `UNIQUE` (`booking_index`),
  KEY `FK__users` (`user_id`),
  KEY `FK__hospital_booked` (`hospital_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='table for storing information about bookings' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `comment_index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` varchar(15) NOT NULL,
  `user_id` varchar(15) NOT NULL,
  `hospital_id` varchar(15) NOT NULL,
  `comment` mediumtext NOT NULL,
  PRIMARY KEY (`comment_id`),
  UNIQUE KEY `UNIQUE` (`comment_index`),
  KEY `FK__users_comment` (`user_id`),
  KEY `FK__hospitals_comments` (`hospital_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='comments on different reviews' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `diseases`
--

CREATE TABLE IF NOT EXISTS `diseases` (
  `disease_index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `disease_id` varchar(15) NOT NULL,
  `disease_name` varchar(50) NOT NULL,
  `disease_description` varchar(100) NOT NULL,
  PRIMARY KEY (`disease_id`),
  UNIQUE KEY `UNIQUE` (`disease_index`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='used for storage of information about different diseases' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `diseases`
--

INSERT INTO `diseases` (`disease_index`, `disease_id`, `disease_name`, `disease_description`) VALUES
(1, 'dis1', 'cancer', ''),
(2, 'dis2', 'dental', ''),
(3, 'dis3', 'diabetis', ''),
(4, 'dis4', 'disease4', '');

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE IF NOT EXISTS `hospitals` (
  `hospital_index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` varchar(15) NOT NULL,
  `hospital_name` varchar(50) NOT NULL,
  `hospital_desc` mediumtext NOT NULL,
  `hospital_image` varchar(50) NOT NULL COMMENT 'storing pointer to image',
  `first_street_add` varchar(100) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL COMMENT 'geolocation',
  `second_street_add` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `pincode` int(6) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `hospital_emailid` varchar(50) NOT NULL,
  `hospital_password` varchar(50) NOT NULL,
  `hspital_contactno` varchar(11) NOT NULL,
  `email_act` enum('0','1') NOT NULL DEFAULT '0',
  `phone_act` enum('0','1') NOT NULL DEFAULT '0',
  `rating` tinyint(4) NOT NULL,
  PRIMARY KEY (`hospital_id`),
  UNIQUE KEY `UNIQUE` (`hospital_index`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='storing information about hospitals and tracking their activity' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`hospital_index`, `hospital_id`, `hospital_name`, `hospital_desc`, `hospital_image`, `first_street_add`, `second_street_add`, `city`, `state`, `country`, `pincode`, `latitude`, `longitude`, `hospital_emailid`, `hospital_password`, `hspital_contactno`, `email_act`, `phone_act`, `rating`) VALUES
(4, 'abc', 'abc', 'adada', 'zFads', 'f', 'f', 'gwalior', 'asd', 'india', 455001, 45, 45, 'a@b.com', 'asdf', '1234', '1', '1', 4),
(1, 'apollo', 'Apollo', 'apollo', 'asdf', 'delhi', '', 'delhi', '', 'india', 0, 0, 0, 'apollo@gmail.com', 'asdfgh', '456', '1', '0', 5),
(3, 'birla', 'BIMR', 'asdad', 'asdas', 'mumbai', '', 'gwalior', '', 'U.S.A.', 0, 0, 0, 'care@bimr.ac.in', 'asdf', '1234', '1', '1', 4),
(2, 'leela', 'LeelaWati Hospital', 'dasdad', 'sd', 'mumbai', '', 'mumbai', '', 'Australia', 0, 0, 0, 'hsleele@gmail.com', 'asdf', '1234', '1', '1', 3);

-- --------------------------------------------------------

--
-- Table structure for table `hospital_disease_filter`
--

CREATE TABLE IF NOT EXISTS `hospital_disease_filter` (
  `hospital_id` varchar(15) NOT NULL,
  `disease_id` varchar(15) NOT NULL,
  PRIMARY KEY (`hospital_id`,`disease_id`),
  KEY `FK__diseases` (`disease_id`,`hospital_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='used for filtering results based on diseases targeted';

--
-- Dumping data for table `hospital_disease_filter`
--

INSERT INTO `hospital_disease_filter` (`hospital_id`, `disease_id`) VALUES
('apollo', 'dis1'),
('apollo', 'dis2'),
('birla', 'dis1'),
('birla', 'dis2'),
('birla', 'dis3'),
('leela', 'dis1'),
('leela', 'dis4');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE IF NOT EXISTS `review` (
  `review_index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `review_id` varchar(15) NOT NULL,
  `user_id` varchar(15) NOT NULL,
  `hospital_id` varchar(15) NOT NULL,
  `upvotes` mediumint(9) NOT NULL DEFAULT '0',
  `downvotes` mediumint(9) NOT NULL DEFAULT '0',
  `review_content` mediumtext NOT NULL,
  PRIMARY KEY (`review_id`),
  UNIQUE KEY `UNIQUE` (`review_index`),
  KEY `FK__users_review` (`user_id`),
  KEY `FK__hospitals_review` (`hospital_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='table to track review on hospitals' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `review_comment_map`
--

CREATE TABLE IF NOT EXISTS `review_comment_map` (
  `map_index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `review_id` varchar(15) NOT NULL,
  `comment_id` varchar(15) NOT NULL,
  PRIMARY KEY (`review_id`,`comment_id`),
  UNIQUE KEY `UNIQUE` (`map_index`),
  KEY `FK__comment` (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mapping between reviews and comments' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(15) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `email_id` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `user_location` varchar(100) NOT NULL COMMENT 'geolocation',
  `contact_no` varchar(11) NOT NULL,
  `email_act` enum('0','1') NOT NULL DEFAULT '0',
  `phone_act` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_index` (`user_index`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='storing information about users of our website' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_index`, `user_id`, `fullname`, `email_id`, `password`, `user_location`, `contact_no`, `email_act`, `phone_act`) VALUES
(1, 'ansh_vyas', 'asdf', 'anshul.vyas380@gmail.com', 'asdfg', 'asdfg', 'asdfgh', '1', '1');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `FK__hospital_booked` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`hospital_id`),
  ADD CONSTRAINT `FK__users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK__hospitals_comments` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`hospital_id`),
  ADD CONSTRAINT `FK__users_comment` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `hospital_disease_filter`
--
ALTER TABLE `hospital_disease_filter`
  ADD CONSTRAINT `FK__diseases` FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`disease_id`),
  ADD CONSTRAINT `FK__hospitals` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`hospital_id`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `FK__hospitals_review` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`hospital_id`),
  ADD CONSTRAINT `FK__users_review` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `review_comment_map`
--
ALTER TABLE `review_comment_map`
  ADD CONSTRAINT `FK__comment` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`comment_id`),
  ADD CONSTRAINT `FK__review` FOREIGN KEY (`review_id`) REFERENCES `review` (`review_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

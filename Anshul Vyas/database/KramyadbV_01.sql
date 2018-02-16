-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.16 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- Dumping data for table kramya.booking: ~0 rows (approximately)
/*!40000 ALTER TABLE `booking` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking` ENABLE KEYS */;

-- Dumping data for table kramya.comment: ~0 rows (approximately)
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;

-- Dumping data for table kramya.diseases: ~4 rows (approximately)
/*!40000 ALTER TABLE `diseases` DISABLE KEYS */;
REPLACE INTO `diseases` (`disease_index`, `disease_id`, `disease_name`, `disease_description`) VALUES
	(1, 'dis1', 'cancer', ''),
	(2, 'dis2', 'dental', ''),
	(3, 'dis3', 'diabetis', ''),
	(4, 'dis4', 'disease4', '');
/*!40000 ALTER TABLE `diseases` ENABLE KEYS */;

-- Dumping data for table kramya.hospitals: ~4 rows (approximately)
/*!40000 ALTER TABLE `hospitals` DISABLE KEYS */;
REPLACE INTO `hospitals` (`hospital_index`, `hospital_id`, `hospital_name`, `hospital_desc`, `hospital_image`, `first_street_add`, `second_street_add`, `city`, `state`, `country`, `pincode`, `latitude`, `longitude`, `hospital_emailid`, `hospital_password`, `hspital_contactno`, `email_act`, `phone_act`, `rating`) VALUES
	(4, 'abc', 'abc', 'adada', 'zFads', 'f', 'f', 'gwalior', 'asd', 'india', 455001, 45, 45, 'a@b.com', 'asdf', '1234', '1', '1', 4),
	(1, 'apollo', 'Apollo', 'apollo', 'asdf', 'delhi', '', 'delhi', '', 'india', 0, 0, 0, 'apollo@gmail.com', 'asdfgh', '456', '1', '0', 5),
	(3, 'birla', 'BIMR', 'asdad', 'asdas', 'mumbai', '', 'gwalior', '', 'U.S.A.', 0, 0, 0, 'care@bimr.ac.in', 'asdf', '1234', '1', '1', 4),
	(2, 'leela', 'LeelaWati Hospital', 'dasdad', 'sd', 'mumbai', '', 'mumbai', '', 'Australia', 0, 0, 0, 'hsleele@gmail.com', 'asdf', '1234', '1', '1', 3);
/*!40000 ALTER TABLE `hospitals` ENABLE KEYS */;

-- Dumping data for table kramya.hospital_disease_filter: ~7 rows (approximately)
/*!40000 ALTER TABLE `hospital_disease_filter` DISABLE KEYS */;
REPLACE INTO `hospital_disease_filter` (`hospital_id`, `disease_id`) VALUES
	('apollo', 'dis1'),
	('apollo', 'dis2'),
	('birla', 'dis1'),
	('birla', 'dis2'),
	('birla', 'dis3'),
	('leela', 'dis1'),
	('leela', 'dis4');
/*!40000 ALTER TABLE `hospital_disease_filter` ENABLE KEYS */;

-- Dumping data for table kramya.keys: ~3 rows (approximately)
/*!40000 ALTER TABLE `keys` DISABLE KEYS */;

-- Dumping data for table kramya.review: ~0 rows (approximately)
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
/*!40000 ALTER TABLE `review` ENABLE KEYS */;

-- Dumping data for table kramya.review_comment_map: ~0 rows (approximately)
/*!40000 ALTER TABLE `review_comment_map` DISABLE KEYS */;
/*!40000 ALTER TABLE `review_comment_map` ENABLE KEYS */;

-- Dumping data for table kramya.users: ~1 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
REPLACE INTO `users` (`user_index`, `user_id`, `fullname`, `email_id`, `password`, `user_location`, `contact_no`, `email_act`, `phone_act`) VALUES
	(1, 'ansh_vyas', 'asdf', 'anshul.vyas380@gmail.com', 'asdfg', 'asdfg', 'asdfgh', '1', '1');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2017 at 11:37 AM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `apninv_card_distribution`
--

CREATE TABLE `apninv_card_distribution` (
  `card_id` int(11) NOT NULL,
  `distributor_dealer_id` int(11) NOT NULL,
  `distributor_or_dealer` varchar(2) NOT NULL COMMENT 'di=Distributor, de=Dealer',
  `card_owner_id` int(11) NOT NULL,
  `create_user_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `apninv_card_distribution`
--

INSERT INTO `apninv_card_distribution` (`card_id`, `distributor_dealer_id`, `distributor_or_dealer`, `card_owner_id`, `create_user_id`, `create_date`) VALUES
(2, 6, 'di', 1, 1, '2017-02-22 15:15:00'),
(3, 6, 'di', 1, 1, '2017-02-22 15:15:00'),
(4, 6, 'di', 1, 1, '2017-02-22 15:15:00'),
(5, 6, 'di', 1, 1, '2017-02-22 15:15:00'),
(6, 6, 'di', 1, 1, '2017-02-22 15:15:00'),
(7, 6, 'di', 1, 1, '2017-02-22 15:15:00'),
(8, 6, 'di', 1, 1, '2017-02-22 15:15:00'),
(9, 6, 'di', 1, 1, '2017-02-22 15:15:00'),
(10, 6, 'di', 1, 1, '2017-02-22 15:15:00'),
(21, 6, 'di', 1, 1, '2017-02-22 15:16:24'),
(22, 6, 'di', 1, 1, '2017-02-22 15:16:24'),
(26, 6, 'di', 1, 1, '2017-02-22 15:16:24'),
(27, 6, 'di', 1, 1, '2017-02-22 15:16:24'),
(28, 6, 'di', 1, 1, '2017-02-22 15:16:24'),
(29, 6, 'di', 1, 1, '2017-02-22 15:16:24'),
(30, 6, 'di', 1, 1, '2017-02-22 15:16:24'),
(11, 6, 'di', 1, 1, '2017-02-22 15:17:56'),
(12, 6, 'di', 1, 1, '2017-02-22 15:17:56'),
(13, 6, 'di', 1, 1, '2017-02-22 15:17:56'),
(14, 6, 'di', 1, 1, '2017-02-22 15:17:56'),
(15, 6, 'di', 1, 1, '2017-02-22 15:17:56'),
(16, 6, 'di', 1, 1, '2017-02-22 15:17:56'),
(17, 6, 'di', 1, 1, '2017-02-22 15:17:56'),
(18, 6, 'di', 1, 1, '2017-02-22 15:17:56'),
(19, 6, 'di', 1, 1, '2017-02-22 15:17:56'),
(20, 6, 'di', 1, 1, '2017-02-22 15:17:56'),
(31, 7, 'di', 1, 1, '2017-02-22 17:36:37'),
(32, 7, 'di', 1, 1, '2017-02-22 17:36:37'),
(33, 7, 'di', 1, 1, '2017-02-22 17:36:37'),
(34, 7, 'di', 1, 1, '2017-02-22 17:36:37'),
(35, 7, 'di', 1, 1, '2017-02-22 17:36:37'),
(36, 7, 'di', 1, 1, '2017-02-22 17:36:37'),
(37, 7, 'di', 1, 1, '2017-02-22 17:36:37'),
(38, 7, 'di', 1, 1, '2017-02-22 17:36:37'),
(39, 7, 'di', 1, 1, '2017-02-22 17:36:37'),
(40, 7, 'di', 1, 1, '2017-02-22 17:36:37');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2017 at 12:15 PM
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
-- Table structure for table `apninv_distributors`
--

CREATE TABLE `apninv_distributors` (
  `distributor_id` int(11) NOT NULL,
  `distributor_code` varchar(10) NOT NULL,
  `distributor_name` varchar(100) NOT NULL,
  `distributor_mobile` varchar(11) NOT NULL,
  `distributor_division` varchar(2) NOT NULL,
  `distributor_district` varchar(2) NOT NULL,
  `distributor_upazila` varchar(2) DEFAULT NULL,
  `distributor_union` varchar(2) DEFAULT NULL,
  `active_status` tinyint(1) NOT NULL COMMENT '0=inactive, 1=active',
  `create_user_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `apninv_distributors`
--

INSERT INTO `apninv_distributors` (`distributor_id`, `distributor_code`, `distributor_name`, `distributor_mobile`, `distributor_division`, `distributor_district`, `distributor_upazila`, `distributor_union`, `active_status`, `create_user_id`, `create_date`, `update_user_id`, `update_date`) VALUES
(6, 'di1000', 'Zahidul Hossein Ripon', '01675794194', '20', '15', '0', '0', 1, 1, '2017-02-15 14:24:02', NULL, NULL),
(7, 'di1001', 'Moinul Islam', '01819825098', '20', '15', '37', '35', 1, 1, '2017-02-15 14:30:38', NULL, NULL),
(8, 'di1002', 'Ahasan Ullah', '01675794194', '30', '26', '', '', 1, 1, '2017-02-15 14:38:06', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apninv_distributors`
--
ALTER TABLE `apninv_distributors`
  ADD PRIMARY KEY (`distributor_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

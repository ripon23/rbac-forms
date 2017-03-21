-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2017 at 04:40 AM
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
-- Table structure for table `apninv_dealers`
--

CREATE TABLE `apninv_dealers` (
  `dealer_id` int(11) NOT NULL,
  `dealer_code` varchar(10) NOT NULL,
  `dealer_name` varchar(100) NOT NULL,
  `dealer_mobile` varchar(11) NOT NULL,
  `dealer_division` varchar(2) NOT NULL,
  `dealer_district` varchar(2) NOT NULL,
  `dealer_upazila` varchar(2) DEFAULT NULL,
  `dealer_union` varchar(2) DEFAULT NULL,
  `active_status` tinyint(1) NOT NULL COMMENT '0=inactive, 1=active',
  `create_user_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apninv_dealers`
--
ALTER TABLE `apninv_dealers`
  ADD PRIMARY KEY (`dealer_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

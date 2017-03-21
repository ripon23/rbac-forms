-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2017 at 07:42 AM
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
-- Table structure for table `apninv_card_type`
--

CREATE TABLE `apninv_card_type` (
  `card_id` int(11) NOT NULL,
  `card_amount` int(11) NOT NULL,
  `card_display_name` varchar(8) NOT NULL,
  `display_order` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `apninv_card_type`
--

INSERT INTO `apninv_card_type` (`card_id`, `card_amount`, `card_display_name`, `display_order`, `status`) VALUES
(1, 100, '100 TK', 2, 1),
(2, 200, '200 TK', 3, 1),
(3, 500, '500 TK', 4, 1),
(4, 60, '60 TK', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apninv_card_type`
--
ALTER TABLE `apninv_card_type`
  ADD PRIMARY KEY (`card_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apninv_card_type`
--
ALTER TABLE `apninv_card_type`
  MODIFY `card_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

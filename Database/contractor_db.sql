-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2024 at 08:15 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `contractor_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `contractor`
--

CREATE TABLE `contractor` (
  `contractor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `site_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `expense_id` int(11) NOT NULL,
  `site_id` int(11) DEFAULT NULL,
  `material` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `other_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `material_details`
--

CREATE TABLE `material_details` (
  `id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `floor_tile` varchar(255) DEFAULT NULL,
  `bath_tile` varchar(255) DEFAULT NULL,
  `front_elevation_tile` varchar(255) DEFAULT NULL,
  `cement_type` varchar(255) DEFAULT NULL,
  `gate` varchar(255) DEFAULT NULL,
  `water_pump_tank` varchar(255) DEFAULT NULL,
  `base_beams` varchar(255) DEFAULT NULL,
  `roof_slab_type` varchar(255) DEFAULT NULL,
  `paint` varchar(255) DEFAULT NULL,
  `steel` varchar(255) DEFAULT NULL,
  `wood_window_door` varchar(255) DEFAULT NULL,
  `bricks_sand_concrete_stone` varchar(255) DEFAULT NULL,
  `electricity_wire_switches` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `owner_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `person_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `expertise` varchar(255) NOT NULL,
  `site_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `site`
--

CREATE TABLE `site` (
  `site_id` int(11) NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `site_address` varchar(255) NOT NULL,
  `covered_area` varchar(50) NOT NULL,
  `owner_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `work_log`
--

CREATE TABLE `work_log` (
  `log_id` int(11) NOT NULL,
  `site_id` int(11) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `work_date` date DEFAULT NULL,
  `work_time` varchar(255) DEFAULT NULL,
  `work_details` text DEFAULT NULL,
  `paid_status` enum('paid','not_paid') DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contractor`
--
ALTER TABLE `contractor`
  ADD PRIMARY KEY (`contractor_id`),
  ADD KEY `site_id` (`site_id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `site_id` (`site_id`);

--
-- Indexes for table `material_details`
--
ALTER TABLE `material_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `site_id` (`site_id`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`owner_id`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`person_id`),
  ADD KEY `site_id` (`site_id`);

--
-- Indexes for table `site`
--
ALTER TABLE `site`
  ADD PRIMARY KEY (`site_id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `work_log`
--
ALTER TABLE `work_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `site_id` (`site_id`),
  ADD KEY `person_id` (`person_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contractor`
--
ALTER TABLE `contractor`
  MODIFY `contractor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `material_details`
--
ALTER TABLE `material_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `owner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `person_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `site`
--
ALTER TABLE `site`
  MODIFY `site_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `work_log`
--
ALTER TABLE `work_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contractor`
--
ALTER TABLE `contractor`
  ADD CONSTRAINT `contractor_ibfk_1` FOREIGN KEY (`site_id`) REFERENCES `site` (`site_id`);

--
-- Constraints for table `expense`
--
ALTER TABLE `expense`
  ADD CONSTRAINT `expense_ibfk_1` FOREIGN KEY (`site_id`) REFERENCES `site` (`site_id`);

--
-- Constraints for table `material_details`
--
ALTER TABLE `material_details`
  ADD CONSTRAINT `material_details_ibfk_1` FOREIGN KEY (`site_id`) REFERENCES `site` (`site_id`);

--
-- Constraints for table `person`
--
ALTER TABLE `person`
  ADD CONSTRAINT `person_ibfk_1` FOREIGN KEY (`site_id`) REFERENCES `site` (`site_id`);

--
-- Constraints for table `site`
--
ALTER TABLE `site`
  ADD CONSTRAINT `site_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `owner` (`owner_id`);

--
-- Constraints for table `work_log`
--
ALTER TABLE `work_log`
  ADD CONSTRAINT `work_log_ibfk_1` FOREIGN KEY (`site_id`) REFERENCES `site` (`site_id`),
  ADD CONSTRAINT `work_log_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

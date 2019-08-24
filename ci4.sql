-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2019 at 03:40 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ci4`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `s_id` int(11) NOT NULL,
  `s_name` varchar(200) NOT NULL,
  `s_subjects` varchar(200) NOT NULL,
  `s_age` int(11) DEFAULT NULL,
  `s_date` datetime DEFAULT NULL,
  `s_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`s_id`, `s_name`, `s_subjects`, `s_age`, `s_date`, `s_updated`) VALUES
(1, 'Alex', 'php', 24, '2019-05-02 05:22:15', NULL),
(3, 'maria', 'java', 20, '2019-05-02 05:22:15', NULL),
(4, 'shakzee', 'java', 22, '2019-05-02 05:22:15', NULL),
(5, 'shakzee 3', 'php', 30, NULL, NULL),
(6, 'shakzee 3', 'java', 40, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `s_classes`
--

CREATE TABLE `s_classes` (
  `s_cid` int(11) NOT NULL,
  `s_class_name` varchar(200) NOT NULL,
  `s_class_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `s_classes`
--
ALTER TABLE `s_classes`
  ADD PRIMARY KEY (`s_cid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `s_classes`
--
ALTER TABLE `s_classes`
  MODIFY `s_cid` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

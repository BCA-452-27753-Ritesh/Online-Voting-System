-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2024 at 11:52 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `voterdatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `addcandidate`
--

CREATE TABLE `addcandidate` (
  `id` int(20) NOT NULL,
  `cname` text NOT NULL,
  `symbol` varchar(200) NOT NULL,
  `cparty` varchar(100) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `votes` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addcandidate`
--

INSERT INTO `addcandidate` (`id`, `cname`, `symbol`, `cparty`, `photo`, `votes`) VALUES
(1, 'Imran Khan', 'pti.jpeg', 'Pakistan Tehreek-e-Insaf ( PTI )', 'imran.jpg', 2),
(3, 'Nawaz Sharif', 'ppp.jpeg', 'Pakistan Muslim League Nawaz ( PMLN )', 'download.jfif', 2);

-- --------------------------------------------------------

--
-- Table structure for table `adminlogin`
--

CREATE TABLE `adminlogin` (
  `id` int(20) NOT NULL,
  `name` text NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminlogin`
--

INSERT INTO `adminlogin` (`id`, `name`, `password`) VALUES
(1, 'Admin', 'Admin@1122');

-- --------------------------------------------------------

--
-- Table structure for table `voterregistration`
--

CREATE TABLE `voterregistration` (
  `id` int(10) NOT NULL,
  `name` text NOT NULL,
  `dob` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `gender` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  `idtype` text NOT NULL,
  `cnic` varchar(100) NOT NULL,
  `issue` varchar(100) NOT NULL,
  `expire` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `cpass` varchar(100) NOT NULL,
  `status` int(100) NOT NULL,
  `votes` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voterregistration`
--

INSERT INTO `voterregistration` (`id`, `name`, `dob`, `email`, `mobile`, `gender`, `photo`, `idtype`, `cnic`, `issue`, `expire`, `pass`, `cpass`, `status`, `votes`) VALUES
(9, 'Zulqarnain Haseeb', '2002-04-24', '', '2147483647', 'Male', '8006e71e-b32f-49a8-908c-5130a7df46c0.jpg', '', '2147483647', '2000-02-02', '', '12345', '12345', 1, 0),
(13, 'Zulqarnain', '2002-04-02', '', '03417041123', 'Male', '55.jfif', '', '110011', '2001-02-02', '', 'asdf', 'asdf', 1, 0),
(14, 'Hamza', '2001-04-02', '', '112233', 'Male', '7139vwSUgxL._AC_UY575_.jpg', '', '121', '2001-02-03', '', 'asdf', 'asdf', 1, 0),
(15, 'Zulqarnain Haseeb', '2002-04-02', '', '03417041124', 'Male', 'my.JPG', '', '13504', '2001-03-02', '', 'zulqarnain123', 'zulqarnain123', 1, 5),
(16, 'a', '2002-02-02', '', '1', 'Male', 'imran.jpg', '', '11', '2002-02-20', '', 'as', 'as', 1, 0),
(17, 'aneela', '1993-02-06', '', '03440995749', 'Female', 'Admin.png', '', '1350464098908', '2017-08-14', '', '1234', '1234', 1, 0),
(18, 'Zulqarnain Haseeb', '2001-02-20', '', '03417041124', 'Male', 'my1.jpg', '', '13504', '1002-02-20', '', 'asdf', 'asdf', 1, 0),
(19, 'Zulqarnain', '2001-02-02', '', '03417041123', 'Male', 'imran.jpg', '', '13504', '2001-02-20', '', '12345', '12345', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addcandidate`
--
ALTER TABLE `addcandidate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `adminlogin`
--
ALTER TABLE `adminlogin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voterregistration`
--
ALTER TABLE `voterregistration`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addcandidate`
--
ALTER TABLE `addcandidate`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `adminlogin`
--
ALTER TABLE `adminlogin`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `voterregistration`
--
ALTER TABLE `voterregistration`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

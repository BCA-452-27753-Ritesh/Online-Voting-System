-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2026 at 05:15 AM
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
  `id` int(11) NOT NULL,
  `cname` varchar(100) DEFAULT NULL,
  `symbol` varchar(200) DEFAULT NULL,
  `cparty` varchar(100) DEFAULT NULL,
  `photo` varchar(200) DEFAULT NULL,
  `votes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addcandidate`
--

INSERT INTO `addcandidate` (`id`, `cname`, `symbol`, `cparty`, `photo`, `votes`) VALUES
(2, 'Ujjawal', '71eb2cc75e9615acd590e8b8e3967707.jpg', 'BJP', 'AirBrushVideo1741676635925.png', 1),
(4, 'Rahul', 'inc-party-indian-national-congress-party-flag-political-party-sign-congress-party-symbol-inc-party-indian-national-congress-party-304577888.webp', 'RJD', 'tt.jpg', 0),
(5, 'raju', '61HYdLcc2lL.jpg', 'bcd', '28888.JPG', 0),
(6, 'RAM', 'Screenshot 2026-03-14 094735.png', 'RJD', '28888.JPG', 0);

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
(1, 'Admin', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `voterregistration`
--

CREATE TABLE `voterregistration` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `photo` varchar(200) DEFAULT NULL,
  `idtype` varchar(50) DEFAULT NULL,
  `adhar` varchar(20) DEFAULT NULL,
  `issue` date DEFAULT NULL,
  `expire` date DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `otp` varchar(10) DEFAULT NULL,
  `otp_expire` datetime DEFAULT NULL,
  `vote` int(11) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT 'pending',
  `token` varchar(100) DEFAULT NULL,
  `plan_days` int(11) DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `is_used` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voterregistration`
--

INSERT INTO `voterregistration` (`id`, `name`, `dob`, `email`, `mobile`, `gender`, `photo`, `idtype`, `adhar`, `issue`, `expire`, `password`, `status`, `otp`, `otp_expire`, `vote`, `payment_status`, `token`, `plan_days`, `expiry_date`, `is_used`) VALUES
(3, 'Ritesh Kumar Pandey', '2010-02-27', 'riteshkumarpandey603@gmail.com', '9661143339', 'Male', '28888.JPG', 'adhar', '111122223333', '2026-03-27', '2026-03-27', '$2y$10$UnLlJM.ttE/1o2XOKYAdwutO6X5L8rSnFYeX5BWlA7SQLC5aKozKO', 1, '888011', '2026-04-07 16:57:12', NULL, 'pending', NULL, NULL, NULL, 0),
(5, 'ujjawal', '2004-11-16', 'ram@gmail.com', '6203850395', 'Male', 'AirBrushVideo1741676635925.png', 'adhar', '123412341234', '2026-03-28', '2026-03-28', '$2y$10$.KdtwicArvrAFvwYt2xuJ.UNsRAf9BVJV7ETtthdULCrSLqZ4B6iG', 1, '682958', '2026-04-07 16:56:13', NULL, 'pending', NULL, NULL, NULL, 0),
(8, 'ujjawal', '2004-01-07', 'hbhsdabck@gmail.com', '8809204872', 'Male', 'AirBrushVideo1741676588660.png', 'adhar', '121212121212', '2026-04-07', '2026-04-07', '$2y$10$eDiQ6CYHOSIIXODe1tjdo.SJExakTjk6HjQtwvHE/EEKJjyTH4s82', 1, NULL, NULL, 2, 'paid', 'aaff30a6aeece8379370afc10a96577d', 3, '2026-04-16 20:57:48', 0);

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `voter_id` int(11) DEFAULT NULL,
  `candidate_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `voter_id`, `candidate_id`) VALUES
(9, 8, 2);

-- --------------------------------------------------------

--
-- Table structure for table `voting_status`
--

CREATE TABLE `voting_status` (
  `id` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voting_status`
--

INSERT INTO `voting_status` (`id`, `status`) VALUES
(1, 1);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `adhar` (`adhar`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `voter_id` (`voter_id`);

--
-- Indexes for table `voting_status`
--
ALTER TABLE `voting_status`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addcandidate`
--
ALTER TABLE `addcandidate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `adminlogin`
--
ALTER TABLE `adminlogin`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `voterregistration`
--
ALTER TABLE `voterregistration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

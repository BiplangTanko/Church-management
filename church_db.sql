-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2025 at 04:35 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `church_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$THld0KEZJj.OeD6SowE1gO95dMHugof5MbCD3NOK9dwQwXSwMllUG', '2025-07-09 01:14:41');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `event_time`, `location`, `created_at`) VALUES
(1, 'wedding', 'the wedding of mr Manji', '2025-07-09', NULL, 'Halle', '2025-07-09 01:31:01'),
(2, 'send-forth', 'the send-forth of blessing', '2025-07-09', '10:30:00', 'Jos', '2025-07-09 01:40:02'),
(3, 'send-forth', 'the send-forth of blessing', '2025-07-09', '10:30:00', 'Jos', '2025-07-09 01:43:27');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `marital_status` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `full_name`, `email`, `phone`, `dob`, `gender`, `marital_status`, `address`, `registration_date`, `password`) VALUES
(1, 'Biplang Tanko Doya', 'biplangdoyat0216@gmail.com', '08032187302', '2025-07-09', 'Male', 'Single', 'Bungha Mangu LGA', '2025-07-09 00:38:22', '$2y$10$wBP6opTBSbVr2UdfzpU9N.BCYTtyTSvX0G3YLQ/JH/6nRuJBxTvtO'),
(2, 'Biplang Tanko Doya', 'biplangdoyat@gmail.com', '08032187302', '2025-07-09', 'Male', 'Single', 'Bungha Mangu LGA', '2025-07-09 00:46:14', '$2y$10$J5WBzRxE3llk25zHxJQDReozcXXbM7.VB1oplc0Hg9ln3xaK4LRIe'),
(3, 'Biplang Tanko Doya', 'marvelousdennis95@gmail.com', '08032187302', '2025-07-09', 'Male', 'Married', 'Bungha Mangu LGA', '2025-07-09 00:49:00', '$2y$10$gCp3lf386EC11LjytXcZLOn88c8hm8MFH6.L/nKYf5mL/BZCMFPTi');

-- --------------------------------------------------------

--
-- Table structure for table `prayer_requests`
--

CREATE TABLE `prayer_requests` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prayer_requests`
--

INSERT INTO `prayer_requests` (`id`, `member_id`, `title`, `message`, `submitted_at`) VALUES
(1, 1, 'peace', 'Pray for peace In Nigeria', '2025-07-09 02:28:06');

-- --------------------------------------------------------

--
-- Table structure for table `sermons`
--

CREATE TABLE `sermons` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `preacher` varchar(100) DEFAULT NULL,
  `sermon_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sermons`
--

INSERT INTO `sermons` (`id`, `title`, `description`, `file_path`, `uploaded_at`, `preacher`, `sermon_date`) VALUES
(2, 'Jesus Lve you', 'the power of love can not be over emphasized', 'sermons/1752026343_53a72284d090483592efad4df89f622c-1.mp4', '2025-07-09 01:59:03', 'John Paul', '2025-07-09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `prayer_requests`
--
ALTER TABLE `prayer_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `sermons`
--
ALTER TABLE `sermons`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prayer_requests`
--
ALTER TABLE `prayer_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sermons`
--
ALTER TABLE `sermons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `prayer_requests`
--
ALTER TABLE `prayer_requests`
  ADD CONSTRAINT `prayer_requests_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

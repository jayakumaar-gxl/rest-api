-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2026 at 12:56 PM
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
-- Database: `api_learning`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `email` varchar(200) NOT NULL,
  `department` varchar(200) NOT NULL,
  `created_At` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `department`, `created_At`) VALUES
(1, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-24 09:48:17'),
(2, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-24 09:48:17'),
(3, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-24 09:48:17'),
(4, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-24 13:07:41'),
(5, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-24 13:07:46'),
(6, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-24 13:20:50'),
(7, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-24 13:26:38'),
(8, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-24 13:27:10'),
(9, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-24 13:27:16'),
(10, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-24 13:27:25'),
(11, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-24 13:41:37'),
(12, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-24 13:44:10'),
(13, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-24 13:44:14'),
(14, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 04:47:55'),
(15, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 04:48:28'),
(16, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 04:49:40'),
(17, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 04:51:35'),
(18, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 04:51:58'),
(19, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:10:22'),
(20, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:12:13'),
(21, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:12:14'),
(22, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:12:15'),
(23, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:12:15'),
(24, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:12:15'),
(25, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:12:16'),
(26, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:12:16'),
(27, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:12:16'),
(28, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:12:16'),
(29, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:12:16'),
(30, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:12:17'),
(31, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:12:17'),
(32, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:12:18'),
(33, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:12:19'),
(34, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:12:19'),
(35, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 05:31:06'),
(36, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:09:30'),
(37, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:11:42'),
(38, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:11:47'),
(39, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:11:48'),
(40, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:11:48'),
(41, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:11:49'),
(42, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:11:49'),
(43, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:11:49'),
(44, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:11:50'),
(45, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:11:50'),
(46, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:11:50'),
(47, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:11:50'),
(48, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:11:51'),
(49, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:11:51'),
(50, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:12:13'),
(51, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:12:14'),
(52, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:12:15'),
(53, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:12:15'),
(54, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:12:15'),
(55, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:12:16'),
(56, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:12:16'),
(57, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:12:16'),
(58, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:12:41'),
(59, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:17:33'),
(60, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:17:50'),
(61, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 06:18:28'),
(62, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 07:41:43'),
(63, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 07:41:48'),
(64, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 07:45:59'),
(65, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 07:46:01'),
(66, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 07:47:18'),
(67, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 07:47:21'),
(68, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 07:47:22'),
(69, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 07:47:27'),
(70, 'Vinothekumar Updated new', 'vinothkumar@gmail.com', 'Software Developer', '2026-09-25 07:48:58'),
(83, 'Ramkumar', 'ram@gmail.com', 'IT Operations', '2026-09-28 04:58:41'),
(84, 'Testuser', 'testuser@gmail.com', 'IT', '2026-09-28 05:33:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 28, 2025 at 04:00 AM
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
-- Database: `agencia`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$Nf4rt3tsNsHdRwsjMrPii.Dh5DtcPxqS/lBLnOs64HvBTpte0YTCO'),
(2, 'nick_rangel', ''),
(3, 'admin3', '$2y$10$K0pJDfpNFRMKgbD.eAmBdO63//DkybjeIZKS42jzqZLP.LmYG5PK6');

-- --------------------------------------------------------

--
-- Table structure for table `carros`
--

CREATE TABLE `carros` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `price` int(11) NOT NULL,
  `type` varchar(16) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `description` text NOT NULL,
  `img_name` varchar(128) NOT NULL,
  `year` int(11) NOT NULL,
  `used` tinyint(1) NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carros`
--

INSERT INTO `carros` (`id`, `name`, `price`, `type`, `featured`, `description`, `img_name`, `year`, `used`, `deleted`) VALUES
(1, 'Corvette C8', 56000, 'sport', 1, 'The 2025 Chevrolet Corvette C8 continues the mid-engine platform, with updates including new colors, interior schemes, and a redesigned Z51 spoiler.', 'corvette_c8', 2025, 0, 0),
(2, 'Chevrolet Malibu', 32000, 'sedan', 1, 'The 2025 Malibu remains a symbol of refinement in the midsize sedan category. For comfort and influential style at an affordable price — Malibu delivers.', 'chevy_malibu', 2025, 0, 0),
(3, 'Honda Civic LX', 5000, 'coupe', 0, 'The 2010 Honda Civic LX Coupe is a compact, fuel-efficient, and reliable two-door coupe known for its good handling and a comfortable ride. ', 'civic_lx_coupe_2010', 2010, 1, 0),
(4, 'Chevrolet Aveo', 1, 'hatchback', 0, 'Used by the #1 volleyball player in the Universidad Cuauhtemoc', 'chevy_aveo', 2017, 1, 0),
(5, 'Nissan Tsuru', 7000, 'sedan', 0, 'Nissan Tsuru, el icónico vehículo que durante más de tres décadas ha brindado movilidad accesible, económica y confiable a millones de mexicanos', 'nissan_tsuru', 2016, 1, 0),
(6, 'Toyota Sera', 34000, 'sport', 1, 'El Toyota Sera es un deportivo compacto que combina un diseño futurista con una experiencia de conducción emocionante. Con sus icónicas puertas de ala de gaviota y un motor ágil, ofrece una conducción dinámica y cómoda.', 'toyota_sera', 1993, 0, 0),
(7, 'Mazda Autozam AZ-1', 20000, 'sport', 1, 'The Autozam AZ-1, known by the framecode PG6SA, is a mid-engined kei-class sports car, designed and manufactured by Mazda under its Autozam brand. Suzuki provided the engine as well as the inspiration for the design.', 'mazda_autozam', 1992, 0, 0),
(8, 'Pegassi Infernus', 230000, 'sport', 1, 'The performance of the Infernus remains similar in all of its appearances by being one of, if not the fastest car in each game. Unlike subsequent depictions that have an all-wheel-drive system, the Infernus in GTA III has a rear-wheel-drive layout.\r\n\r\nDespite the change in drivetrain layout, its handling remains similar and its all-wheel-drive system means the car has good front grip.', 'pegassi_infernus', 2023, 0, 0),
(9, 'Volkswagen Golf GTI 3', 62000, 'hatchback', 1, 'Its cool i think idk tho ', 'gti', 2025, 0, 0),
(10, 'Chevrolet Express Cargo', 10, 'van', 0, 'coolo\r\n', 'chevy_van', 2025, 0, 0),
(11, 'Mazda 3', 2147483644, 'hatchback', 1, 'Fofo lo choco que pendejo', 'maserati-removebg-preview', 2015, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `client_name` varchar(50) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `id_car` int(11) NOT NULL,
  `id_employee` int(11) NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `citas`
--

INSERT INTO `citas` (`id`, `datetime`, `client_name`, `phone`, `id_car`, `id_employee`, `deleted`) VALUES
(1, '2025-04-04 11:00:00', 'Johnny', 0, 6, 1, 0),
(2, '2025-04-25 10:00:00', 'brah idk', 0, 6, 1, 0),
(3, '2025-04-30 14:00:00', 'Miguel Angel', 4491234567, 6, 8, 0),
(4, '2025-05-23 12:00:00', 'Kadir Koprulu Hernandez', 4497510546, 8, 1, 0),
(5, '2025-05-30 13:00:00', 'Ricardo ', 4495714543, 7, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `level` int(11) NOT NULL,
  `rfc` varchar(13) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `level`, `rfc`, `phone`, `password`, `deleted`) VALUES
(1, 'Bob Johnny', 0, 'abcdefghijklm', 4495810546, '$2y$10$K0pJDfpNFRMKgbD.eAmBdO63//DkybjeIZKS42jzqZLP.LmYG5PK6', 0),
(2, 'Alice Johnson', 1, '', 0, '$2y$10$K0pJDfpNFRMKgbD.eAmBdO63//DkybjeIZKS42jzqZLP.LmYG5PK6', 0),
(3, 'Bob Smith', 2, '', 0, '$2y$10$K0pJDfpNFRMKgbD.eAmBdO63//DkybjeIZKS42jzqZLP.LmYG5PK6', 0),
(4, 'Charlie Brown', 3, '', 0, '$2y$10$K0pJDfpNFRMKgbD.eAmBdO63//DkybjeIZKS42jzqZLP.LmYG5PK6', 0),
(5, 'Diana Evans', 1, '', 0, '$2y$10$K0pJDfpNFRMKgbD.eAmBdO63//DkybjeIZKS42jzqZLP.LmYG5PK6', 0),
(6, 'Ethan Foster', 2, '', 0, '$2y$10$K0pJDfpNFRMKgbD.eAmBdO63//DkybjeIZKS42jzqZLP.LmYG5PK6', 0),
(7, 'Fiona Green', 3, '', 0, '$2y$10$K0pJDfpNFRMKgbD.eAmBdO63//DkybjeIZKS42jzqZLP.LmYG5PK6', 0),
(8, 'George Harris', 1, '', 0, '$2y$10$K0pJDfpNFRMKgbD.eAmBdO63//DkybjeIZKS42jzqZLP.LmYG5PK6', 0),
(9, 'Hannah White', 2, '', 0, '$2y$10$K0pJDfpNFRMKgbD.eAmBdO63//DkybjeIZKS42jzqZLP.LmYG5PK6', 0),
(10, 'Ian King', 3, '', 0, '$2y$10$K0pJDfpNFRMKgbD.eAmBdO63//DkybjeIZKS42jzqZLP.LmYG5PK6', 0),
(11, 'hey', 4, '4', 4491234567, '$2y$10$K0pJDfpNFRMKgbD.eAmBdO63//DkybjeIZKS42jzqZLP.LmYG5PK6', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `rating` text NOT NULL,
  `name` varchar(32) NOT NULL,
  `score` int(11) NOT NULL,
  `id_car` int(11) NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `rating`, `name`, `score`, `id_car`, `deleted`) VALUES
(1, 'Not worth the money at all. Too many mechanical issues.', 'Hannah Gold', 2, 10, 0),
(2, 'Could be better, but it works. Performance is acceptable.', 'Diana Black', 3, 5, 0),
(3, 'Quite satisfied with this vehicle. Comfortable for long drives.', 'Bob White', 4, 4, 0),
(4, 'Handles well and is reliable. Minor issues but nothing major.', 'George Red', 4, 6, 0),
(5, 'Handles well and is reliable. Good value for the price.', 'Ethan Grey', 4, 10, 0),
(6, 'Absolutely love this car! Reliable and fuel-efficient.', 'Hannah Gold', 5, 2, 0),
(7, 'Quite satisfied with this vehicle. Comfortable for long drives.', 'Bob White', 4, 5, 0),
(8, 'Absolutely love this car! The features are amazing.', 'Diana Black', 5, 3, 0),
(9, 'A complete letdown. The vehicle broke down quickly.', 'Alice Brown', 2, 7, 0),
(10, 'A complete letdown. Performance was abysmal.', 'Fiona Blue', 2, 9, 0),
(11, 'Could be better, but it works. It meets basic needs.', 'Alice Brown', 3, 1, 0),
(12, 'Very happy with the performance. Smooth ride and great handling.', 'Jane Smith', 5, 4, 0),
(13, 'Excellent vehicle! Incredibly comfortable and spacious.', 'Hannah Gold', 5, 1, 0),
(14, 'A complete letdown. Definitely not what I expected.', 'Fiona Blue', 2, 4, 0),
(15, 'Absolutely love this car! The features are amazing.', 'Alice Brown', 5, 5, 0),
(16, 'Gets the job done, nothing more. Reliability seems standard.', 'Diana Black', 3, 4, 0),
(17, 'Highly recommend to everyone. Incredibly comfortable and spacious.', 'Bob White', 5, 4, 0),
(18, 'Absolutely love this car! Smooth ride and great handling.', 'Alice Brown', 5, 3, 0),
(19, 'Absolutely love this car! Smooth ride and great handling.', 'Fiona Blue', 5, 2, 0),
(20, 'A solid choice for its class. Minor issues but nothing major.', 'John Doe', 4, 1, 0),
(21, 'Handles well and is reliable. Good value for the price.', 'John Doe', 4, 4, 0),
(22, 'Terrible experience. Definitely not what I expected.', 'Ethan Grey', 2, 1, 0),
(23, 'Very happy with the performance. The features are amazing.', 'George Red', 5, 3, 0),
(24, 'Fantastic purchase decision. Exceeded all my expectations.', 'Diana Black', 5, 6, 0),
(25, 'Fairly neutral experience. Reliability seems standard.', 'Ethan Grey', 3, 10, 0),
(26, 'Highly regret this purchase. Performance was abysmal.', 'Jane Smith', 2, 10, 0),
(27, 'Above average performance. A strong contender.', 'Fiona Blue', 4, 7, 0),
(28, 'Highly regret this purchase. Definitely not what I expected.', 'Charlie Green', 2, 10, 0),
(29, 'Could be better, but it works. Reliability seems standard.', 'George Red', 3, 8, 0),
(30, 'Terrible experience. The vehicle broke down quickly.', 'Hannah Gold', 2, 10, 0),
(31, 'It is an average car. Reliability seems standard.', 'Ethan Grey', 3, 3, 0),
(32, 'Quite satisfied with this vehicle. Would consider buying again.', 'Bob White', 4, 10, 0),
(33, 'Not worth the money at all. The vehicle broke down quickly.', 'Diana Black', 1, 5, 0),
(34, 'A complete letdown. Comfort is non-existent.', 'Fiona Blue', 1, 5, 0),
(35, 'Highly regret this purchase. The vehicle broke down quickly.', 'John Doe', 1, 6, 0),
(36, 'Terrible experience. Definitely not what I expected.', 'Diana Black', 2, 9, 0),
(37, 'Absolutely love this car! Reliable and fuel-efficient.', 'Ethan Grey', 5, 1, 0),
(38, 'A complete letdown. The vehicle broke down quickly.', 'Hannah Gold', 2, 5, 0),
(39, 'Gets the job done, nothing more. Reliability seems standard.', 'John Doe', 3, 3, 0),
(40, 'A complete letdown. Performance was abysmal.', 'Ethan Grey', 1, 5, 0),
(41, 'Very happy with the performance. Incredibly comfortable and spacious.', 'Diana Black', 5, 7, 0),
(42, 'Terrible experience. Performance was abysmal.', 'Bob White', 1, 6, 0),
(43, 'Absolutely love this car! Reliable and fuel-efficient.', 'Ethan Grey', 5, 8, 0),
(44, 'A complete letdown. Definitely not what I expected.', 'Bob White', 1, 5, 0),
(45, 'Decent for daily use. It meets basic needs.', 'Charlie Green', 3, 1, 0),
(46, 'Handles well and is reliable. Good value for the price.', 'Ethan Grey', 4, 4, 0),
(47, 'A solid choice for its class. A strong contender.', 'Alice Brown', 4, 5, 0),
(48, 'Handles well and is reliable. Minor issues but nothing major.', 'Charlie Green', 4, 4, 0),
(49, 'A complete letdown. Too many mechanical issues.', 'Alice Brown', 2, 7, 0),
(50, 'Could be better, but it works. It meets basic needs.', 'George Red', 3, 6, 0),
(51, 'Highly recommend to everyone. Smooth ride and great handling.', 'Fiona Blue', 5, 9, 0),
(52, 'Handles well and is reliable. Minor issues but nothing major.', 'Alice Brown', 4, 7, 0),
(53, 'Could be better, but it works. It meets basic needs.', 'George Red', 3, 6, 0),
(54, 'Very happy with the performance. Smooth ride and great handling.', 'Fiona Blue', 5, 1, 0),
(55, 'Terrible experience. Performance was abysmal.', 'Alice Brown', 1, 3, 0),
(56, 'Very happy with the performance. Incredibly comfortable and spacious.', 'Diana Black', 5, 9, 0),
(57, 'Handles well and is reliable. Minor issues but nothing major.', 'Hannah Gold', 4, 8, 0),
(58, 'Not worth the money at all. Too many mechanical issues.', 'John Doe', 2, 8, 0),
(59, 'Excellent vehicle! Smooth ride and great handling.', 'Hannah Gold', 5, 3, 0),
(60, 'Fairly neutral experience. Reliability seems standard.', 'Charlie Green', 3, 6, 0),
(61, 'Decent for daily use. It meets basic needs.', 'Fiona Blue', 3, 5, 0),
(62, 'Terrible experience. Too many mechanical issues.', 'Fiona Blue', 2, 6, 0),
(63, 'It is an average car. Performance is acceptable.', 'Hannah Gold', 3, 6, 0),
(64, 'It is an average car. It meets basic needs.', 'Charlie Green', 3, 3, 0),
(65, 'A solid choice for its class. A strong contender.', 'John Doe', 4, 4, 0),
(66, 'Terrible experience. Performance was abysmal.', 'George Red', 2, 1, 0),
(67, 'A solid choice for its class. Good value for the price.', 'Bob White', 4, 2, 0),
(68, 'Above average performance. Comfortable for long drives.', 'Fiona Blue', 4, 2, 0),
(69, 'Above average performance. Minor issues but nothing major.', 'Charlie Green', 4, 2, 0),
(70, 'Highly recommend to everyone. Exceeded all my expectations.', 'George Red', 5, 8, 0),
(71, 'Absolutely love this car! Exceeded all my expectations.', 'John Doe', 5, 8, 0),
(72, 'Excellent vehicle! Reliable and fuel-efficient.', 'Bob White', 5, 3, 0),
(73, 'Gets the job done, nothing more. It meets basic needs.', 'John Doe', 3, 2, 0),
(74, 'Not worth the money at all. Performance was abysmal.', 'Charlie Green', 2, 9, 0),
(75, 'A good car overall. A strong contender.', 'Charlie Green', 4, 5, 0),
(76, 'Decent for daily use. Reliability seems standard.', 'Alice Brown', 3, 4, 0),
(77, 'Terrible experience. Comfort is non-existent.', 'Fiona Blue', 1, 2, 0),
(78, 'Above average performance. Comfortable for long drives.', 'Alice Brown', 4, 9, 0),
(79, 'A solid choice for its class. Would consider buying again.', 'George Red', 4, 9, 0),
(80, 'Not worth the money at all. Too many mechanical issues.', 'Jane Smith', 2, 6, 0),
(81, 'Above average performance. Minor issues but nothing major.', 'Fiona Blue', 4, 1, 0),
(82, 'Not worth the money at all. Performance was abysmal.', 'Charlie Green', 2, 10, 0),
(83, 'It is an average car. No major complaints, but no praise either.', 'Jane Smith', 3, 6, 0),
(84, 'Above average performance. Would consider buying again.', 'Bob White', 4, 9, 0),
(85, 'Handles well and is reliable. Would consider buying again.', 'John Doe', 4, 7, 0),
(86, 'A good car overall. A strong contender.', 'John Doe', 4, 5, 0),
(87, 'Very happy with the performance. Smooth ride and great handling.', 'Diana Black', 5, 6, 0),
(88, 'Highly regret this purchase. The vehicle broke down quickly.', 'Ethan Grey', 2, 5, 0),
(89, 'Highly recommend to everyone. The features are amazing.', 'John Doe', 5, 6, 0),
(90, 'Terrible experience. Performance was abysmal.', 'Charlie Green', 2, 8, 0),
(91, 'Highly regret this purchase. Comfort is non-existent.', 'Ethan Grey', 2, 9, 0),
(92, 'A complete letdown. Comfort is non-existent.', 'Bob White', 2, 5, 0),
(93, 'Could be better, but it works. It meets basic needs.', 'Bob White', 3, 9, 0),
(94, 'Very disappointing. The vehicle broke down quickly.', 'Ethan Grey', 2, 6, 0),
(95, 'Terrible experience. Too many mechanical issues.', 'John Doe', 2, 5, 0),
(96, 'Fairly neutral experience. Performance is acceptable.', 'John Doe', 3, 3, 0),
(97, 'Very happy with the performance. The features are amazing.', 'Bob White', 5, 10, 0),
(98, 'Terrible experience. Definitely not what I expected.', 'George Red', 2, 7, 0),
(99, 'Above average performance. Minor issues but nothing major.', 'Charlie Green', 4, 2, 0),
(100, 'A complete letdown. Too many mechanical issues.', 'Bob White', 2, 9, 0),
(101, 'Above average performance. Would consider buying again.', 'Charlie Green', 4, 8, 0),
(102, 'Above average performance. Would consider buying again.', 'Charlie Green', 4, 1, 0),
(103, 'Could be better, but it works. Performance is acceptable.', 'Charlie Green', 3, 9, 0),
(104, 'Fantastic purchase decision. The features are amazing.', 'Charlie Green', 5, 4, 0),
(105, 'Could be better, but it works. Performance is acceptable.', 'Charlie Green', 3, 6, 0),
(106, 'Fantastic purchase decision. Smooth ride and great handling.', 'Hannah Gold', 5, 10, 0),
(107, 'Absolutely love this car! Smooth ride and great handling.', 'Charlie Green', 5, 3, 0),
(108, 'Very happy with the performance. Smooth ride and great handling.', 'Ethan Grey', 5, 4, 0),
(109, 'Could be better, but it works. It meets basic needs.', 'Bob White', 3, 1, 0),
(110, 'Fantastic purchase decision. Exceeded all my expectations.', 'Jane Smith', 5, 4, 0),
(111, 'Very disappointing. Performance was abysmal.', 'Diana Black', 2, 3, 0),
(112, 'Very disappointing. Comfort is non-existent.', 'Alice Brown', 1, 5, 0),
(113, 'Terrible experience. Too many mechanical issues.', 'George Red', 2, 8, 0),
(114, 'Quite satisfied with this vehicle. A strong contender.', 'Charlie Green', 4, 9, 0),
(115, 'A good car overall. Good value for the price.', 'Hannah Gold', 4, 9, 0),
(116, 'Fairly neutral experience. Performance is acceptable.', 'Hannah Gold', 3, 6, 0),
(117, 'Excellent vehicle! Reliable and fuel-efficient.', 'Jane Smith', 5, 1, 0),
(118, 'Handles well and is reliable. Would consider buying again.', 'John Doe', 4, 8, 0),
(119, 'Very disappointing. Comfort is non-existent.', 'Fiona Blue', 1, 8, 0),
(120, 'Terrible experience. Too many mechanical issues.', 'George Red', 2, 9, 0),
(121, 'Very disappointing. Too many mechanical issues.', 'Hannah Gold', 1, 10, 0),
(122, 'Excellent vehicle! Reliable and fuel-efficient.', 'George Red', 5, 2, 0),
(123, 'Gets the job done, nothing more. No major complaints, but no praise either.', 'Hannah Gold', 3, 10, 0),
(124, 'Above average performance. Minor issues but nothing major.', 'Alice Brown', 4, 9, 0),
(125, 'Terrible experience. Performance was abysmal.', 'Alice Brown', 2, 5, 0),
(126, 'Quite satisfied with this vehicle. Minor issues but nothing major.', 'Alice Brown', 4, 2, 0),
(127, 'Terrible experience. Definitely not what I expected.', 'Jane Smith', 1, 3, 0),
(128, 'Quite satisfied with this vehicle. A strong contender.', 'Diana Black', 4, 3, 0),
(129, 'Above average performance. Would consider buying again.', 'Diana Black', 4, 10, 0),
(130, 'Very disappointing. Comfort is non-existent.', 'Jane Smith', 2, 4, 0),
(131, 'Quite satisfied with this vehicle. Comfortable for long drives.', 'Alice Brown', 4, 2, 0),
(132, 'Quite satisfied with this vehicle. Would consider buying again.', 'John Doe', 4, 2, 0),
(133, 'Decent for daily use. Comfort is just okay.', 'Charlie Green', 3, 3, 0),
(134, 'Excellent vehicle! The features are amazing.', 'Jane Smith', 5, 9, 0),
(135, 'It is an average car. No major complaints, but no praise either.', 'George Red', 3, 3, 0),
(136, 'Excellent vehicle! Reliable and fuel-efficient.', 'Bob White', 5, 4, 0),
(137, 'Not worth the money at all. Definitely not what I expected.', 'Fiona Blue', 2, 7, 0),
(138, 'Not worth the money at all. Definitely not what I expected.', 'Charlie Green', 2, 10, 0),
(139, 'Not worth the money at all. Performance was abysmal.', 'Charlie Green', 2, 4, 0),
(140, 'Handles well and is reliable. Would consider buying again.', 'Fiona Blue', 4, 3, 0),
(141, 'A solid choice for its class. Would consider buying again.', 'Alice Brown', 4, 5, 0),
(142, 'A solid choice for its class. Would consider buying again.', 'Ethan Grey', 4, 5, 0),
(143, 'Highly regret this purchase. The vehicle broke down quickly.', 'Alice Brown', 1, 10, 0),
(144, 'Decent for daily use. No major complaints, but no praise either.', 'Alice Brown', 3, 8, 0),
(145, 'Very disappointing. The vehicle broke down quickly.', 'Fiona Blue', 1, 9, 0),
(146, 'Fantastic purchase decision. Reliable and fuel-efficient.', 'Fiona Blue', 5, 1, 0),
(147, 'It is an average car. Performance is acceptable.', 'Bob White', 3, 8, 0),
(148, 'Fantastic purchase decision. Smooth ride and great handling.', 'Fiona Blue', 5, 3, 0),
(149, 'Fantastic purchase decision. Incredibly comfortable and spacious.', 'Alice Brown', 5, 4, 0),
(150, 'A complete letdown. Performance was abysmal.', 'Diana Black', 2, 5, 0),
(151, 'Not worth the money at all. Definitely not what I expected.', 'Alice Brown', 1, 7, 0),
(152, 'Terrible experience. Comfort is non-existent.', 'Hannah Gold', 2, 4, 0),
(153, 'Highly regret this purchase. The vehicle broke down quickly.', 'Fiona Blue', 2, 1, 0),
(154, 'It is an average car. Reliability seems standard.', 'Alice Brown', 3, 5, 0),
(155, 'A complete letdown. The vehicle broke down quickly.', 'Ethan Grey', 1, 9, 0),
(156, 'Very disappointing. Definitely not what I expected.', 'John Doe', 2, 9, 0),
(157, 'Decent for daily use. It meets basic needs.', 'Jane Smith', 3, 2, 0),
(158, 'Fantastic purchase decision. Reliable and fuel-efficient.', 'Alice Brown', 5, 10, 0),
(159, 'Could be better, but it works. It meets basic needs.', 'Fiona Blue', 3, 6, 0),
(160, 'Fairly neutral experience. Performance is acceptable.', 'Jane Smith', 3, 5, 0),
(161, 'Excellent vehicle! Incredibly comfortable and spacious.', 'Fiona Blue', 5, 8, 0),
(162, 'Absolutely love this car! Smooth ride and great handling.', 'Charlie Green', 5, 10, 0),
(163, 'Highly regret this purchase. Performance was abysmal.', 'Charlie Green', 1, 8, 0),
(164, 'Handles well and is reliable. Minor issues but nothing major.', 'Hannah Gold', 4, 7, 0),
(165, 'Not worth the money at all. Comfort is non-existent.', 'Jane Smith', 2, 6, 0),
(166, 'Above average performance. Comfortable for long drives.', 'Ethan Grey', 4, 10, 0),
(167, 'Fantastic purchase decision. Reliable and fuel-efficient.', 'Diana Black', 5, 8, 0),
(168, 'Very disappointing. Definitely not what I expected.', 'Diana Black', 2, 3, 0),
(169, 'Absolutely love this car! Exceeded all my expectations.', 'George Red', 5, 3, 0),
(170, 'Excellent vehicle! Reliable and fuel-efficient.', 'Fiona Blue', 5, 7, 0),
(171, 'Very disappointing. Definitely not what I expected.', 'George Red', 1, 2, 0),
(172, 'Quite satisfied with this vehicle. Would consider buying again.', 'Fiona Blue', 4, 2, 0),
(173, 'Handles well and is reliable. A strong contender.', 'John Doe', 4, 9, 0),
(174, 'Highly recommend to everyone. Incredibly comfortable and spacious.', 'Diana Black', 5, 8, 0),
(175, 'Gets the job done, nothing more. No major complaints, but no praise either.', 'Ethan Grey', 3, 3, 0),
(176, 'A solid choice for its class. Minor issues but nothing major.', 'Fiona Blue', 4, 3, 0),
(177, 'Excellent vehicle! Reliable and fuel-efficient.', 'George Red', 5, 7, 0),
(178, 'A complete letdown. Definitely not what I expected.', 'John Doe', 1, 1, 0),
(179, 'Absolutely love this car! The features are amazing.', 'Ethan Grey', 5, 4, 0),
(180, 'Very disappointing. The vehicle broke down quickly.', 'Charlie Green', 1, 10, 0),
(181, 'Highly regret this purchase. Performance was abysmal.', 'George Red', 1, 2, 0),
(182, 'Very happy with the performance. The features are amazing.', 'Jane Smith', 5, 10, 0),
(183, 'Absolutely love this car! The features are amazing.', 'John Doe', 5, 10, 0),
(184, 'Above average performance. Would consider buying again.', 'Diana Black', 4, 4, 0),
(185, 'Highly regret this purchase. Comfort is non-existent.', 'John Doe', 1, 2, 0),
(186, 'A complete letdown. Definitely not what I expected.', 'Alice Brown', 1, 5, 0),
(187, 'Terrible experience. Definitely not what I expected.', 'Jane Smith', 2, 5, 0),
(188, 'A solid choice for its class. Good value for the price.', 'Fiona Blue', 4, 3, 0),
(189, 'Above average performance. Would consider buying again.', 'Hannah Gold', 4, 2, 0),
(190, 'Quite satisfied with this vehicle. Good value for the price.', 'John Doe', 4, 1, 0),
(191, 'Very disappointing. Too many mechanical issues.', 'George Red', 2, 3, 0),
(192, 'Could be better, but it works. Reliability seems standard.', 'Ethan Grey', 3, 4, 0),
(193, 'Quite satisfied with this vehicle. A strong contender.', 'Bob White', 4, 4, 0),
(194, 'Highly recommend to everyone. Exceeded all my expectations.', 'George Red', 5, 9, 0),
(195, 'It is an average car. No major complaints, but no praise either.', 'John Doe', 3, 3, 0),
(196, 'A good car overall. Would consider buying again.', 'Jane Smith', 4, 5, 0),
(197, 'Gets the job done, nothing more. Comfort is just okay.', 'Alice Brown', 3, 8, 0),
(198, 'Quite satisfied with this vehicle. A strong contender.', 'Charlie Green', 4, 5, 0),
(199, 'Handles well and is reliable. Minor issues but nothing major.', 'Charlie Green', 4, 10, 0),
(200, 'A good car overall. A strong contender.', 'Bob White', 4, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `id_car` int(11) NOT NULL,
  `client` varchar(32) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `down` float NOT NULL,
  `monthly` int(11) NOT NULL DEFAULT 0,
  `months` int(11) NOT NULL DEFAULT 0,
  `card_number` bigint(20) NOT NULL,
  `expiration_month` int(11) NOT NULL,
  `expiration_year` int(11) NOT NULL,
  `pin` int(11) NOT NULL,
  `datetimePurchase` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `id_car`, `client`, `employee_id`, `client_id`, `price`, `down`, `monthly`, `months`, `card_number`, `expiration_month`, `expiration_year`, `pin`, `datetimePurchase`, `deleted`) VALUES
(1, 7, 'John Doe', 3, 0, 20000, 2000, 1, 36, 4532756279624064, 5, 2026, 1234, '2024-06-12 14:40:06', 0),
(2, 1, 'Jane Smith', 7, 0, 56000, 0, 0, 0, 4716108999716532, 8, 2027, 4321, '2024-12-04 05:49:32', 0),
(3, 5, 'Carlos Rivera', 2, 0, 7000, 700, 1, 12, 6011000990139424, 3, 2028, 5678, '2024-04-13 09:07:27', 0),
(4, 6, 'Emily Johnson', 5, 0, 34000, 3400, 1, 18, 6011111111111117, 11, 2029, 8765, '2023-08-21 04:08:43', 0),
(5, 2, 'Liam Brown', 1, 0, 32000, 0, 0, 0, 5105105105105100, 6, 2030, 3456, '2024-05-01 17:21:15', 0),
(6, 6, 'Sophia Wilson', 9, 0, 34000, 3400, 1, 24, 4111111111111111, 12, 2026, 9876, '2023-10-03 02:20:10', 0),
(7, 1, 'Olivia Taylor', 8, 0, 56000, 0, 0, 0, 4222222222222, 7, 2028, 2468, '2024-10-08 07:37:05', 0),
(8, 1, 'William Martinez', 4, 0, 56000, 5600, 1, 24, 378282246310005, 2, 2027, 1357, '2023-08-02 07:05:03', 0),
(9, 7, 'Mia Anderson', 6, 0, 20000, 2000, 1, 12, 6011111111111117, 10, 2030, 2468, '2024-08-12 12:34:00', 0),
(10, 5, 'James Thomas', 1, 0, 7000, 0, 0, 0, 5425233430109903, 9, 2029, 1234, '2023-04-25 17:34:57', 0),
(11, 3, 'Charlotte Garcia', 3, 0, 5000, 500, 1, 36, 4916012544445423, 5, 2026, 9876, '2023-09-25 02:12:46', 0),
(12, 6, 'Benjamin Lee', 10, 0, 34000, 3400, 1, 24, 4539314185927235, 11, 2027, 7654, '2023-09-18 06:19:00', 0),
(13, 7, 'Evelyn Harris', 2, 0, 20000, 2000, 1, 36, 6011000995504100, 8, 2028, 8765, '2024-05-15 00:56:46', 0),
(14, 3, 'Lucas White', 7, 0, 5000, 0, 0, 0, 4485739310396723, 3, 2030, 2345, '2023-09-15 09:46:55', 0),
(15, 2, 'Amelia Walker', 5, 0, 32000, 3200, 1, 12, 4024007139845788, 4, 2027, 9876, '2024-06-01 22:04:17', 0),
(16, 7, 'Alexander Hall', 9, 0, 20000, 2000, 1, 18, 6011123678931256, 10, 2026, 8642, '2023-12-22 09:00:45', 0),
(17, 7, 'Ella Allen', 1, 0, 20000, 2000, 1, 36, 4263982640269299, 6, 2031, 6543, '2023-08-12 02:50:58', 0),
(18, 5, 'Daniel Young', 4, 0, 7000, 0, 0, 0, 5425233430109903, 2, 2029, 7832, '2023-02-19 11:12:36', 0),
(19, 3, 'Scarlett Hernandez', 8, 0, 5000, 500, 1, 36, 4532756279624064, 7, 2032, 4321, '2023-11-05 23:30:07', 0),
(20, 2, 'Henry King', 3, 0, 32000, 0, 0, 0, 6011000990139424, 9, 2028, 2468, '2024-10-27 11:52:53', 0),
(21, 2, 'Grace Scott', 5, 0, 32000, 3200, 1, 12, 6011123678931256, 4, 2027, 1234, '2023-07-27 12:54:07', 0),
(22, 7, 'Samuel Adams', 6, 0, 20000, 2000, 1, 18, 4539314185927235, 7, 2028, 5678, '2024-05-18 04:51:57', 0),
(23, 2, 'Victoria Carter', 8, 0, 32000, 0, 0, 0, 4916012544445423, 11, 2030, 8765, '2024-03-07 09:37:29', 0),
(24, 2, 'Sebastian Ramirez', 2, 0, 32000, 0, 0, 0, 4111111111111111, 2, 2026, 6543, '2024-10-09 09:31:35', 0),
(25, 2, 'Harper Nelson', 4, 0, 32000, 3200, 1, 36, 5425233430109903, 9, 2031, 4321, '2024-04-25 18:45:32', 0),
(26, 7, 'Jack Mitchell', 7, 0, 20000, 0, 0, 0, 6011000990139424, 8, 2028, 2468, '2024-04-06 17:12:58', 0),
(27, 5, 'Isla Murphy', 1, 0, 7000, 700, 1, 12, 6011111111111117, 6, 2029, 1357, '2023-05-16 05:48:19', 0),
(28, 4, 'Daniel Hughes', 9, 0, 1, 0.1, 1, 18, 378282246310005, 5, 2027, 7890, '2023-01-23 01:22:40', 0),
(29, 3, 'Luna Torres', 3, 0, 5000, 0, 0, 0, 4532756279624064, 10, 2030, 9864, '2024-03-11 13:28:24', 0),
(30, 4, 'Logan Flores', 10, 0, 1, 0.1, 1, 24, 4222222222222, 1, 2027, 5648, '2024-10-11 15:14:05', 0),
(31, 1, 'Chloe Price', 6, 0, 56000, 5600, 1, 36, 4916012544445423, 9, 2032, 7654, '2024-04-24 11:45:18', 0),
(32, 3, 'Owen Jenkins', 1, 0, 5000, 500, 1, 24, 4539314185927235, 3, 2028, 4321, '2024-03-24 13:04:17', 0),
(33, 3, 'Layla Cooper', 8, 0, 5000, 0, 0, 0, 6011000995504100, 2, 2031, 3456, '2023-03-15 06:05:36', 0),
(34, 4, 'Nathan Gray', 5, 0, 1, 0.1, 1, 18, 4263982640269299, 8, 2026, 2345, '2024-04-27 15:15:10', 0),
(35, 5, 'Aria Brooks', 3, 0, 7000, 700, 1, 36, 6011123678931256, 4, 2029, 1357, '2023-01-01 09:59:04', 0),
(36, 4, 'Mason Powell', 9, 0, 1, 0, 0, 0, 5105105105105100, 12, 2027, 6543, '2023-01-17 04:09:54', 0),
(37, 7, 'Ellie Bell', 2, 0, 20000, 2000, 1, 36, 4532756279624064, 6, 2030, 8765, '2023-03-21 14:52:19', 0),
(38, 2, 'Leo Morgan', 7, 0, 32000, 0, 0, 0, 6011000990139424, 11, 2031, 2468, '2023-12-16 13:51:57', 0),
(39, 6, 'Zoe Griffin', 4, 0, 34000, 3400, 1, 12, 378282246310005, 10, 2027, 7890, '2023-02-17 00:42:50', 0),
(40, 6, 'Carter Reed', 10, 0, 34000, 3400, 1, 18, 6011111111111117, 1, 2029, 9876, '2024-10-10 09:58:19', 0),
(41, 2, 'Hannah Fox', 6, 0, 32000, 3200, 1, 36, 4222222222222, 5, 2026, 8642, '2023-06-26 23:43:10', 0),
(42, 7, 'Elijah Ward', 3, 0, 20000, 0, 0, 0, 4916012544445423, 7, 2030, 7564, '2024-02-06 16:40:57', 0),
(43, 7, 'Addison Bailey', 8, 0, 20000, 2000, 1, 36, 4539314185927235, 3, 2032, 3245, '2023-01-16 12:15:17', 0),
(44, 6, 'Gabriel Bryant', 1, 0, 34000, 0, 0, 0, 6011000995504100, 2, 2028, 9871, '2023-12-01 11:13:34', 0),
(45, 2, 'Penelope Hayes', 5, 0, 32000, 3200, 1, 12, 4263982640269299, 8, 2027, 6124, '2023-06-13 19:22:04', 0),
(46, 7, 'Hunter Perry', 7, 0, 20000, 2000, 1, 18, 6011123678931256, 4, 2026, 8574, '2024-06-30 15:09:40', 0),
(47, 6, 'Avery Cruz', 9, 0, 34000, 0, 0, 0, 5105105105105100, 9, 2031, 5432, '2023-02-18 17:42:14', 0),
(48, 2, 'Stella Jenkins', 4, 0, 32000, 3200, 1, 24, 4532756279624064, 11, 2029, 1286, '2023-03-07 19:02:11', 0),
(49, 4, 'David Sanders', 2, 0, 1, 0.1, 1, 36, 6011000990139424, 6, 2027, 6724, '2023-07-02 18:04:14', 0),
(50, 7, 'Leah Barnes', 3, 0, 20000, 0, 0, 0, 378282246310005, 10, 2030, 4187, '2024-12-17 09:14:40', 0),
(51, 2, 'Nick Rangel', 1, 0, 32000, 3200, 0, 0, 1234567890000000, 8, 2027, 1234, '2023-04-20 16:00:45', 0),
(52, 2, 'Fofo Marquez', 1, 0, 32000, 9600, 0, 0, 1234567890987654, 8, 2027, 2345, '2024-08-15 04:19:46', 0),
(53, 2, 'Nick Rangel', 1, 0, 32000, 9600, 0, 0, 2345672345678987, 8, 2027, 2345, '2024-03-14 21:36:37', 0),
(54, 2, 'Nickla Rangel', 1, 0, 32000, 9600, 0, 0, 3456789098765434, 8, 2027, 3456, '2024-02-22 23:03:52', 0),
(55, 2, 'Daniel O\'tarris', 1, 0, 32000, 9600, 0, 0, 5896123655478965, 8, 4568, 3456, '2023-02-12 02:29:34', 0),
(105, 2, 'Kadir', 1, 0, 32000, 9600, 0, 0, 1234567890000000, 8, 2027, 3949, '2024-02-23 15:16:12', 0),
(106, 2, 'Kadir Koprulu Hernandez', 1, 0, 32000, 6400, 0, 0, 1234567890000000, 8, 7899, 7879, '2024-05-19 20:52:21', 0),
(107, 2, 'Kadir Koprulu Hernandez', 1, 0, 32000, 9600, 0, 0, 1234567890000000, 8, 2029, 4567, '2024-06-21 10:33:14', 0),
(108, 2, 'Kadir Koprulu Hernandez', 1, 0, 32000, 6400, 0, 0, 1234567890000000, 9, 9090, 909, '2024-03-17 14:09:08', 0),
(109, 2, 'Ka', 1, 0, 32000, 3200, 0, 0, 1234567890000000, 9, 9090, 9090, '2024-08-18 15:06:03', 0),
(110, 2, 'Kadir Koprulu Hernandez', 1, 0, 32000, 3200, 0, 0, 1234567890000000, 9, 9090, 909, '2023-07-11 09:02:55', 0),
(111, 2, 'Kadir Koprulu Hernandez', 1, 0, 32000, 6400, 0, 0, 1234567890000000, 9, 9090, 9090, '2024-09-23 23:56:26', 0),
(112, 2, 'Kadir Koprulu Hernandez', 1, 0, 32000, 6400, 0, 0, 1234567890000000, 9, 9090, 9090, '2024-01-28 20:33:33', 0),
(113, 4, 'Bob Johnny', 1, 0, 1, 0.1, 0, 0, 1234567890000000, 9, 9090, 9090, '2023-03-10 06:58:27', 0),
(114, 6, 'K', 1, 0, 34000, 3400, 0, 0, 1234567890000000, 9, 9090, 9090, '2024-09-16 21:11:35', 0),
(115, 1, 'Kadir Koprulu Hernandez', 1, 0, 56000, 11200, 0, 0, 1234567890000000, 9, 9090, 9876, '2024-12-27 13:02:42', 0),
(116, 7, 'Kadir', 1, 0, 20000, 6000, 0, 0, 1234567890000000, 9, 9090, 9090, '2025-05-14 20:18:34', 0),
(117, 1, 'Kadir Koprulu Hernandez', 1, 0, 56000, 5600, 0, 0, 1234567890000000, 9, 9090, 9090, '2025-05-22 09:11:16', 0),
(118, 1, 'Kadir Koprulu Hernandez', 1, 0, 56000, 5600, 0, 0, 1234567890000000, 9, 9090, 9090, '2025-05-22 09:13:21', 0),
(119, 1, 'Kadir Koprulu Hernandez', 1, 0, 56000, 5600, 0, 0, 1234567890000000, 9, 9090, 9090, '2025-05-22 09:14:34', 0),
(120, 1, 'Kadir Koprulu Hernandez', 1, 0, 56000, 5600, 0, 0, 1234567890000000, 9, 9090, 9090, '2025-05-22 09:18:54', 0),
(121, 1, 'Kadir Koprulu Hernandez', 1, 0, 56000, 5600, 0, 0, 1234567890000000, 9, 9090, 9090, '2025-05-22 09:25:20', 0),
(122, 1, 'Kadir Koprulu Hernandez', 1, 3, 56000, 5600, 0, 12, 2526935717563634, 8, 2026, 4518, '2025-05-22 09:33:27', 0),
(123, 7, 'Kadir Koprulu Hernandez', 2, 3, 20000, 2000, 0, 0, 1234567890000000, 9, 9090, 9090, '2025-05-22 09:42:34', 0),
(124, 9, 'Kadir Koprulu Hernandez', 3, 3, 62000, 6200, 0, 55, 1234567890000000, 9, 2035, 9090, '2025-05-25 11:11:50', 0),
(125, 9, 'Kadir Koprulu Hernandez', 3, 3, 62000, 6200, 0, 0, 1234567890000000, 9, 2035, 9090, '2025-05-25 11:13:20', 0),
(126, 9, 'Kadir Koprulu Hernandez', 4, 3, 62000, 6200, 1, 33, 1234567890000000, 9, 2035, 909, '2025-05-25 11:13:53', 0);

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `id_employee` int(11) NOT NULL,
  `date_request` date NOT NULL,
  `date_finish` date DEFAULT NULL,
  `date_pickup` date DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `id_car` int(11) NOT NULL,
  `problem` text NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `id_employee`, `date_request`, `date_finish`, `date_pickup`, `id_user`, `id_car`, `problem`, `deleted`) VALUES
(1, 11, '2025-05-25', NULL, NULL, 3, 122, 'Oil change', 0),
(2, 11, '2025-05-25', '2025-05-23', '2025-05-21', 3, 122, 'Oil change', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `fullname`, `profile_picture`) VALUES
(1, 'Fy644', '$2y$10$9UrXEwJhcjp/lOJ8ycqNhuiOONPMxIfQ3/povF95d8FPYV8aKsAlK', '', '', NULL),
(2, 'Kadir', '$2y$10$QNufLCcjYvhuE2ICmWSgIeeKxE4vpX7fb.y.Zg33IIj11TIK4.AZy', '', '', NULL),
(3, 'fy64', '$2y$10$6qYFDz5LcKUSNO7ceyRrreg619.bgFHWU7ZIE.JGRwor15JxNxfnm', 'fy6644@gmail.com', 'Kadir Koprulu Hernandez', 'userpfp/682576ccb04cc.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carros`
--
ALTER TABLE `carros`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `carros`
--
ALTER TABLE `carros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

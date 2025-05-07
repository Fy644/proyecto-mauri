-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 09, 2025 at 05:28 PM
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
(2, 'nick_rangel', '');

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
  `img_name` varchar(32) NOT NULL,
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
(8, 'Pegassi Infernus', 230000, 'sport', 1, 'The performance of the Infernus remains similar in all of its appearances by being one of, if not the fastest car in each game. Unlike subsequent depictions that have an all-wheel-drive system, the Infernus in GTA III has a rear-wheel-drive layout.\r\n\r\nDespite the change in drivetrain layout, its handling remains similar and its all-wheel-drive system means the car has good front grip.', 'pegassi_infernus', 2023, 0, 0);

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
(2, '2025-04-25 10:00:00', 'brah idk', 0, 6, 1, 0);

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
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `level`, `rfc`, `phone`, `deleted`) VALUES
(1, 'Bob Johnny', 0, 'abcdefghijklm', 4495810546, 0),
(2, 'Alice Johnson', 1, '', 0, 0),
(3, 'Bob Smith', 2, '', 0, 0),
(4, 'Charlie Brown', 3, '', 0, 0),
(5, 'Diana Evans', 1, '', 0, 0),
(6, 'Ethan Foster', 2, '', 0, 0),
(7, 'Fiona Green', 3, '', 0, 0),
(8, 'George Harris', 1, '', 0, 0),
(9, 'Hannah White', 2, '', 0, 0),
(10, 'Ian King', 3, '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `id_car` int(11) NOT NULL,
  `client` varchar(32) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `percent` float NOT NULL,
  `down` float NOT NULL,
  `monthly` tinyint(1) NOT NULL,
  `months` int(11) NOT NULL,
  `card_number` bigint(20) NOT NULL,
  `expiration_month` int(11) NOT NULL,
  `expiration_year` int(11) NOT NULL,
  `pin` int(11) NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `id_car`, `client`, `employee_id`, `price`, `percent`, `down`, `monthly`, `months`, `card_number`, `expiration_month`, `expiration_year`, `pin`, `deleted`) VALUES
(1, 7, 'John Doe', 3, 20000, 0.1605, 2000, 1, 36, 4532756279624064, 5, 2026, 1234, 0),
(2, 1, 'Jane Smith', 7, 56000, 0.4124, 0, 0, 0, 4716108999716532, 8, 2027, 4321, 0),
(3, 5, 'Carlos Rivera', 2, 7000, 0.5802, 700, 1, 12, 6011000990139424, 3, 2028, 5678, 0),
(4, 6, 'Emily Johnson', 5, 34000, 0.6638, 3400, 1, 18, 6011111111111117, 11, 2029, 8765, 0),
(5, 2, 'Liam Brown', 1, 32000, 0.5783, 0, 0, 0, 5105105105105100, 6, 2030, 3456, 0),
(6, 6, 'Sophia Wilson', 9, 34000, 0.9001, 3400, 1, 24, 4111111111111111, 12, 2026, 9876, 0),
(7, 1, 'Olivia Taylor', 8, 56000, 0.7655, 0, 0, 0, 4222222222222, 7, 2028, 2468, 0),
(8, 1, 'William Martinez', 4, 56000, 0.1274, 5600, 1, 24, 378282246310005, 2, 2027, 1357, 0),
(9, 7, 'Mia Anderson', 6, 20000, 0.3405, 2000, 1, 12, 6011111111111117, 10, 2030, 2468, 0),
(10, 5, 'James Thomas', 1, 7000, 0.3204, 0, 0, 0, 5425233430109903, 9, 2029, 1234, 0),
(11, 3, 'Charlotte Garcia', 3, 5000, 0.5803, 500, 1, 36, 4916012544445423, 5, 2026, 9876, 0),
(12, 6, 'Benjamin Lee', 10, 34000, 0.9403, 3400, 1, 24, 4539314185927235, 11, 2027, 7654, 0),
(13, 7, 'Evelyn Harris', 2, 20000, 0.9605, 2000, 1, 36, 6011000995504100, 8, 2028, 8765, 0),
(14, 3, 'Lucas White', 7, 5000, 0.9815, 0, 0, 0, 4485739310396723, 3, 2030, 2345, 0),
(15, 2, 'Amelia Walker', 5, 32000, 0.0263, 3200, 1, 12, 4024007139845788, 4, 2027, 9876, 0),
(16, 7, 'Alexander Hall', 9, 20000, 0.1867, 2000, 1, 18, 6011123678931256, 10, 2026, 8642, 0),
(17, 7, 'Ella Allen', 1, 20000, 0.8547, 2000, 1, 36, 4263982640269299, 6, 2031, 6543, 0),
(18, 5, 'Daniel Young', 4, 7000, 0.7135, 0, 0, 0, 5425233430109903, 2, 2029, 7832, 0),
(19, 3, 'Scarlett Hernandez', 8, 5000, 0.0035, 500, 1, 36, 4532756279624064, 7, 2032, 4321, 0),
(20, 2, 'Henry King', 3, 32000, 0.8768, 0, 0, 0, 6011000990139424, 9, 2028, 2468, 0),
(21, 2, 'Grace Scott', 5, 32000, 0.3737, 3200, 1, 12, 6011123678931256, 4, 2027, 1234, 0),
(22, 7, 'Samuel Adams', 6, 20000, 0.2379, 2000, 1, 18, 4539314185927235, 7, 2028, 5678, 0),
(23, 2, 'Victoria Carter', 8, 32000, 0.0685, 0, 0, 0, 4916012544445423, 11, 2030, 8765, 0),
(24, 2, 'Sebastian Ramirez', 2, 32000, 0.6288, 0, 0, 0, 4111111111111111, 2, 2026, 6543, 0),
(25, 2, 'Harper Nelson', 4, 32000, 0.9386, 3200, 1, 36, 5425233430109903, 9, 2031, 4321, 0),
(26, 7, 'Jack Mitchell', 7, 20000, 0.8065, 0, 0, 0, 6011000990139424, 8, 2028, 2468, 0),
(27, 5, 'Isla Murphy', 1, 7000, 0.2166, 700, 1, 12, 6011111111111117, 6, 2029, 1357, 0),
(28, 4, 'Daniel Hughes', 9, 1, 0.6636, 0.1, 1, 18, 378282246310005, 5, 2027, 7890, 0),
(29, 3, 'Luna Torres', 3, 5000, 0.6682, 0, 0, 0, 4532756279624064, 10, 2030, 9864, 0),
(30, 4, 'Logan Flores', 10, 1, 0.3501, 0.1, 1, 24, 4222222222222, 1, 2027, 5648, 0),
(31, 1, 'Chloe Price', 6, 56000, 0.7461, 5600, 1, 36, 4916012544445423, 9, 2032, 7654, 0),
(32, 3, 'Owen Jenkins', 1, 5000, 0.6802, 500, 1, 24, 4539314185927235, 3, 2028, 4321, 0),
(33, 3, 'Layla Cooper', 8, 5000, 0.1627, 0, 0, 0, 6011000995504100, 2, 2031, 3456, 0),
(34, 4, 'Nathan Gray', 5, 1, 0.7727, 0.1, 1, 18, 4263982640269299, 8, 2026, 2345, 0),
(35, 5, 'Aria Brooks', 3, 7000, 0.3756, 700, 1, 36, 6011123678931256, 4, 2029, 1357, 0),
(36, 4, 'Mason Powell', 9, 1, 0.5599, 0, 0, 0, 5105105105105100, 12, 2027, 6543, 0),
(37, 7, 'Ellie Bell', 2, 20000, 0.6726, 2000, 1, 36, 4532756279624064, 6, 2030, 8765, 0),
(38, 2, 'Leo Morgan', 7, 32000, 0.6835, 0, 0, 0, 6011000990139424, 11, 2031, 2468, 0),
(39, 6, 'Zoe Griffin', 4, 34000, 0.3994, 3400, 1, 12, 378282246310005, 10, 2027, 7890, 0),
(40, 6, 'Carter Reed', 10, 34000, 0.9465, 3400, 1, 18, 6011111111111117, 1, 2029, 9876, 0),
(41, 2, 'Hannah Fox', 6, 32000, 0.5346, 3200, 1, 36, 4222222222222, 5, 2026, 8642, 0),
(42, 7, 'Elijah Ward', 3, 20000, 0.8334, 0, 0, 0, 4916012544445423, 7, 2030, 7564, 0),
(43, 7, 'Addison Bailey', 8, 20000, 0.563, 2000, 1, 36, 4539314185927235, 3, 2032, 3245, 0),
(44, 6, 'Gabriel Bryant', 1, 34000, 0.3151, 0, 0, 0, 6011000995504100, 2, 2028, 9871, 0),
(45, 2, 'Penelope Hayes', 5, 32000, 0.8863, 3200, 1, 12, 4263982640269299, 8, 2027, 6124, 0),
(46, 7, 'Hunter Perry', 7, 20000, 0.4861, 2000, 1, 18, 6011123678931256, 4, 2026, 8574, 0),
(47, 6, 'Avery Cruz', 9, 34000, 0.7716, 0, 0, 0, 5105105105105100, 9, 2031, 5432, 0),
(48, 2, 'Stella Jenkins', 4, 32000, 0.3999, 3200, 1, 24, 4532756279624064, 11, 2029, 1286, 0),
(49, 4, 'David Sanders', 2, 1, 0.6846, 0.1, 1, 36, 6011000990139424, 6, 2027, 6724, 0),
(50, 7, 'Leah Barnes', 3, 20000, 0.2232, 0, 0, 0, 378282246310005, 10, 2030, 4187, 0),
(100, 2, 'Nick Rangel', 1, 32000, 0.05, 3200, 0, 0, 1234567890000000, 8, 2027, 1234, 0),
(101, 2, 'Fofo Marquez', 1, 32000, 0.15, 9600, 0, 0, 1234567890987654, 8, 2027, 2345, 0),
(102, 2, 'Nick Rangel', 1, 32000, 0.15, 9600, 0, 0, 2345672345678987, 8, 2027, 2345, 0),
(103, 2, 'Nickla Rangel', 1, 32000, 0.15, 9600, 0, 0, 3456789098765434, 8, 2027, 3456, 0),
(104, 2, 'Daniel O\'tarris', 1, 32000, 0.15, 9600, 0, 0, 5896123655478965, 8, 4568, 3456, 0);

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
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `carros`
--
ALTER TABLE `carros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

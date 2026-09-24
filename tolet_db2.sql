-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2026 at 09:53 PM
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
-- Database: `tolet_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `rent` decimal(10,2) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `property_type` varchar(50) DEFAULT NULL,
  `status` enum('available','rented') DEFAULT 'available',
  `approval_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `owner_id`, `title`, `description`, `rent`, `location`, `property_type`, `status`, `approval_status`, `created_at`) VALUES
(1, 2, '3BHK Flat', '3 bedroom flat', 1200.00, 'Nikunja', 'Room', 'available', 'approved', '2026-04-18 21:06:34'),
(2, 2, 'Grand Mansion', '3 bed ,4 bath, parking Available', 25070.00, 'Uttara 11', 'Flat', 'available', 'approved', '2026-04-19 07:42:14'),
(3, 5, 'Slow Burn', '2BHK, Parking available, Bua, ', 15200.00, 'Nikunja 02, Dhaka', 'Flat', 'available', 'approved', '2026-04-19 07:52:31'),
(4, 7, 'Carabat Aranis', '4BHK Flat\r\nAll facilities available... Utility free', 45500.00, 'Near Dhaka Airport', 'Flat', 'available', 'approved', '2026-04-19 09:43:46');

-- --------------------------------------------------------

--
-- Table structure for table `property_images`
--

CREATE TABLE `property_images` (
  `id` int(11) NOT NULL,
  `property_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_images`
--

INSERT INTO `property_images` (`id`, `property_id`, `image_path`) VALUES
(1, 1, '1776546394_WhatsApp Image 2025-05-19 at 23.14.19_72133db7.jpg'),
(2, 2, '1776584534_images.jpg'),
(3, 3, '1776585151_images (2).jpg'),
(4, 4, '1776591826_download.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `property_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `property_id`, `user_id`, `message`, `status`, `created_at`) VALUES
(1, 1, 1, 'hii sadi..i want to rent ur property ', 'pending', '2026-04-19 07:32:15'),
(2, 3, 1, 'hello bhsi i want to visit your property', 'pending', '2026-04-19 07:53:46'),
(3, 2, 1, 'Hello Bhai is it available?', 'pending', '2026-04-19 08:51:16'),
(4, 4, 6, 'intersted...\r\nplz call me when u r free', 'pending', '2026-04-19 09:57:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('tenant','owner','admin') DEFAULT 'tenant',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Ahmad Reza', '01730224720', 'ahmadreza@gmail.com', '$2y$10$iyOGoB9v1RYb4g4RA9KXNuBfsxbLKJATZQ56v084GYVWrC5IbofAm', 'tenant', '2026-04-18 19:51:46'),
(2, 'Saleh Sadik', '01671082199', 'sadik@gmail.com', '$2y$10$RJQBzZUZqt6tkirEpViareKpLvx.fX3tsRiJMdw1tQ21Rnb.L9/LC', 'owner', '2026-04-18 20:35:29'),
(3, 'Super Admin', '01521217918', 'admin@tolet.com', '$2y$10$wH5z9lQ8u3Y0vQq7vQq7vOeQZkQmQmQmQmQmQmQmQmQmQmQmQm', 'admin', '2026-04-18 21:21:43'),
(4, 'Super Admin', '01700000000', 'admin@tolet.com', '$2y$10$72Zb9BCRNRDj.IY4afgoGuEcuYrsOwYlpQKxQOWa7fDocGUb6jBS6', 'admin', '2026-04-18 21:36:54'),
(5, 'MUM water', '01234567890', 'mum@yahoo.com', '$2y$10$TvSwGnPLp6E9v0IeLMmTTuFp.IV1djViC28EpMWx9WdnjGDPqKbXi', 'owner', '2026-04-19 07:44:27'),
(6, 'Chudling Png', '013', 'chudlingpong@gmail.com', '$2y$10$yF5FFeFU.cB4qCwxnmkzDuLUNUMkTuoSAB3wr7wITPFClwKxjijV6', 'tenant', '2026-04-19 08:53:46'),
(7, 'Carabat', '1010101010', 'carabat@yaho.com', '$2y$10$iVIroaRk.eZBnsZ0I9vFwuR9ZVs0jtde.WSPs0ZuDorC0atPDGztm', 'owner', '2026-04-19 09:39:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `property_images`
--
ALTER TABLE `property_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `property_images`
--
ALTER TABLE `property_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `properties`
--
ALTER TABLE `properties`
  ADD CONSTRAINT `properties_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_images`
--
ALTER TABLE `property_images`
  ADD CONSTRAINT `property_images_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

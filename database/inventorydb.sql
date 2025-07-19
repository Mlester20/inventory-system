-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2025 at 04:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventorydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_name` text DEFAULT NULL,
  `category` text DEFAULT NULL,
  `unit` text DEFAULT NULL,
  `price` double DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `user_id`, `product_name`, `category`, `unit`, `price`, `expiration_date`, `created_at`) VALUES
(1, 2, 'Alcohol', 'Medicine', '35', 15, '2025-07-19', '2025-07-19 15:13:11'),
(2, 2, 'Feeds', 'Foods', '25', 35, '2025-09-30', '2025-07-19 15:18:35'),
(3, 2, 'Samsung S22 ', 'Electronics', '32', 18999, '2025-07-31', '2025-07-19 15:57:23'),
(24, 2, 'Organic Chicken Feed', 'Poultry', 'kg', 35.5, '2025-12-01', '2025-07-10 00:00:00'),
(25, 2, 'Natural Pig Meal', 'Livestock', 'kg', 42, '2025-11-15', '2025-06-18 00:00:00'),
(26, 2, 'Fresh Duckweed', 'Aquatic Plant', 'kg', 15, '2025-10-20', '2025-05-30 00:00:00'),
(27, 2, 'Soya Meal', 'Protein Source', 'kg', 55, '2025-08-12', '2025-07-01 00:00:00'),
(28, 2, 'Copra Cake', 'Energy Source', 'kg', 28.75, '2025-12-05', '2025-03-22 00:00:00'),
(29, 2, 'Molasses', 'Supplement', 'liter', 18.25, '2026-01-10', '2025-04-15 00:00:00'),
(30, 2, 'Fermented Banana', 'Energy Source', 'kg', 22, '2025-09-09', '2025-06-25 00:00:00'),
(31, 2, 'Flamengia Leaves', 'Fiber Source', 'kg', 19.5, '2025-08-30', '2025-07-12 00:00:00'),
(32, 2, 'Rensonii', 'Green Feed', 'kg', 14.75, '2025-10-10', '2025-05-18 00:00:00'),
(33, 2, 'Duck Starter Mix', 'Poultry', 'kg', 37, '2025-11-20', '2025-06-02 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `stock_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`stock_id`, `product_id`, `quantity`, `last_updated`) VALUES
(1, 1, 35, '2025-07-19 15:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `stock_logs`
--

CREATE TABLE `stock_logs` (
  `log_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `change_amount` int(11) DEFAULT NULL,
  `type` text DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `address` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `address`, `email`, `password`, `role`) VALUES
(2, 'Mark Lester Raguindin', 'San Diego, California', 'suguitanmark123@gmail.com', '353e0f8a9bc0fe11bb0099c4c009d45c', 'admin'),
(3, 'Test', 'Rizal, Roxas', 'test@gmail.com', '$2y$10$qaeRfF.IGqAOSCo.aRSi3elOQDJ53HiELgYVNq7kpRI', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `stock_logs`
--
ALTER TABLE `stock_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock_logs`
--
ALTER TABLE `stock_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `stock_logs`
--
ALTER TABLE `stock_logs`
  ADD CONSTRAINT `stock_logs_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

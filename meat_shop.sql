-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3305
-- Generation Time: Mar 23, 2025 at 05:01 AM
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
-- Database: `meat_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `bill_no` int(100) NOT NULL,
  `cus_name` varchar(20) NOT NULL,
  `date` datetime(2) NOT NULL,
  `cus_phone` char(10) NOT NULL,
  `grand_total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`bill_no`, `cus_name`, `date`, `cus_phone`, `grand_total`) VALUES
(250000, 'Mayil', '2025-03-16 20:32:00.00', '8870426718', 1735),
(250001, 'Cash Customer ', '2025-03-16 20:38:00.00', 'null', 350),
(250002, 'Aswak', '2025-03-16 20:39:00.00', '7845086717', 975),
(250003, 'Cash Customer ', '2025-03-16 21:24:00.00', 'null', 1985),
(250004, 'periyan', '2025-03-17 11:52:00.00', '98755432', 350),
(250005, 'Mayil', '2025-03-17 11:59:00.00', '8870426718', 1000),
(250012, 'periyan', '2025-03-17 12:21:00.00', '98755432', 215),
(250013, 'Cash Customer ', '2025-03-19 10:45:00.00', 'null', 1260),
(250014, 'Aswak', '2025-03-19 10:49:00.00', '7845086717', 690),
(250015, 'Cash Customer ', '2025-03-19 10:51:00.00', 'null', 690);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cus_id` int(10) NOT NULL,
  `cus_name` varchar(15) NOT NULL,
  `cus_phone` char(10) NOT NULL,
  `cus_address` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cus_id`, `cus_name`, `cus_phone`, `cus_address`) VALUES
(1, 'Cash Customer ', 'null', '-'),
(2, 'periyan', '98755432', 'kalakurichi'),
(3, 'gokul', '2147483642', 'sellapacolny,namakkal'),
(4, 'prakash', '2147483643', 'puthukottai'),
(5, 'Aswak', '7845086717', 'Thrippur'),
(6, 'dasigan', '2147483647', 'salem'),
(7, 'sundar', '9087234876', 'sullangudi'),
(8, 'Cash Customer ', 'none', '-'),
(10, 'Mayil', '8870426718', 'Sundarampalli vill,');

-- --------------------------------------------------------

--
-- Table structure for table `distributors`
--

CREATE TABLE `distributors` (
  `distributor_id` int(11) NOT NULL,
  `distributor_name` varchar(30) NOT NULL,
  `distributor_address` varchar(40) NOT NULL,
  `distributor_phone` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `distributors`
--

INSERT INTO `distributors` (`distributor_id`, `distributor_name`, `distributor_address`, `distributor_phone`) VALUES
(1, 'AB traders', 'Namakkal', '2147483647'),
(2, 'Ranga', 'Dharmapuri ', '3456278901'),
(3, 'Murugan poultry farm', 'Namakkal', '4567834222'),
(4, 'Balaji ', 'Namakkal', '0987654321');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `emp_id` int(10) NOT NULL,
  `emp_name` varchar(25) NOT NULL,
  `emp_address` varchar(50) NOT NULL,
  `emp_phone` char(10) NOT NULL,
  `emp_salary` float NOT NULL,
  `emp_position` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`emp_id`, `emp_name`, `emp_address`, `emp_phone`, `emp_salary`, `emp_position`) VALUES
(1, 'mayil', 'Sundarampalli vill,', '2147483647', 10000, 'MD'),
(2, 'siva', 'Sundarampalli vill,', '8870426718', 8000, 'Cashier '),
(9, 'Gowri', 'Sundarampalli vill,', '9566627398', 13000, 'MD');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `customerName` varchar(20) NOT NULL,
  `product` varchar(20) NOT NULL,
  `quantity` int(10) NOT NULL,
  `orderDate` datetime(2) NOT NULL,
  `deliveryDate` date NOT NULL,
  `cus_phone` char(10) NOT NULL,
  `order_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`customerName`, `product`, `quantity`, `orderDate`, `deliveryDate`, `cus_phone`, `order_id`) VALUES
('mayil', 'MUTTON', 3, '2025-03-21 05:55:32.00', '2025-03-15', '8870426718', 9653),
('KSHORE', 'MUTTON', 2, '2025-03-21 05:55:50.00', '2025-03-26', '8755272890', 9654);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(10) NOT NULL,
  `product_name` varchar(25) NOT NULL,
  `product_price` float NOT NULL,
  `product_quantity` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_price`, `product_quantity`) VALUES
(2, 'Chikken', 215, 63),
(3, 'Fish', 250, 80),
(4, 'Beef', 300, 82),
(5, 'prawn ', 350, 97),
(6, 'pork', 440, 98),
(7, 'Rabbit', 1000, 95),
(15, 'Mutton', 760, 96),
(23, 'snake', 860, 100);

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `purchase_id` int(10) NOT NULL,
  `distributor_name` varchar(30) NOT NULL,
  `product_name` varchar(30) NOT NULL,
  `product_quantity` int(10) NOT NULL,
  `purchase_date` date NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`purchase_id`, `distributor_name`, `product_name`, `product_quantity`, `purchase_date`, `price`) VALUES
(1, 'AB traders', 'Mutton', 20, '2025-03-06', 16000),
(2, 'ranga', 'Rabbit', 12, '2025-03-14', 120000),
(67, 'Balaji ', 'Chikken', 10, '2025-03-17', 1000),
(68, 'AB traders', 'Chikken', 10, '2025-03-19', 1200),
(77, 'Balaji ', 'Chikken', 2, '2025-03-19', 200);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(10) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(15) NOT NULL,
  `user_type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `user_type`) VALUES
(1, 'mayil', '12345', 'user'),
(2, 'Mayil', 'mayil@11', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`bill_no`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cus_id`),
  ADD UNIQUE KEY `cus_phone` (`cus_phone`);

--
-- Indexes for table `distributors`
--
ALTER TABLE `distributors`
  ADD PRIMARY KEY (`distributor_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`emp_id`),
  ADD UNIQUE KEY `emp_phone` (`emp_phone`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD UNIQUE KEY `product_id` (`product_id`),
  ADD UNIQUE KEY `product_name` (`product_name`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`purchase_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `bill_no` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250044;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `cus_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `distributors`
--
ALTER TABLE `distributors`
  MODIFY `distributor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `emp_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9655;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `purchase_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

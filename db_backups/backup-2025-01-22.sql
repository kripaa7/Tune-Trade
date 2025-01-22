-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2025 at 05:26 AM
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
-- Database: `tunetrade`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `price` int(50) NOT NULL,
  `qty` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `price`, `qty`) VALUES
('a1YY4x4ZijCUZkUY5hFV', 'lNIC0MFTgYg0OvwE1nlG', '2tH61heFnSdfBolUpzbP', 2500, '1'),
('PBWMqmtNYK8u1xAzaqYU', 'cWkfRF7Hf3uRi1ed4aEw', 'QmiFmS2R3Q1rVpvYpsOZ', 180, '1');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `seller_id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `address_type` varchar(10) NOT NULL,
  `method` varchar(50) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `price` int(10) NOT NULL,
  `qty` int(2) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'in progress',
  `payment_status` varchar(100) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `seller_id`, `name`, `number`, `email`, `address`, `address_type`, `method`, `product_id`, `price`, `qty`, `date`, `status`, `payment_status`) VALUES
('6759282f4668b', 'lNIC0MFTgYg0OvwE1nlG', 'cnFJQHroosIVVG1vBQv2', 'Kripa Acharya', '12335235', 'kripaacharyaa@laravel.com', 'Kathmandu, Kathmandu, KTM, Nepal, 44600', 'home', 'cash on delivery', '2OzX7JzWcip8lmGxHlqN', 50000, 1, '2024-12-11', 'in progress', 'pending'),
('675a447c16d88', 'lNIC0MFTgYg0OvwE1nlG', '4WtdfCzyyg3HHfuEl6SZ', 'Kripa Acharya', '9864243678', 'kripaacharyaa@gmail.com', 'Kathmandu, Kathmandu, KTM, Nepal, 44600', 'home', 'cash on delivery', 'SOzSiqvWQO3iX8VjLcwt', 300, 1, '2024-12-12', 'in progress', '');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` varchar(20) NOT NULL,
  `seller_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `image` varchar(100) NOT NULL,
  `stock` int(100) NOT NULL,
  `product_detail` varchar(1000) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `seller_id`, `name`, `price`, `image`, `stock`, `product_detail`, `status`) VALUES
('xLhPhShYa5z18Auvjzza', 'cnFJQHroosIVVG1vBQv2', 'Acoustic Guitar', 150000, 'Frameedit7.jpg', 63, 'gaerngohenrg', 'deactive'),
('QmiFmS2R3Q1rVpvYpsOZ', 'cnFJQHroosIVVG1vBQv2', 'Capo', 180, 'Frame 5.svg', 70, 'CAPOAJPFNAK', 'active'),
('2OzX7JzWcip8lmGxHlqN', 'cnFJQHroosIVVG1vBQv2', 'Electric Guitar', 50000, 'Frame 3.png', 150, 'Very awesome', 'active'),
('2tH61heFnSdfBolUpzbP', 'cnFJQHroosIVVG1vBQv2', 'Guitar Bag', 2500, 'Frame 2.png', 50, 'comfortable', 'active'),
('DLMqGgAks51P7zNT694p', 'cnFJQHroosIVVG1vBQv2', 'Amp', 15000, 'Frame 6.png', 14, 'for electric', 'active'),
('SOzSiqvWQO3iX8VjLcwt', '4WtdfCzyyg3HHfuEl6SZ', 'Bag', 300, 'blackguitarbag.jpg', 0, 'A durable and stylish bag for carrying your guitar.', 'active'),
('VGBxwRXOqE2kvT8qKZNR', '4WtdfCzyyg3HHfuEl6SZ', 'Guitar Link', 15000, 'guitarlink.png', 83, 'A premium guitar link cable for optimal sound quality.', 'active'),
('V743SG429UyePArihvZW', '4WtdfCzyyg3HHfuEl6SZ', 'Microphone', 3000, 'microphone.png', 38, 'High-quality microphone for clear audio recording.', 'active'),
('63OWCCLQHP4eYkEkFe11', '4WtdfCzyyg3HHfuEl6SZ', 'Miniplay', 56000, 'miniplay.png', 41, 'Compact and portable music player for high-quality sound.', 'deactive'),
('hEVIAhPxCBstnui4cVmU', '4WtdfCzyyg3HHfuEl6SZ', 'Mixing', 45000, 'mixing.png', 91, 'Professional-grade mixing console for live performances.', 'active'),
('V1310itHurPEqOqpDbaS', '4WtdfCzyyg3HHfuEl6SZ', 'SamplePad', 70000, 'samplepad.png', 33, 'Versatile electronic sample pad for musicians.', 'active'),
('oZtaVt9SuPcsUjeMVyvE', '4WtdfCzyyg3HHfuEl6SZ', 'Speakers', 1200, 'speakers.png', 0, 'Powerful speakers delivering clear, crisp sound.', 'active'),
('ePVKFqhh4At0H4Hoj0UZ', '4WtdfCzyyg3HHfuEl6SZ', ';jwaln', 240, 'wallpaper.jpg', 3, 'jaeofb', 'deactive');

-- --------------------------------------------------------

--
-- Table structure for table `sellers`
--

CREATE TABLE `sellers` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sellers`
--

INSERT INTO `sellers` (`id`, `name`, `email`, `password`, `image`) VALUES
('4WtdfCzyyg3HHfuEl6SZ', 'Kripa', 'kripaacharyaa@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'qHV5g2MTp78gsaNntQL0.avif'),
('cnFJQHroosIVVG1vBQv2', 'Test1', 'test1@gmail.com', '8cb2237d0679ca88db6464eac60da96345513964', '7nYJa6MmjoQ3rZpuUZ2e.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `created_at`) VALUES
(3, 'test1@gmail.com', '2024-12-09 03:53:06'),
(4, 'kripaacharyaa@gmail.com', '2024-12-09 11:09:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `image`) VALUES
('lNIC0MFTgYg0OvwE1nlG', 'Testt', 'test123@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'IQOpEsvMLxji8ZPCE1SO.avif'),
('cWkfRF7Hf3uRi1ed4aEw', 'Test1', 'Test1@gmail.com', '8308651804facb7b9af8ffc53a33a22d6a1c8ac2', 'i5gVHfHuIC6dyCZWTadK.avif');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `price` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `price`) VALUES
('Sodk6LJLkZzWGbKRvC4J', 'lNIC0MFTgYg0OvwE1nlG', '2OzX7JzWcip8lmGxHlqN', 50000),
('xnxjFxvDb3QLDNHXjiqg', 'lNIC0MFTgYg0OvwE1nlG', 'QmiFmS2R3Q1rVpvYpsOZ', 180);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products` ADD FULLTEXT KEY `product_detail` (`product_detail`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

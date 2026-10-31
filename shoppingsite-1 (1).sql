-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 31, 2026 at 08:17 AM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shoppingsite-1`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Shoes'),
(2, 'Clothing'),
(3, 'Mobiles'),
(4, 'Watches');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `brand`, `price`, `stock`, `image`, `category_id`) VALUES
(1, 'Air Max', 'Nike', '5999.00', 10, 'airmax.jpg', 1),
(2, 'Runner', 'Adidas', '4999.00', 5, 'runner.jpg', 1),
(3, 'RS-X', 'Puma', '3999.00', 8, 'rsx.jpg', 1),
(4, 'Classic', 'Reebok', '4499.00', 0, 'classic.jpg', 1),
(5, 'T-Shirt', 'Nike', '1499.00', 10, 'tshirt.jpg', 2),
(6, 'Hoodie', 'Adidas', '2499.00', 5, 'hoodie.jpg', 2),
(7, 'Jacket', 'Puma', '2999.00', 0, 'jacket.jpg', 2),
(8, 'Track Pants', 'Reebok', '1999.00', 7, 'trackpants.jpg', 2),
(9, 'Galaxy S25', 'Samsung', '74999.00', 3, 's25.jpg', 3),
(10, 'iPhone 16', 'Apple', '69999.00', 5, 'iphone16.jpg', 3),
(11, 'OnePlus 13', 'OnePlus', '64999.00', 0, 'oneplus13.jpg', 3),
(12, 'Xiaomi 15', 'Xiaomi', '59999.00', 4, 'xiaomi15.jpg', 3),
(13, 'G-Shock', 'Casio', '7999.00', 7, 'gshock.jpg', 4),
(14, 'Edge', 'Titan', '5999.00', 3, 'edge.jpg', 4),
(15, 'Reflex', 'Fastrack', '2999.00', 0, 'reflex.jpg', 4),
(16, 'Weekender', 'Timex', '4999.00', 6, 'weekender.jpg', 4);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

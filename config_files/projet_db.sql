-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 04, 2020 at 06:19 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projet_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

DROP TABLE IF EXISTS `calendar`;
CREATE TABLE IF NOT EXISTS `calendar` (
  `id` int(11) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` blob DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

DROP TABLE IF EXISTS `card`;
CREATE TABLE IF NOT EXISTS `card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `points` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
  `id` int(11) NOT NULL,
  `CALENDAR_id` int(11) NOT NULL,
  `FOOD_TRUCK_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_EVENT_CALENDAR1_idx` (`CALENDAR_id`),
  KEY `fk_EVENT_FOOD_TRUCK1_idx` (`FOOD_TRUCK_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

DROP TABLE IF EXISTS `food`;
CREATE TABLE IF NOT EXISTS `food` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` blob DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_has_item`
--

DROP TABLE IF EXISTS `food_has_item`;
CREATE TABLE IF NOT EXISTS `food_has_item` (
  `FOOD_id` int(11) NOT NULL,
  `ITEM_id` int(11) NOT NULL,
  PRIMARY KEY (`FOOD_id`,`ITEM_id`),
  KEY `fk_FOOD_has_ITEM_ITEM1_idx` (`ITEM_id`),
  KEY `fk_FOOD_has_ITEM_FOOD1_idx` (`FOOD_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_truck`
--

DROP TABLE IF EXISTS `food_truck`;
CREATE TABLE IF NOT EXISTS `food_truck` (
  `id` int(11) NOT NULL,
  `date_register` datetime NOT NULL,
  `date_last_check` datetime DEFAULT NULL,
  `mileage` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `ITEM_id` int(11) NOT NULL,
  `WAREHOUSE_id` int(11) DEFAULT NULL,
  `FOOD_TRUCK_id` int(11) DEFAULT NULL,
  `FOOD_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_INVENTORY_ITEM1_idx` (`ITEM_id`),
  KEY `fk_INVENTORY_WAREHOUSE1_idx` (`WAREHOUSE_id`),
  KEY `fk_INVENTORY_FOOD_TRUCK1_idx` (`FOOD_TRUCK_id`),
  KEY `fk_INVENTORY_FOOD1_idx` (`FOOD_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_has_stock_order`
--

DROP TABLE IF EXISTS `item_has_stock_order`;
CREATE TABLE IF NOT EXISTS `item_has_stock_order` (
  `ITEM_id` int(11) NOT NULL,
  `STOCK_ORDER_id` int(11) NOT NULL,
  PRIMARY KEY (`ITEM_id`,`STOCK_ORDER_id`),
  KEY `fk_ITEM_has_STOCK_ORDER_STOCK_ORDER1_idx` (`STOCK_ORDER_id`),
  KEY `fk_ITEM_has_STOCK_ORDER_ITEM1_idx` (`ITEM_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `USER_id` int(11) NOT NULL,
  `FOOD_TRUCK_id` int(11) NOT NULL,
  `delivery_time` datetime DEFAULT NULL,
  `STATUS_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ORDERS_USER1_idx` (`USER_id`),
  KEY `fk_ORDERS_FOOD_TRUCK1_idx` (`FOOD_TRUCK_id`),
  KEY `fk_ORDER_STATUS1_idx` (`STATUS_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_has_food`
--

DROP TABLE IF EXISTS `order_has_food`;
CREATE TABLE IF NOT EXISTS `order_has_food` (
  `ORDER_id` int(11) NOT NULL,
  `FOOD_id` int(11) NOT NULL,
  PRIMARY KEY (`ORDER_id`,`FOOD_id`),
  KEY `fk_ORDER_has_FOOD_FOOD1_idx` (`FOOD_id`),
  KEY `fk_ORDER_has_FOOD_ORDER1_idx` (`ORDER_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_order`
--

DROP TABLE IF EXISTS `stock_order`;
CREATE TABLE IF NOT EXISTS `stock_order` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `FOOD_TRUCK_id` int(11) NOT NULL,
  `WAREHOUSE_id` int(11) DEFAULT NULL,
  `STATUS_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_STOCK_ORDER_FOOD_TRUCK1_idx` (`FOOD_TRUCK_id`),
  KEY `fk_STOCK_ORDER_WAREHOUSE1_idx` (`WAREHOUSE_id`),
  KEY `fk_STOCK_ORDER_STATUS1_idx` (`STATUS_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `is_client` int(11) NOT NULL,
  `is_employe` int(11) NOT NULL,
  `is_worker` int(11) NOT NULL,
  `date_register` datetime NOT NULL,
  `date_modify` datetime DEFAULT NULL,
  `worker_qrcode` blob DEFAULT NULL,
  `CARD_id` int(11) DEFAULT NULL,
  `WORKER_ENTRY_id` int(11) DEFAULT NULL,
  `street_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `street_number` int(11) NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `FOOD_TRUCK_id` int(11) DEFAULT NULL,
  `WAREHOUSE_id` int(11) DEFAULT NULL,
  `activated` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_USER_CARD_idx` (`CARD_id`),
  KEY `fk_USER_WORKER_ENTRY1_idx` (`WORKER_ENTRY_id`),
  KEY `fk_USER_FOOD_TRUCK1_idx` (`FOOD_TRUCK_id`),
  KEY `fk_USER_WAREHOUSE1_idx` (`WAREHOUSE_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_has_calendar`
--

DROP TABLE IF EXISTS `user_has_calendar`;
CREATE TABLE IF NOT EXISTS `user_has_calendar` (
  `USER_id` int(11) NOT NULL,
  `CALENDAR_id` int(11) NOT NULL,
  PRIMARY KEY (`USER_id`,`CALENDAR_id`),
  KEY `fk_USER_has_CALENDAR_CALENDAR1_idx` (`CALENDAR_id`),
  KEY `fk_USER_has_CALENDAR_USER1_idx` (`USER_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

DROP TABLE IF EXISTS `warehouse`;
CREATE TABLE IF NOT EXISTS `warehouse` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `street_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `street_number` int(11) NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worker_entry`
--

DROP TABLE IF EXISTS `worker_entry`;
CREATE TABLE IF NOT EXISTS `worker_entry` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `resume_link` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `pick_text` blob NOT NULL,
  `payment` int(11) NOT NULL,
  `STATUS_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_WORKER_ENTRY_STATUS1_idx` (`STATUS_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `fk_EVENT_CALENDAR1` FOREIGN KEY (`CALENDAR_id`) REFERENCES `calendar` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_EVENT_FOOD_TRUCK1` FOREIGN KEY (`FOOD_TRUCK_id`) REFERENCES `food_truck` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `food_has_item`
--
ALTER TABLE `food_has_item`
  ADD CONSTRAINT `fk_FOOD_has_ITEM_FOOD1` FOREIGN KEY (`FOOD_id`) REFERENCES `food` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_FOOD_has_ITEM_ITEM1` FOREIGN KEY (`ITEM_id`) REFERENCES `item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk_INVENTORY_FOOD1` FOREIGN KEY (`FOOD_id`) REFERENCES `food` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_INVENTORY_FOOD_TRUCK1` FOREIGN KEY (`FOOD_TRUCK_id`) REFERENCES `food_truck` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_INVENTORY_ITEM1` FOREIGN KEY (`ITEM_id`) REFERENCES `item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_INVENTORY_WAREHOUSE1` FOREIGN KEY (`WAREHOUSE_id`) REFERENCES `warehouse` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `item_has_stock_order`
--
ALTER TABLE `item_has_stock_order`
  ADD CONSTRAINT `fk_ITEM_has_STOCK_ORDER_ITEM1` FOREIGN KEY (`ITEM_id`) REFERENCES `item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ITEM_has_STOCK_ORDER_STOCK_ORDER1` FOREIGN KEY (`STOCK_ORDER_id`) REFERENCES `stock_order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_ORDERS_FOOD_TRUCK1` FOREIGN KEY (`FOOD_TRUCK_id`) REFERENCES `food_truck` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ORDERS_USER1` FOREIGN KEY (`USER_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ORDER_STATUS1` FOREIGN KEY (`STATUS_id`) REFERENCES `status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `order_has_food`
--
ALTER TABLE `order_has_food`
  ADD CONSTRAINT `fk_ORDER_has_FOOD_FOOD1` FOREIGN KEY (`FOOD_id`) REFERENCES `food` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ORDER_has_FOOD_ORDER1` FOREIGN KEY (`ORDER_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `stock_order`
--
ALTER TABLE `stock_order`
  ADD CONSTRAINT `fk_STOCK_ORDER_FOOD_TRUCK1` FOREIGN KEY (`FOOD_TRUCK_id`) REFERENCES `food_truck` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_STOCK_ORDER_STATUS1` FOREIGN KEY (`STATUS_id`) REFERENCES `status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_STOCK_ORDER_WAREHOUSE1` FOREIGN KEY (`WAREHOUSE_id`) REFERENCES `warehouse` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_USER_CARD` FOREIGN KEY (`CARD_id`) REFERENCES `card` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_USER_FOOD_TRUCK1` FOREIGN KEY (`FOOD_TRUCK_id`) REFERENCES `food_truck` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_USER_WAREHOUSE1` FOREIGN KEY (`WAREHOUSE_id`) REFERENCES `warehouse` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_USER_WORKER_ENTRY1` FOREIGN KEY (`WORKER_ENTRY_id`) REFERENCES `worker_entry` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_has_calendar`
--
ALTER TABLE `user_has_calendar`
  ADD CONSTRAINT `fk_USER_has_CALENDAR_CALENDAR1` FOREIGN KEY (`CALENDAR_id`) REFERENCES `calendar` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_USER_has_CALENDAR_USER1` FOREIGN KEY (`USER_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `worker_entry`
--
ALTER TABLE `worker_entry`
  ADD CONSTRAINT `fk_WORKER_ENTRY_STATUS1` FOREIGN KEY (`STATUS_id`) REFERENCES `status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

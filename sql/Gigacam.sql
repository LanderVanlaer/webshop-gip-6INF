-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2021 at 12:54 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gigacam`
--
CREATE DATABASE IF NOT EXISTS `gigacam` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gigacam`;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
CREATE TABLE IF NOT EXISTS `address`
(
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `street_id` int(11) NOT NULL,
    `number`    int(4)  NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article`
(
    `id`           int(11)     NOT NULL AUTO_INCREMENT,
    `brand_id`     int(11)     NOT NULL,
    `name`         varchar(32) NOT NULL,
    `descriptionD` varchar(1023)        DEFAULT NULL,
    `descriptionF` varchar(1023)        DEFAULT NULL,
    `descriptionE` varchar(1023)        DEFAULT NULL,
    `price`        float(7, 2) NOT NULL,
    `visible`      tinyint(1)           DEFAULT NULL,
    `create_date`  timestamp   NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `articlecategory`
--

DROP TABLE IF EXISTS `articlecategory`;
CREATE TABLE IF NOT EXISTS `articlecategory`
(
    `category_id` int(11) NOT NULL,
    `article_id`  int(11) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `articleimage`
--

DROP TABLE IF EXISTS `articleimage`;
CREATE TABLE IF NOT EXISTS `articleimage`
(
    `id`          int(11)      NOT NULL AUTO_INCREMENT,
    `path`        varchar(255) NOT NULL,
    `article_id`  int(11)      NOT NULL,
    `isThumbnail` tinyint(1) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `articlespecification`
--

DROP TABLE IF EXISTS `articlespecification`;
CREATE TABLE IF NOT EXISTS `articlespecification`
(
    `id`               int(11)     NOT NULL AUTO_INCREMENT,
    `article_id`       int(11)     NOT NULL,
    `specification_id` int(11)     NOT NULL,
    `value`            varchar(31) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

DROP TABLE IF EXISTS `brand`;
CREATE TABLE IF NOT EXISTS `brand`
(
    `id`   int(11)     NOT NULL AUTO_INCREMENT,
    `name` varchar(31) NOT NULL,
    `logo` varchar(127) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category`
(
    `id`    int(11)     NOT NULL AUTO_INCREMENT,
    `nameD` varchar(31) NOT NULL,
    `nameF` varchar(31) NOT NULL,
    `nameE` varchar(31) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
CREATE TABLE IF NOT EXISTS `country`
(
    `id`   int(11)     NOT NULL AUTO_INCREMENT,
    `name` varchar(31) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer`
(
    `id`                int(11)      NOT NULL AUTO_INCREMENT,
    `firstname`         varchar(63)  NOT NULL,
    `lastname`          varchar(63)  NOT NULL,
    `email`             varchar(127) NOT NULL,
    `password`          varchar(255) NOT NULL,
    `registration_code` varchar(255) DEFAULT NULL,
    `active`            tinyint(1)   DEFAULT NULL,
    `address_id`        int(11)      DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
CREATE TABLE IF NOT EXISTS `employee`
(
    `id`        int(11)      NOT NULL AUTO_INCREMENT,
    `password`  varchar(255) NOT NULL,
    `username`  varchar(127) NOT NULL,
    `firstname` varchar(63) DEFAULT NULL,
    `lastname`  varchar(63) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order`
(
    `id`                  int(11)   NOT NULL AUTO_INCREMENT,
    `date`                timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `purchase_address_id` int(11)   NOT NULL,
    `delivery_address_id` int(11)   NOT NULL,
    `customer_id`         int(11)   NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orderarticle`
--

DROP TABLE IF EXISTS `orderarticle`;
CREATE TABLE IF NOT EXISTS `orderarticle`
(
    `order_id`   int(11) NOT NULL AUTO_INCREMENT,
    `article_id` int(11) NOT NULL,
    `amount`     int(2)  NOT NULL,
    PRIMARY KEY (`order_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `province`
--

DROP TABLE IF EXISTS `province`;
CREATE TABLE IF NOT EXISTS `province`
(
    `id`         int(11)     NOT NULL AUTO_INCREMENT,
    `name`       varchar(31) NOT NULL,
    `country_id` int(11)     NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `shoppingcartarticle`
--

DROP TABLE IF EXISTS `shoppingcartarticle`;
CREATE TABLE IF NOT EXISTS `shoppingcartarticle`
(
    `shoppingcartarticle_id` int(11)   NOT NULL AUTO_INCREMENT,
    `customer_id`            int(11)   NOT NULL,
    `article_id`             int(11)   NOT NULL,
    `amount`                 int(2)    NOT NULL,
    `date`                   timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`shoppingcartarticle_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `specification`
--

DROP TABLE IF EXISTS `specification`;
CREATE TABLE IF NOT EXISTS `specification`
(
    `id`          int(11)     NOT NULL AUTO_INCREMENT,
    `category_id` int(11)     NOT NULL,
    `nameD`       varchar(31) NOT NULL,
    `nameF`       varchar(31) NOT NULL,
    `nameE`       varchar(31) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `street`
--

DROP TABLE IF EXISTS `street`;
CREATE TABLE IF NOT EXISTS `street`
(
    `id`          int(11)     NOT NULL AUTO_INCREMENT,
    `name`        varchar(31) NOT NULL,
    `township_id` int(11)     NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `township`
--

DROP TABLE IF EXISTS `township`;
CREATE TABLE IF NOT EXISTS `township`
(
    `id`         int(11)    NOT NULL AUTO_INCREMENT,
    `postcode`   varchar(7) NOT NULL,
    `country_id` int(11)    NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `visited`
--

DROP TABLE IF EXISTS `visited`;
CREATE TABLE IF NOT EXISTS `visited`
(
    `id`          int(11)   NOT NULL AUTO_INCREMENT,
    `date`        timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `customer_id` int(11)   NOT NULL,
    `article_id`  int(11)   NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;

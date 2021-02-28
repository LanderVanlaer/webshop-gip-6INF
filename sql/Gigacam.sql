-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2021 at 01:30 PM
-- Server version: 10.4.16-MariaDB
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

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address`
(
    `id`          int(11)      NOT NULL,
    `township_id` int(11)      NOT NULL,
    `street`      varchar(255) NOT NULL,
    `number`      int(4)       NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE `article`
(
    `id`           int(11)     NOT NULL,
    `brand_id`     int(11)     NOT NULL,
    `name`         varchar(32) NOT NULL,
    `descriptionD` varchar(1023)        DEFAULT NULL,
    `descriptionF` varchar(1023)        DEFAULT NULL,
    `descriptionE` varchar(1023)        DEFAULT NULL,
    `price`        float(7, 2) NOT NULL,
    `visible`      tinyint(1)           DEFAULT NULL,
    `create_date`  timestamp   NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `articlecategory`
--

CREATE TABLE `articlecategory`
(
    `category_id` int(11) NOT NULL,
    `article_id`  int(11) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `articleimage`
--

CREATE TABLE `articleimage`
(
    `id`         int(11)      NOT NULL,
    `path`       varchar(255) NOT NULL,
    `article_id` int(11)      NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `articlespecification`
--

CREATE TABLE `articlespecification`
(
    `id`         int(11)     NOT NULL,
    `article_id` int(11)     NOT NULL,
    `value`      varchar(31) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand`
(
    `id`   int(11)     NOT NULL,
    `name` varchar(31) NOT NULL,
    `logo` varchar(127) DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category`
(
    `id`    int(11)     NOT NULL,
    `nameD` varchar(31) NOT NULL,
    `nameF` varchar(31) NOT NULL,
    `nameE` varchar(31) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country`
(
    `id`   int(11)     NOT NULL,
    `name` varchar(31) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer`
(
    `id`                int(11)      NOT NULL,
    `firstname`         varchar(63)  NOT NULL,
    `lastname`          varchar(63)  NOT NULL,
    `email`             varchar(127) NOT NULL,
    `password`          varchar(255) NOT NULL,
    `registration_code` varchar(255) DEFAULT NULL,
    `active`            tinyint(1)   DEFAULT NULL,
    `address_id`        int(11)      DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee`
(
    `id`        int(11)      NOT NULL,
    `password`  varchar(255) NOT NULL,
    `username`  varchar(127) NOT NULL,
    `firstname` varchar(63) DEFAULT NULL,
    `lastname`  varchar(63) DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order`
(
    `id`                  int(11)   NOT NULL,
    `date`                timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `purchase_address_id` int(11)   NOT NULL,
    `delivery_address_id` int(11)   NOT NULL,
    `customer_id`         int(11)   NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orderarticle`
--

CREATE TABLE `orderarticle`
(
    `order_id`   int(11) NOT NULL,
    `article_id` int(11) NOT NULL,
    `amount`     int(2)  NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `province`
--

CREATE TABLE `province`
(
    `id`         int(11)     NOT NULL,
    `name`       varchar(31) NOT NULL,
    `country_id` int(11)     NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `shoppingcart`
--

CREATE TABLE `shoppingcart`
(
    `id`           int(11)   NOT NULL,
    `customers_id` int(11)   NOT NULL,
    `date`         timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `shoppingcartarticle`
--

CREATE TABLE `shoppingcartarticle`
(
    `shoppingcart_id` int(11) NOT NULL,
    `article_id`      int(11) NOT NULL,
    `amount`          int(2)  NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `specification`
--

CREATE TABLE `specification`
(
    `id`          int(11)     NOT NULL,
    `category_id` int(11)     NOT NULL,
    `nameD`       varchar(31) NOT NULL,
    `nameF`       varchar(31) NOT NULL,
    `nameE`       varchar(31) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `township`
--

CREATE TABLE `township`
(
    `id`          int(11)     NOT NULL,
    `name`        varchar(63) NOT NULL,
    `postcode`    varchar(7)  NOT NULL,
    `province_id` int(11)     NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

-- --------------------------------------------------------

--
-- Table structure for table `visited`
--

CREATE TABLE `visited`
(
    `id`          int(11)   NOT NULL,
    `date`        timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `customer_id` int(11)   NOT NULL,
    `article_id`  int(11)   NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article`
--
ALTER TABLE `article`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `articleimage`
--
ALTER TABLE `articleimage`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `articlespecification`
--
ALTER TABLE `articlespecification`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orderarticle`
--
ALTER TABLE `orderarticle`
    ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `province`
--
ALTER TABLE `province`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shoppingcart`
--
ALTER TABLE `shoppingcart`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `specification`
--
ALTER TABLE `specification`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `township`
--
ALTER TABLE `township`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visited`
--
ALTER TABLE `visited`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `articleimage`
--
ALTER TABLE `articleimage`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `articlespecification`
--
ALTER TABLE `articlespecification`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderarticle`
--
ALTER TABLE `orderarticle`
    MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `province`
--
ALTER TABLE `province`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shoppingcart`
--
ALTER TABLE `shoppingcart`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `specification`
--
ALTER TABLE `specification`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `township`
--
ALTER TABLE `township`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visited`
--
ALTER TABLE `visited`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;

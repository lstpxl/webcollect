-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: wkh.mysql
-- Generation Time: Jul 24, 2024 at 03:42 PM
-- Server version: 5.6.51-91.0
-- PHP Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_wkh_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `binding`
--
-- Creation: Oct 06, 2022 at 04:12 AM
--

DROP TABLE IF EXISTS `binding`;
CREATE TABLE `binding` (
  `binding_id` int(11) UNSIGNED NOT NULL,
  `shorttext` varchar(20) NOT NULL DEFAULT '',
  `longtext` varchar(200) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `correction`
--
-- Creation: Oct 06, 2022 at 04:12 AM
-- Last update: Sep 19, 2023 at 02:40 PM
-- Last check: Oct 06, 2022 at 04:12 AM
--

DROP TABLE IF EXISTS `correction`;
CREATE TABLE `correction` (
  `correction_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `added` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  `text` varchar(1000) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `enamel`
--
-- Creation: Oct 06, 2022 at 04:12 AM
--

DROP TABLE IF EXISTS `enamel`;
CREATE TABLE `enamel` (
  `enamel_id` int(11) UNSIGNED NOT NULL,
  `shorttext` varchar(20) NOT NULL DEFAULT '',
  `longtext` varchar(200) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `factory`
--
-- Creation: Oct 06, 2022 at 04:12 AM
--

DROP TABLE IF EXISTS `factory`;
CREATE TABLE `factory` (
  `factory_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--
-- Creation: Oct 06, 2022 at 04:12 AM
-- Last update: Jul 24, 2024 at 03:41 PM
-- Last check: Oct 06, 2022 at 04:12 AM
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `item_id` int(11) UNSIGNED NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'I',
  `submitter_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `moderator_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `time_submit_start` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  `time_submit_finish` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  `time_approved` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  `shipmodel_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `ship_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `ship_has_model` enum('N','Y') NOT NULL DEFAULT 'Y',
  `shipmodel_str` varchar(120) NOT NULL DEFAULT '',
  `shipmodel_code_str` varchar(40) NOT NULL DEFAULT '',
  `shipmodel_nick_str` varchar(120) NOT NULL DEFAULT '',
  `shipmodel_type_str` varchar(80) NOT NULL DEFAULT '',
  `ship_str` varchar(120) NOT NULL DEFAULT '',
  `shipyard_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `shipyard_str` varchar(180) NOT NULL DEFAULT '',
  `notes` varchar(2000) NOT NULL DEFAULT '',
  `extlink` varchar(400) NOT NULL DEFAULT '',
  `lettering` varchar(400) NOT NULL DEFAULT '',
  `width` decimal(4,1) UNSIGNED NOT NULL DEFAULT '0.0',
  `height` decimal(4,1) UNSIGNED NOT NULL DEFAULT '0.0',
  `metal_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `enamel_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `binding_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `has_patch` enum('N','Y') NOT NULL DEFAULT 'N',
  `batchsize` decimal(8,0) UNSIGNED NOT NULL DEFAULT '0',
  `factory_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `factory_str` varchar(80) NOT NULL DEFAULT '',
  `issuedate` decimal(4,0) UNSIGNED NOT NULL DEFAULT '0',
  `searchstring` varchar(400) NOT NULL DEFAULT '',
  `shipmodelclass_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `shipmodelclass_str` varchar(180) NOT NULL DEFAULT '',
  `sortfield_a` varchar(200) NOT NULL DEFAULT '',
  `sortfield_c` varchar(200) NOT NULL DEFAULT '',
  `top_shipmodelclass_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `image_filename_original` varchar(200) NOT NULL DEFAULT '',
  `itemset_id` int(11) NOT NULL DEFAULT '0',
  `itemset_str` varchar(250) NOT NULL DEFAULT '',
  `is_itemset_title` enum('N','Y') CHARACTER SET ucs2 NOT NULL DEFAULT 'N',
  `ship_factoryserialnum_str` varchar(20) NOT NULL DEFAULT '',
  `natoc_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `natoc_str` varchar(80) NOT NULL DEFAULT '',
  `occasion_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `ti_parent` varchar(200) NOT NULL DEFAULT 'xxxxx',
  `ti_self` varchar(64) NOT NULL DEFAULT 'ixxxxx',
  `ti_subsort` int(11) NOT NULL DEFAULT '0',
  `refresh` enum('N','Y') NOT NULL DEFAULT 'N',
  `downlink_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  `imgcrc` decimal(16,0) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `itemset`
--
-- Creation: Oct 06, 2022 at 04:12 AM
--

DROP TABLE IF EXISTS `itemset`;
CREATE TABLE `itemset` (
  `itemset_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `iurel`
--
-- Creation: Oct 06, 2022 at 04:12 AM
-- Last update: Jul 24, 2024 at 03:20 PM
-- Last check: Jan 21, 2024 at 05:02 AM
--

DROP TABLE IF EXISTS `iurel`;
CREATE TABLE `iurel` (
  `iurel_id` int(11) UNSIGNED NOT NULL,
  `item_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `gotit` enum('N','Y') NOT NULL DEFAULT 'N',
  `wantit` enum('N','Y') NOT NULL DEFAULT 'N',
  `sellit` enum('N','Y') NOT NULL DEFAULT 'N',
  `sellprice` decimal(15,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `initialprice` decimal(15,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `storageplace` varchar(200) NOT NULL DEFAULT '',
  `personalnote` varchar(200) NOT NULL DEFAULT '',
  `iusearchstring` varchar(300) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--
-- Creation: Oct 06, 2022 at 04:12 AM
-- Last update: Jul 24, 2024 at 03:41 PM
-- Last check: Jan 21, 2024 at 01:05 AM
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `log_id` int(11) NOT NULL,
  `type` varchar(1) NOT NULL DEFAULT 'N',
  `time` datetime NOT NULL,
  `remote_ip` varchar(16) NOT NULL,
  `uv` enum('U','V') NOT NULL DEFAULT 'V',
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `visitor_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `message` varchar(254) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `metal`
--
-- Creation: Oct 06, 2022 at 04:14 AM
--

DROP TABLE IF EXISTS `metal`;
CREATE TABLE `metal` (
  `metal_id` int(11) UNSIGNED NOT NULL,
  `shorttext` varchar(20) NOT NULL DEFAULT '',
  `longtext` varchar(200) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `natoc`
--
-- Creation: Oct 06, 2022 at 04:14 AM
--

DROP TABLE IF EXISTS `natoc`;
CREATE TABLE `natoc` (
  `natoc_id` int(10) UNSIGNED NOT NULL,
  `text` varchar(80) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `occasion`
--
-- Creation: Oct 06, 2022 at 04:14 AM
--

DROP TABLE IF EXISTS `occasion`;
CREATE TABLE `occasion` (
  `occasion_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ship`
--
-- Creation: Oct 06, 2022 at 04:14 AM
--

DROP TABLE IF EXISTS `ship`;
CREATE TABLE `ship` (
  `ship_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(256) NOT NULL DEFAULT '',
  `has_model` enum('N','Y') NOT NULL DEFAULT 'Y',
  `shipmodel_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `shipmodelclass_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `shipyard_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `factoryserialnum` varchar(24) NOT NULL DEFAULT '',
  `treeindex` varchar(96) NOT NULL DEFAULT '',
  `ti_subsort` int(11) NOT NULL DEFAULT '0',
  `refresh` enum('N','Y') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shipmodel`
--
-- Creation: Oct 06, 2022 at 04:14 AM
--

DROP TABLE IF EXISTS `shipmodel`;
CREATE TABLE `shipmodel` (
  `shipmodel_id` int(11) UNSIGNED NOT NULL,
  `shipmodelclass_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(128) NOT NULL DEFAULT '',
  `numcode` varchar(40) NOT NULL DEFAULT '',
  `nick` varchar(40) NOT NULL DEFAULT '',
  `type` varchar(40) NOT NULL DEFAULT '',
  `natoc_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `has_blueprint` enum('N','Y') NOT NULL DEFAULT 'N',
  `treeindex` varchar(64) NOT NULL DEFAULT '',
  `ti_subsort` int(11) NOT NULL DEFAULT '0',
  `refresh` enum('N','Y') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shipmodelclass`
--
-- Creation: Oct 06, 2022 at 04:14 AM
--

DROP TABLE IF EXISTS `shipmodelclass`;
CREATE TABLE `shipmodelclass` (
  `shipmodelclass_id` int(11) UNSIGNED NOT NULL,
  `text` varchar(120) NOT NULL DEFAULT '''''',
  `parent_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `displayorder` int(11) NOT NULL DEFAULT '0',
  `treeindex` varchar(48) NOT NULL DEFAULT '',
  `ti_subsort` int(11) NOT NULL DEFAULT '0',
  `refresh` enum('N','Y') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shipyard`
--
-- Creation: Oct 06, 2022 at 04:14 AM
--

DROP TABLE IF EXISTS `shipyard`;
CREATE TABLE `shipyard` (
  `shipyard_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--
-- Creation: Oct 06, 2022 at 04:14 AM
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `password_hash` varchar(128) NOT NULL,
  `password` varchar(64) NOT NULL,
  `ip_address` varchar(16) NOT NULL DEFAULT 'undefined',
  `time_last_request` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  `email_address` varchar(256) NOT NULL,
  `email_verified` enum('N','Y') NOT NULL DEFAULT 'N',
  `email_code` varchar(128) NOT NULL,
  `login_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `cookie_hash` varchar(80) NOT NULL,
  `is_registered_user` enum('N','Y') NOT NULL DEFAULT 'N',
  `time_registered` datetime DEFAULT NULL,
  `is_moderator` enum('N','Y') NOT NULL DEFAULT 'N',
  `is_lim_moderator` enum('N','Y') NOT NULL DEFAULT 'N',
  `is_admin` enum('N','Y') NOT NULL DEFAULT 'N',
  `is_superadmin` enum('N','Y') NOT NULL DEFAULT 'N',
  `is_vipcollector` enum('N','Y') NOT NULL DEFAULT 'N',
  `can_submit_item` enum('N','Y') NOT NULL DEFAULT 'Y',
  `is_active` enum('N','Y') NOT NULL DEFAULT 'Y',
  `is_ban` enum('N','Y') NOT NULL DEFAULT 'N',
  `firstname` varchar(120) NOT NULL DEFAULT '',
  `middlename` varchar(120) NOT NULL DEFAULT '',
  `lastname` varchar(120) NOT NULL DEFAULT '',
  `city` varchar(120) NOT NULL DEFAULT '',
  `phone_number` varchar(30) NOT NULL DEFAULT '',
  `phone_check` decimal(6,0) NOT NULL DEFAULT '0',
  `phone_verified` enum('N','Y') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitor`
--
-- Creation: Jul 24, 2024 at 01:09 PM
--

DROP TABLE IF EXISTS `visitor`;
CREATE TABLE `visitor` (
  `visitor_id` int(11) UNSIGNED NOT NULL,
  `hash` varchar(80) NOT NULL DEFAULT '',
  `time_last_request` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  `ip_address` varchar(16) NOT NULL DEFAULT '',
  `request_count` int(12) UNSIGNED NOT NULL DEFAULT '1',
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `captcha_code` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `binding`
--
ALTER TABLE `binding`
  ADD PRIMARY KEY (`binding_id`);

--
-- Indexes for table `correction`
--
ALTER TABLE `correction`
  ADD PRIMARY KEY (`correction_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `enamel`
--
ALTER TABLE `enamel`
  ADD PRIMARY KEY (`enamel_id`);

--
-- Indexes for table `factory`
--
ALTER TABLE `factory`
  ADD PRIMARY KEY (`factory_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `sortfield_c` (`sortfield_c`),
  ADD KEY `sortfield_a` (`sortfield_a`),
  ADD KEY `metal_id` (`metal_id`),
  ADD KEY `enamel_id` (`enamel_id`),
  ADD KEY `binding_id` (`binding_id`),
  ADD KEY `factory_id` (`factory_id`),
  ADD KEY `occasion_id` (`occasion_id`),
  ADD KEY `searchstring` (`searchstring`(255)),
  ADD KEY `imgcrc` (`imgcrc`);
ALTER TABLE `item` ADD FULLTEXT KEY `searchstring_2` (`searchstring`);

--
-- Indexes for table `itemset`
--
ALTER TABLE `itemset`
  ADD PRIMARY KEY (`itemset_id`);

--
-- Indexes for table `iurel`
--
ALTER TABLE `iurel`
  ADD PRIMARY KEY (`iurel_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);
ALTER TABLE `iurel` ADD FULLTEXT KEY `iusearchstring` (`iusearchstring`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `metal`
--
ALTER TABLE `metal`
  ADD PRIMARY KEY (`metal_id`);

--
-- Indexes for table `natoc`
--
ALTER TABLE `natoc`
  ADD PRIMARY KEY (`natoc_id`);

--
-- Indexes for table `occasion`
--
ALTER TABLE `occasion`
  ADD PRIMARY KEY (`occasion_id`);

--
-- Indexes for table `ship`
--
ALTER TABLE `ship`
  ADD PRIMARY KEY (`ship_id`),
  ADD KEY `factoryserialnum` (`factoryserialnum`),
  ADD KEY `shipmodel_id` (`shipmodel_id`);

--
-- Indexes for table `shipmodel`
--
ALTER TABLE `shipmodel`
  ADD PRIMARY KEY (`shipmodel_id`),
  ADD KEY `shipmodelclass_id` (`shipmodelclass_id`);

--
-- Indexes for table `shipmodelclass`
--
ALTER TABLE `shipmodelclass`
  ADD PRIMARY KEY (`shipmodelclass_id`);

--
-- Indexes for table `shipyard`
--
ALTER TABLE `shipyard`
  ADD PRIMARY KEY (`shipyard_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `visitor`
--
ALTER TABLE `visitor`
  ADD PRIMARY KEY (`visitor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `binding`
--
ALTER TABLE `binding`
  MODIFY `binding_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `correction`
--
ALTER TABLE `correction`
  MODIFY `correction_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enamel`
--
ALTER TABLE `enamel`
  MODIFY `enamel_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `factory`
--
ALTER TABLE `factory`
  MODIFY `factory_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `itemset`
--
ALTER TABLE `itemset`
  MODIFY `itemset_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `iurel`
--
ALTER TABLE `iurel`
  MODIFY `iurel_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `metal`
--
ALTER TABLE `metal`
  MODIFY `metal_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `natoc`
--
ALTER TABLE `natoc`
  MODIFY `natoc_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `occasion`
--
ALTER TABLE `occasion`
  MODIFY `occasion_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ship`
--
ALTER TABLE `ship`
  MODIFY `ship_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipmodel`
--
ALTER TABLE `shipmodel`
  MODIFY `shipmodel_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipmodelclass`
--
ALTER TABLE `shipmodelclass`
  MODIFY `shipmodelclass_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipyard`
--
ALTER TABLE `shipyard`
  MODIFY `shipyard_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitor`
--
ALTER TABLE `visitor`
  MODIFY `visitor_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

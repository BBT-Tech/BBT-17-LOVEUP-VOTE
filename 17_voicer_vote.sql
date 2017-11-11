-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-11-11 22:27:18
-- 服务器版本： 5.5.56-log
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `17_voicer_vote`
--

-- --------------------------------------------------------

--
-- 表的结构 `system_var`
--

CREATE TABLE `system_var` (
  `var_name` char(32) NOT NULL,
  `var_val` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `system_var`
--

INSERT INTO `system_var` (`var_name`, `var_val`) VALUES
('VOTE_LIMIT_PERIOD', '0'),
('VOTE_LIMIT_TICKET', '200'),
('VOTE_STATUS', '1');

-- --------------------------------------------------------

--
-- 表的结构 `user_info`
--

CREATE TABLE `user_info` (
  `user_id` int(11) NOT NULL,
  `open_id` char(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `voicer_info`
--

CREATE TABLE `voicer_info` (
  `voicer_id` int(11) NOT NULL,
  `voicer_name` char(32) NOT NULL,
  `vote_count` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `voicer_info`
--

INSERT INTO `voicer_info` (`voicer_id`, `voicer_name`, `vote_count`) VALUES
(1, '廖立胜', 0),
(2, '刘入豪', 0),
(3, '林倩雯', 0),
(4, '蒋格洋', 0),
(5, '陈瑞森', 0),
(6, '崔艺潆', 0),
(7, '刘一达', 0),
(8, '黄新杰', 0);

-- --------------------------------------------------------

--
-- 表的结构 `vote_log`
--

CREATE TABLE `vote_log` (
  `user_id` int(11) NOT NULL,
  `vote_date` date NOT NULL,
  `vote_voicer_id` int(11) NOT NULL,
  `vote_datetime` datetime NOT NULL,
  `ip_addr` char(16) NOT NULL,
  `request_headers` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `system_var`
--
ALTER TABLE `system_var`
  ADD PRIMARY KEY (`var_name`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `open_id` (`open_id`);

--
-- Indexes for table `voicer_info`
--
ALTER TABLE `voicer_info`
  ADD PRIMARY KEY (`voicer_id`);

--
-- Indexes for table `vote_log`
--
ALTER TABLE `vote_log`
  ADD PRIMARY KEY (`user_id`,`vote_date`),
  ADD KEY `vote_voicer_id` (`vote_voicer_id`),
  ADD KEY `vote_datetime` (`vote_datetime`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `user_info`
--
ALTER TABLE `user_info`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `voicer_info`
--
ALTER TABLE `voicer_info`
  MODIFY `voicer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

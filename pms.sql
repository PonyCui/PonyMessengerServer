-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2015 年 03 月 27 日 09:26
-- 服务器版本: 5.6.14
-- PHP 版本: 5.5.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 数据库: `pms`
--

-- --------------------------------------------------------

--
-- 表的结构 `pms_pub`
--

CREATE TABLE IF NOT EXISTS `pms_pub` (
  `pub_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sub_user_id` bigint(20) NOT NULL,
  `sub_service` varchar(32) NOT NULL,
  `sub_method` varchar(32) NOT NULL,
  `sub_params` text NOT NULL,
  PRIMARY KEY (`pub_id`),
  KEY `sub_user_id` (`sub_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12048 ;

-- --------------------------------------------------------

--
-- 表的结构 `pms_token`
--

CREATE TABLE IF NOT EXISTS `pms_token` (
  `user_id` bigint(20) unsigned NOT NULL,
  `session_token` varchar(128) NOT NULL,
  `session_access` varchar(128) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `pms_token`
--

INSERT INTO `pms_token` (`user_id`, `session_token`, `session_access`) VALUES
(1, 'testToken', ''),
(2, 'testToken', 'pub'),
(9, 'a5116a8d10b7b0419b00d313efa2aebc', '');

-- --------------------------------------------------------

--
-- 表的结构 `pms_user_base`
--

CREATE TABLE IF NOT EXISTS `pms_user_base` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `password` varchar(32) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `pms_user_base`
--

INSERT INTO `pms_user_base` (`user_id`, `email`, `password`) VALUES
(9, 'ponycui@me.com', 'c4f34afe14817af7fbb175da2e609dd3');

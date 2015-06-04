-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2015 年 06 月 04 日 14:32
-- 服务器版本: 5.6.14
-- PHP 版本: 5.4.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 数据库: `pms`
--

-- --------------------------------------------------------

--
-- 表的结构 `pms_chat_record`
--

CREATE TABLE IF NOT EXISTS `pms_chat_record` (
  `record_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` bigint(20) unsigned NOT NULL,
  `from_user_id` bigint(20) unsigned NOT NULL,
  `record_time` int(10) unsigned NOT NULL,
  `record_type` enum('0','1','2','3','4','5') COLLATE utf16_bin NOT NULL,
  `record_title` text COLLATE utf16_bin NOT NULL,
  `record_params` text COLLATE utf16_bin NOT NULL,
  `record_hash` varchar(32) COLLATE utf16_bin NOT NULL,
  PRIMARY KEY (`record_id`),
  KEY `from_user_id` (`from_user_id`,`record_time`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- --------------------------------------------------------

--
-- 表的结构 `pms_chat_session`
--

CREATE TABLE IF NOT EXISTS `pms_chat_session` (
  `session_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_type` enum('1','2') NOT NULL,
  `session_title` varchar(64) CHARACTER SET utf16 COLLATE utf16_bin NOT NULL,
  `session_icon` varchar(255) NOT NULL,
  `session_last_update` int(11) NOT NULL,
  `session_last_post` varchar(64) NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `session_last_update` (`session_last_update`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `pms_chat_session_user`
--

CREATE TABLE IF NOT EXISTS `pms_chat_session_user` (
  `session_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  KEY `session_id` (`session_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pms_user_default`
--

CREATE TABLE IF NOT EXISTS `pms_user_default` (
  `user_id` bigint(20) unsigned NOT NULL,
  `privacy_contact_need_agree` enum('0','1') NOT NULL COMMENT '加我为朋友时需要验证',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `pms_user_information`
--

CREATE TABLE IF NOT EXISTS `pms_user_information` (
  `user_id` bigint(20) unsigned NOT NULL,
  `nickname` varchar(64) COLLATE utf16_bin NOT NULL,
  `avatar` varchar(255) COLLATE utf16_bin NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- --------------------------------------------------------

--
-- 表的结构 `pms_user_relation`
--

CREATE TABLE IF NOT EXISTS `pms_user_relation` (
  `relation_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `from_user_id` bigint(20) unsigned NOT NULL,
  `to_user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`relation_id`),
  KEY `from_user_id` (`from_user_id`,`to_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

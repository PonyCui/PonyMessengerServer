-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2015 年 04 月 03 日 02:53
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12172 ;

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
(9, 'a5116a8d10b7b0419b00d313efa2aebc', ''),
(13, '443e37d59a4ca8f164e6b5dc64968895', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1002 ;

--
-- 转存表中的数据 `pms_user_base`
--

INSERT INTO `pms_user_base` (`user_id`, `email`, `password`) VALUES
(9, 'ponycui@me.com', 'c4f34afe14817af7fbb175da2e609dd3'),
(13, 'test@126.com', 'c4f34afe14817af7fbb175da2e609dd3'),
(1000, 'test@test.com', '123456'),
(1001, 'test2@test.com', '123456');

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

--
-- 转存表中的数据 `pms_user_information`
--

INSERT INTO `pms_user_information` (`user_id`, `nickname`, `avatar`) VALUES
(9, 'PonyCui', 'http://tp4.sinaimg.cn/1961248227/180/5706181721/0'),
(13, '测试用户', 'http://tp1.sinaimg.cn/1199605160/180/5624382513/0'),
(1000, '春哥233', 'http://tp1.sinaimg.cn/1199605160/180/5624382513/0'),
(1001, 'Jake', 'http://tp4.sinaimg.cn/5571833295/180/5722160098/0');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `pms_user_relation`
--

INSERT INTO `pms_user_relation` (`relation_id`, `from_user_id`, `to_user_id`) VALUES
(1, 1, 9),
(2, 9, 1000),
(6, 9, 1001);

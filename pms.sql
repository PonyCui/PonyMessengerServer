-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2015 年 04 月 12 日 02:23
-- 服务器版本: 5.6.14
-- PHP 版本: 5.5.14

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf16 COLLATE=utf16_bin AUTO_INCREMENT=40 ;

--
-- 转存表中的数据 `pms_chat_record`
--

INSERT INTO `pms_chat_record` (`record_id`, `session_id`, `from_user_id`, `record_time`, `record_type`, `record_title`, `record_params`, `record_hash`) VALUES
(1, 1, 1000, 1000, '0', 0x48656c6c6f, '', 'ewuqrgfljsdac'),
(2, 1, 9, 1428374077, '1', 0x313233, '', '29660036727441187354047264103'),
(3, 1, 9, 1428374099, '1', 0x333231, '', '48397458942302000784038163070'),
(4, 1, 9, 1428374116, '1', 0x6675636b, '', '6496483079813094601946055326'),
(5, 2, 9, 1428374383, '1', 0x31333431333432, '', '384531916033372791721469740598'),
(6, 2, 9, 1428374410, '1', 0x313233313233, '', '13088701736567827243335851887'),
(7, 2, 9, 1428374462, '1', 0x313233, '', '219887102919336972063580724209'),
(8, 2, 9, 1428374475, '1', 0x313233313233, '', '280943929812201048191481544826'),
(9, 2, 9, 1428374493, '1', 0x3133313233313332, '', '367230771295290699715176935'),
(10, 1, 9, 1428374686, '1', 0x313233, '', '4216284726841547037605871149'),
(11, 1, 9, 1428374696, '1', 0x313233, '', '36239426781198328111766592740'),
(12, 1, 9, 1428374932, '1', 0x313432333431343332, '', '25850015313669180488335971338'),
(13, 1, 9, 1428374942, '1', 0x313233313233, '', '315152062516554148192667017271'),
(14, 1, 9, 1428375404, '1', 0x313233, '', '420119431314763228673616881488'),
(15, 1, 9, 1428375407, '1', 0x313233313233, '', '394688184637336509772271528142'),
(16, 1, 9, 1428376055, '1', 0x313233, '', '245946434427216792043574368202'),
(17, 1, 9, 1428376057, '1', 0x31323331333231, '', '6220706946363699182053499249'),
(18, 1, 9, 1428376347, '1', 0x313233313233, '', '169454582430632753934064321295'),
(19, 1, 9, 1428376506, '1', 0x313233313233, '', '18021234732277144119496343955'),
(20, 1, 9, 1428376560, '1', 0x313233, '', '34955550903917365316406399420'),
(21, 1, 9, 1428376634, '1', 0x313233, '', '145299375321190686042438484421'),
(22, 1, 9, 1428376708, '1', 0x313233, '', '1578994143392301130926342188'),
(23, 1, 9, 1428376751, '1', 0x313233, '', '19907616802886982182534423341'),
(24, 1, 9, 1428377075, '1', 0x313233, '', '21813331263596248432104263249'),
(25, 1, 9, 1428377078, '1', 0x313233313233, '', '396634649510981732061036673849'),
(26, 1, 9, 1428377111, '1', 0x313233313233, '', '353983536729688714504276232626'),
(27, 1, 9, 1428377176, '1', 0x313233313331, '', '11514978989987384121061604769'),
(28, 1, 9, 1428377208, '1', 0x333231333231, '', '231608346826915022252588576905'),
(29, 1, 9, 1428377213, '1', 0x31323331323331, '', '134183906024967722803507043434'),
(30, 1, 9, 1428377215, '1', 0x313233313331323331323331, '', '420010688618237769342256576456'),
(31, 1, 9, 1428377809, '1', 0x313233343536373839, '', '15440065631914072562986924449'),
(32, 1, 9, 1428377830, '1', 0x31323334353637383930, '', '1396351149480136071003063868'),
(33, 1, 9, 1428377905, '1', 0x31323334353637383930, '', '36305675572044199505489518780'),
(34, 1, 9, 1428377917, '1', 0x31323334353637383930, '', '172333752820803176251791799571'),
(35, 1, 9, 1428377970, '1', 0x31323334353637383930, '', '53860846741340166742314188154'),
(36, 1, 1000, 1428377971, '1', 0xe68891e693a6, '', '53860846741340166742314188150'),
(37, 1, 9, 1428378259, '1', 0x3132333132333133, '', '38188224392284690291702593836'),
(38, 1, 1000, 1428378971, '1', 0xe68891e693a6, '', '53860846741340166742314188123213'),
(39, 1, 9, 1428379962, '1', 0x333231, '', '1820797176719950063856123826');

-- --------------------------------------------------------

--
-- 表的结构 `pms_chat_session`
--

CREATE TABLE IF NOT EXISTS `pms_chat_session` (
  `session_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `pms_chat_session`
--

INSERT INTO `pms_chat_session` (`session_id`) VALUES
(1),
(2);

-- --------------------------------------------------------

--
-- 表的结构 `pms_chat_session_user`
--

CREATE TABLE IF NOT EXISTS `pms_chat_session_user` (
  `session_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  KEY `session_id` (`session_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `pms_chat_session_user`
--

INSERT INTO `pms_chat_session_user` (`session_id`, `user_id`) VALUES
(1, 9),
(1, 1000),
(2, 9),
(2, 1001);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `pms_user_relation`
--

INSERT INTO `pms_user_relation` (`relation_id`, `from_user_id`, `to_user_id`) VALUES
(1, 1, 9),
(12, 9, 13),
(2, 9, 1000),
(6, 9, 1001);

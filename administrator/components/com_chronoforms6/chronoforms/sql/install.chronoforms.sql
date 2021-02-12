-- --------------------------------------------------------

--
-- Table structure for table `#__chronoengine_acls`
--

CREATE TABLE IF NOT EXISTS `#__chronoengine_acls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aco` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `rules` text,
  PRIMARY KEY (`id`),
  KEY `aco` (`aco`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__chronoengine_extensions`
--

CREATE TABLE IF NOT EXISTS `#__chronoengine_extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(4) NOT NULL DEFAULT '0',
  `settings` text,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) DEFAULT CHARSET=utf8;

--
-- Table structure for table `jos_chronoengine_connections`
--

CREATE TABLE IF NOT EXISTS `#__chronoengine_forms6` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `alias` varchar(200) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `public` tinyint(1) NOT NULL DEFAULT '1',
  `description` text NOT NULL,
  `params` longtext NOT NULL,
  `events` text NOT NULL,
  `sections` text NOT NULL,
  `views` longtext NOT NULL,
  `functions` longtext NOT NULL,
  `locales` longtext NOT NULL,
  `rules` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`)
) DEFAULT CHARSET=utf8;

ALTER TABLE `#__chronoengine_forms6` CHANGE `views` `views` LONGTEXT CHARACTER SET utf8 NOT NULL;
ALTER TABLE `#__chronoengine_forms6` CHANGE `functions` `functions` LONGTEXT CHARACTER SET utf8 NOT NULL;
ALTER TABLE `#__chronoengine_forms6` CHANGE `locales` `locales` LONGTEXT CHARACTER SET utf8 NOT NULL;
ALTER TABLE `#__chronoengine_forms6` CHANGE `params` `params` LONGTEXT CHARACTER SET utf8 NOT NULL;
ALTER TABLE `#__chronoengine_forms6` CHANGE `sections` `sections` LONGTEXT CHARACTER SET utf8 NOT NULL;
ALTER TABLE `#__chronoengine_forms6` CHANGE `events` `events` LONGTEXT CHARACTER SET utf8 NOT NULL;

-- --------------------------------------------------------

--
-- Table structure for table `#__chronoengine_forms6_blocks`
--

CREATE TABLE IF NOT EXISTS `#__chronoengine_forms6_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `block_id` varchar(55) NOT NULL,
  `desc` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `group` varchar(30) NOT NULL,
  `content` longtext NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__chronoengine_forms6_log`
--

CREATE TABLE IF NOT EXISTS `#__chronoengine_forms6_datalog` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(5) NOT NULL,
  `uid` varchar(50) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime DEFAULT NULL,
  `ipaddress` varchar(50) NOT NULL,
  `page` varchar(50) NOT NULL,
  `data` longtext NOT NULL,
  PRIMARY KEY (`aid`),
  UNIQUE KEY `uid` (`uid`),
  KEY `form_id` (`form_id`),
  KEY `user_id` (`user_id`)
) DEFAULT CHARSET=utf8;
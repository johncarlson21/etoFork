-- etomite_full.sql
-- 8-31-2010
-- updated by John Carlson <johncarlson21@gmail.com> to reflect the updates

-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}active_users`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}active_users` (
  `internalKey` int(9) NOT NULL default '0',
  `username` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `lasthit` int(20) NOT NULL default '0',
  `id` int(10) default NULL,
  `action` varchar(10) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `ip` varchar(20) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`internalKey`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Contains data about active users.';


--
-- Table structure for table `{PREFIX}cron_log`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}cron_log` (
  `id` int(11) NOT NULL auto_increment,
  `date_ran` datetime NOT NULL,
  `errors` text NOT NULL,
  `msg` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{PREFIX}cron_log`
--


-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}documentgroup_names`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}documentgroup_names` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Contains data used for access permissions.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `{PREFIX}documentgroup_names`
--

INSERT INTO `{PREFIX}documentgroup_names` (`id`, `name`) VALUES
(1, 'member_area');

-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}document_groups`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}document_groups` (
  `id` int(10) NOT NULL auto_increment,
  `document_group` int(10) NOT NULL default '0',
  `document` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Contains data used for access permissions.' AUTO_INCREMENT=83 ;

--
-- Dumping data for table `{PREFIX}document_groups`
--

-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}log_access`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}log_access` (
  `visitor` bigint(11) NOT NULL default '0',
  `document` bigint(11) NOT NULL default '0',
  `timestamp` int(20) NOT NULL default '0',
  `hour` tinyint(2) NOT NULL default '0',
  `weekday` tinyint(1) NOT NULL default '0',
  `referer` bigint(11) NOT NULL default '0',
  `entry` tinyint(1) NOT NULL default '0',
  KEY `visitor` (`visitor`),
  KEY `document` (`document`),
  KEY `timestamp` (`timestamp`),
  KEY `referer` (`referer`),
  KEY `entry` (`entry`),
  KEY `hour` (`hour`),
  KEY `weekday` (`weekday`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Contains visitor statistics.';

--
-- Dumping data for table `{PREFIX}log_access`
--


-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}log_hosts`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}log_hosts` (
  `id` bigint(11) NOT NULL default '0',
  `data` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Contains visitor statistics.';

--
-- Dumping data for table `{PREFIX}log_hosts`
--


-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}log_operating_systems`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}log_operating_systems` (
  `id` bigint(11) NOT NULL default '0',
  `data` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Contains visitor statistics.';

--
-- Dumping data for table `{PREFIX}log_operating_systems`
--


-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}log_referers`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}log_referers` (
  `id` bigint(11) NOT NULL default '0',
  `data` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Contains visitor statistics.';

--
-- Dumping data for table `{PREFIX}log_referers`
--


-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}log_totals`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}log_totals` (
  `today` date NOT NULL default '0000-00-00',
  `month` char(2) character set utf8 collate utf8_unicode_ci NOT NULL default '0',
  `piDay` int(11) NOT NULL default '0',
  `piMonth` int(11) NOT NULL default '0',
  `piAll` int(11) NOT NULL default '0',
  `viDay` int(11) NOT NULL default '0',
  `viMonth` int(11) NOT NULL default '0',
  `viAll` int(11) NOT NULL default '0',
  `visDay` int(11) NOT NULL default '0',
  `visMonth` int(11) NOT NULL default '0',
  `visAll` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Stores temporary logging information.';

--
-- Dumping data for table `{PREFIX}log_totals`
--


-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}log_user_agents`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}log_user_agents` (
  `id` bigint(11) NOT NULL default '0',
  `data` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Contains visitor statistics.';

--
-- Dumping data for table `{PREFIX}log_user_agents`
--


-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}log_visitors`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}log_visitors` (
  `id` bigint(11) NOT NULL default '0',
  `os_id` bigint(11) NOT NULL default '0',
  `ua_id` bigint(11) NOT NULL default '0',
  `host_id` bigint(11) NOT NULL default '0',
  KEY `id` (`id`),
  KEY `os_id` (`os_id`),
  KEY `ua_id` (`ua_id`),
  KEY `host_id` (`host_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Contains visitor statistics.';

--
-- Dumping data for table `{PREFIX}log_visitors`
--


-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}manager_log`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}manager_log` (
  `id` int(10) NOT NULL auto_increment,
  `timestamp` int(20) NOT NULL default '0',
  `internalKey` int(10) NOT NULL default '0',
  `username` varchar(255) character set utf8 collate utf8_unicode_ci default NULL,
  `action` int(10) NOT NULL default '0',
  `itemid` varchar(10) character set utf8 collate utf8_unicode_ci default '0',
  `itemname` varchar(255) character set utf8 collate utf8_unicode_ci default NULL,
  `message` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Contains a record of user interaction with Etomite.' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{PREFIX}manager_log`
--


-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}manager_users`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}manager_users` (
  `id` int(10) NOT NULL auto_increment,
  `username` varchar(15) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `password` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Contains login information for Etomite users.' AUTO_INCREMENT=2 ;


--
-- Table structure for table `{PREFIX}membergroup_access`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}membergroup_access` (
  `id` int(10) NOT NULL auto_increment,
  `membergroup` int(10) NOT NULL default '0',
  `documentgroup` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Contains data used for access permissions.' AUTO_INCREMENT=2 ;


--
-- Table structure for table `{PREFIX}membergroup_names`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}membergroup_names` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Contains data used for access permissions.' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}member_groups`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}member_groups` (
  `id` int(10) NOT NULL auto_increment,
  `user_group` int(10) NOT NULL default '0',
  `member` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Contains data used for access permissions.' AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}member_log`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}member_log` (
  `id` int(11) NOT NULL auto_increment,
  `mem_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `dstamp` datetime NOT NULL,
  `document` mediumtext NOT NULL,
  `action` mediumtext NOT NULL,
  `message` mediumtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='members area log of actions' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{PREFIX}member_log`
--


-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}member_validation`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}member_validation` (
  `id` int(10) NOT NULL auto_increment,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) default NULL,
  `userName` varchar(100) default NULL,
  `email` varchar(100) default NULL,
  `phone` varchar(100) default NULL,
  `mobilephone` varchar(100) default NULL,
  `hash` varchar(100) default NULL,
  `password` varchar(100) default NULL,
  `role` int(10) default NULL,
  `tstmp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `address` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}site_content`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}site_content` (
  `id` int(10) NOT NULL auto_increment,
  `type` varchar(20) character set utf8 collate utf8_unicode_ci NOT NULL default 'document',
  `contentType` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL default 'text/html',
  `pagetitle` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `longtitle` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `description` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `alias` varchar(100) character set utf8 collate utf8_unicode_ci default '',
  `published` int(1) NOT NULL default '0',
  `pub_date` int(20) NOT NULL default '0',
  `unpub_date` int(20) NOT NULL default '0',
  `parent` int(10) NOT NULL default '0',
  `isfolder` int(1) NOT NULL default '0',
  `content` mediumtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `richtext` tinyint(1) NOT NULL default '1',
  `template` int(10) NOT NULL default '1',
  `menuindex` int(10) NOT NULL default '0',
  `searchable` int(1) NOT NULL default '1',
  `cacheable` int(1) NOT NULL default '1',
  `createdby` int(10) NOT NULL default '0',
  `createdon` int(20) NOT NULL default '0',
  `editedby` int(10) NOT NULL default '0',
  `editedon` int(20) NOT NULL default '0',
  `deleted` int(1) NOT NULL default '0',
  `deletedon` int(20) NOT NULL default '0',
  `deletedby` int(10) NOT NULL default '0',
  `authenticate` int(1) NOT NULL default '0',
  `showinmenu` int(1) NOT NULL default '1',
  `meta_title` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `meta_description` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `meta_keywords` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `parent` (`parent`),
  FULLTEXT KEY `content_ft_idx` (`pagetitle`,`description`,`content`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Contains the site''s document tree.' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `{PREFIX}site_content`
--

INSERT INTO `{PREFIX}site_content` (`id`, `type`, `contentType`, `pagetitle`, `longtitle`, `description`, `alias`, `published`, `pub_date`, `unpub_date`, `parent`, `isfolder`, `content`, `richtext`, `template`, `menuindex`, `searchable`, `cacheable`, `createdby`, `createdon`, `editedby`, `editedon`, `deleted`, `deletedon`, `deletedby`, `authenticate`, `showinmenu`, `meta_title`, `meta_description`, `meta_keywords`) VALUES
(1, 'document', 'text/html', 'Home', '', '', 'index', 1, 0, 0, 0, 0, '<p>Home page</p>', 1, 1, 7, 1, 1, 1, 1268322081, 1, 1283282459, 0, 0, 0, 0, 1, 'Home', '', ''),
(2, 'document', 'text/html', 'Repository', 'The secret Repository', 'Folder for other stuff :)', '', 0, 0, 0, 0, 1, 'Why are you reading this?', 0, 3, 16, 0, 0, 1, 1268322081, 1, 1283282560, 0, 0, 0, 0, 1, '', '', ''),
(3, 'document', 'text/html', 'Error 404 - Page Not Found', 'Unable to locate the page you requested.', 'The error page which is displayed when the requested page cannot be found', 'http404', 1, 0, 0, 2, 0, '<p><strong>404 Error - File not Found</strong></p>\r\n<p>Hm. The page you''ve requested wasn''t found. Perhaps the page has moved, or you mistyped the URL. Or, you could try going back to the main site and trying to find the page you were looking for from there.[[DontLogPageHit]]</p>', 1, 3, 6, 0, 0, 1, 1268322081, 1, 1283282416, 0, 0, 0, 0, 1, '', '', ''),
(4, 'document', 'text/xml', 'Google Site Map', '', '', 'google-sitemap', 1, 0, 0, 2, 0, '', 0, 2, 10, 0, 0, 1, 1268322081, 1, 1268322081, 0, 0, 0, 0, 1, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}site_content_versions`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}site_content_versions` (
  `id` int(10) NOT NULL auto_increment,
  `versionedon` int(20) NOT NULL default '0',
  `orig_id` int(10) NOT NULL,
  `type` varchar(20) character set utf8 collate utf8_unicode_ci NOT NULL default 'document',
  `contentType` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL default 'text/html',
  `pagetitle` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `longtitle` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `description` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `alias` varchar(100) character set utf8 collate utf8_unicode_ci default '',
  `published` int(1) NOT NULL default '0',
  `pub_date` int(20) NOT NULL default '0',
  `unpub_date` int(20) NOT NULL default '0',
  `parent` int(10) NOT NULL default '0',
  `isfolder` int(1) NOT NULL default '0',
  `content` mediumtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `richtext` tinyint(1) NOT NULL default '1',
  `template` int(10) NOT NULL default '1',
  `menuindex` int(10) NOT NULL default '0',
  `searchable` int(1) NOT NULL default '1',
  `cacheable` int(1) NOT NULL default '1',
  `createdby` int(10) NOT NULL default '0',
  `createdon` int(20) NOT NULL default '0',
  `editedby` int(10) NOT NULL default '0',
  `editedon` int(20) NOT NULL default '0',
  `deleted` int(1) NOT NULL default '0',
  `deletedon` int(20) NOT NULL default '0',
  `deletedby` int(10) NOT NULL default '0',
  `authenticate` int(1) NOT NULL default '0',
  `showinmenu` int(1) NOT NULL default '1',
  `meta_title` varchar(255) character set utf8 collate utf8_unicode_ci default '',
  `meta_description` varchar(255) character set utf8 collate utf8_unicode_ci default '',
  `meta_keywords` varchar(255) character set utf8 collate utf8_unicode_ci default '',
  PRIMARY KEY  (`id`),
  KEY `parent` (`parent`),
  FULLTEXT KEY `content_ft_idx` (`pagetitle`,`description`,`content`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Contains the site''s document tree versions.' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{PREFIX}site_content_versions`
--


-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}site_htmlsnippets`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}site_htmlsnippets` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `description` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default 'Chunk',
  `snippet` mediumtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `locked` tinyint(4) NOT NULL default '0',
  `section` int(11) NOT NULL COMMENT 'id from section table',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Contains the site''s chunks.' AUTO_INCREMENT=33 ;


--
-- Table structure for table `{PREFIX}site_htmlsnippets_versions`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}site_htmlsnippets_versions` (
  `id` int(10) NOT NULL auto_increment,
  `date_mod` datetime NOT NULL,
  `htmlsnippet_id` int(10) NOT NULL,
  `name` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `description` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default 'Chunk',
  `snippet` mediumtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `locked` tinyint(4) NOT NULL default '0',
  `section` int(11) NOT NULL COMMENT 'id from section table',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Contains the site''s chunks.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}site_section`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}site_section` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) NOT NULL,
  `section_type` enum('snippet','chunk') NOT NULL,
  `sort_order` char(2) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='table for snippet and chunk sections' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `{PREFIX}site_section`
--

INSERT INTO `{PREFIX}site_section` (`id`, `name`, `description`, `section_type`, `sort_order`) VALUES (1, 'Default', 'Default section for un-organized snippets', 'snippet', '0');

INSERT INTO `{PREFIX}site_section` (`id`, `name`, `description`, `section_type`, `sort_order`) VALUES (2, 'Default', 'Default section for un-organized chunks', 'chunk', '0');

INSERT INTO `{PREFIX}site_section` (`id`, `name`, `description`, `section_type`, `sort_order`) VALUES
(3, '831 Gallery', '', 'snippet', '1'),
(4, '831 Gallery', '', 'chunk', '1');

-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}site_snippets`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}site_snippets` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `description` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default 'Snippet',
  `snippet` mediumtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `locked` tinyint(4) NOT NULL default '0',
  `section` int(11) NOT NULL COMMENT 'id from section table',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 COMMENT='Contains the site''s snippets.' AUTO_INCREMENT=112 ;


--
-- Table structure for table `{PREFIX}site_snippets_versions`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}site_snippets_versions` (
  `id` int(10) NOT NULL auto_increment,
  `date_mod` datetime NOT NULL,
  `snippet_id` int(10) NOT NULL,
  `name` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `description` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default 'Snippet',
  `snippet` mediumtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `locked` tinyint(4) NOT NULL default '0',
  `section` int(11) NOT NULL COMMENT 'id from section table',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0 COMMENT='Contains the site''s snippets.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}site_templates`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}site_templates` (
  `id` int(10) NOT NULL auto_increment,
  `templatename` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `description` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default 'Template',
  `content` mediumtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `locked` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 COMMENT='Contains the site''s templates.' AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}system_settings`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}system_settings` (
  `setting_name` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `setting_value` varchar(250) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  UNIQUE KEY `setting_name` (`setting_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Contains Etomite settings.';

-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}user_attributes`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}user_attributes` (
  `id` int(10) NOT NULL auto_increment,
  `internalKey` int(10) NOT NULL default '0',
  `fullname` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `role` int(10) NOT NULL default '0',
  `email` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `phone` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `mobilephone` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `blocked` int(1) NOT NULL default '0',
  `blockeduntil` int(11) NOT NULL default '0',
  `logincount` int(11) NOT NULL default '0',
  `lastlogin` int(11) NOT NULL default '0',
  `thislogin` int(11) NOT NULL default '0',
  `failedlogincount` int(10) NOT NULL default '0',
  `sessionid` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `address` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip` varchar(100) NOT NULL,
  `mailmessages` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userid` (`internalKey`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Contains information about Etomite users.' AUTO_INCREMENT=2 ;


-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}user_messages`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}user_messages` (
  `id` int(10) NOT NULL auto_increment,
  `type` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `subject` varchar(60) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `message` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `sender` int(10) NOT NULL default '0',
  `recipient` int(10) NOT NULL default '0',
  `private` tinyint(4) NOT NULL default '0',
  `postdate` int(20) NOT NULL default '0',
  `messageread` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Contains messages for the Etomite messaging system.' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{PREFIX}user_messages`
--


-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}user_roles`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}user_roles` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `description` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  `frames` int(1) NOT NULL default '0',
  `home` int(1) NOT NULL default '0',
  `view_document` int(1) NOT NULL default '0',
  `new_document` int(1) NOT NULL default '0',
  `save_document` int(1) NOT NULL default '0',
  `delete_document` int(1) NOT NULL default '0',
  `action_ok` int(1) NOT NULL default '0',
  `logout` int(1) NOT NULL default '0',
  `help` int(1) NOT NULL default '0',
  `messages` int(1) NOT NULL default '0',
  `new_user` int(1) NOT NULL default '0',
  `edit_user` int(1) NOT NULL default '0',
  `logs` int(1) NOT NULL default '0',
  `edit_parser` int(1) NOT NULL default '0',
  `save_parser` int(1) NOT NULL default '0',
  `edit_template` int(1) NOT NULL default '0',
  `settings` int(1) NOT NULL default '0',
  `credits` int(1) NOT NULL default '0',
  `new_template` int(1) NOT NULL default '0',
  `save_template` int(1) NOT NULL default '0',
  `delete_template` int(1) NOT NULL default '0',
  `edit_snippet` int(1) NOT NULL default '0',
  `new_snippet` int(1) NOT NULL default '0',
  `save_snippet` int(1) NOT NULL default '0',
  `delete_snippet` int(1) NOT NULL default '0',
  `empty_cache` int(1) NOT NULL default '0',
  `edit_document` int(1) NOT NULL default '0',
  `change_password` int(1) NOT NULL default '0',
  `error_dialog` int(1) NOT NULL default '0',
  `about` int(1) NOT NULL default '0',
  `file_manager` int(1) NOT NULL default '0',
  `save_user` int(1) NOT NULL default '0',
  `delete_user` int(1) NOT NULL default '0',
  `save_password` int(11) NOT NULL default '0',
  `edit_role` int(11) NOT NULL default '0',
  `save_role` int(11) NOT NULL default '0',
  `delete_role` int(11) NOT NULL default '0',
  `new_role` int(11) NOT NULL default '0',
  `access_permissions` int(1) NOT NULL default '0',
  `new_chunk` int(1) NOT NULL default '0',
  `save_chunk` int(1) NOT NULL default '0',
  `edit_chunk` int(1) NOT NULL default '0',
  `delete_chunk` int(1) NOT NULL default '0',
  `export_html` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Contains information describing the Etomite user roles.' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}web_users`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}web_users` (
  `id` int(10) NOT NULL auto_increment,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) default NULL,
  `username` varchar(100) default NULL,
  `email` varchar(100) default NULL,
  `phone` varchar(100) default NULL,
  `phone2` varchar(100) NOT NULL,
  `hash` varchar(100) default NULL,
  `password` varchar(100) default NULL,
  `role` int(10) default '1' COMMENT 'default of 1 = normal member',
  `tstmp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `address` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip` varchar(100) NOT NULL,
  `company_name` varchar(250) NOT NULL,
  `fax` varchar(100) NOT NULL,
  `website` varchar(250) NOT NULL,
  `logo` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `status` char(1) NOT NULL default '0',
  `date_joined` int(11) NOT NULL,
  `lastlogin` datetime NOT NULL,
  `furl` varchar(250) NOT NULL COMMENT 'friendly url for dealers',
  `show_contact_info` char(1) NOT NULL default '0',
  `package_id` int(11) NOT NULL COMMENT 'package id for dealers',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='frontend web users' AUTO_INCREMENT=2 ;


-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}web_user_roles`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}web_user_roles` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  `description` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='frontend user roles' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `{PREFIX}web_user_roles`
--

INSERT INTO `{PREFIX}web_user_roles` (`id`, `name`, `description`) VALUES
(1, 'Member', 'Normal Website Member');

INSERT INTO `{PREFIX}manager_users` VALUES (1, '{ADMIN}', MD5('{ADMINPASS}'));

INSERT INTO `{PREFIX}user_attributes` VALUES (1, 1, 'Administration account', 1, 'Your email goes here', '', '', 0, 0, 0, {TIMESTAMP}, {TIMESTAMP}, 0, '', '', '', '', '', 0);

INSERT INTO `{PREFIX}user_roles` VALUES(1, 'Administrator', 'Site administrators have full access to all functions', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0);

--
-- Dumping data for table `{PREFIX}system_settings`
--

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('settings_version', '1.1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('date_format', '%Y-%m-%d');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('time_format', '%I:%M %p');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('server_offset_time', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('server_protocol', 'http');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('manager_language', 'english');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('etomite_charset', 'iso-8859-1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('site_name', 'My Site');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('site_start', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('error_page', '3');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('site_status', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('site_unavailable_message', 'The site is currently unavailable');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('track_visitors', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('resolve_hostnames', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('top_howmany', '10');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('default_template', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('publish_default', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('search_default', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('cache_default', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('syncsitecheck_default', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('showinmenu_default', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('friendly_urls', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('friendly_url_prefix', '');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('friendly_url_suffix', '.html');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('friendly_alias_urls', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('use_udperms', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('use_uvperms', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('access_denied_message', '');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('udperms_allowroot', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('use_mgr_logging', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('max_attempts', '3');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('use_captcha', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('captcha_words', 'Array,BitCode,Chunk,Document,Etomite,Forum,Index,Javascript,Keyword,MySQL,Parser,Query,Random,Snippet,Template,Website');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('signupemail_message', 'Hi! \r\n\r\nHere are your login details for Etomite:\r\n\r\nUsername: %s\r\nPassword: %s\r\n\r\nOnce you log into Etomite, you can change your password.\r\n\r\nRegards,\r\nThe Management');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('emailsender', 'you@yourdomain.com');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('emailsubject', 'Your Etomite login details');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('number_of_logs', '100');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('number_of_messages', '100');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('show_doc_data_preview', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('use_doc_editor', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('which_editor', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xSkin', '');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xLang', 'en');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('strict_editor', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('strip_base_href', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('cm_plugin', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('to_plugin', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp__svn', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_FullPage', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_PasteText', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_Abbreviation', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_GenericPlugin', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_PersistentStorage', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_CSS', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_GetHtml', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_PreserveScripts', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_CSSPicker', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_HtmlEntities', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_QuickTag', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_CharCounter', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_InsertNote', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_SaveOnBlur', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_CharacterMap', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_InsertPagebreak', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_SaveSubmit', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_ContextMenu', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_InsertSmiley', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_SetId', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_DefinitionList', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_InsertSnippet', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_SmartReplace', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_DoubleClick', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_InsertSnippet2', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_SpellChecker', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_DynamicCSS', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_InsertWords', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_SuperClean', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_EditTag', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_LangMarks', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_TableOperations', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_Equation', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_ListType', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_UnFormat', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_ExtendedFileManager', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_MootoolsFileManager', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_UnsavedChanges', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_FindReplace', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_PSFixed', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_WysiwygWrap', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_FormOperations', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_PSLocal', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_Forms', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_PSServer', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_Stylist', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('xp_Stylist_path', '');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('im_plugin', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('im_plugin_base_dir', '');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('im_plugin_base_url', '');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('fm_plugin', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('fm_plugin_base_url', '');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('fm_plugin_document_url', '');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('fm_path', '/assets/documents');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('fm_exclude', '.,..,cgi-bin,aspnet_client,index.php,index.html');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('fm_upload_files', 'jpg,gif,png,ico,txt,php,html,htm,xml,js,css,cache,zip,gz,rar,z,tgz,tar,htaccess,bmp,mp3,wav,au,wmv,avi,mpg,mpeg,pdf,psd');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('tiny_css_path', '');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('tiny_css_selectors', '');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('use_code_editor', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('code_highlight', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('dumpSQL', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('dumpSnippets', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('allow_embedded_php', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('strip_image_paths', '0');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('filemanager_path', '');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('upload_files', 'jpg,gif,png,ico,txt,php,html,htm,xml,js,css,cache,zip,gz,rar,z,tgz,tar,htaccess,bmp,mp3,wav,au,wmv,avi,mpg,mpeg,pdf,psd');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('inlineview_files', 'txt,php,html,htm,xml,js,css');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('view_files', 'jpg,gif,png,ico');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('edit_files', 'txt,php,html,htm,xml,js,css');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('exclude_paths', '.,..,cgi-bin,manager');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('maxuploadsize', '');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('useNotice', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('manager_layout', '1');

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('zend_urls', '1');

--
-- Dumping data for table `{PREFIX}site_snippets`
--

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (1, 'DontLogPageHit', 'Stops the parser from logging the page hit', '$this->config[''track_visitors'']=0;\r\nreturn "";', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (2, 'PoweredBy', 'A little link to Etomite', '// Snippet name: PoweredBy\r\n// Snippet description: A little link to Etomite.\r\n// Revision: 1.00 ships with Etomite 0.6.1-Final\r\n\r\n$version = $etomite->getVersionData();\r\nreturn ''<a href="http://www.etomite.com" title="Etomite Website">Powered by Etomite <b>''.$version[''version''].$version[''patch_level''].''</b> <i>(''.$version[''code_name''].'')</i>.</a>'';', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (3, 'PageTrail', 'Outputs the page trail, based on Bill Wilson''s script', '// Snippet name: PageTrail\r\n// Snippet description: Outputs the page trail, based on Bill Wilson''s script\r\n// Revision: 1.00 ships with Etomite 0.6.1-Final\r\n\r\n$sep = $etomite->bcSep; // system breadcrumb seperator\r\n\r\n// end config\r\n$ptarr = array();\r\n$pid = $etomite->documentObject[''parent''];\r\n$ptarr[] = "<a href=''[~".$etomite->documentObject[''id'']."~]''>".$etomite->documentObject[''pagetitle'']."</a>";\r\n\r\nwhile ($parent=$etomite->getParent($pid)) {\r\n    $ptarr[] = "<a href=''[~".$parent[''id'']."~]''>".$parent[''pagetitle'']."</a>";\r\n    $pid = $parent[''parent''];\r\n}\r\n\r\n$ptarr = array_reverse($ptarr);\r\nreturn join($ptarr, $sep);', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (4, 'MenuBuilder', 'Builds the site menu', '// Snippet name: MenuBuilder\r\n// Snippet description: Builds the site menu\r\n// Revision: 1.00 ships with Etomite 0.6.1-Final\r\n\r\n$id = isset($id) ? $id : $etomite->documentIdentifier;\r\n$sortby = "menuindex";\r\n$sortdir = "ASC";\r\n$fields = "id, pagetitle, description, parent, alias";\r\n\r\n$indentString="";\r\n\r\nif(!isset($indent)) {\r\n    $indent = "";\r\n    $indentString .= "";\r\n} else {\r\n    for($in=0; $in<$indent; $in++) {\r\n        $indentString .= "&nbsp;";\r\n    }\r\n    $indentString .= "&raquo;&nbsp;";\r\n}\r\n\r\n$children = $etomite->getActiveChildren($id, $sortby, $sortdir, $fields);\r\n$menu = "";\r\n$childrenCount = count($children);\r\n$active="";\r\n\r\nif($children==false) {\r\n    return false;\r\n}\r\nfor($x=0; $x<$childrenCount; $x++) {\r\n  if($children[$x][''id'']==$etomite->documentIdentifier) {\r\n   $active="class=\\"highLight\\"";\r\n  } else {\r\n    $active="";\r\n }\r\n if($children[$x][''id'']==$etomite->documentIdentifier || $children[$x][''id'']==$etomite->documentObject[''parent'']) {\r\n    $menu .= "<a ".$active." href=\\"[~".$children[$x][''id'']."~]\\">$indentString".$children[$x][''pagetitle'']."</a>[[MenuBuilder?id=".$children[$x][''id'']."&indent=2]]";  \r\n  } else {\r\n    $menu .= "<a href=\\"[~".$children[$x][''id'']."~]\\">$indentString".$children[$x][''pagetitle'']."</a>";\r\n }\r\n}\r\nreturn $menu."";', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (5, 'MetaTagsExtra', 'Output page related meta tags', '/*\r\nSnippet Title:     MetaTagsExtra\r\nSnippet Version:   2.2\r\nEtomite Version:   0.6 +\r\n\r\nDescription:\r\n  Returns XHTML for document meta tags: \r\n     Content-Type, Content-Language, Generator,\r\n     Title, Description, Keywords, Abstract, Author, Copyright, \r\n     Robots, Googlebot, Cache-Control, Pragma, Expires, Last Modified,\r\n     Distribution and Rating.\r\n  Can also return XHTML for Dublin Core Metadata Initiative meta tags:\r\n     DC.format, DC.language, DC.title, \r\n     DC.description, DC.subject, DC.title.alternative,\r\n     DC.publisher, DC.creator, DC.rights,\r\n     DC.date.created, DC.date.modified, DC.date.valid and DC.identifier.\r\n  Can also return the GeoURL and GeoTags meta tags:\r\n     DC.title, ICBM, geo.position, geo.placename and geo.region.\r\n\r\nSnippet Author:\r\n  Miels with mods by Lloyd Borrett (lloyd@borrett.id.au)\r\n\r\nVersion History:\r\n  1.3 - Lloyd Borrett added the Robots meta tag based \r\n  on the idea in the SearchableSE snippet by jaredc\r\n\r\n  1.4 - Lloyd Borrett added the Abstract meta tag\r\n  based on the Site Name and the Long Title.\r\n  Also added the Generator meta tag based on the Etomite version details.\r\n  The Robots meta tag is now only output if the document is non-searchable,\r\n  to reduce XHTML bloat. The Googlebot meta tag is now also output \r\n  when the document is non-searchable.\r\n\r\n  1.5 - Lloyd Borrett added no-cache directives via the Cache-Control \r\n  and Pragma meta tags if the document is non-cacheable.\r\n  Abstract meta tag uses the document description if long title not set.\r\n  Cleaned up some other tests.\r\n\r\n  1.6 - 2006-01-26 - Lloyd Borrett cleaned up some code.\r\n\r\n  1.7 - 2006-01-27 - Lloyd Borrett\r\n  Added support for the Distribution and Rating meta tags.\r\n  Copyright meta tag can now include a year range being from either \r\n  a site creation year to the current year, or from the year the \r\n  document was created to the current year, e.g. 2005-2006.\r\n  Added ability to specify a site wide author, and thus be able to\r\n  skip looking up document author details.\r\n\r\n  1.8 - 2006-01-27 - Lloyd Borrett\r\n  Current year now based on local time using the Etomite\r\n  Server Offset Time configuration setting\r\n\r\n  1.9 - 2006-03-08 - Lloyd Borrett\r\n  Dates in meta tags can be output in your choice of ISO 8601\r\n  or RFC 822 formats.\r\n  Dates in the meta tags are now corrected to local time.\r\n  Fixed problem with the generation of the "description" meta tag.\r\n\r\n  2.0 - 2006-03-10 - Lloyd Borrett\r\n  Moved the generation of the "content-type", "content-language" \r\n  and "title" meta tags into this snippet.\r\n  Added in support for the Dublin Core Metadata Initiative meta tags.\r\n\r\n  2.1 - 2006-03-15 - Lloyd Borrett\r\n  Dropped the choice of date formats. Dublin Core tags now use ISO dates. \r\n  Others tags use RFC 822 dates. This is what is properly supported.\r\n  Added in support for GeoURL (www.geourl.org) and \r\n  GeoTags (www.geotags.com) meta tags.\r\n\r\n  2.2 - 2006-04-07 - Lloyd Borrett\r\n  Get the base URL from Etomite instead of it being a configuration option.\r\n   \r\nSnippet Category:\r\n  Search Engines           \r\n\r\nUsage:\r\n  Insert [[MetaTagsExtra]] anywhere in the head section of your template.\r\n  Don''t forget to set the full name of all document authors.\r\n  You can find it at "Manage users" -> your username -> "full name".\r\n  This value is used for the Author and Copyright meta tags.\r\n\r\n  When you mark a page as "NOT searchable" - a Robots meta tag \r\n  with "noindex, nofollow" is inserted to keep web search engines\r\n  from indexing that document. After all, there''s little value in \r\n  making your Etomite document unsearchable to Etomite, when \r\n  Google still knows where it is! For "searchable" documents, no\r\n  Robots meta tag is inserted. The default is "index, follow", so not\r\n  putting it in reduced HTML bloat.\r\n  A Googlebot meta tag with "noindex, nofollow, noarchive, nosnippet"\r\n  is also output, to tell Google to clean out its cache.\r\n\r\n  When you mark a page as "non cacheable", no-cache directives \r\n  are inserted via the Cache-Control and Pragma meta tags.\r\n*/\r\n\r\n// *** Configuration Settings ***\r\n\r\n// Provide the content type setting.\r\n$content_type = "text/html; charset=utf-8";\r\n\r\n// Provide the language setting.\r\n$language = "en";\r\n\r\n// Distribution can be "global", "local" or "iu"\r\n// If you want no Distribution meta tag use ''''\r\n$distribution = ''global'';\r\n\r\n// Rating can be "14 years", "general", "mature", "restricted" or "safe for kids"\r\n// If you want no Rating meta tag use ''''\r\n$rating = ''general'';\r\n\r\n// Start Date of the web site as used for the copyright meta tag\r\n// To use the document creation date, set this to ''''\r\n$site_start_year = ''2010'';\r\n\r\n// Site Author can be used for the Author and Copyright meta tags\r\n// To use the document author details of each document, set this to ''''\r\n$site_author = $etomite->config[''site_name''];\r\n\r\n// Provide the full URL of your web site.\r\n// For example: http://www.yourdomain.com\r\n// NOTE: Do not put a / on the end of the web site URL.\r\n// Used to build the DC.identifier tag\r\nglobal $ETOMITE_PAGE_BASE;\r\n$websiteurl = $ETOMITE_PAGE_BASE[''www''];\r\n\r\n// Provide the latitude of the resource\r\n$latitude = "";\r\n\r\n// Provide the longitude of the resource\r\n$longitude = "";\r\n\r\n// Provide the place name of the resource\r\n$placename = "";\r\n\r\n// Provide the ISO 3166 region code of the resource\r\n$region = "";\r\n\r\n// DC Tags is used to specify if the Dublin Core Metadata Initiative \r\n// meta tags should also be generated.\r\n// Set to true to generate them, false otherwise.\r\n$dc_tags = false;\r\n\r\n// Geo Tags is used to specify if the Geo Tags \r\n// meta tags should also be generated.\r\n// Set to true to generate them, false otherwise.\r\n$geo_tags = false;\r\n\r\n\r\n// Initialise variables\r\n\r\n$MetaType = "";\r\n$MetaLanguage = "";\r\n$MetaTitle = "";\r\n$MetaGenerator = "";\r\n$MetaDesc = "";\r\n$MetaKeys = "";\r\n$MetaAbstract = "";\r\n$MetaAuthor = "";\r\n$MetaCopyright = "";\r\n$MetaRobots = "";\r\n$MetaGooglebot = "";\r\n$MetaCache = "";\r\n$MetaPragma = "";\r\n$MetaExpires = "";\r\n$MetaEditedOn = "";\r\n$MetaDistribution = "";\r\n$MetaRating = "";\r\n\r\n// The data format of the resource\r\n$DC_format = "";\r\n\r\n// The language of the content of the resource\r\n$DC_language = "";\r\n\r\n// The name given to the resource\r\n$DC_title = "";\r\n\r\n// A textual description of the content and/or purpose of the resource\r\n// Equivalent to "description"\r\n$DC_description = "";\r\n\r\n// The subject and topic of the resource that succinctly \r\n// describes the content of the resource.\r\n// Equivalent to "keywords"\r\n$DC_subject = "";\r\n\r\n// Any form of the title used as a substitute or alternative \r\n// to the formal title of the resource.\r\n// Equivalent to "abstract"\r\n$DC_title_alternative = "";\r\n\r\n// The name of the entity responsible for making the resource available\r\n// Equivalent to "author"\r\n$DC_publisher = "";\r\n\r\n// An entity primarily responsible for making the content of the resource\r\n// Equivalent to "author"\r\n$DC_creator = "";\r\n\r\n// A statement or pointer to a statement about the \r\n// rights management information for the resource\r\n// Equivalent to "copyright"\r\n$DC_rights = "";\r\n\r\n// The date the resource was created in its current form\r\n$DC_date_created = "";\r\n\r\n// The date the resource was last modified or updated\r\n$DC_date_modified = "";\r\n\r\n// The date of validity of the resource.\r\n// Specified as from the creation date to the expiry date\r\n$DC_date_valid = "";\r\n\r\n// A unique identifier for the resource\r\n$DC_identifier = "";\r\n\r\n\r\n// The latitude and longitude of the resource\r\n$Geo_position = "";\r\n\r\n// The latitude and longitude of the resource\r\n$Geo_icbm = "";\r\n\r\n// The place name of the resource\r\n$Geo_placename = "";\r\n\r\n// The region of the resource\r\n$Geo_region = "";\r\n\r\n\r\n// *** FUNCTIONS ***\r\n\r\nfunction get_local_GMT_offset($server_offset_time) {\r\n    // Get the local GMT offset when given the\r\n    // local to Etomite server offset time in seconds\r\n    $GMT_offset = date("O");\r\n    $GMT_hr = substr($GMT_offset,1,2);\r\n    $GMT_min = substr($GMT_offset,4,2);\r\n    $GMT_sign = substr($GMT_offset,0,1);\r\n    $GMT_secs = (intval($GMT_hr) * 3600) + (intval($GMT_min) * 60);\r\n    if ($GMT_sign == ''-'') { $GMT_secs = $GMT_secs * (-1); }\r\n\r\n    // Get the local GMT offset in seconds\r\n    $GMT_local_seconds = $GMT_secs + $server_offset_time;\r\n    $GMT_local_secs = abs($GMT_local_seconds);\r\n\r\n    // round down to the number of hours\r\n    $GMT_local_hours = intval($GMT_local_secs / 3600);\r\n    // round down to the number of minutes\r\n    $GMT_local_minutes = intval(($GMT_local_secs - ($GMT_local_hours * 3600)) / 60);\r\n    if ($GMT_local_seconds < 0) {\r\n      $GMT_value = "-";\r\n    } else {\r\n      $GMT_value = "+";\r\n    }\r\n    $GMT_value .= sprintf("%02d:%02d", $GMT_local_hours, $GMT_local_minutes);\r\n    return $GMT_value;\r\n}\r\n\r\nfunction get_local_iso_8601_date($int_date, $server_offset_time) {\r\n    // Return an ISO 8601 style local date\r\n    // $int_date: current date in UNIX timestamp\r\n    $GMT_value = get_local_GMT_offset($server_offset_time);\r\n    $local_date = date("Y-m-d\\TH:i:s", $int_date + $server_offset_time);\r\n    $local_date .= $GMT_value;\r\n    return $local_date;\r\n}\r\n\r\nfunction get_local_rfc_822_date($int_date, $server_offset_time) {\r\n    // return an RFC 822 style local date\r\n    // $int_date: current date in UNIX timestamp\r\n    $GMT_value = get_local_GMT_offset($server_offset_time);\r\n    $local_date = date("D, d M Y H:i:s", $int_date + $server_offset_time);\r\n    $local_date .= " ".str_replace('':'', '''', $GMT_value);\r\n    return $local_date;\r\n}\r\n\r\n\r\n// *** Start Creating Meta Tags ***\r\n\r\n// *** CONTENT-TYPE ***\r\n$MetaType = " <meta http-equiv=\\"content-type\\" content=\\"".$content_type."\\" />\\n";\r\n\r\n// *** DC.FORMAT ***\r\nif ($dc_tags) {\r\n   $DC_format = " <meta name=\\"DC.format\\" content=\\"".$content_type."\\" />\\n";\r\n}\r\n\r\n\r\n// *** CONTENT-LANGUAGE ***\r\n$MetaLanguage = " <meta http-equiv=\\"content-language\\" content=\\"".$language."\\" />\\n";\r\n\r\n// *** DC.LANGUAGE ***\r\nif ($dc_tags) {\r\n   $DC_language = " <meta name=\\"DC.language\\" content=\\"".$language."\\" />\\n";\r\n}\r\n\r\n// *** GENERATOR ***\r\n$version = $etomite->getVersionData();\r\n$version[''version''] = trim($version[''version'']);\r\n$version[''code_name''] = trim($version[''code_name'']);\r\nif (($version[''version''] != "") || ($version[''code_name''] != "")) {\r\n   $MetaGenerator = " <meta name=\\"generator\\" content=\\"Etomite";\r\n   if($version[''version''] != ""){\r\n      $MetaGenerator .= " ".$version[''version''];\r\n   }\r\n   if($version[''code_name''] != ""){\r\n      $MetaGenerator .= " (".$version[''code_name''].")";\r\n   }\r\n   $MetaGenerator .= "\\" />\\n";\r\n}\r\n\r\n$docInfo = $etomite->getDocument($etomite->documentIdentifier);\r\n\r\n// *** DESCRIPTION ***\r\n// Trim and replace double quotes with entity\r\n$description = $docInfo[''meta_description''];\r\n$description = str_replace(''"'', ''&#34;'', trim($description)); \r\nif(!$description == ""){\r\n   $MetaDesc = " <meta name=\\"description\\" content=\\"$description\\" />\\n";\r\n\r\n// *** DC.DESCRIPTION ***\r\n   if ($dc_tags) {\r\n      $DC_description = " <meta name=\\"DC.description\\"";\r\n      $DC_description .= " content=\\"$description\\" />\\n";\r\n   }\r\n}\r\n\r\n// *** KEYWORDS ***\r\n$keywords = $docInfo[''meta_keywords'']; //$etomite->getKeywords();\r\nif(count($keywords)>0) {\r\n   $keys = $keywords; //join($keywords, ", ");\r\n   $MetaKeys = " <meta name=\\"keywords\\" content=\\"$keys\\" />\\n";\r\n\r\n// *** DC.SUBJECT ***\r\n   if ($dc_tags) {\r\n      $keys = join($keywords, "; ");\r\n      $DC_subject = " <meta name=\\"DC.subject\\"";\r\n      $DC_subject .= " content=\\"$keys\\" />\\n";\r\n   }\r\n}\r\n\r\n// *** ABSTRACT ***\r\n// Use the Site Name and the documents Long Title (or Description)  \r\n// to build an Abstract meta tag.\r\n$sitename = $etomite->config[''site_name''];\r\n// Trim and replace double quotes with entity\r\n$sitename = str_replace(''"'', ''&#34;'', trim($sitename)); \r\n\r\n$abstract = trim($docInfo[''longtitle'']);\r\nif($abstract == ""){\r\n   $abstract = $description;\r\n}\r\n// Replace double quotes with entity\r\n$abstract = str_replace(''"'', ''&#34;'', $abstract); \r\n\r\nif(($sitename != "") || ($abstract != "")) {\r\n   $separator = " - ";\r\n   if($sitename == ""){\r\n      $separator = "";\r\n   }\r\n   $MetaAbstract = " <meta name=\\"abstract\\" content=\\"".$sitename.$separator.$abstract."\\" />\\n";\r\n\r\n// *** DC.TITLE.ALTERNATIVE ***\r\n   if ($dc_tags) {\r\n      $DC_title_alternative = " <meta name=\\"DC.title.alternative\\"";\r\n      $DC_title_alternative .= " content=\\"".$sitename.$separator.$abstract."\\" />\\n";\r\n   }\r\n}\r\n\r\n// *** TITLE ***\r\n// Use the Site Name and the documents Page Title and Long Title  \r\n// to build the Title meta tag.\r\n\r\n// Start with the site name\r\n/*$title = ''''; //$sitename;\r\n// Get the pagetitle, trim and replace double quotes with entity\r\n$pagetitle = str_replace(''"'', ''&#34;'', trim($docInfo[''meta_title''])); \r\nif ($pagetitle != "") {\r\n   if ($title == "") {\r\n      $title = $pagetitle;\r\n   } else {\r\n      $title .= " - ".$pagetitle;\r\n   }\r\n}\r\n// Get the longtitle, trim and replace double quotes with entity\r\n$longtitle = str_replace(''"'', ''&#34;'', trim($docInfo[''longtitle''])); \r\nif ($longtitle != "") {\r\n   if ($title == "") {\r\n      $title = $longtitle;\r\n   } else {\r\n      $title .= " - ".$longtitle;\r\n   }\r\n}\r\nif ($title != "") {\r\n   $MetaTitle = " <title>".$title."</title>\\n";\r\n\r\n// *** DC.TITLE ***\r\n   if ($dc_tags || $geo_tags) {\r\n      $DC_title = " <meta name=\\"DC.title\\"";\r\n      $DC_title .= " content=\\"".$title."\\" />\\n";\r\n   }\r\n}*/\r\n\r\n$MetaTitle = " <title>".$docInfo[''meta_title'']."</title>\\n";\r\n\r\n// *** AUTHOR ***\r\nif ($site_author == '''') {\r\n   $authorid = $docInfo[''createdby''];\r\n   $tbl = $etomite->dbConfig[''dbase''].".".$etomite->dbConfig[''table_prefix'']."user_attributes";\r\n   $query = "SELECT fullname FROM $tbl WHERE $tbl.id = $authorid"; \r\n   $rs = $etomite->dbQuery($query);\r\n   $limit = $etomite->recordCount($rs); \r\n   if($limit=1) {\r\n      $resourceauthor = $etomite->fetchRow($rs); \r\n      $authorname = $resourceauthor[''fullname''];  \r\n   }\r\n   // Trim and replace double quotes with entity\r\n   $authorname = str_replace(''"'', ''&#34;'', trim($authorname));\r\n} else {\r\n   $authorname = $site_author;\r\n}\r\nif (!$authorname == ""){\r\n   $MetaAuthor = " <meta name=\\"author\\" content=\\"$authorname\\" />\\n";\r\n\r\n// *** DC.PUBLISHER & DC.CREATOR ***\r\n   if ($dc_tags) {\r\n      $DC_publisher = " <meta name=\\"DC.publisher\\" content=\\"$authorname\\" />\\n";\r\n      $DC_creator = " <meta name=\\"DC.creator\\" content=\\"$authorname\\" />\\n";\r\n   }\r\n}\r\n\r\n// *** COPYRIGHT ***\r\n// get the Etomite server offset time in seconds\r\n$server_offset_time = $etomite->config[''server_offset_time''];\r\nif (!$server_offset_time) {\r\n   $server_offset_time = 0;\r\n}\r\n\r\n// get the current time and apply the offset\r\n$timestamp = time() + $server_offset_time;\r\n// Set the current year\r\n$today_year = date(''Y'',$timestamp);\r\n$createdon = date(''Y'',$docInfo[''createdon'']);\r\nif ($site_start_year == '''') {\r\n   if ($today_year != $createdon) {\r\n      $copydate = $createdon."&#8211;".$today_year;\r\n   } else {\r\n      $copydate = $today_year;\r\n   }\r\n} else {\r\n   if ($today_year != $site_start_year) {\r\n      $copydate = $site_start_year."&#8211;".$today_year;\r\n   } else {\r\n      $copydate = $today_year;\r\n   }\r\n}\r\nif ($authorname == '''') {\r\n   $copyname = $authorname;\r\n} else {\r\n   $copyname = " by ".$authorname;\r\n}\r\n$MetaCopyright = " <meta name=\\"copyright\\" content=\\"Copyright &#169; ";\r\n$MetaCopyright .= $copydate.$copyname.". All rights reserved.\\" />\\n";\r\n\r\n// *** DC.RIGHTS ***\r\nif ($dc_tags) {\r\n   $DC_rights = " <meta name=\\"DC.rights\\" content=\\"Copyright &#169; ";\r\n   $DC_rights .= $copydate.$copyname.". All rights reserved.\\" />\\n";\r\n}\r\n\r\n// *** ROBOTS and GOOGLEBOT ***\r\n// Determine if this document has been set to non-searchable.\r\n// As the default for the Robots and Googlebot Meta Tags are index and follow,\r\n// these tags are only needed when we don''t want the document searched. \r\nif(!$etomite->documentObject[''searchable'']){\r\n   $MetaRobots = " <meta name=\\"robots\\" content=\\"noindex, nofollow\\" />\\n";\r\n   $MetaGooglebot = " <meta name=\\"googlebot\\" content=\\"noindex, nofollow, noarchive, nosnippet\\" />\\n";\r\n}\r\n\r\n// *** CACHE-CONTROL and PRAGMA ***\r\n// Output no-cache directives via the Cache-Control and Pragma meta tags\r\n// if this document is set to non-cacheable. \r\n$cacheable = $docInfo[''cacheable''];\r\nif (!$cacheable) {\r\n   $MetaCache = " <meta http-equiv=\\"cache-control\\" content=\\"no-cache\\" />\\n";\r\n   $MetaPragma = " <meta http-equiv=\\"pragma\\" content=\\"no-cache\\" />\\n";\r\n}\r\n\r\n// *** DC.DATE.CREATED ***\r\nif ($dc_tags) {\r\n   $createdon = get_local_iso_8601_date($docInfo[''createdon''], $server_offset_time);\r\n   $created = substr($createdon,0,10);\r\n   $DC_date_created = " <meta name=\\"DC.date.created\\" content=\\"";\r\n   $DC_date_created .= $created."\\" />\\n";\r\n}\r\n\r\n// *** EXPIRES ***\r\n$unpub_date = $docInfo[''unpub_date''];\r\nif ($unpub_date > 0) {\r\n   $unpubdate = get_local_rfc_822_date($unpub_date, $server_offset_time);\r\n   $MetaExpires = " <meta http-equiv=\\"expires\\" content=\\"$unpubdate\\" />\\n";\r\n\r\n// *** DC.DATE.VALID ***\r\n   if ($dc_tags) {\r\n      $dcunpubdate = get_local_iso_8601_date($unpub_date, $server_offset_time);\r\n      $valid = substr($dcunpubdate,0,10);\r\n      $DC_date_valid = " <meta name=\\"DC.date.valid\\" content=\\"";\r\n      $DC_date_valid .= $created."/".$valid."\\" />\\n";\r\n   }\r\n}\r\n\r\n// *** LAST MODIFIED ***\r\n$editedon = get_local_rfc_822_date($docInfo[''editedon''], $server_offset_time);\r\n$MetaEditedOn = " <meta http-equiv=\\"last-modified\\" content=\\"$editedon\\" />\\n";\r\n\r\n// *** DC.DATE.MODIFIED ***\r\nif ($dc_tags) {\r\n   $dceditedon = get_local_iso_8601_date($docInfo[''editedon''], $server_offset_time);\r\n   $modified = substr($dceditedon,0,10);\r\n   $DC_date_modified = " <meta name=\\"DC.date.modified\\" content=\\"";\r\n   $DC_date_modified .= $modified."\\" />\\n";\r\n}\r\n\r\n// *** DISTRIBUTION ***\r\nif (!$distribution == '''') {\r\n   $MetaDistribution = " <meta name=\\"distribution\\" content=\\"".$distribution."\\" />\\n";\r\n}\r\n\r\n// *** RATING ***\r\nif (!$rating == '''') {\r\n   $MetaRating = " <meta name=\\"rating\\" content=\\"".$rating."\\" />\\n";\r\n}\r\n\r\nif ($dc_tags) {\r\n\r\n// *** DC.IDENTIFIER ***\r\n   $url = $websiteurl."[~".$etomite->documentIdentifier."~]";\r\n   $DC_identifier = " <meta name=\\"DC.identifier\\" content=\\"".$url."\\" />\\n";\r\n}\r\n\r\n\r\nif ($geo_tags) {\r\n   if (($latitude != "") && (longitude != "")) {\r\n\r\n// *** GEO.ICBM ***\r\n      $Geo_icbm = " <meta name=\\"ICBM\\"";\r\n      $Geo_icbm .= " content=\\"".$latitude.", ".$longitude."\\" />\\n";\r\n\r\n// *** GEO.POSITION ***\r\n      $Geo_position = " <meta name=\\"geo.position\\"";\r\n      $Geo_position .= " content=\\"".$latitude.";".$longitude."\\" />\\n";\r\n   }\r\n\r\n   if ($region != "") {\r\n\r\n// *** GEO.REGION ***\r\n      $Geo_region = " <meta name=\\"geo.region\\"";\r\n      $Geo_region .= " content=\\"".$region."\\" />\\n";\r\n   }\r\n\r\n   if ($placename != "") {\r\n\r\n// *** GEO.PLACENAME ***\r\n      $Geo_placename = " <meta name=\\"geo.placename\\"";\r\n      $Geo_placename .= " content=\\"".$placename."\\" />\\n";\r\n   }\r\n\r\n}\r\n\r\n\r\n// *** RETURN RESULTS ***\r\n\r\n\r\n$output = $MetaTitle.$MetaDesc.$MetaKeys;\r\n$output .= $MetaType.$MetaLanguage.$MetaGenerator;\r\n$output .= $MetaAbstract.$MetaAuthor.$MetaCopyright;\r\n$output .= $MetaRobots.$MetaGooglebot;\r\n$output .= $MetaCache.$MetaPragma.$MetaExpires.$MetaEditedOn;\r\n$output .= $MetaDistribution.$MetaRating;\r\n\r\nif ($dc_tags) {\r\n  $dc_output = $DC_format.$DC_language.$DC_title;\r\n  $dc_output .= $DC_description.$DC_subject.$DC_title_alternative;\r\n  $dc_output .= $DC_publisher.$DC_creator.$DC_rights;\r\n  $dc_output .= $DC_date_created.$DC_date_modified.$DC_date_valid;\r\n  $dc_output .= $DC_identifier;\r\n  if ($dc_output != "") {\r\n    $output .= " \\n".$dc_output;\r\n  }\r\n}\r\nif ($geo_tags) {\r\n  $geo_output = "";\r\n  if (!$dc_tags) {\r\n    $geo_output .= $DC_title;\r\n  }\r\n  $geo_output .= $Geo_icbm;\r\n  $geo_output .= $Geo_position.$Geo_region.$Geo_placename;\r\n  if ($geo_output != "") {\r\n    $output .= " \\n".$geo_output;\r\n  }\r\n}\r\n\r\nreturn $output;', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (6, 'GoogleSiteMap_XML', 'Output a Google XML site map', '/**\r\n * GoogleSiteMap_XML Snippet for Etomite CMS\r\n * Version 0.8 2006-11-17\r\n *\r\n * Parameters:\r\n * [!GoogleSiteMap_XML?validate=true!] or [!GoogleSiteMap_XML?validate=1!]\r\n * tells the snippet to output the additional headers required to validate \r\n * your Sitemap file against a schema.\r\n *\r\n * Useage:\r\n * Create a snippet: GoogleSiteMap_XML\r\n * with the content of this file.\r\n * Update the configuration options below to suit your needs.\r\n * Create a template: GoogleSiteMap_Template \r\n * with the content "[!GoogleSiteMap_XML!]".\r\n * Create a page in your repository: Google Site Map\r\n * with no content, the alias "google-sitemap",\r\n * using the GoogleSiteMap_Template, not searchable,\r\n * not cacheable, with content type "text/xml".\r\n *\r\n * Goto the Google Webaster Tools site at https://www.google.com/webmasters/tools/\r\n * Create an account, or login using your existing account.\r\n * Enter http://www.<your domain name>/ in the add site box and click OK.\r\n * Click on "Verify your site".\r\n * Choose "Add a META tag" as your verification option.\r\n * Add the generated meta tag to the head section of your home page template.\r\n * Back in Google Webmaster Tools, click on "Verify".\r\n * Click on the "Sitemaps" button.\r\n * Click on "Add a Sitemap".\r\n * Select "Add General Web Sitemap".\r\n * Enter "http://www.<your domain name>/google-sitemap.htm" as your sitemap URL.\r\n * Click on "Add Web Sitemap".\r\n *\r\n * \r\n * Ryan Nutt - http://blog.nutt.net\r\n * v0.1 - June 4, 2005\r\n * v0.2 - June 5, 2005 - Fixed a stupid mistake :-)\r\n * \r\n * Changes by Lloyd Borrett - http://www.borrett.id.au\r\n *\r\n * v0.3 - Sep 22, 2005\r\n * Only list searchable pages (Mod suggested by mplx)\r\n * Added configuration settings.\r\n * Made the site URL a configuration option.\r\n * Made displaying lastmoddate, priority and/or changefreq optional.\r\n * Added ability to display long date & time for lastmoddate\r\n * Made the long or short timeformat optional.\r\n * \r\n * v0.4 - 05-Feb-2006\r\n * Changed the snippet to output the local time for all date values\r\n * based on the Etomite server offset time\r\n * \r\n * v0.5 - 15-Feb-2006\r\n * Fixed incorrect local GMT offset value\r\n * \r\n * v0.6 - 7-Apr-2006\r\n * Get the base URL from Etomite instead of it being a configuration option.\r\n * \r\n * v0.7 - 30-Apr-2006\r\n * Get the base URL from Etomite using the new available \r\n * method built in to Etomite 0.6.1 Final. If using an earlier\r\n * version of Etomite, you''ll still need to provide the URL\r\n * as a configuration option.\r\n * \r\n * v0.8 - 17-Nov-2006\r\n * Updated to identify itself as using the Sitemap 0.9 protocol.\r\n * Added ability to force the change frequency to a set value for all documents.\r\n * Added ability to output the additional headers required to validate the sitemap format.\r\n * Additional comments added.\r\n * Code layout made consistent.\r\n * \r\n * Based on the ListSiteMap snippet by\r\n * JaredDC\r\n * \r\n * datediff function from\r\n * www.ilovejackdaniels.com\r\n */ \r\n\r\n// Overcome single use limitation on functions\r\nglobal $MakeMapDefined;\r\n\r\n// Get the validate parameter, if any\r\n$validateschema = false;\r\nif (isset($validate)) {\r\n   if (($validate == "1") || ($validate == "true")) {\r\n       $validateschema = true;\r\n   }\r\n}\r\n\r\n// Determine values required to convert the lastmod date and\r\n// time to local time. \r\n// get the Etomite server offset time in seconds\r\nglobal $server_offset_time;\r\nglobal $GMT_value;\r\n$server_offset_time = $etomite->config[''server_offset_time''];\r\nif (!$server_offset_time) {\r\n    $server_offset_time = 0;\r\n} \r\n\r\n// Get the server GMT offset in seconds\r\n$GMT_offset = date("O");\r\n$GMT_hr = substr($GMT_offset, 1, 2);\r\n$GMT_min = substr($GMT_offset, 4, 2);\r\n$GMT_sign = substr($GMT_offset, 0, 1);\r\n$GMT_secs = (intval($GMT_hr) * 3600) + (intval($GMT_min) * 60);\r\nif ($GMT_sign == ''-'') {\r\n    $GMT_secs = $GMT_secs * (-1);\r\n} \r\n\r\n// Get the local GMT offset in seconds\r\n$GMT_local_seconds = $GMT_secs + $server_offset_time;\r\n$GMT_local_secs = abs($GMT_local_seconds); \r\n// round down to the number of hours\r\n$GMT_local_hours = intval($GMT_local_secs / 3600); \r\n// round down to the number of minutes\r\n$GMT_local_minutes = intval(($GMT_local_secs - ($GMT_local_hours * 3600)) / 60);\r\nif ($GMT_local_seconds < 0) {\r\n    $GMT_value = "-";\r\n} else {\r\n    $GMT_value = "+";\r\n} \r\n$GMT_value .= sprintf("%02d:%02d", $GMT_local_hours, $GMT_local_minutes);\r\n\r\nif (!function_exists(datediff)) {\r\n    function datediff($interval, $datefrom, $dateto, $using_timestamps = false)\r\n    {\r\n        /**\r\n         * $interval can be:\r\n         * yyyy - Number of full years\r\n         * q - Number of full quarters\r\n         * m - Number of full months\r\n         * y - Difference between day numbers\r\n         * (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)\r\n         * d - Number of full days\r\n         * w - Number of full weekdays\r\n         * ww - Number of full weeks\r\n         * h - Number of full hours\r\n         * n - Number of full minutes\r\n         * s - Number of full seconds (default)\r\n         */\r\n\r\n        if (!$using_timestamps) {\r\n            $datefrom = strtotime($datefrom, 0);\r\n            $dateto = strtotime($dateto, 0);\r\n        } \r\n\r\n        $difference = $dateto - $datefrom; // Difference in seconds\r\n        \r\n        switch ($interval) {\r\n            case ''yyyy'': // Number of full years\r\n                $years_difference = floor($difference / 31536000);\r\n                if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom) + $years_difference) > $dateto) {\r\n                    $years_difference--;\r\n                } \r\n                if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto) - ($years_difference + 1)) > $datefrom) {\r\n                    $years_difference++;\r\n                } \r\n                $datediff = $years_difference;\r\n                break;\r\n\r\n            case "q": // Number of full quarters\r\n                $quarters_difference = floor($difference / 8035200);\r\n                while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom) + ($quarters_difference * 3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {\r\n                    $months_difference++;\r\n                } \r\n                $quarters_difference--;\r\n                $datediff = $quarters_difference;\r\n                break;\r\n\r\n            case "m": // Number of full months\r\n                $months_difference = floor($difference / 2678400);\r\n                while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom) + ($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {\r\n                    $months_difference++;\r\n                } \r\n                $months_difference--;\r\n                $datediff = $months_difference;\r\n                break;\r\n\r\n            case ''y'': // Difference between day numbers\r\n                $datediff = date("z", $dateto) - date("z", $datefrom);\r\n                break;\r\n\r\n            case "d": // Number of full days\r\n                $datediff = floor($difference / 86400);\r\n                break;\r\n\r\n            case "w": // Number of full weekdays\r\n                $days_difference = floor($difference / 86400);\r\n                $weeks_difference = floor($days_difference / 7); // Complete weeks\r\n                $first_day = date("w", $datefrom);\r\n                $days_remainder = floor($days_difference % 7);\r\n                $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?\r\n                if ($odd_days > 7) { // Sunday\r\n                    $days_remainder--;\r\n                } \r\n                if ($odd_days > 6) { // Saturday\r\n                    $days_remainder--;\r\n                } \r\n                $datediff = ($weeks_difference * 5) + $days_remainder;\r\n                break;\r\n\r\n            case "ww": // Number of full weeks\r\n                $datediff = floor($difference / 604800);\r\n                break;\r\n\r\n            case "h": // Number of full hours\r\n                $datediff = floor($difference / 3600);\r\n                break;\r\n\r\n            case "n": // Number of full minutes\r\n                $datediff = floor($difference / 60);\r\n                break;\r\n\r\n            default: // Number of full seconds (default)\r\n                $datediff = $difference;\r\n                break;\r\n        } \r\n\r\n        return $datediff;\r\n    } \r\n} \r\n\r\nif (!isset($MakeMapDefined)) {\r\n    function MakeMap($funcEtomite, $listParent)\r\n    {\r\n        global $server_offset_time;\r\n        global $GMT_value;\r\n\r\n        // ***********************************\r\n        // Configuration Settings \r\n        // ***********************************\r\n\r\n        // $websiteURL [string]\r\n        // Provide the full base path URL of your web site,\r\n        // or let Etomite get it (v0.6.1 Final).\r\n        // For example: http://www.yourdomain.com/\r\n        // NOTE: You must put a / on the end of the web site URL.\r\n        //\r\n        // Original hard coded way to specify $websiteURL\r\n        // $websiteurl = ''http://www.<your domain name>/''; \r\n        //\r\n        // Early Etomite way to get $websiteURL automatically\r\n        // $websiteurl = $etomite->config[''www_base_path''];\r\n        //\r\n        // Etomite 0.6.1 Final way to get $websiteURL automatically\r\n        global $ETOMITE_PAGE_BASE;\r\n        $websiteurl = $ETOMITE_PAGE_BASE[''www''];\r\n\r\n        // $showlastmoddate [true | false]\r\n        // You can choose to disable providing the last modification\r\n        // date, or get it from the documents.\r\n        // true  - Get time from documents\r\n        // false - Disabled, do not write it\r\n        $showlastmoddate = true; \r\n\r\n        // $showlongtimeformat [ true | false ]\r\n        // You can choose to provide the time format as:\r\n        // true  - Long time format (with time, e.g. 2006-09-29T13:43:51+11:00)\r\n        // false - Short time format (date only, e.g. 2006-11-17)\r\n        $showlongtimeformat = true; \r\n\r\n        // $showpriority [ true | false ]\r\n        // You can choose to disable prividing the priority\r\n        // of a document relative to the whole set of documents,\r\n        // or calculate it based on the date difference.\r\n        // true  - Provide the priority\r\n        // false - Disabled, do not write it\r\n        $showpriority = true; \r\n\r\n        // $showchangefreq [true | false]\r\n        // You can choose to disable prividing the update\r\n        // (change) frequency of a document relative to the\r\n        // whole set of documents, or calculate it based on\r\n        // the date difference.\r\n        // true  - Provide the change frequency\r\n        // false - Disabled, do not write it\r\n        $showchangefreq = true;\r\n\r\n        // $forcechangefreq [string]\r\n        // You can choose to force the change frequency for all \r\n        // documents to one of the valid values.\r\n        // By specifying nothing, the snippet will calculate the \r\n        // change frequency of a document relative to the\r\n        // whole set of documents, or calculate it based on\r\n        // the date difference.\r\n        // "always", "hourly", "daily", "weekly", "monthly",\r\n        // "yearly", "never" - Force this value for every document\r\n        // "" - Calculate change frequency from last mod date\r\n        $forcechangefreq = "";\r\n\r\n        // ***********************************\r\n        // END CONFIG SETTINGS\r\n        // THE REST SHOULD TAKE CARE OF ITSELF\r\n        // ***********************************\r\n\r\n        $children = $funcEtomite->getActiveChildren($listParent, "menuindex", "ASC", "id, editedon, searchable");\r\n        $cats = getCatNavNew();\r\n        $catsArray = array();\r\n        for($i=0;$i<count($cats);$i++){\r\n            $catsArray[$i][''id''] = null;\r\n          $catsArray[$i][''editedon''] = time();\r\n          $catsArray[$i][''searchable''] = 1;\r\n         $catsArray[$i][''furl''] = $cats[$i][''furl''];\r\n         $catsArray[$i][''cat''] = true;\r\n         array_push($children,$catsArray[$i]);\r\n        }\r\n        \r\n        //$children += $catsArray;\r\n        \r\n        foreach($children as $child) {\r\n            $id = $child[''id''];\r\n            if($child[''cat'']==true){\r\n               $furl = $child[''furl''];\r\n               $url = $funcEtomite->makeUrl('''',''listings'',array(''category''=>$furl));\r\n            }else{\r\n               if($id==1){\r\n                 $url = $websiteurl;\r\n             }else{\r\n                  $url = $websiteurl . "[~" . $id . "~]";\r\n             }\r\n            }\r\n\r\n            $date = $child[''editedon''];\r\n            $lastmoddate = $date;\r\n            $date = date("Y-m-d", $date);\r\n\r\n            $searchable = $child[''searchable''];\r\n            if ($searchable) {\r\n                // Get the date difference\r\n                $datediff = datediff("d", $date, date("Y-m-d"));\r\n                if ($datediff <= 1) {\r\n                    $priority = "1.0";\r\n                    $update = "daily";\r\n                } elseif (($datediff > 1) && ($datediff <= 7)) {\r\n                    $priority = "0.75";\r\n                    $update = "weekly";\r\n                } elseif (($datediff > 7) && ($datediff <= 30)) {\r\n                    $priority = "0.50";\r\n                    $update = "weekly";\r\n                } else {\r\n                    $priority = "0.25";\r\n                    $update = "monthly";\r\n                } \r\n\r\n                $output .= "<url>\\n";\r\n\r\n                $output .= "<loc>$url</loc>\\n";\r\n\r\n                if ($showlastmoddate) {\r\n                    if (!$showlongtimeformat) {\r\n                        $lastmoddate = date("Y-m-d", $lastmoddate + $server_offset_time);\r\n                    } else {\r\n                        $lastmoddate = date("Y-m-d\\TH:i:s", $lastmoddate + $server_offset_time) . $GMT_value;\r\n                    } \r\n                    $output .= "<lastmod>$lastmoddate</lastmod>\\n";\r\n                } \r\n\r\n                if ($showchangefreq) {\r\n                    if ($forcechangefreq == "") {\r\n                        $output .= "<changefreq>$update</changefreq>\\n";\r\n                    } else {\r\n                        $output .= "<changefreq>$forcechangefreq</changefreq>\\n";\r\n                    }\r\n                } \r\n\r\n                if ($showpriority) {\r\n                    $output .= "<priority>$priority</priority>\\n";\r\n                } \r\n\r\n                $output .= "</url>\\n";\r\n            } \r\n\r\n     if(!isset($child[''cat'']) && $child[''cat'']!=true){\r\n               if ($funcEtomite->getActiveChildren($child[''id''])) {\r\n                  $output .= MakeMap($funcEtomite, $child[''id'']);\r\n               } \r\n          }\r\n        } \r\n        return $output;\r\n    } \r\n    $MakeMapDefined = true;\r\n} \r\n\r\n$out = "<?xml version=\\"1.0\\" encoding=\\"UTF-8\\"?>\\n";\r\nif ($validateschema) {\r\n    $out .= "<urlset xmlns:xsi=\\"http://www.w3.org/2001/XMLSchema-instance\\"\\n";\r\n    $out .= "         xsi:schemaLocation=\\"http://www.sitemaps.org/schemas/sitemaps/0.9\\n";\r\n    $out .= "         http://www.sitemaps.org/schemas/sitemaps/sitemap.xsd\\"\\n";\r\n    $out .= "         xmlns=\\"http://www.sitemaps.org/schemas/sitemap/0.9\\">\\n";\r\n} else {\r\n    // $out .= "<urlset xmlns=\\"http://www.google.com/schemas/sitemap/0.84\\">\\n";\r\n    $out .= "<urlset xmlns=\\"http://www.sitemaps.org/schemas/sitemap/0.9\\">\\n";\r\n}\r\n\r\n// Produce the sitemap for the main web site\r\n$out .= MakeMap($etomite, 0);\r\n\r\n// To also list documents in unpublished repository folders,\r\n// place an additional call to MakeMap here for each one, e.g. \r\n// $out .= MakeMap($etomite, 8);\r\n// where 8 is the document id of the unpublished repository folder.\r\n\r\n$out .= "</urlset>";\r\n\r\nreturn $out;\r\n\r\nfunction getCatNavNew($parent=1,$w=array()){\r\n   // get categories that are children of the parent\r\n   global $etomite;\r\n    $result = $etomite->getIntTableRows($fields="*",$from="listings_category",$where="parent=".$parent,''sort_order'',''ASC'');\r\n\r\n return $result;\r\n}', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (7, 'SearchForm', 'All-in-one snippet to search the site', '// Snippet name: SearchForm\r\n// Snippet description: All-in-one snippet to search the site\r\n// Revision: 1.1 ships with Etomite 0.6.1-Final\r\n\r\n$searchString = \r\nisset($_POST[''search'']) && \r\n$_POST[''search'']!= "{{" && \r\n$_POST[''search'']!= "[[" && \r\n$_POST[''search'']!= "[(" && \r\n$_POST[''search'']!= "[~" && \r\n$_POST[''search'']!= "[*" ?\r\n$_POST[''search''] : "" ;\r\n\r\n\r\n$SearchForm .= ''<form name="SearchForm" action="" method="post">''."\\n"; \r\n$SearchForm .= ''<input type="text" name="search" class="text" value="''.$searchString.''" /><br />''."\\n"; \r\n$SearchForm .= ''<input type="submit" name="sub" class="button" value="Search" />''."\\n"; \r\n$SearchForm .= ''</form>''; \r\n\r\nif(isset($_POST[''search'']) && $_POST[''search'']!='''') { \r\n   $search = explode(" ", $_POST[''search'']); \r\n   $tbl = $etomite->dbConfig[''dbase''].".".$etomite->dbConfig[''table_prefix'']."site_content";\r\n   $sql = "SELECT id, pagetitle, parent, description FROM $tbl WHERE ($tbl.content LIKE ''%".$search[0]."%''"; \r\n   for ($x=1;$x < count($search); $x++) { \r\n       $sql .= " AND $tbl.content like ''%$search[$x]%''"; \r\n   } \r\n   $sql .= " OR $tbl.pagetitle LIKE ''%".$search[0]."%'' "; \r\n   for ($x=1;$x < count($search); $x++) { \r\n       $sql .= " AND $tbl.pagetitle like ''%$search[$x]%''"; \r\n   } \r\n   $sql .= " OR $tbl.description LIKE ''%".$search[0]."%'' "; \r\n   for ($x=1;$x < count($search); $x++) { \r\n       $sql .= " AND $tbl.description like ''%$search[$x]%''"; \r\n   } \r\n   $sql .= ") AND $tbl.published = 1 AND $tbl.searchable=1 AND $tbl.deleted=0;"; \r\n   $rs = $etomite->dbQuery($sql); \r\n   $limit = $etomite->recordCount($rs); \r\n   if($limit>0) { \r\n      $SearchForm .= "<p>The following results were found:</p>\\n";\r\n      $SearchForm .= "<table cellspacing=\\"0\\" cellpadding=\\"0\\">\\n"; \r\n      for ($y = 0; $y < $limit; $y++) { \r\n         $SearchFormsrc=$etomite->fetchRow($rs); \r\n         $SearchForm.="<tr><td style=\\"padding: 1px\\"><a href=\\"[~".$SearchFormsrc[''id'']."~]\\"><b>".$SearchFormsrc[''pagetitle'']."</b></a></td>\\n";\r\n         $SearchForm.="<td style=\\"padding: 1px\\">"; \r\n         $SearchForm.=$SearchFormsrc[''description'']!='''' ? " - <small>".$SearchFormsrc[''description'']."</small>" : "" ; \r\n         $SearchForm .= "</td></tr>";\r\n      } \r\n      $SearchForm .= "</table>";\r\n   } else { \r\n      $SearchForm.="<p>Sorry, couldn''t find anything!</p>"; \r\n   } \r\n} \r\n\r\nreturn $SearchForm;', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (9, 'SiteUpdate', 'Returns date of most recent document update', '// Snippet name: SiteUpdate\r\n// Snippet description: Returns date of most recent published document update\r\n// Revision: 1.2 ships with Etomite Prelude v1.0\r\n\r\n// Author: Ralph A. Dahlgren -- 2005-07-13\r\n// Usage: [!SiteUpdate?dateFormat=%B %e, %Y!] Returns date formatted: July 13, 2005 \r\n// See strftime() documentation for additional formatting options\r\n\r\n// Changes:\r\n//   v1.1 by Lloyd Borrett -- 2006-04-07\r\n//     Return local time based on Etomite server offset time\r\n//   v1.2 by Ralph A. Dahlgren -- 2008-04-12\r\n//     Ignore unpublished and deleted documents\r\n//     Use Date and Time formats from configuration\r\n\r\n// was $dateFormat sent in snippet call?\r\nif(isset($dateFormat))\r\n{\r\n  // use $dateFormat sent in snippet call\r\n  $format = $dateFormat;\r\n}\r\nelse\r\n{\r\n  // use default Date & Time formats from configuration\r\n  $format = $etomite->config[''date_format'']." ".$etomite->config[''time_format''];\r\n}\r\n\r\n// get the Etomite server offset time in seconds\r\n$server_offset_time = $etomite->config[''server_offset_time''];\r\n// if no server offset time was found, use zero\r\nif(!$server_offset_time)\r\n{\r\n  $server_offset_time = 0;\r\n}\r\n\r\n// define our database query\r\n$sql = <<<QUERY\r\n  SELECT editedon \r\n  FROM {$etomite->db}site_content \r\n  WHERE published=1\r\n  AND deleted=0\r\n  ORDER BY editedon DESC\r\nQUERY;\r\n\r\n// perform the database query\r\n$rs = $etomite->dbQuery($sql);\r\n\r\n// check to see if results were returned\r\nif($etomite->recordCount($rs) > 0)\r\n{\r\n  // fetch the first data row (last edited)\r\n  $row = $etomite->fetchRow($rs);\r\n  // add server offset to timestamp\r\n  $update = strftime($format,$row[''editedon''] + $server_offset_time);\r\n}\r\nelse\r\n{\r\n  // no results returned so set to null\r\n  $update = null;\r\n}\r\n\r\n// return formatted timestamp to caller\r\nreturn $update;', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (10, 'SearchPrompt', 'Search prompt snippet for use with SearchResults', '// Snippet name: SearchPrompt\r\n// Snippet description: Search prompt snippet for use with SearchResults\r\n// Revision: 1.00 ships with Etomite 0.6.1-Final\r\n// Use: [!SearchPrompt?resultsid=###!] snippet call where ### is the\r\n//   document id of the page which contains the [!SearchResults!] snippet call\r\n\r\n$resultsDefault = "14";  // Document id to use if $resultsid not sent\r\n$resultsid = isset($resultsid) ? $resultsid : $resultsDefault;\r\n\r\n$prompt = "Search this site";  // Search box label text\r\n$submit = "Search";  // Submit button label\r\n\r\n$output = \r\n<<<END\r\n<form id="SearchForm" action="[~{$resultsid}~]" method="post"> \r\n  <div class="searchbox" style="text-align:center;">\r\n    <p>{$prompt}</p>\r\n    <p><input type="text" name="search" value="" /></p>\r\n    <p><input type="submit" name="sub" class="button" value="{$submit}" /></p>\r\n  </div>\r\n</form>\r\nEND;\r\n\r\nreturn $output;', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (11, 'SearchResults', 'Displays results of SearchPrompt snippet', '//  SearchResults\r\n//  Displays results of SearchPrompt snippet\r\n\r\n$noResults = "<p>No search results were found.</p>";\r\n$resultsText = "<p>The following results were found:</p>";\r\n$searchString = \r\nisset($_POST[''search'']) && \r\n$_POST[''search'']!= "{{" && \r\n$_POST[''search'']!= "[[" && \r\n$_POST[''search'']!= "[(" && \r\n$_POST[''search'']!= "[~" && \r\n$_POST[''search'']!= "[*" ?\r\n$_POST[''search''] : "" ;\r\n\r\nif(isset($_POST[''search'']) && $_POST[''search'']!='''') { \r\n   $search = explode(" ", $_POST[''search'']); \r\n   $sql = "SELECT id, pagetitle, parent, description FROM ".$etomite->db.site_content." WHERE (content LIKE ''%".$search[0]."%''"; \r\n   for ($x=1;$x < count($search); $x++) { \r\n       $sql .= " AND content like ''%$search[$x]%''"; \r\n   } \r\n   $sql .= " OR pagetitle LIKE ''%".$search[0]."%'' "; \r\n   for ($x=1;$x < count($search); $x++) { \r\n       $sql .= " AND pagetitle like ''%$search[$x]%''"; \r\n   } \r\n   $sql .= " OR description LIKE ''%".$search[0]."%'' "; \r\n   for ($x=1;$x < count($search); $x++) { \r\n       $sql .= " AND description like ''%$search[$x]%''"; \r\n   } \r\n   $sql .= ") AND published = 1 AND searchable=1 AND deleted=0;"; \r\n   $rs = $etomite->dbQuery($sql); \r\n   $limit = $etomite->recordCount($rs); \r\n   if($limit>0) { \r\n      $SearchForm .= $resultsText."<p><table cellspacing=''0'' cellpadding=''0''>"; \r\n      for ($y = 0; $y < $limit; $y++) { \r\n         $SearchFormsrc=$etomite->fetchRow($rs); \r\n         $SearchForm.="<tr><td style=''padding: 1px''><a href=''[~".$SearchFormsrc[''id'']."~]''><b>".$SearchFormsrc[''pagetitle'']."</b></a></td><td style=''padding: 1px''>"; \r\n         $SearchForm.=$SearchFormsrc[''description'']!='''' ? " - <small>".$SearchFormsrc[''description'']."</small>" : "" ; \r\n         $SearchForm .= "</td></tr>";\r\n      } \r\n      $SearchForm .= "</table>";\r\n   } else { \r\n      $SearchForm .= $noResults; \r\n   } \r\n} \r\n\r\nreturn $SearchForm;', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (14, 'GetDocContent', 'Returns additional document content for inclusion in a page', '# Snippet:  GetDocContent -- Etomite Prelude v1.0\r\n# Author:   Ralph A. Dahlgren\r\n# Created:  2005-04-17\r\n# Modified: 2008-04-17\r\n# Purpose:  Returns additional document content for inclusion in a page\r\n# Usage: [[GetDocContent?id=nn]] where nn = id of the document being requested\r\n\r\n// if a document id was sent, fetch the document content\r\nif(isset($id))\r\n{\r\n  // we only want the content column\r\n  $fields = "content";\r\n  // query the database for our record\r\n  $doc = $etomite->getDocument($id, $fields);\r\n  // if our record was found, return the content\r\n  if($doc)\r\n  {\r\n    return $doc[''content''];\r\n  }\r\n}\r\n\r\n// if all else fails, return empty\r\nreturn;\r\n', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (32, 'membership_validator', 'Membership Validator', '//Snippet: membership_validator\r\n\r\n//Author: Cris D.\r\n\r\n//Date: 2007/12/27\r\n\r\n//Use: allows users to create their own (validated) memberships to your site.\r\n\r\n//It creates a temp row and sends an email with validation message and link.\r\n\r\n\r\n\r\n//Set the role id of the member (as per the id of etomite_user_roles table).\r\n\r\n//recommendation: NOT ''1'' as it will allow all users access to all areas of your site manager!\r\n\r\n$memberRole=isset($memberRole)? $memberRole: 2 ;\r\n\r\n\r\n\r\n//tempMemberExpires is how long the temp row will stay before deleting if not validated.\r\n\r\n$tempExpires= time() - (86400);//3600 seconds from now (1 hour) 86400 seconds from now (1 day)\r\n\r\n$tempMemberExpires= date(''Y-m-d H:i:s'',$tempExpires);\r\n\r\n\r\n\r\n\r\n\r\n//set to the id of the page that the email link will point to\r\n\r\n//this will contain the snippet membership_validator\r\n\r\n\r\n\r\n$confirmationPage=isset($confirmationPage) ? $confirmationPage:42;\r\n\r\n\r\n\r\n//set the name of the table to store the temporary member details while waiting for verification.\r\n\r\n$tableName=''member_validation'';\r\n\r\n\r\n\r\n//set to the email address that will be in the "From:" section of the verification email.\r\n\r\n$webmasterEmail="john@mnww.com";\r\n\r\n\r\n\r\n//$email_switch for deactivation of emails out and testing.\r\n\r\n$email_switch=1;//1=on, 0=off.\r\n\r\n\r\n\r\n//these can be uncommented to clear the session variables for testing.\r\n\r\n//you can only create one test account in a session!\r\n\r\n\r\n\r\nunset($_SESSION[''userName'']);\r\n\r\nunset($_SESSION[''firstName'']);\r\n\r\nunset($_SESSION[''lastName'']);\r\n\r\nunset($_SESSION[''email'']);\r\n\r\nunset($_SESSION[''hash'']);\r\n\r\n\r\n\r\n$messages=array(\r\n\r\n''table_created'' =>"Table <b>$table</b> created!  This module is ready to go, please refresh this page.",\r\n\r\n''error1'' => "Unable to delete temporary records. SQL failed. Registration can still continue.",\r\n\r\n''error2'' => "The username <b>".$_POST[''mv_userName'']."</b> is already taken, please choose another.<br />",\r\n\r\n''error3'' => "The email <b>".$_POST[''mv_email'']."</b> is already being used, please use another address.<br />",\r\n\r\n''error_pword_match'' => "The passwords you have given do not match, please try again.<br />",\r\n\r\n''error_pword_short'' => " Password is too short, it must be at least 6 digits!<br />",\r\n\r\n''error_phone'' => " The phone numbers must contain only numbers.<br />",\r\n\r\n''error_blank_fields'' => " Please fill in all fields.<br />",\r\n\r\n''error_email'' =>  "This email address does not appear to be valid.<br />",\r\n\r\n''intro'' => "<p>Registration for membership is a 3 Step process.<br />\\n",\r\n\r\n''step1'' => "<p><b>Step 1</b> | Step 2 | Step 3 <br />",\r\n\r\n''step2'' => "<p>Step 1 | <b>Step 2</b> | Step 3 <br />",\r\n\r\n''personal_details'' => "Please enter your personal details. ALL FIELDS ARE REQUIRED!<br />",\r\n\r\n''email_subject'' => " ( Confirmation of Registration )",\r\n\r\n''email_body1'' => "  If you did not request membership from our site, please delete this email.",\r\n\r\n''email_body2'' => "  Log in details: Username: ",\r\n\r\n''email_body3'' => "  Password: ",\r\n\r\n''email_body4'' => "  Follow the link to complete your registration: ",\r\n\r\n''check_email'' => "Please check your email inbox at <b>".$_POST[''mv_email'']."</b> \r\n\r\n                   and click the link to complete your registration.</p>\\n",\r\n\r\n''register_button'' => "Register Now" );\r\n\r\n\r\n\r\n#########  Snippet Starts here  ############\r\n\r\n\r\n\r\n//Snippet variables\r\n\r\n$host = $GLOBALS[''database_server''];  //localhost\r\n\r\n$user = $GLOBALS[''database_user'']; //root\r\n\r\n$pass = $GLOBALS[''database_password''];  //password\r\n\r\n$dbase= trim($GLOBALS[''dbase''],''`'');  //database\r\n\r\n$etoPrefix=$GLOBALS[''table_prefix''];    //etomite_\r\n\r\n$table=$etoPrefix.$tableName;           //etomite_tableName\r\n\r\n\r\n\r\n//check if temp table exists, otherwise create it\r\n\r\n$tableCheck=$etomite->extTableExists($host, $user, $pass, $dbase, $table);\r\n\r\n\r\n\r\nif($tableCheck!=1){\r\n\r\n//table does not exist, create it\r\n\r\n$sql .= ''CREATE TABLE `''.$table.''` (''\r\n\r\n        . '' `id` INT(10) NOT NULL AUTO_INCREMENT, ''\r\n\r\n        . '' `firstName` VARCHAR(100) NOT NULL, ''\r\n\r\n        . '' `lastName` VARCHAR(100), ''\r\n\r\n        . '' `userName` VARCHAR(100), ''\r\n\r\n        . '' `email` VARCHAR(100), ''\r\n\r\n        . '' `phone` VARCHAR(100), ''\r\n\r\n        . '' `mobilephone` VARCHAR(100), ''\r\n\r\n        . '' `hash` VARCHAR(100), ''\r\n        \r\n        . '' `address` VARCHAR(100), ''\r\n        \r\n        . '' `city` VARCHAR(100), ''\r\n        \r\n        . '' `state` VARCHAR(100), ''\r\n        \r\n        . '' `zip` VARCHAR(100), ''\r\n\r\n        . '' `password` VARCHAR(100), ''\r\n\r\n        . '' `role` INT(10), ''\r\n\r\n        . '' `tstmp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,''\r\n\r\n        . '' PRIMARY KEY (`id`)''\r\n\r\n        . '' )''\r\n\r\n        . '' TYPE = myisam'';\r\n\r\n$etomite->dbQuery($sql);\r\n\r\n\r\n\r\nreturn $messages[''table_created''];\r\n\r\n}\r\n\r\n\r\n\r\n//delete old unverified temporary records\r\n\r\n$sql = "DELETE FROM $dbase.".$etoPrefix."$tableName WHERE `tstmp` < ''$tempMemberExpires''";\r\n\r\n            if(!$rs = mysql_query($sql)) {\r\n\r\n              $error. $messages[''error1''];\r\n\r\n}\r\n\r\n\r\n\r\nif(isset($_POST[''msubmit''])){\r\n\r\n\r\n\r\n//set the unique session hash first\r\n\r\nif(!isset($_SESSION[''hash''])) {$_SESSION[''hash''] = md5(rand() * time());}\r\n\r\n\r\n\r\n$hash = $_SESSION[''hash''];\r\n\r\n\r\n\r\n########  Error Handling:  ##################\r\n\r\n\r\n\r\n\r\n\r\n//UserName and email check for uniqueness in temp table\r\n\r\n$rs=$etomite->getIntTableRows($fields="*", \r\n\r\n                              $from=$table, \r\n\r\n                              $where="`userName` = ''".$_POST[''mv_userName'']."'' \r\n\r\n                                   OR `email`    = ''".$_POST[''mv_email'']."''", \r\n\r\n                              $sort="", $dir="ASC", \r\n\r\n                              $limit="5", \r\n\r\n                              $push=true, $addPrefix=false);\r\n\r\n\r\n\r\nfor ($i=0; count($rs)>$i;  $i++){                           \r\n\r\n    if ($rs[$i][''userName''] == $_POST[''mv_userName'']){$usernameTaken++;}\r\n\r\n    if ($rs[$i][''email'']    == $_POST[''mv_email''])   {$emailTaken++;}\r\n\r\n    }\r\n\r\n\r\n\r\n//check all verified usernames used so far in manager_users table                             \r\n\r\n$rsEtomite=$etomite->getIntTableRows($fields="*", \r\n\r\n                              $from="manager_users", \r\n\r\n                              $where="`username` = ''".$_POST[''mv_userName'']."''",\r\n\r\n                              $sort="", $dir="ASC", \r\n\r\n                              $limit="1", \r\n\r\n                              $push=true, $addPrefix=true); \r\n\r\n  while ( count($rsEtomite) > $i){                           \r\n\r\n   if ( $rsEtomite[$i][''username''] == $_POST[''mv_userName'']){$usernameTaken++;}\r\n\r\n    $i++;}\r\n\r\n    \r\n\r\n//check all verified emails used so far in user_attributes table \r\n\r\n$rsEtomiteB=$etomite->getIntTableRows($fields="*", \r\n\r\n                              $from="user_attributes", \r\n\r\n                              $where="`email` = ''".$_POST[''mv_email'']."''",\r\n\r\n                              $sort="", $dir="ASC", \r\n\r\n                              $limit="1", \r\n\r\n                              $push=true, $addPrefix=true);  \r\n\r\n \r\n\r\n  while ( count($rsEtomiteB)>$j ){   \r\n\r\n   if ( $rsEtomiteB[$j][''email'']   == $_POST[''mv_email''])   {$emailTaken++;}\r\n\r\n    $j++; }\r\n\r\n \r\n\r\n//report errors    \r\n\r\nif ($usernameTaken > 0){      \r\n\r\n        $error .= $messages[''error2''];               \r\n\r\n       } \r\n\r\nif ($emailTaken > 0){      \r\n\r\n        $error .= $messages[''error3''];               \r\n\r\n       }       \r\n\r\n\r\n\r\n\r\n\r\n//Check for matching passwords\r\n\r\nif($_POST[''mv_password''] != $_POST[''mv_password2'']){\r\n\r\n$error .= $messages[''error_pword_match''];\r\n\r\n}\r\n\r\n\r\n\r\nif(strlen($_POST[''mv_password'']) < 6 ) {\r\n\r\n      $error .= $messages[''error_pword_short''];\r\n\r\n      }\r\n\r\nif (ereg(''[^\\(\\)0-9]'', $_POST[''mv_phone''])|| ereg(''[^\\(\\)0-9]'', $_POST[''mv_mobilephone''])) {\r\n\r\n$error .= $messages[''error_phone''];\r\n\r\n}\r\n\r\n//check for no blank fields\r\n\r\nif(\r\n\r\n       $_POST[''mv_firstName'']==''''   || $_POST[''mv_firstName'']==''{firstName}''||\r\n\r\n       $_POST[''mv_lastName''] ==''''   || $_POST[''mv_lastName''] ==''{lastName}'' || \r\n\r\n       //$_POST[''mv_mobilephone'']=='''' || $_POST[''mv_mobile'']==''{mobilephone}'' ||\r\n       \r\n       $_POST[''mv_address'']==''''    || $_POST[''mv_address'']==''{address}''  ||\r\n       \r\n       $_POST[''mv_city'']==''''   || $_POST[''mv_city'']==''{city}''  ||\r\n       \r\n       $_POST[''mv_state'']==''''  || $_POST[''mv_state'']==''{state}''  ||\r\n       \r\n       $_POST[''mv_zip'']==''''  || $_POST[''mv_zip'']==''{zip}''  ||\r\n\r\n       $_POST[''mv_password'']==''''    || $_POST[''mv_password'']==''{password}''  ||\r\n\r\n       $_POST[''mv_password2'']==''''   || $_POST[''mv_password2'']==''{password2}''||\r\n\r\n       $_POST[''mv_email''] ==''''      || $_POST[''mv_email''] ==''{email}''\r\n\r\n       )\r\n\r\n       {\r\n\r\n  $error .= $messages[''error_blank_fields''];\r\n\r\n  }\r\n\r\n\r\n\r\n//validate email address\r\n\r\nif(!function_exists(''check_email_address'')){\r\n\r\n     function check_email_address($email) {\r\n\r\n// First, we check that there''s one @ symbol, and that the lengths are right\r\n\r\n   if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {\r\n\r\n// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.\r\n\r\n   return false;\r\n\r\n   }\r\n\r\n   // Split it into sections to make life easier\r\n\r\n   $email_array = explode("@", $email);\r\n\r\n   $local_array = explode(".", $email_array[0]);\r\n\r\n   for ($i = 0; $i < sizeof($local_array); $i++) {\r\n\r\n    if (!ereg("^(([A-Za-z0-9!#$%&''*+/=?^_`{|}~-][A-Za-z0-9!#$%&''*+/=?^_`{|}~\\.-]{0,63})|(\\"[^(\\\\|\\")]{0,62}\\"))$", $local_array[$i])) {\r\n\r\n    return false;\r\n\r\n      }\r\n\r\n    }\r\n\r\n if($email_switch){\r\n\r\n   //check the domain\r\n\r\n   list($userName, $mailDomain) = split("@", $email);\r\n\r\n    $dns=(checkdnsrr($mailDomain, "MX"));\r\n\r\n    if(!$dns){\r\n\r\n    return false;\r\n\r\n    }\r\n\r\n}\r\n\r\n  \r\n\r\n  if (!ereg("^\\[?[0-9\\.]+\\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name\r\n\r\n    $domain_array = explode(".", $email_array[1]);\r\n\r\n  if (sizeof($domain_array) < 2) {\r\n\r\n  return false; // Not enough parts to domain\r\n\r\n    }\r\n\r\n  for ($i = 0; $i < sizeof($domain_array); $i++) {\r\n\r\n   if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {\r\n\r\n  return false;\r\n\r\n     }\r\n\r\n   }\r\n\r\n }\r\n\r\nreturn true;\r\n\r\n }\r\n\r\n}//end function\r\n\r\n\r\n\r\nif(! check_email_address($_POST[''mv_email''])){\r\n\r\n  \r\n\r\n  $error .= $messages[''error_email'']; }\r\n\r\n\r\n\r\nif(!$error){\r\n\r\n//if all fields have been validated, set the session variables so that they won''t change. \r\n\r\n\r\n\r\nif(!isset($_SESSION[''userName''])) { $_SESSION[''userName''] = $_POST[''mv_userName''];}\r\n\r\nif(!isset($_SESSION[''firstName''])){ $_SESSION[''firstName'']= $_POST[''mv_firstName''];}\r\n\r\nif(!isset($_SESSION[''lastName''])) { $_SESSION[''lastName'']= $_POST[''mv_lastName''];}\r\n\r\n\r\n\r\n\r\n\r\n$userName  = $_SESSION[''userName''];\r\n\r\n$firstName = $_SESSION[''firstName''];\r\n\r\n$lastName  = $_SESSION[''lastName''];\r\n\r\n$email     = $_POST[''mv_email''];\r\n\r\n\r\n\r\n}\r\n\r\n        \r\n\r\n$form  =$messages[''step1''];\r\n\r\n$form .=$messages[''personal_details''];\r\n\r\n$form .="<form action=\\"".$etomite->makeURL($etomite->documentIdentifier,'''','''')."\\" method=\\"post\\">";\r\n\r\n$form .=\r\n\r\n<<<END\r\n\r\n<input type=''hidden'' name=''prefix'' value=''mv_'' />\\n\r\n\r\n<input type=''hidden'' name=''into'' value=''$table'' />\\n\r\n<table border="0" cellspacing="5" cellpadding="5">\r\n  <tr>\r\n    <td>UserName:</td>\r\n    <td><input type="text" name="mv_userName" value="{userName}" /></td>\r\n  </tr>\r\n  <tr>\r\n    <td>Password:</td>\r\n    <td><input type="password" name="mv_password" value="{password}" />\r\n    (Must be at least 6 characters long)</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Confirm Password:</td>\r\n    <td><input type="password" name="mv_password2" value="{password2}" /></td>\r\n  </tr>\r\n  <tr>\r\n    <td>First Name:</td>\r\n    <td><input type="text" name="mv_firstName" value="{firstName}" /></td>\r\n  </tr>\r\n  <tr>\r\n    <td>Last Name:</td>\r\n    <td><input type="text" name="mv_lastName" value="{lastName}" /></td>\r\n  </tr>\r\n  <tr>\r\n    <td>Email:</td>\r\n    <td><input type="text" name="mv_email" value="{email}" /></td>\r\n  </tr>\r\n  <tr>\r\n    <td>Phone:</td>\r\n    <td><input type="text" name="mv_phone" value="{phone}" /></td>\r\n  </tr>\r\n  <tr>\r\n    <td>Address:</td>\r\n    <td><input type="text" name="mv_address" value="{address}" /></td>\r\n  </tr>\r\n  <tr>\r\n    <td>City:</td>\r\n    <td><input type="text" name="mv_city" value="{city}" /></td>\r\n  </tr>\r\n  <tr>\r\n    <td>State:</td>\r\n    <td><input type="text" name="mv_state" value="{state}" /></td>\r\n  </tr>\r\n  <tr>\r\n    <td>Zip Code:</td>\r\n    <td><input type="text" name="mv_zip" value="{zip}" /></td>\r\n  </tr>\r\n    <!-- <tr>\r\n    <td>Mobile Phone:</td>\r\n    <td><input type="text" name="mv_mobilephone" value="{mobilephone}" /></td>\r\n  </tr> -->\r\n  <tr>\r\n    <td colspan="2"><input type="reset" name="reset" value="Reset" /> <input type="submit" name="msubmit" value="Save Details" /></td>\r\n  </tr>\r\n</table>\r\n</form></p>\\n\r\n\r\nEND;\r\n\r\n\r\n\r\n//collect form fields and re-populate form if an error\r\n\r\n$fields =array();\r\n\r\n$into   =$_POST[''into''];\r\n\r\n$prefix =$_POST[''prefix''];\r\n\r\n$fields = $etomite->getFormVars($method="POST",$prefix,$trim=true,$REQUEST_METHOD);\r\n\r\n\r\n\r\n$output .=$etomite->mergeCodeVariables($form,\r\n\r\n                                 $rs=$fields,\r\n\r\n                                 $prefix="{",$suffix="}",\r\n\r\n                                 $oddStyle="",$evenStyle="",\r\n\r\n                                 $tag="div");\r\n\r\n\r\n\r\n//all done, now send the confirmation email\r\n\r\nif(!$error){\r\n\r\n$address_to = $email;\r\n\r\n\r\n\r\n$initial_name_string = $firstName;\r\n\r\n$initial_email_string = $email;\r\n\r\n$initial_subject_string = ''Your Membership Confirmation'';\r\n\r\n\r\n\r\n$site_name = $etomite->config[''site_name''].''  '';\r\n\r\n\r\n\r\n//extract the email domain\r\n\r\nlist($tempName, $mailDomain) = split("@", $_POST[''mv_email'']);\r\n\r\n\r\n\r\n         $name = $firstName." ".$lastName." (".$userName.")";\r\n\r\n            \r\n\r\n            $subject = $site_name.$messages[''email_subject''];\r\n\r\n         $content_text .= $messages[''email_body1''];\r\n\r\n            $content_text .= $messages[''email_body2''].$_SESSION[''userName''];\r\n\r\n            $content_text .= $messages[''email_body3''].$_POST[''mv_password''];\r\n\r\n            $content_text .= $messages[''email_body4''];\r\n\r\n            $content_text .= $etomite->makeURL($confirmationPage,'''',''?hash=''.$hash.'''');\r\n\r\n           $message = stripslashes($content_text);\r\n\r\n         \r\n\r\n            $headers = "MIME-Version: 1.0\\r\\n";\r\n\r\n           $headers .= "Content-type: text; charset=iso-8859-1\\r\\n";\r\n\r\n         $headers .= "From: $site_name <$webmasterEmail>\\r\\n";\r\n\r\n\r\n\r\n         $headers .= "X-Priority: 1\\r\\n";\r\n\r\n          $headers .= "X-Mailer: Etomite PHP mailer\\r\\n";\r\n\r\n       \r\n\r\n         if($email_switch){ \r\n\r\n            mail ($address_to, $subject, $message, $headers);\r\n\r\n                       }\r\n\r\n//Save the user details to table after email verification\r\n\r\n  $fields=array(''firstName''=>$fields[''firstName''],\r\n\r\n              ''lastName''=>$fields[''lastName''], \r\n\r\n           ''userName''=>$fields[''userName''], \r\n\r\n           ''email''=>$fields[''email''],\r\n\r\n              ''mobilephone''=>$fields[''mobilephone''],\r\n\r\n              ''hash'' =>$hash,\r\n\r\n           ''phone'' => $fields[''phone''],\r\n\r\n            ''password''=>md5($fields[''password'']),\r\n\r\n           ''role''=>$memberRole,\r\n              \r\n            ''address''=> $fields[''address''],\r\n             \r\n            ''city''=> $fields[''city''],\r\n           \r\n            ''state''=> $fields[''state''],\r\n             \r\n            ''zip''=> $fields[''zip'']\r\n\r\n              );    \r\n\r\n    \r\n\r\n$rs = $etomite->putIntTableRow($fields, $table, $addPrefix=false);\r\n\r\n  \r\n\r\n$output ='''';\r\n\r\n$output .= $messages[''step2''];\r\n\r\n$output .= $messages[''check_email''];\r\n\r\n}\r\n\r\n\r\n\r\n//if nothing submitted, show Register Now button.\r\n\r\n}else{//end if isset Post[''msubmit'']\r\n\r\n\r\n\r\n$output  =$messages[''intro''];\r\n\r\n$output .="<form action=\\"".$etomite->makeURL($etomite->documentIdentifier,'''','''')."\\" method=\\"post\\">";\r\n\r\n$output .="<input type=\\"submit\\" name=\\"msubmit\\" value=\\"".$messages[''register_button'']."\\" />";\r\n\r\n$output .="</form></p>";\r\n\r\n\r\n\r\n\r\n\r\n}//end for if msubmit not sent\r\n\r\n\r\n\r\n//hide errors unless form data form was sent\r\n\r\nif(!$_POST[''into'']){ $error='''';}//don''t show errors unless the form was submitted\r\n\r\n\r\n\r\nreturn $error.$output;', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (33, 'membership_verifier', 'Membership Verifier', '//Snippet: membership_verifier\r\n\r\n//Cris D:\r\n\r\n//2007/12/27\r\n\r\n//This is part of the membership_validator module.\r\n\r\n//Use: This listens for a md5 hash that is sent from an emailed link and saves the user as a verified\r\n\r\n//manager and member of the user group set in the snippet call.\r\n\r\n\r\n\r\n//set to the id of the page that has your login script [!authenticate_visitor!]\r\n\r\n// (or set in snippet call).\r\n\r\n$loginId=isset($loginId) ? $loginId:''44'';\r\n\r\n\r\n\r\n//set the contact page where users can nontact the webmaster if having difficulties\r\n\r\n$contactPage=''98'';\r\n\r\n\r\n\r\n//set to the name of the member group that the user will belong to \r\n\r\n//(create this group and set the permissions and document groups in the etomite manager first)\r\n\r\n//must be identical to the name of the user group created in the etomite manager\r\n\r\n$memberGroup = isset($memberGroup) ? $memberGroup: ''members'';\r\n\r\n\r\n\r\n//set to the name of the membership_validator snippet in the library\r\n\r\n$membershipValidatorSnippetName=''membership_validator'';\r\n\r\n\r\n\r\n//name of the table that holds the temp records (must be set the same as the membership_validator snippet).\r\n\r\n$tableName=''member_validation'';\r\n\r\n\r\n\r\n$messages = array(\r\n\r\n''create_new_account'' => "Create a new account or log-in below.<br />\\n",\r\n\r\n''error1'' => "There is a registration error...this could be because:<br /> \\n\r\n\r\n             1) you have attempted hack the site,<br /> \\n\r\n\r\n             2) you have tried to create another account,<br /> \\n\r\n\r\n            <b> Please register again or contact the webmaster for assistance.</b><br />\\n",\r\n\r\n''error2'' => "Your username and passwords do not match your registration link, \r\n\r\n             please check your confirmation email and try again.<br />",\r\n\r\n''error3'' => "If you already have an account, please login below or",\r\n\r\n''error4'' => " If you continue to have difficulties,", \r\n\r\n''step3''  => "<p>Step 1 | Step 2 | <b>Step 3</b> <br />",\r\n\r\n''confirm_registration'' => " Please confirm your username and password to complete the registration.",\r\n\r\n''username'' => " User Name: \\n",\r\n\r\n''password'' => " Password: \\n",\r\n\r\n''complete_reg'' => "Complete Registration",\r\n\r\n''reset'' => "Reset",\r\n\r\n''congratulations'' => "Congratulations: Your Membership has been activated!<br />",\r\n\r\n''login'' => " Please log in ",\r\n\r\n''here'' => " here ",\r\n\r\n''webmaster'' => " contact the webmaster.",\r\n\r\n''have_account'' => " Already have an account? "\r\n\r\n);\r\n\r\n\r\n\r\n######  END SNIPPET CONFIGURATION Don''t touch below! ########\r\n\r\n\r\n\r\n\r\n\r\n$table=$GLOBALS[''table_prefix''].$tableName;\r\n\r\n$dbase=$GLOBALS[''dbase''];\r\n\r\n\r\n\r\n$hash=$_GET[''hash''];\r\n\r\n\r\n\r\n//if no linkback has been followed\r\n\r\nif(!isset($_GET[''hash''])){\r\n\r\n    $out.=$messages[''create_new_account''];\r\n\r\n                  \r\n\r\n    $out.=$etomite->runSnippet($membershipValidatorSnippetName,'''');\r\n\r\n     \r\n\r\n             }else{\r\n\r\n   \r\n\r\n   if(!isset($_GET[''hash''])){//if link has been followed, don''t show new registration\r\n\r\n    $out.=$etomite->runSnippet($membershipValidatorSnippetName,'''');}        \r\n\r\n             }\r\n\r\n             \r\n\r\n             \r\n\r\n             \r\n\r\nif(isset($_GET[''hash''])){\r\n\r\n  \r\n\r\n$retrieveTempUserDetails = \r\n\r\n    $etomite->getIntTableRows($fields="*", \r\n\r\n                              $from=$table, \r\n\r\n                              $where="`hash` = ''".$_GET[''hash'']."''", \r\n\r\n                              $sort="id", $dir="ASC", \r\n\r\n                              $limit="999", \r\n\r\n                              $push=true, $addPrefix=false);\r\n\r\n \r\n\r\n if(count($retrieveTempUserDetails) > 1 ){\r\n\r\n\r\n\r\n$error .=$messages[''error1''];\r\n\r\n\r\n\r\n}else{\r\n\r\n\r\n\r\n$form  = $messages[''step3''];\r\n\r\n$form .= $messages[''confirm_registration''];\r\n\r\n$form .="<p><form action=\\"\\" name=\\"\\" method=\\"post\\" >\\n";\r\n\r\n$form .="<lable for username>".$messages[''username''];\r\n\r\n$form .="<input type=\\"text\\" name=\\"userName\\" value=\\"".$_SESSION[''userName'']."\\" /><br />\\n";\r\n\r\n$form .="<lable for password>".$messages[''password'']."       \r\n\r\n              <input type=\\"password\\" name=\\"mvpassword\\" value=\\"".$_POST[''mvpassword'']."\\" /><br />\\n\r\n\r\n              <input type=\\"submit\\" name=\\"vsubmit\\" value=\\"".$messages[''complete_reg'']."\\" />\\n\r\n\r\n              <input type=\\"reset\\" name=\\"reset\\" value=\\"".$messages[''reset'']."\\" /><br />\\n\r\n\r\n              </form></p>";\r\n\r\n}//end for valid get hash exists in the table and is checked against the current session\r\n\r\n\r\n\r\n}//end if link was followed or get hash placed into browser\r\n\r\n\r\n\r\n//check that username and passwords match\r\n\r\nif(isset($_POST[''vsubmit''])){\r\n\r\n\r\n\r\nif( ($_POST[''userName'']      == $retrieveTempUserDetails[0][''userName'']) && \r\n\r\n    (md5($_POST[''mvpassword'']) == $retrieveTempUserDetails[0][''password'']) ){\r\n\r\n\r\n\r\n\r\n\r\n  //save etomite_manager_users: username and password\r\n\r\n  $managerFields=array(''username''=> $retrieveTempUserDetails[0][''userName''],\r\n\r\n                       ''password''=> $retrieveTempUserDetails[0][''password'']);\r\n\r\n  $into="manager_users";\r\n\r\n  $rs=$etomite->putIntTableRow($managerFields, $into); \r\n\r\n\r\n\r\n  $internalKey= $etomite-> getIntTableRows($fields="id", \r\n\r\n                                         $from="manager_users", \r\n\r\n                                         $where="`username`= ''".$retrieveTempUserDetails[0][''userName'']."''", \r\n\r\n                                         $sort="id", \r\n\r\n                                         $dir="ASC", \r\n\r\n                                         $limit="1", \r\n\r\n                                         $push=true, \r\n\r\n                                         $addPrefix=true);\r\n\r\n\r\n\r\n//save etomite_user_attributes: names, numbers, email etc \r\n\r\n\r\n\r\n$userFields=array(''internalKey''=> $internalKey[0][''id''],\r\n\r\n                  ''fullname''=> $retrieveTempUserDetails[0][''firstName'']." ".$retrieveTempUserDetails[0][''lastName''],\r\n\r\n                  ''role''=>  $retrieveTempUserDetails[0][''role'']  ,\r\n\r\n                  ''email''=> $retrieveTempUserDetails[0][''email''] ,\r\n\r\n                  ''phone''=> $retrieveTempUserDetails[0][''phone''],\r\n\r\n                  //''mobilephone''=> $retrieveTempUserDetails[0][''mobilephone''], \r\n                  \r\n                  ''address''=> $retrieveTempUserDetails[0][''address''],\r\n                  \r\n                  ''city''=> $retrieveTempUserDetails[0][''city''],\r\n                  \r\n                  ''state''=> $retrieveTempUserDetails[0][''state''], \r\n                  \r\n                  ''zip''=> $retrieveTempUserDetails[0][''zip'']\r\n\r\n                   );\r\n\r\n$into="user_attributes";\r\n\r\n$rs=$etomite->putIntTableRow($userFields, $into);\r\n\r\n\r\n\r\n$userGroupNameId=$etomite->getIntTableRows($fields="id", \r\n\r\n                                         $from="membergroup_names", \r\n\r\n                                         $where="`name`= ''".$memberGroup."''", \r\n\r\n                                         $sort="id", \r\n\r\n                                         $dir="ASC", \r\n\r\n                                         $limit="1", \r\n\r\n                                         $push=true, \r\n\r\n                                         $addPrefix=true);\r\n\r\n if(!$userGroupNameId) {return "<b> $memberGroup </b> is not a valid member group name.  \r\n\r\n       Please set this in etomite manager and in the snippet configuration before proceeding.";}\r\n\r\n\r\n\r\n//save user into membership group             \r\n\r\n$setMemberGroup=array(''member''=> $internalKey[0][''id''],\r\n\r\n                  ''user_group''=>$userGroupNameId[0][''id''],\r\n\r\n                  );\r\n\r\n$into="member_groups";\r\n\r\n$rs=$etomite->putIntTableRow($setMemberGroup, $into);\r\n\r\n\r\n\r\n/*add additional code here to send data off to additional tables if collected like...\r\n\r\n//save new user into author_attributes table\r\n\r\n$author_attributes=array(''fullname'' => $userfields[''fullname''],''internalKey''=> $_SESSION[''internalKey'']);\r\n\r\n$rs=$etomite->putIntTableRow($author_attributes, ''author_atributes'');\r\n\r\n*/\r\n\r\n\r\n\r\n//delete old temporary record\r\n\r\n$sql = "DELETE FROM $dbase.$table WHERE `id` = ''".$retrieveTempUserDetails[0][''id'']."''";\r\n\r\n            if(!$rs = mysql_query($sql)) {\r\n\r\n              $error. "Unable to delete temporary records. \r\n\r\n               SQL failed. Validation can still continue.";\r\n\r\n                        }else{ $rs=$etomite->dbQuery($sql);}\r\n\r\n  \r\n\r\n  return $messages[''congratulations''].$messages[''login'']."<a href=\\"".$etomite->makeURL($loginId,'''','''')."\\">".$messages[''here'']."</a>.";\r\n\r\n  }else{\r\n\r\n  $error .= $messages[''error2''];\r\n\r\n  $error .= $messages[''error3'']."<a href=\\"".$etomite->makeURL($loginId,'''','''')."\\">".$messages[''here'']."</a>.<br />";\r\n\r\n  $error .= $messages[''error4'']."<a href=\\"".$etomite->makeURL($contactPage,'''','''')."\\">".$messages[''webmaster'']."</a><br />";\r\n\r\n  }\r\n\r\n\r\n\r\n}\r\n\r\n\r\n\r\n//This returns the log-in snippet if \r\n\r\n//someone uses thier email link to try to log into an existing account.\r\n\r\nif($_SESSION[''validated'']==1){$form=''''; $error='''';}\r\n\r\n$form .="<hr><b> ".$messages[''have_account'']."</b><br />".$etomite->runSnippet(''authenticate_visitor'','''');\r\n\r\n\r\n\r\nreturn $error.$out.$form;', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (34, 'authenticate_visitor', 'Authenticate Site Visitors', '// Aauthenticate_visitor\r\n//\r\n// For use with Etomite Prelude Final or newer ONLY\r\n//\r\n// *** NOTE ***\r\n// This is an extremely advanced snippet and an understanding of the logic \r\n// involved will be required if any modifications are to be made. Time \r\n// constraints do not allow hand-holding during implementation of advanced \r\n// snippets. Some of the techniques used in this snippet have not yet been \r\n// adequately documemnted. YOU HAVE BEEN WARNED!!!\r\n//\r\n// Last Modified: 2006-04-24 by Ralph Dahlgren\r\n//\r\n// Purpose: Provides a visitor authentication login interface\r\n//\r\n// Parameters: ( All Are Optional )\r\n//   use_captcha: [0=false|1=true]\r\n//   use_logout: [0=false|1=true]\r\n//   url: url to be redirected to on success\r\n//   id: id of document to be redirected to on success\r\n//   alias: alias of document to be redirected to on success\r\n// Examples of Use:\r\n//   [!authenticate_visitor?use_captcha=1&id=123!]\r\n//   [!authenticate_visitor?alias=success&use_logout=1!]\r\n//   [!authenticate_visitor?use_captcha=0&url=http://www.mysite.com!]\r\n//\r\n// Passing no parameters in the snippet call will cause the parser to return\r\n// to the same page on either success or failure\r\n//\r\n// Styles used in this snippet - All are optional and can be modified\r\n// .loginForm - Used for custom formatting of the form\r\n// .loginTable - Used for formatting of the table\r\n// .alignCenter { text-align:center; } - Used for text and button centering\r\n// .button - Used to style the form buttons\r\n// \r\n\r\n// should captchaCodes security be used [0=false|1=true(default)]\r\n$use_captcha = isset($use_captcha) ? $use_captcha : 1;\r\n\r\n// should a logout prompt replace the login prompt [0=false|1=true(default)]\r\n$use_logout = isset($use_logout) ? $use_logout : 1;\r\n\r\n// default id\r\n$id = isset($id) ? $id : 45;\r\n\r\n\r\n// PROCESSING STARTS HERE\r\n\r\n// if the user is authenticated and no destination was provided, provide a logout button.\r\n// this conditional code block is optional and can be remarked or bypassed\r\nif($_SESSION[''validated''] && $use_logout)\r\n{\r\n  if(isset($_POST[''logout''])) $etomite->userLogout($url,$id,$alias="");\r\n\r\n    $output = ''\r\n    <form action="" method="post">\r\n      <input type="submit" name="logout" value="Logout" /> [ ''.$_SESSION[''shortname''].'' ]\r\n    </form>\r\n    '';\r\n    \r\n    return $output;\r\n}\r\n\r\n// if the form has been submitted, attempt to validate the user\r\nif(isset($_POST[''submit'']))\r\n{\r\n    \r\n  // get only the forms $_POST variables we want based on the prefix "frm_"\r\n  $fields = $etomite->getFormVars($method="POST",$prefix="frm_",$trim=1,$REQUEST_METHOD);\r\n  \r\n  // extract the variable array into plain variables\r\n  extract($fields);\r\n\r\n  // prepare a url for redirect upon successful login if no snippet call param was sent\r\n  if(($url=="") && ($id=="") && ($alias=="")) $url = $etomite->makeUrl($etomite->documentIdentifier, $alias=0, $args='''');\r\n\r\n  // perform the user login attempt\r\n  $etomite->userLogin($username,$password,$rememberme,$url,$id,$alias,$use_captcha,$captcha_code=$captcha);\r\n\r\n}\r\n\r\n// if all else fails, generate the user authentication form\r\nelse\r\n{\r\n\r\n  // assign miscellaneous variables\r\n  $_submitText = "Submit";\r\n  $_resetText = "Reset";\r\n  $formVar = array();\r\n  \r\n  // create all required form elements using the form class\r\n  $form = new formClass();\r\n  \r\n  // create the <form> element\r\n  $_openform = $form->openform(array(\r\n    ''id''=>''authenticate_visitor'',\r\n    ''action''=>'''',\r\n    ''method''=>''post'',\r\n    ''onsubmit''=>''return v.exec()''\r\n    )\r\n  );\r\n  \r\n  // define default name attribute prefix\r\n  $form->namePrefix="frm_";\r\n  \r\n  // define default label name prefix\r\n  $form->labelSuffix="_lbl";\r\n  \r\n  // NOTE: the following three hidden parameter elements are for \r\n  // optional redirect upon successful authentication\r\n  \r\n  // $url is the URL param passed in the snippet call\r\n  $formVar[''url''] = $form->input(array(\r\n    ''type''=>''hidden'',\r\n    ''id''=>''url'',\r\n    ''name''=>''url'',\r\n    ''value''=>$url\r\n    )\r\n  );\r\n  \r\n  // $id is the document id param passed in the snippet call\r\n  $formVar[''id''] = $form->input(array(\r\n    ''type''=>''hidden'',\r\n    ''id''=>''id'',\r\n    ''name''=>''id'',\r\n    ''value''=>$id\r\n    )\r\n  );\r\n  \r\n  // $alias is the document alias param passed in the snippet call\r\n  $formVar[''alias''] = $form->input(array(\r\n    ''type''=>''hidden'',\r\n    ''id''=>''alias'',\r\n    ''name''=>''alias'',\r\n    ''value''=>$alias\r\n    )\r\n  );\r\n  // END: optional snippet call redirect parameters\r\n\r\n  // username input tag\r\n  $_username = $form->input(array(\r\n    ''type''=>''text'',\r\n    ''id''=>''username'',\r\n    ''name''=>''username'',\r\n    ''value''=>'''',\r\n    ''label''=>array(\r\n      ''id''=>''username'',\r\n      ''for''=>''username'',\r\n      ''label''=>''Username:''\r\n      ),\r\n    ''validate''=>array(\r\n      ''l''=>''Username:'',\r\n      ''r''=>''1'',\r\n      ''t''=>''username''\r\n      )\r\n    )\r\n  );\r\n  \r\n  // password input tag\r\n  $_password = $form->input(array(\r\n    ''type''=>''password'',\r\n    ''id''=>''password'',\r\n    ''name''=>''password'',\r\n    ''value''=>'''',\r\n    ''label''=>array(\r\n      ''id''=>''password'',\r\n      ''for''=>''password'',\r\n      ''label''=>''Password:''\r\n      ),\r\n    ''validate''=>array(\r\n      ''l''=>''Password:'',\r\n      ''r''=>''1'',\r\n      ''t''=>''password''\r\n      )\r\n    )\r\n  );\r\n\r\n  // if CaptchCodes are enabled, generate the additional code required\r\n  if($use_captcha==1) {\r\n    \r\n    // captcha code input tag\r\n    $_captcha = $form->input(array(\r\n      ''type''=>''text'',\r\n      ''id''=>''captcha'',\r\n      ''name''=>''captcha'',\r\n      ''value''=>'''',\r\n      ''label''=>array(\r\n        ''id''=>''captcha'',\r\n        ''for''=>''captcha'',\r\n        ''label''=>''Image Text:''\r\n        ),\r\n      ''validate''=>array(\r\n        ''l''=>''Image Text'',\r\n        ''r''=>''1'',\r\n        ''t''=>''captcha'',\r\n        )\r\n      )\r\n    );\r\n  \r\n    // alternate example of directly calling the form class label function\r\n    // $captcha_lbl = $form->label(array(''id''=>''captcha'',''for''=>''captcha'',''label''=>''CaptchaCode:''));\r\n    \r\n    // get the form field label from the form object for local use\r\n    // notice the need to manually add the label suffix\r\n    $captcha_lbl = $form->formLabels[''captcha_lbl''];\r\n  \r\n    // create the complete captchaCode HTML code block for optional insertion\r\n    $captcha = \r\n  ''      <tr>\r\n          <td>\r\n            ''.$captcha_lbl.''\r\n          </td>\r\n          <td>\r\n            ''.$_captcha.''\r\n          </td>\r\n        </tr>\r\n        <tr>\r\n          <td colspan="2" style="text-align:center;">\r\n            ''.$etomite->getCaptchaCode().''\r\n          </td>\r\n        </tr>'';\r\n  \r\n  }\r\n  \r\n  // form data submit button\r\n  $_submit = $form->input(array(\r\n    ''type''=>''submit'',\r\n    ''id''=>''submit'',\r\n    ''name''=>''submit'',\r\n    ''value''=>$_submitText,\r\n    ''class''=>''button''\r\n    )\r\n  );\r\n  \r\n  // form data reset button\r\n  $_reset = $form->input(array(\r\n    ''type''=>''reset'',\r\n    ''id''=>''reset'',\r\n    ''name''=>''reset'',\r\n    ''value''=>$_resetText,\r\n    ''class''=>''button''\r\n    )\r\n  );\r\n  \r\n  // end of field generation\r\n\r\n  // create the </form> element\r\n  $_closeform = $form->closeform();\r\n  \r\n  // extract the form labels created by the form class\r\n  extract($form->formLabels);\r\n  \r\n  // get any tigra forms validation rules\r\n  $rules = $form->tigraGetRules();\r\n\r\n// render the form using simple template technique\r\n$output .= <<<END\r\n$rules\r\n  $_openform\r\n    <div class="loginForm">\r\n      <table class="loginTable">\r\n        <!-- <tr>\r\n          <th colspan="2">Visitor Login</th>\r\n        </tr> -->\r\n        <tr>\r\n          <td>$username_lbl</td>\r\n          <td>$_username</td>\r\n        </tr>\r\n        <tr>\r\n          <td>$password_lbl</td>\r\n          <td>$_password</td>\r\n        </tr>\r\n        $captcha\r\n        <tr>\r\n          <td colspan="2" class="alignCenter">\r\n            $_submit\r\n            $_reset\r\n          </td>\r\n        </tr>\r\n      </table>\r\n    </div>\r\n  $_closeform\r\n\\n\r\nEND;\r\n\r\n  // return to caller for display\r\n  return stripslashes($output);\r\n}\r\n// THE END\r\n', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (45, 'pagetitle', 'Page Title', '// get page title\r\n\r\nif($_SESSION[''validated'']==1){\r\n $uinfo = "<div style=''font-weight:normal;font-size:11px;float:right;padding:10px;''>Welcome: ".$_SESSION[''fullname'']." ( ".$_SESSION[''shortname'']." )</div>";\r\n}else{ \r\n   $uinfo = "<div style=''font-weight:normal;font-size:11px;float:right;padding:10px;''>Visiting as: Guest - <a href=''register.html''>Register</a> or <a href=''login.html''>Login</a></div>"; \r\n}\r\n\r\n$page = $etomite->getDocument($etomite->documentIdentifier, $fields="pagetitle");\r\n\r\n$output = "<div class=''page-title''><h2 style=''float:left;''>".$page[''pagetitle'']."</h2>".$uinfo."<div style=''clear:both;''></div></div>";\r\n//$output = "<div style=''float:left''>".$page[''pagetitle'']."</div><div style=''clear:both;''></div>";\r\nreturn $output;', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (59, 'cron_snip', '', '// cron snippet to run at various times during the day.\r\n\r\n// change the active status of a classified if it is expired.\r\n\r\n// get current date and time\r\n\r\n$output = '''';\r\n\r\n$dt = date("Y-m-d : H:i:s");\r\n\r\n$result = $etomite->updIntTableRows($fields=array(''active''=>0),$into=''classifieds'',$where="active=1 AND exp<''".$dt."''");\r\n$nums = $etomite->affectedRows();\r\n\r\nif($nums>0){\r\n  $output .= "[".$dt."] - There were ".$nums." set active=0<br />\\n";\r\n}else{\r\n  $output .= "[".$dt."] - There were ".$nums." set active=0<br />\\n";\r\n}\r\n\r\n\r\n// move ads older than 30 days of exp to the archive table.\r\n\r\n$tm = time(); // current time\r\n$we4 = strtotime("-1 weeks",$tm); // timestamp 1 weeks back\r\n$we4F = date("Y-m-d H:i:s",$we4); // 1 weeks back formatted\r\n\r\n$result2 = $etomite->getIntTableRows($fields=''*'', $from=''classifieds'', $where="exp<''".$we4F."'' AND active=0");\r\n$nums2 = $etomite->affectedRows();\r\nif(!empty($result2) && $nums2>0){\r\n  // time to move the fields\r\n  // first move them to the new archive table\r\n foreach($result2 as $row){\r\n      while(list($key,$val)=each($row)){\r\n          $row[$key] = mysql_real_escape_string($val);\r\n        }\r\n       $row[''class_id''] = $row[''id''];\r\n      unset($row[''id'']);\r\n        $result = $etomite->putIntTableRow($fields=$row,$into=''classifieds_archive'');\r\n     $output .= "[".$dt."] - Moved classified id: ".$row[''class_id'']."!<br />\\n";\r\n     // delete the record from the main classifieds table\r\n        $rdel = $etomite->dbQuery("DELETE FROM ".$etomite->db."classifieds WHERE id=".$row[''class_id'']." LIMIT 1");\r\n   }\r\n}else{\r\n // no rows to move\r\n  $output .= "[".$dt."] - There are no classifieds to archive today!<br />\\n";\r\n}\r\n\r\n// get all classifieds submitted today to send to office..\r\nunset($result,$result2,$nums,$nums2,$row);\r\n/*\r\n// get yesterday''s date\r\n$yd = strtotime("-1 day",$tm);\r\n$yd = date("Y-m-d H:i:s",$yd);\r\n// timestamp format to show yesterday at 5pm\r\n$ydt = mktime(17,0,0,substr($yd,5,2),substr($yd,8,2),substr($yd,0,4));\r\n// readable format of time\r\n$ydf = date("Y-m-d H:i:s",$ydt);\r\n\r\n$output .= "[".$dt."] - Time and Date for yesterday at 5:00pm : ".$ydf."<br />\\n";\r\n\r\n$results = $etomite->getIntTableRows($fields="*", $from="classifieds", $where="mem_id!=1 AND added>''".$ydf."'' AND exp>''".$dt."''", $sort=''added'', $dir=''ASC'');\r\n// run through results to put them into an output to be mailed.\r\n$nums = $etomite->affectedRows();\r\nif(!empty($results) && $nums>0){\r\n    // prepare the mail\r\n $mess = "<h2>Classifieds Report for ".$dt."!</h2><hr />";\r\n   $mess .= ''<table width="100%" border="1" cellpadding="3" cellspacing="3"><tr><td>ID</td><td>Classified</td><td>Category</td><td>Member</td><td>Date Added</td><td>Date Expires</td><td>Eye Stopper</td><td>Bold Box</td><td>Weeks</td><td>Publication(s)</td><td>Paypal Transaction #</td><td>Active</td></tr>'';\r\n  foreach($results as $res){\r\n      $mem = $etomite->getAuthorData($res[''mem_id''])// get member info in an array\r\n      $mess .= "<tr><td>".$res[''id'']."</td><td>".$res[''content'']."</td><td>".$getCatName($res[''cat_id''])."</td><td>".$mem[''fullname'']." - ".$mem[''email'']." - ".$mem[''phone'']."</td><td>".$res[''added'']."</td><td>".$res[''exp'']."</td><td>".$stopper."</td><td>".$bold."</td><td>".$res[''weeks'']."</td><td>".$res[''pub'']."</td><td>".$res[''txn_id'']."</td><td>".$active."</td></tr>";\r\n   }\r\n   $mess .= "</table>";\r\n    \r\n    \r\n    \r\n}else{\r\n  // sorry there are no submitted classifieds to mail\r\n $output .= "[".$dt."] - There are no classifieds to report for today!<br />\\n";\r\n}\r\n\r\n//$output .= "4 weeks back in time = ".date("Y-m-d H:i:s",$we4)."<br />\\n";\r\n*/\r\n\r\nreturn $output;', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (65, 'Pagination', 'pagination script', '/* new pagination */\r\nfunction pagination($total_pages,$page,$total,$url,$title=" Listings"){\r\n\r\n   //global $url;\r\n  $webpage = $url;\r\n\r\n    $pagination = ''<div class="pagNav">\r\n                    <ul>\r\n                    <li><strong>Pages:</strong></li>'';\r\n                 \r\n    if($total_pages>1){\r\n \r\n        //the total links visible\r\n         \r\n      $max_links=10;\r\n      \r\n        \r\n        //$max links_marker is the top of the loop\r\n      //$h is the start\r\n       \r\n        $max_links_marker = $max_links+1;            \r\n       $h=1;            \r\n       \r\n        \r\n        //$link_block is the block of links on the page\r\n     //When this is an integer we need a new block of links\r\n                \r\n      $link_block=(($page-1)/$max_links);\r\n     \r\n        //if the page is greater than the top of th loop and link block\r\n     //is an integer\r\n     \r\n        if(($page>=$max_links_marker)&&(is_int($link_block))){\r\n      \r\n                //reset the top of the loop to a new link block\r\n     \r\n            $max_links_marker=$page+$max_links;\r\n         \r\n                //and set the bottom of the loop         \r\n           \r\n            $h=$max_links_marker-$max_links;\r\n            $prev=$h-1;                                                                    \r\n     }\r\n       \r\n            //if not an integer we are still within a link block\r\n        \r\n        elseif(($page>=$max_links_marker)&&(!is_int($link_block))){\r\n         \r\n                //round up the link block\r\n           \r\n            $round_up=ceil($link_block);\r\n                    \r\n            $new_top_link = $round_up*$max_links;\r\n           \r\n                //and set the top of the loop to the top link\r\n           \r\n            $max_links_marker=$new_top_link+1;\r\n          \r\n                //and the bottom of the loop to the top - max links\r\n         \r\n            $h=$max_links_marker-$max_links;\r\n            $prev=$h-1;                            \r\n     }\r\n       \r\n          //if greater than total pages then set the top of the loop to\r\n       // total_pages\r\n        \r\n        if($max_links_marker>$total_pages){\r\n         $max_links_marker=$total_pages+1;\r\n       }\r\n       \r\n            //first and prev buttons\r\n        \r\n        if($page>''1''){\r\n            $pagination.=''<li class="first"><a href="''.$webpage.''/page/1">&laquo;</a></li>\r\n           <li class="prev"><a href="''.$webpage.''/page=''.($page-1).''">&lsaquo;</a></li>'';\r\n     }\r\n       \r\n            //provide a link to the previous block of links\r\n     \r\n        \r\n        $prev_start = $h-$max_links; \r\n       $prev_end = $h-1;\r\n       if($prev_start <=1){\r\n            $prev_start=1;\r\n      }\r\n       $prev_block = "Pages $prev_start to $prev_end";\r\n     \r\n        if($page>$max_links){\r\n           $pagination.=''<li class="current"><a href="''.$webpage.''/page/''.$prev.''">''.$prev_block.''</a></li>'';\r\n      }\r\n       \r\n            //loop through the results\r\n          \r\n        for ($i=$h;$i<$max_links_marker;$i++){\r\n          if($i==$page){\r\n              $pagination.= ''<li><a class="current" href="''.$webpage.''/page/''.$i.''">''.$i.''</a></li>'';\r\n         }\r\n           else{\r\n               $pagination.= ''<li><a href="''.$webpage.''/page/''.$i.''">''.$i.''</a></li>'';\r\n         }\r\n       }\r\n           //provide a link to the next block o links\r\n      \r\n        $next_start = $max_links_marker; \r\n       $next_end = $max_links_marker+$max_links;\r\n       if($next_end >=$total_pages){\r\n           $next_end=$total_pages;\r\n     }\r\n       $next_block = "Pages $next_start to $next_end";\r\n     if($total_pages>$max_links_marker-1){\r\n           $pagination.=''<li class="current"><a href="''.$webpage.''/page/''.$max_links_marker.''">''.$next_block.''</a></li>'';\r\n      }\r\n       \r\n          //link to next and last pages\r\n     \r\n        \r\n        if(($page >="1")&&($page!=$total_pages)){\r\n           $pagination.=''<li class="next"><a href="''.$webpage.''/page/''.($page+1).''">&rsaquo;</a></li>\r\n               <li class="last"><a href="''.$webpage.''/page/''.$total_pages.''">&raquo;</a></li>'';\r\n     }\r\n   }\r\n   \r\n    //if one page of results\r\n    \r\n    else{\r\n     $pagination.=''<li><a href="''.$webpage.''/page/1" class="current">1</a></li>'';\r\n  }\r\n   $pagination .= ''<li><em>( Total''.$title.'': ''.$total.'' )</em></li>'';\r\n   $pagination .= ''<li><u><em>Page ''.$page.''</em></u></li>'';\r\n   $pagination.=''</ul>\r\n        <div class="clear"></div>\r\n       </div>'';\r\n   \r\n    return($pagination);\r\n}\r\n//$displayposts = 10;\r\n$total_pages = ceil($total/$displayposts);\r\n$title = isset($title) ? $title:'''';\r\nreturn pagination($total_pages,$page,$total,$url,$title);', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (86, 'check-username', 'Check Username for Registration', '// check username\r\n\r\n$uname = $_POST[''username''];\r\nif(empty($uname)){ $arr = array(''error''=>"You need to enter a username",''success''=>""); print_r(json_encode($arr));exit; }\r\nif(preg_match(''/[^0-9A-Za-z]/'',$uname)){\r\n $arr = array(''error''=>"Username can only be Alpha Numeric Characters!",''success''=>""); print_r(json_encode($arr));exit;\r\n}\r\nif(strlen($uname)>14 || strlen($uname)<6){\r\n  $arr = array(''error''=>"Username must be 6-14 characters!",''success''=>""); print_r(json_encode($arr));exit;\r\n}\r\n\r\n$result = $etomite->getIntTableRows($fields="id",$from="web_users",$where="username=''".$uname."''");\r\nif(count($result)>0){\r\n   $arr = array(''error''=>"That Username is not available!",''success''=>""); \r\n    print_r(json_encode($arr));\r\n exit;\r\n}else{\r\n $arr = array(''error''=>"",''success''=>"Username Available!"); \r\n    print_r(json_encode($arr));\r\n exit;\r\n}', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (87, 'register_member', 'Register a member', '// register a member form\r\n\r\n// FUNCTIONS\r\n\r\nif(!function_exists(''check_email_address'')){\r\n\r\n   function check_email_address($email) {\r\n  $email_switch = true;\r\n\r\n// First, we check that there''s one @ symbol, and that the lengths are right\r\n\r\n   if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {\r\n\r\n// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.\r\n\r\n   return false;\r\n\r\n   }\r\n\r\n   // Split it into sections to make life easier\r\n\r\n   $email_array = explode("@", $email);\r\n\r\n   $local_array = explode(".", $email_array[0]);\r\n\r\n   for ($i = 0; $i < sizeof($local_array); $i++) {\r\n\r\n    if (!ereg("^(([A-Za-z0-9!#$%&''*+/=?^_`{|}~-][A-Za-z0-9!#$%&''*+/=?^_`{|}~\\.-]{0,63})|(\\"[^(\\\\|\\")]{0,62}\\"))$", $local_array[$i])) {\r\n\r\n return false;\r\n\r\n     }\r\n\r\n }\r\n\r\n if($email_switch){\r\n\r\n   //check the domain\r\n\r\n   list($userName, $mailDomain) = split("@", $email);\r\n\r\n  $dns=(checkdnsrr($mailDomain, "MX"));\r\n\r\n   if(!$dns){\r\n\r\n  return false;\r\n\r\n   }\r\n\r\n}\r\n\r\n  \r\n\r\n  if (!ereg("^\\[?[0-9\\.]+\\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name\r\n\r\n $domain_array = explode(".", $email_array[1]);\r\n\r\n  if (sizeof($domain_array) < 2) {\r\n\r\n  return false; // Not enough parts to domain\r\n\r\n   }\r\n\r\n  for ($i = 0; $i < sizeof($domain_array); $i++) {\r\n\r\n   if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {\r\n\r\n  return false;\r\n\r\n    }\r\n\r\n   }\r\n\r\n }\r\n\r\nreturn true;\r\n\r\n }\r\n\r\n}//end function\r\n\r\nfunction buildStatesSel($arr){\r\n $ret = '''';\r\n    $ret = "<option value=''''>Select Your State</option>";\r\n foreach($arr as $k => $v){\r\n      if(isset($_POST[''frm_state'']) && $_POST[''frm_state'']==$k){ $s = '' selected="selected"''; }else{ $s=''''; }      \r\n            $ret .= ''<option value="''.$k.''"''.$s.''>''.$v.''</option>''."\\n";     \r\n        }\r\n        return $ret;\r\n\r\n}\r\n\r\nfunction cleanVar($var){\r\n   if(empty($var)){ return ''''; }\r\n $var = stripslashes($var);\r\n  $var = strip_tags($var);\r\n    $var = htmlentities($var);\r\n  $var = trim($var);\r\n  return $var;\r\n}\r\n\r\n// END FUNCTIONS\r\n\r\n// STATIC VARIABLES\r\n\r\n$req = $etomite->_request;\r\nif(empty($req)){\r\n  $req = $_POST;\r\n}\r\n\r\n$output = '''';\r\n$error = '''';\r\n$randChar = time();\r\n$validate = true;\r\n$site_url = $etomite->config[''www_base_path''];\r\n$from = "Tractor-Warehouse.com <support@tractor-warehouse.com>";\r\n$mailHeader = ''Welcome to Tractor-Warehouse.com'';\r\n$mailRegTitle = "Tractor-Warehouse.com Member Registration";\r\n$logo = ''http://tractorwarehouse.831web.com/templates/tractorwarehouse/images/tractor_warehouse_heading.jpg'';\r\n$mailTitle = "Thank you for registering with tractor-warehouse.com! Please Review your information!";\r\n$mailTitleValidate = "You must click the link below to verify your registration!";\r\n$mailValidate2 = "If you did not sign-up with us, please ignore this e-mail!";\r\n$headers = "Content-type: text/html; charset=utf-8\\r\\n";\r\n$headers .= "From: $from\\r\\n";\r\n$headers .= "X-Priority: 1\\r\\n";\r\n$headers .= "X-Mailer: Etomite PHP mailer\\r\\n";\r\n$formAction = $etomite->makeUrl($etomite->documentIdentifier,'''',array(''action''=>''register''));\r\n$regForm = ''registration_form''; \r\n$regTitle = "Register as a member, it&rsquo;s FREE!";\r\n$valTitle = "Validate your registration!";\r\n$states_arr = array(''AL''=>"Alabama",''AK''=>"Alaska",''AZ''=>"Arizona",''AR''=>"Arkansas",''CA''=>"California",''CO''=>"Colorado",''CT''=>"Connecticut",''DE''=>"Delaware",''DC''=>"District Of Columbia",''FL''=>"Florida",''GA''=>"Georgia",''HI''=>"Hawaii",''ID''=>"Idaho",''IL''=>"Illinois", ''IN''=>"Indiana", ''IA''=>"Iowa",  ''KS''=>"Kansas",''KY''=>"Kentucky",''LA''=>"Louisiana",''ME''=>"Maine",''MD''=>"Maryland", ''MA''=>"Massachusetts",''MI''=>"Michigan",''MN''=>"Minnesota",''MS''=>"Mississippi",''MO''=>"Missouri",''MT''=>"Montana",''NE''=>"Nebraska",''NV''=>"Nevada",''NH''=>"New Hampshire",''NJ''=>"New Jersey",''NM''=>"New Mexico",''NY''=>"New York",''NC''=>"North Carolina",''ND''=>"North Dakota",''OH''=>"Ohio",''OK''=>"Oklahoma", ''OR''=>"Oregon",''PA''=>"Pennsylvania",''RI''=>"Rhode Island",''SC''=>"South Carolina",''SD''=>"South Dakota",''TN''=>"Tennessee",''TX''=>"Texas",''UT''=>"Utah",''VT''=>"Vermont",''VA''=>"Virginia",''WA''=>"Washington",''WV''=>"West Virginia",''WI''=>"Wisconsin",''WY''=>"Wyoming");\r\n\r\n\r\n$action = isset($req[''action'']) ? $req[''action'']:'''';\r\n\r\nswitch($action){\r\n    case ''validate'': // confirmation of signup\r\n        // check the validation\r\n     if(isset($req[''regid'']) && !empty($req[''regid''])){\r\n          // check the regid\r\n          $result = $etomite->getIntTableRows($fields="*",$from="web_users",$where="hash=''".$req[''regid'']."''");\r\n           if(count($result)>0){\r\n               // set the user to status 1 and remove the hash\r\n             $r = $result[0];\r\n                $result2 = $etomite->updIntTableRows($fields=array(''status''=>1,''hash''=>''''),$into="web_users",$where="id=".$r[''id'']." AND hash=''".$req[''regid'']."''");\r\n                $output .= "<p>Thank you for confirming your account! You may now login and use the site!</p>";\r\n         }else{\r\n              $output .= "<p>That is not a valid registration code! Please check your e-mail again!</p>";\r\n         }\r\n       }else{\r\n          $output .= "<p>You are not supposed to be here!</p>";\r\n       }\r\n   \r\n    break;\r\n  case ''register'': // registration\r\n  default:\r\n        if(!isset($_POST[''submit''])){ // just show the form\r\n           $frm_state = buildStatesSel($states_arr);\r\n           $form = $etomite->parseChunk($regForm,array(''form_action''=>$formAction,''title''=>$regTitle,''error''=>$error,''frm_username''=>'''',''frm_firstName''=>'''',''frm_lastName''=>'''',''frm_email''=>'''',''frm_phone''=>'''',''frm_phone2''=>'''',''frm_address''=>'''',''frm_city''=>'''',''frm_state''=>$frm_state,''frm_zip''=>''''));\r\n          $output .= $form;\r\n       }else{ // process the registration\r\n          // do some error checking first\r\n         if(empty($_POST[''frm_username''])){ $error .= "Username is REQUIRED.<br />"; }\r\n         if(empty($_POST[''frm_password''])){ $error .= "Password is REQUIRED.<br />"; }else{\r\n                if($_POST[''frm_password'']!=$_POST[''pass_conf'']){ $error .= "Password did not confirm.<br />"; }\r\n             if(strlen($_POST[''frm_password''])<6 || strlen($_POST[''frm_password''])>14){ $error .= "Password is not the correct length of characters!<br />"; }\r\n           }\r\n           if(empty($_POST[''frm_firstName''])){ $error .= "First Name is REQUIRED.<br />"; }\r\n          if(empty($_POST[''frm_lastName''])){ $error .= "Last Name is REQUIRED.<br />"; }\r\n            if(empty($_POST[''frm_email''])){ $error .= "Email is REQUIRED.<br />"; }else{\r\n              if(!check_email_address($_POST[''frm_email''])){ $error .= "Email did not validate!<br />"; }\r\n           }\r\n           if(empty($_POST[''frm_phone''])){ $error .= "Phone # is REQUIRED.<br />"; }\r\n         if(empty($_POST[''frm_address''])){ $error .= "Address is REQUIRED.<br />"; }\r\n           if(empty($_POST[''frm_city''])){ $error .= "City is REQUIRED.<br />"; }\r\n         if(empty($_POST[''frm_state''])){ $error .= "State is REQUIRED.<br />"; }\r\n           if(empty($_POST[''frm_zip''])){ $error .= "zip is REQUIRED.<br />"; }\r\n           \r\n            if(empty($error)){\r\n              // finish registration\r\n              $fields = $etomite->getFormVars($method="POST", $prefix=''frm_'', $trim=true,$REQUEST_METHOD); // grab the form fields\r\n              $fields[''password''] = md5($fields[''password'']); // encrypt the password\r\n             // if validation is required set these and also send the validation email\r\n               if($validate){ \r\n                 $hash = md5($randChar); // create a hash of alphanum characters for validation\r\n                  $fields[''hash''] = $hash; // set the hash to the user\r\n              }else{\r\n                  $fields[''status''] = 1;\r\n                }\r\n               $fields[''furl''] = $fields[''username''];\r\n              $result = $etomite->putIntTableRow($fields,$into=''web_users''); // create the user\r\n             \r\n                // send the validation e-mail. or send just the information\r\n             \r\n                if($validate){\r\n                  // build the message\r\n                    $message = ''<html><head><title>''.$mailRegTitle.''</title></head>'';\r\n                   $message .= ''<body>'';\r\n                 if(!empty($logo)){\r\n                      $message .= ''<p><a style="text-decoration:none;" href="''.$site_url.''" title="Tractor-Warehouse.com"><img src="''.$logo.''" alt="Tractor-Warehouse.com" border="0" /></a></p>'';\r\n                  }\r\n                   $message .= "<h1>".$mailHeader."</h1>";\r\n                 $message .= ''<p>''.$mailTitle.''</p>'';\r\n                    $message .= ''<p>''.$mailTitleValidate.''</p>'';\r\n                    $message .= ''<p>''.$mailValidate2.''</p>'';\r\n                    $message .= ''<hr />'';\r\n                 // show the registration information\r\n                    $message .= ''<table width="100%" cellpadding="5" border="0">\r\n                       <tr>\r\n                            <td width="150"><strong>First Name:</strong></td><td>''.$fields[''firstName''].''</td>\r\n                      </tr>\r\n                       <tr>\r\n                            <td><strong>Last Name:</strong></td><td>''.$fields[''lastName''].''</td>\r\n                        </tr>\r\n                       <tr>\r\n                            <td><strong>Username:</strong></td><td>''.$fields[''username''].''</td>\r\n                     </tr>\r\n                       <tr>\r\n                            <td><strong>Password:</strong></td><td>Removed for security!</td>\r\n                       </tr>\r\n                       <tr>\r\n                            <td><strong>Email Address:</strong></td><td>''.$fields[''email''].''</td>\r\n                       </tr>\r\n                       <tr>\r\n                            <td><strong>Phone #:</strong></td><td>''.$fields[''phone''].''</td>\r\n                     </tr>\r\n                       <tr>\r\n                            <td><strong>Phone # 2:</strong></td><td>''.$fields[''phone2''].''</td>\r\n                      </tr>\r\n                       <tr>\r\n                            <td><strong>Address:</strong></td><td>''.$fields[''address''].''</td>\r\n                       </tr>\r\n                       <tr>\r\n                            <td><strong>City:</strong></td><td>''.$fields[''city''].''</td>\r\n                     </tr>\r\n                       <tr>\r\n                            <td><strong>State:</strong></td><td>''.$fields[''state''].''</td>\r\n                       </tr>\r\n                       <tr>\r\n                            <td><strong>Zip Code:</strong></td><td>''.$fields[''zip''].''</td>\r\n                      </tr>\r\n                       </table>'';\r\n                 $validateLink = $etomite->makeUrl($etomite->documentIdentifier,'''',array(''action''=>''validate'',''regid''=>$hash));\r\n                  $message .= "<p>&nbsp;</p><p>Click <a href=".$validateLink.">here</a> to finish your registration!</p><p>If the link above does not work, please copy and paste this URL into your browser: ".$validateLink."</p>";\r\n                 \r\n                    // send the message\r\n                 mail($fields[''email''],$mailRegTitle,$message,$headers);\r\n                   $output .= "<p>Thank you for your registration!</p><p>Please check your email to finish the registration. You must first validate your account.</p>";\r\n                   \r\n                }else{\r\n                  // just send them the information\r\n                   $message = ''<html><head><title>''.$mailRegTitle.''</title></head>'';\r\n                   $message .= ''<body>'';\r\n                 if(!empty($logo)){\r\n                      $message .= ''<p><a style="text-decoration:none;" href="''.$site_url.''" title="Tractor-Warehouse.com"><img src="''.$logo.''" alt="Tractor-Warehouse.com" border="0" /></a></p>'';\r\n                  }\r\n                   $message .= "<h1>".$mailHeader."</h1>";\r\n                 $message .= ''<p>''.$mailTitle.''</p>'';\r\n                    $message .= ''<p>''.$mailValidate2.''</p>'';\r\n                    $message .= ''<hr />'';\r\n                 // show the registration information\r\n                    $message .= ''<table width="100%" cellpadding="5" border="0">\r\n                       <tr>\r\n                            <td width="150"><strong>First Name:</strong></td><td>''.$fields[''firstName''].''</td>\r\n                      </tr>\r\n                       <tr>\r\n                            <td><strong>Last Name:</strong></td><td>''.$fields[''lastName''].''</td>\r\n                        </tr>\r\n                       <tr>\r\n                            <td><strong>Username:</strong></td><td>''.$fields[''username''].''</td>\r\n                     </tr>\r\n                       <tr>\r\n                            <td><strong>Password:</strong></td><td>Removed for security!</td>\r\n                       </tr>\r\n                       <tr>\r\n                            <td><strong>Email Address:</strong></td><td>''.$fields[''email''].''</td>\r\n                       </tr>\r\n                       <tr>\r\n                            <td><strong>Phone #:</strong></td><td>''.$fields[''phone''].''</td>\r\n                     </tr>\r\n                       <tr>\r\n                            <td><strong>Phone # 2:</strong></td><td>''.$fields[''phone2''].''</td>\r\n                      </tr>\r\n                       <tr>\r\n                            <td><strong>Address:</strong></td><td>''.$fields[''address''].''</td>\r\n                       </tr>\r\n                       <tr>\r\n                            <td><strong>City:</strong></td><td>''.$fields[''city''].''</td>\r\n                     </tr>\r\n                       <tr>\r\n                            <td><strong>State:</strong></td><td>''.$fields[''state''].''</td>\r\n                       </tr>\r\n                       <tr>\r\n                            <td><strong>Zip Code:</strong></td><td>''.$fields[''zip''].''</td>\r\n                      </tr>\r\n                       </table>'';\r\n                 \r\n                    // send the message\r\n                 mail($fields[''email''],$mailRegTitle,$message,$headers);\r\n                   $output .= "<p>Thank you for your registration! You may now login!</p>";\r\n                \r\n                }\r\n               \r\n                \r\n            }else{\r\n              // there were errors show the form again\r\n                $frm_state = buildStatesSel($states_arr);\r\n               $frm_username = cleanVar($_POST[''frm_username'']);\r\n             $frm_firstName = cleanVar($_POST[''frm_firstName'']);\r\n               $frm_lastName = cleanVar($_POST[''frm_lastName'']);\r\n             $frm_email = cleanVar($_POST[''frm_email'']);\r\n               $frm_phone = cleanVar($_POST[''frm_phone'']);\r\n               $frm_phone2 = cleanVar($_POST[''frm_phone2'']);\r\n             $frm_address = cleanVar($_POST[''frm_address'']);\r\n               $frm_city = cleanVar($_POST[''frm_city'']);\r\n             $frm_zip = cleanVar($_POST[''frm_zip'']);\r\n               $frm_state = buildStatesSel($states_arr);\r\n               $error = "<div class=''form_error''><h2>ERRORS:</h2>".$error."</div>";\r\n              $form = $etomite->parseChunk($regForm,array(''form_action''=>$formAction,''title''=>$regTitle,''error''=>$error,''frm_username''=>$frm_username,''frm_firstName''=>$frm_firstName,''frm_lastName''=>$frm_lastName,''frm_email''=>$frm_email,''frm_phone''=>$frm_phone,''frm_phone2''=>$frm_phone2,''frm_address''=>$frm_address,''frm_city''=>$frm_city,''frm_state''=>$frm_state,''frm_zip''=>$frm_zip));\r\n              $output .= $form;\r\n           }\r\n       }\r\n   \r\n    break;\r\n\r\n}// end switch\r\n\r\nreturn $output;\r\n', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (88, 'member_login', 'Member Login', '/* Member login */\r\n\r\n// example of variables\r\n\r\n$req = $etomite->_request;\r\nif(empty($req)){\r\n  $req = $_POST;\r\n}\r\n\r\n$redirectUrl = (isset($req[''redUrl'']) && !empty($req[''redUrl''])) ? base64_decode($req[''redUrl'']):$etomite->makeUrl($etomite->config[''site_start''],'''',array()); // default member area\r\n\r\n$registerUrl = $etomite->makeUrl(43,'''',array());\r\n$formAction = $etomite->makeUrl($etomite->documentIdentifier,'''',array(''redUrl''=>$req[''redUrl'']));\r\n$use_captcha = 0; // use captcha code for help with spam\r\n$user = $etomite->webUserLoggedIn(); // grab current logged in member\r\n$output = '''';\r\n$error = '''';\r\n\r\nif($user && isset($req[''logout'']) && $req[''logout'']==1){\r\n   $etomite->webUserLogout('''',$etomite->documentIdentifier,'''');\r\n}\r\n\r\nif(isset($_SESSION[''validated'']) && $user){ // redirect member to the member area or to the redirect url\r\n $etomite->sendRedirect($redirectUrl);\r\n}\r\n\r\n// create the form\r\nif(!isset($_POST[''submit''])){\r\n // show form\r\n    $output .= "<fieldset class=''login-field''>";\r\n  $output .= "<form method=''post'' name=''login'' action=''".$formAction."''>";\r\n  $output .= "<table width=''90%'' cellpadding=''5'' border=''0'' class=''login-table''>";\r\n    $output .= "<tr>\r\n        <td valign=''top''><strong>Username:</strong></td><td><input type=''text'' name=''username'' value='''' /></td>\r\n     </tr>\r\n       <tr>\r\n        <td valign=''top''><strong>Password:</strong></td><td><input type=''password'' name=''password'' value='''' /></td>\r\n     </tr>";\r\n if($use_captcha==1){\r\n        $output .= "<tr><td valign=''top''><strong>Image Text:</strong></td><td><input type=''text'' name=''captcha'' value='''' /><p class=''captcha''>".$etomite->getCaptchaCode()."</p></td></tr>";\r\n  }\r\n   $output .= "<tr><td colspan=''2''><input class=''submit'' type=''submit'' name=''submit'' value=''Login'' /></td></tr>";\r\n    $output .= "</table></form></fieldset>";\r\n    \r\n}else{\r\n  // attempt to login\r\n $etomite->webUserLogin($username=$_POST[''username''],$password=$_POST[''password''],0,$redirectUrl,'''','''',$use_captcha,$_POST[''captcha'']);\r\n}\r\n\r\nreturn $output;', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (100, 'chain_db', 'test chain db', '// test a chain db\r\n\r\n$db = new ExtDB($etomite);\r\n\r\n$packages = $db->select(array(''listings''=>''l''))\r\n    ->leftJoin(array(''listings_package''=>''lp''),''l.package_id=lp.id'',array(''id as p_id'',''name as p_name''))\r\n ->leftJoin(array(''web_users''=>''u''),''l.user_id=u.id'',array(''id as u_id'',''username'',''firstName'',''lastName''))\r\n    ->where("l.active=1")\r\n   ->where("l.paid=1")->create();\r\n  \r\n\r\n\r\n    //->fetchAll();\r\n//print_r($packages);\r\n$listings = $packages->fetchAll();\r\n//print_r($listings);\r\nforeach($listings as $p){\r\n    $output .= "<p>".$p[''title'']."</p>";\r\n}\r\n\r\n$output .= ''$db = new ExtDB($etomite);\r\n\r\n$packages = $db->select(array(\\''listings\\''=>\\''l\\''))\r\n   ->leftJoin(array(\\''listings_package\\''=>\\''lp\\''),\\''l.package_id=lp.id\\'',array(\\''id as p_id\\'',\\''name as p_name\\''))\r\n ->leftJoin(array(\\''web_users\\''=>\\''u\\''),\\''l.user_id=u.id\\'',array(\\''id as u_id\\'',\\''username\\'',\\''firstName\\'',\\''lastName\\''))\r\n    ->where("l.active=1")\r\n   ->where("l.paid=1")->create();\r\n$listings = $packages->fetchAll();\r\n    '';\r\n \r\nreturn $output;', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (111, 'sitemap', 'Custom Sitemap', '$parent = 0;\r\n$children = $etomite->getActiveChildren($parent, "menuindex", "ASC", "id, editedon, searchable, pagetitle , alias");\r\n       \r\nif($children){\r\n  $output = "<ul class=''sitemap''>";\r\n foreach($children as $c){\r\n       if($c[''id'']==1){\r\n          $output .= "<li><a href=''".$ETOMITE_PAGE_BASE[''www'']."''>".$c[''pagetitle'']."</a></li>";\r\n        }elseif($c[''alias'']==''listings''){\r\n           $output .= "<li><a href=''".$etomite->makeUrl('''',''listings'',array())."''>".$c[''pagetitle'']."</a>";\r\n                // output the categories\r\n                $catsArray = $etomite->getIntTableRows($fields="*",$from="listings_category",$where="parent=1",''sort_order'',''ASC'');\r\n             if($catsArray){\r\n             $output .= "<ul>";\r\n              foreach($catsArray as $cat){\r\n                    $output .= "<li><a href=''".$etomite->makeUrl('''',''listings'',array(''category''=>$cat[''furl'']))."''>".$cat[''name'']."</a></li>";\r\n              }\r\n               $output .= "</ul>";\r\n             }\r\n           $output .= "</li>";\r\n     }else{\r\n          $output .= "<li><a href=''".$ETOMITE_PAGE_BASE[''www'']."/".$c[''alias'']."/''>".$c[''pagetitle'']."</a></li>";\r\n     }\r\n   }\r\n   $output .= "</ul>";\r\n}\r\n\r\nreturn $output;', 0, 1);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES 
(112, '831_gallery_manager', '', '// Gallery\r\n\r\nif($_SESSION[''validated'']!=1 && $_SESSION[''role'']!=1){\r\n  $etomite->sendRedirect($etomite->makeUrl(13,'''',array()));\r\n}\r\n\r\n$thumbnail_widths = array(120,207,800); // 120 = thumb, 212 = slide for front (height will be 266 and use the zc=C crop function), 640 = full image size\r\n$capture_raw_data = false;\r\n\r\n$dir = $etomite->config[''absolute_base_path'']."assets/gallery/";\r\n$www = $etomite->config[''www_base_path'']."assets/gallery/";\r\n$orig = "original/"; // original image dir\r\n$thumbs = "thumbs/"; // thumbs dir \r\n$full = "full/"; // full size image dir\r\n$slides = "slides/"; // slides images\r\n$slide_height = 300; // slide height\r\n\r\n// set all items to slide\r\n$slideFlag = true;\r\n\r\n\r\n$req = $etomite->_request; // get paramaters\r\nif(empty($req)){\r\n   $req = $_POST;\r\n}\r\n\r\n$docId = $etomite->documentIdentifier;\r\n\r\n$action = (isset($req[''action'']) && !empty($req[''action''])) ? $req[''action'']:''view'';\r\n$added = (isset($req[''added'']) && !empty($req[''added''])) ? $req[''added'']:'''';\r\n$gId = (isset($req[''gId'']) && !empty($req[''gId''])) ? $req[''gId'']:'''';\r\n$editing = (isset($req[''editing'']) && !empty($req[''editing''])) ? $req[''editing'']:'''';\r\n$deleteConf = (isset($req[''deleteConf'']) && !empty($req[''deleteConf''])) ? $req[''deleteConf'']:'''';\r\n$galId = (isset($req[''galId'']) && !empty($req[''galId''])) ? $req[''galId'']:'''';\r\n\r\nif(empty($galId) && empty($_SESSION[''galId''])){\r\n  $galId = $docId;\r\n}elseif(!empty($galId)){\r\n    $galId = $galId;\r\n}elseif(!empty($_SESSION[''galId''])){\r\n  $galId = $_SESSION[''galId''];\r\n}\r\n\r\n// set the galId to a session variable\r\n$_SESSION[''galId''] = $galId;\r\n\r\n$error = '''';\r\n$success = '''';\r\n\r\n$menu = "<a href=''[~".$docId."~]/action/upload''>Upload Files</a> | <a href=''[~".$docId."~]/action/create''>Process Gallery Items</a> | <a href=''[~".$docId."~]/action/view''>View Gallery Items</a>";\r\n$output = "<style type=''text/css''> \r\n.gall-manager {font-family:arial;font-size:12px;}\r\n.gall-manager .success {background-color:#ECFFD4; padding:10px; color:#999; border:#47DA15 solid 1px;}\r\n.gall-manager .success p, .gall-manager .error p { margin:0; }\r\n.gall-manager .error {background-color:#FFE1EA; padding:10px; color:#999; border:#F00 solid 1px;} \r\n.gall-list th { font-size:14px; font-weight:bold; background-color:#ccc; }\r\n.gall-list img { border:#000 solid 1px; }\r\n\r\n</style>";\r\n$output .= "<div class=''gall-manager''>";\r\n\r\n$output .= "<h2>Gallery Manager</h2>";\r\n$output .= "<p class=''gall-manager-menu''>".$menu."</p>";\r\n\r\n// check for error and success messages\r\nif(isset($_SESSION[''gall_success'']) && !empty($_SESSION[''gall_success''])){\r\n  $output .="<div class=''success''>".$_SESSION[''gall_success'']."</div>"; \r\n}\r\n\r\nif(isset($_SESSION[''gall_error'']) && !empty($_SESSION[''gall_error''])){\r\n   $output .="<div class=''error''>".$_SESSION[''gall_error'']."</div>";\r\n}\r\n\r\nunset($_SESSION[''gall_success'']);\r\nunset($_SESSION[''gall_error'']);\r\n\r\nswitch($action){\r\n\r\n  case ''view'':\r\n  default:\r\n        // need to check for order submit\r\n       if(isset($_POST[''submit'']) && $_POST[''submit'']=="Save Order" && !empty($_SESSION[''gItems''])){\r\n         $ids = $_SESSION[''gItems''];\r\n           foreach($ids as $id){\r\n               $order = 0;\r\n             if(isset($_POST[''item_''.$id.''_order'']) && !empty($_POST[''item_''.$id.''_order''])){\r\n                    $order = $_POST[''item_''.$id.''_order''];\r\n              }\r\n               $result = $etomite->updIntTableRows($fields=array(''gal_order''=>$order),$into="831gallery",$where=''id=''.$id);\r\n            }\r\n       \r\n        }\r\n       \r\n        $_SESSION[''gItems''] = array();\r\n    \r\n        $output .= "<p>Please use the navigation above to manage your gallery!</p>";\r\n        $gItems = array();\r\n      // make the list of current items\r\n       $output .= "<form method=''post''name=''orderForm'' action=''''>";\r\n      $output .= "<table width=''100%'' cellpadding=''3'' border=''1'' class=''gall-list''>";\r\n     $output .= "<tr><td colspan=''5'' align=''right''><input type=''submit'' name=''submit'' value=''Save Order'' /></td></tr>";\r\n        \r\n        if($items = $etomite->getIntTableRows($fields="*",$from="831gallery",$where="docId=".$galId,$sort=''gal_order'',$dir=''ASC'')){\r\n         if(count($items)>0){\r\n                $output .= "<tr><th>Image</th><th>Information</th><th>Add to Slides</th><th>Order</th><th>Actions</th></tr>";\r\n               foreach($items as $item){\r\n                   $gItems[] = $item[''id''];\r\n                  $output .= "<tr>";\r\n                  $output .= "<td align=''center'' width=''150'' valign=''middle''><img src=''".$www.$thumbs."th_".$item[''fn'']."'' border=''0'' /></td>";\r\n                   $output .= "<td valign=''top''><p><strong>Title:</strong>".stripslashes($item[''title''])."</p><p><strong>Description:</strong><br />".stripslashes($item[''description''])."</p></td>";\r\n                    \r\n                    $output .= "<td valign=''middle'' align=''center'' width=''75''>";\r\n                  if($item[''slide'']==1){ $output .= "<span class=''slide-on''>YES</span>"; }else{ $output .= "&nbsp;"; }\r\n                    $output .= "</td>";\r\n                 // order\r\n                    $output .= "<td valign=''middle'' align=''center'' width=''75''><input type=''text'' size=''5'' maxchars=''2'' name=''item_".$item[''id'']."_order'' value=''".$item[''gal_order'']."'' /></td>";\r\n                   // actions for the item\r\n                 $editLink = $etomite->makeUrl($docId,'''',array(''action''=>''edit'',''gId''=>$item[''id''],''added''=>1));\r\n                 $delLink = $etomite->makeUrl($docId,'''',array(''action''=>''delete'',''gId''=>$item[''id'']));\r\n                 $output .= "<td align=''center'' width=''75''><a href=''".$editLink."''>EDIT</a><br /><a href=''".$delLink."''>DELETE</a></td>";\r\n                    $output .= "</tr>";\r\n             }\r\n           }\r\n       }else{\r\n          $output .= "<tr><td><p>Sorry there are no gallery items!</p><p>Please upload some images!</p></td></tr>";\r\n       }\r\n       \r\n        $_SESSION[''gItems''] = $gItems;\r\n        \r\n        $output .= "</table>";\r\n      $output .= "</form>";\r\n   break;\r\n  \r\n    case ''edit'':\r\n      if(!empty($gId)){\r\n       $_SESSION[''gItems_added''] = array($gId);\r\n      $url = $etomite->makeUrl($docId,'''',array(''action''=>''multi-edit'',''added''=>1,''editing''=>''1''));\r\n        $etomite->sendRedirect($url); // redirect to the edit script\r\n        }else{\r\n          $_SESSION[''gall_error''] = "<p>You are not supposed to be here, or that is not a valid Item ID!</p>";\r\n          $etomite->sendRedirect($etomite->makeUrl($docId,'''',array()));\r\n     }\r\n   break;\r\n  \r\n    case ''delete'':\r\n        if(!empty($gId)){\r\n           if(!isset($deleteConf) || $deleteConf!=1){\r\n              // show them a confirmation button\r\n              $output .= "<h3>Are you sure you want to DELETE this gallery item?</h3>";\r\n               $cancelLink = $etomite->makeUrl($docId,'''',array());\r\n               $confLink = $etomite->makeUrl($docId,'''',array(''action''=>''delete'',''gId''=>$gId,''deleteConf''=>1));\r\n               $output .= "<p><a href=''".$confLink."''>YES</a> &nbsp; &nbsp; <a href=''".$cancelLink."''>NO</a></p>";\r\n         }else{\r\n              // delete it\r\n                // get item\r\n             $result = $etomite->getIntTableRows($fields="*",$from="831gallery",$where="id=".$gId);\r\n              if(count($result)>0){\r\n                   $item = $result[0];\r\n                 //delete the images\r\n                 @unlink($dir.$full.$item[''fn'']);\r\n                  @unlink($dir.$slides.$item[''fn'']);\r\n                    @unlink($dir.$thumbs."th_".$item[''fn'']);\r\n                  if($result = $etomite->dbQuery("delete from ".$etomite->db."831gallery where id=".$gId)){\r\n                       $_SESSION[''gall_success''] = "<p>Gallery Item with ID: ".$gId." was removed!</p>";\r\n                                     $etomite->sendRedirect($etomite->makeUrl($docId,'''',array()));\r\n                 }else{\r\n                      $_SESSION[''gall_error''] = "<p>Gallery Item with ID: ".$gId." was not removed!</p>";\r\n                                           $etomite->sendRedirect($etomite->makeUrl($docId,'''',array()));\r\n                 }\r\n               \r\n                }else{\r\n                  $_SESSION[''gall_error''] = "<p>That is not a valid Item ID!</p>";\r\n                          $etomite->sendRedirect($etomite->makeUrl($docId,'''',array()));\r\n             }\r\n           }\r\n       }else{\r\n          $_SESSION[''gall_error''] = "<p>You are not supposed to be here, or that is not a valid Item ID!</p>";\r\n                  $etomite->sendRedirect($etomite->makeUrl($docId,'''',array()));\r\n     }\r\n   \r\n    break;\r\n\r\n  case ''upload'': // create the uploader\r\n     $output .= $etomite->parseChunk(''fancy_uploader'', array(''ch''=>''''));\r\n   break;\r\n\r\n  case ''create'':\r\n// CREATE GALLERY ITEMS\r\n\r\nrequire_once($etomite->config[''absolute_base_path''].''assets/phpThumb/phpthumb.class.php'');\r\n\r\n// create phpThumb object\r\n$phpThumb = new phpThumb();\r\n\r\n\r\n// get the files\r\n$imgs = array();\r\nif ($handle = opendir($dir.$orig)) {\r\n\r\n    /* This is the correct way to loop over the directory. */\r\n    while (false !== ($file = readdir($handle))) {\r\n    if($file != "." && $file != ".." && !is_dir($file)){\r\n        $imgs[] = $file; // put the file names into an array to go over later\r\n   }// end if for files\r\n    }\r\n\r\n    closedir($handle);\r\n}\r\n\r\n// loop through the files\r\n\r\nini_set(''display_errors'',''On'');\r\n\r\n$output .= "<p>There are ".count($imgs)." images to add to the gallery</p>";\r\n$added = array();\r\nforeach($imgs as $im){\r\n\r\n //echo $im."<br />";\r\n    // run them through the php thumb\r\n   $file_name = '''';\r\n  $file_name = explode(".",$im); // 0 = name, 1 = ext\r\n $z = 1;\r\n foreach($thumbnail_widths as $thumbnail_width){\r\n     $www_filename = '''';\r\n       $output_filename = '''';\r\n        $fn = '''';\r\n\r\n     $phpThumb->resetObject();\r\n       $phpThumb->setSourceFilename($dir.$orig.$im);\r\n       $phpThumb->setParameter(''w'', $thumbnail_width);\r\n       $phpThumb->setParameter(''config_output_format'', ''jpg'');\r\n     //$phpThumb->setParameter(''config_imagemagick_path'', ''/usr/local/bin/convert'');\r\n     if($thumbnail_width == 120){\r\n            $output_filename = $dir.$thumbs."th_".$file_name[0].".".$phpThumb->config_output_format;\r\n            $www_filename = $www.$thumbs."th_".$file_name[0].".".$phpThumb->config_output_format;\r\n           $fn = $file_name[0].".".$phpThumb->config_output_format;\r\n\r\n        }elseif($thumbnail_width == 207){\r\n           $phpThumb->setParameter(''h'', $slide_height); // slide height\r\n          $phpThumb->setParameter(''zc'', ''C''); // crop\r\n         $output_filename = $dir.$slides.$file_name[0].".".$phpThumb->config_output_format;\r\n                        $www_filename = $www.$slides.$file_name[0].".".$phpThumb->config_output_format;\r\n                        $fn = $file_name[0].".".$phpThumb->config_output_format;\r\n\r\n       }else{\r\n          $output_filename = $dir.$full.$file_name[0].".".$phpThumb->config_output_format;\r\n            $www_filename = $www.$full.$file_name[0].".".$phpThumb->config_output_format;\r\n           $fn = $file_name[0].".".$phpThumb->config_output_format;\r\n\r\n        }\r\n       \r\n        if ($phpThumb->GenerateThumbnail()) {\r\n           $output_size_x = ImageSX($phpThumb->gdimg_output);\r\n          $output_size_y = ImageSY($phpThumb->gdimg_output);\r\n          if ($output_filename || $capture_raw_data) {\r\n                if ($capture_raw_data && $phpThumb->RenderOutput()) {\r\n               // RenderOutput renders the thumbnail data to $phpThumb->outputImageData, not to a file or the browser\r\n              } elseif ($phpThumb->RenderToFile($output_filename)) {\r\n              // do something on success\r\n                  //echo ''Successfully rendered:<br><img src="''.$www_filename.''">'';\r\n                   $output .= "File: ".$output_filename." was created!<br />";\r\n                 if($z==1){\r\n                  // check for slide flag\r\n                 if($slideFlag){ $sf = ''1''; }else{ $sf = ''0''; }\r\n                  if($result = $etomite->putIntTableRow($fields=array(''fn''=>$fn,''slide''=>$sf,''docId''=>$galId),$into="831gallery")){\r\n                     // added to db\r\n                      $insId = $etomite->insertId();\r\n                      if(!in_array($insId,$added)){\r\n                           $added[] = $insId;\r\n                      }\r\n                   }\r\n                   }\r\n               } else {\r\n                // do something with debug/error messages\r\n                   //echo ''Failed (size=''.$thumbnail_width.''):<pre>''.implode("\\n\\n", $phpThumb->debugmessages).''</pre>'';\r\n                   $output .= "There was an error creating file: ".$output_filename."<br />";\r\n              }\r\n           } else {\r\n                $phpThumb->OutputThumbnail();\r\n           }\r\n       }// end if for generate thumb\r\n       else {\r\n          // do something with debug/error messages\r\n           // echo ''Failed (size=''.$thumbnail_width.'').<br>'';\r\n          // echo ''<div style="background-color:#FFEEDD; font-weight: bold; padding: 10px;">''.$phpThumb->fatalerror.''</div>'';\r\n         // echo ''<form><textarea rows="10" cols="60" wrap="off">''.htmlentities(implode("\\n* ", $phpThumb->debugmessages)).''</textarea></form><hr>'';\r\n        }\r\n       $z++;\r\n   }// end foreach on widths\r\n   \r\n    @unlink($dir.$orig.$im); // delete the uploaded image.\r\n\r\n}// end foreach for imgs\r\n\r\n// END CREATE GALLERY IMAGES\r\n\r\n      if(count($added)>0){\r\n        \r\n            // need a redirect here to the multi edit form\r\n          //print_r($added);\r\n          $multiEdit = $etomite->makeUrl($docId,'''',array(''action''=>''multi-edit'',''added''=>count($added)));\r\n         $_SESSION[''gItems_added''] = $added;\r\n           \r\n            $etomite->sendRedirect($multiEdit);\r\n     \r\n        }else{\r\n          // no files added\r\n           $output .= "<p>Sorry no Images were added to the gallery!</p>";\r\n     }\r\n\r\n   break; // end create\r\n    \r\n    case ''multi-edit'':\r\n        if(isset($added) && $added>0 && isset($_SESSION[''gItems_added'']) && count($_SESSION[''gItems_added''])>0){\r\n            if(!isset($_POST[''submit'']) || empty($_POST[''submit''])){\r\n                // lets loop through the items to be added\r\n              $ids = $_SESSION[''gItems_added''];\r\n             $output .= "<h2>Edit Processed Items</h2>";\r\n             $output .= "<form method=''post'' action=''".$etomite->makeUrl($docId,'''',array(''action''=>''multi-edit'',''added''=>$added))."'' name=''multi-edit-items''>";\r\n                $output .= "<table width=''100%'' cellpadding=''10'' border=''1''>";\r\n                foreach($ids as $id){\r\n                   if($item = $etomite->getIntTableRows($fields="*",$from="831gallery",$where="id=".$id)){\r\n                 // setup image the image\r\n                    $output .= "<tr><td align=''center'' valign=''top''><img src=''".$www.$thumbs."th_".$item[0][''fn'']."'' border=''1'' /></td>";\r\n                 $output .= "<td align=''left'' valign=''top''>";\r\n                    // set the form elements\r\n                    $title = $item[0][''title''];\r\n                   $description = $item[0][''description''];\r\n                   if($item[0][''slide'']==1){ $slideC = '' checked="checked"''; }\r\n                 \r\n                    $output .= "<p><strong>Title:</strong> <input type=''text'' name=''item_".$id."_title'' size=''35'' maxchars=''250'' value=''".$title."'' /></p>\r\n                    <p><strong>Add to Front Slide Show:</strong> <input type=''checkbox'' name=''item_".$id."_slide'' value=''1''".$slideC." /></p>\r\n                 <p><strong>Description:</strong><br /><textarea name=''item_".$id."_description'' rows=''4'' cols=''25''>".$description."</textarea></p>\r\n                    ";\r\n                  $output .= "</tr>";\r\n                 }// end if for item\r\n             }\r\n               $output .= "<tr><td colspan=''2''><input type=''submit'' name=''submit'' value=''Submit'' /></td></tr>";\r\n                $output .= "</table>";  \r\n                $output .= "</form>";\r\n           }else{ // process the forms\r\n         \r\n                // loop through the ids in the session\r\n              $ids = $_SESSION[''gItems_added''];\r\n             $i = 0;\r\n             foreach($ids as $id){\r\n               \r\n                    // check id first\r\n                   $items = $etomite->getIntTableRows($fields=''id'',$from=''831gallery'',$where=''id=''.$id);\r\n                 \r\n                    if(count($items)>0 && !empty($items[0][''id''])){ // item exists\r\n                        \r\n                        $title = (isset($_POST[''item_''.$id.''_title'']) && !empty($_POST[''item_''.$id.''_title''])) ? mysql_real_escape_string($_POST[''item_''.$id.''_title'']):'''';\r\n                       $description = (isset($_POST[''item_''.$id.''_description'']) && !empty($_POST[''item_''.$id.''_description''])) ? mysql_real_escape_string($_POST[''item_''.$id.''_description'']):'''';\r\n                       $slide = (isset($_POST[''item_''.$id.''_slide'']) && $_POST[''item_''.$id.''_slide'']==1) ? ''1'':''0'';\r\n                        if($result = $etomite->updIntTableRows($fields=array(''title''=>$title,''description''=>$description,''slide''=>$slide),$into="831gallery",$where=''id=''.$id)){\r\n                            \r\n                            $success .= "<p>Item with ID: ".$id." was updated!</p>";\r\n                            $i++;\r\n                       }else{\r\n                          $error .= "<p>Item with ID: ".$id." was not updated!</p>";\r\n                      }\r\n                       \r\n                    }// end if for item check\r\n                   else{\r\n                       $error .= "<p>There no gallery item with the ID: ".$id."</p>";\r\n                  }\r\n                   \r\n                } // end foreach on the ids\r\n             \r\n                $success = "<p>Total Items Updated: ".$i."</p>".$success;\r\n               $_SESSION[''gall_error''] = $error;\r\n                 $_SESSION[''gall_success''] = $success;\r\n                 unset($_SESSION[''gItems_added'']);\r\n                 \r\n                    $etomite->sendRedirect($etomite->makeUrl($docId,'''',array()));\r\n         \r\n            }\r\n       }else{\r\n          // not supposed to be here\r\n          $output .= "<p>You don''t have any gallery items to edit!</p>";\r\n     }\r\n       \r\n        \r\n    break;\r\n  \r\n}// end switch\r\n\r\n$output .= "</div><!-- end gall-manager div -->";\r\n\r\nreturn $output;', 0, 3);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES 
(113, '831_gallery', '', '$dir = $etomite->config[''absolute_base_path'']."assets/gallery/";\r\n$www = $etomite->config[''www_base_path'']."assets/gallery/";\r\n$orig = "original/"; // original image dir\r\n$thumbs = "thumbs/"; // thumbs dir \r\n$full = "full/"; // full size image dir\r\n$slides = "slides/"; // slides images\r\n$slide_height = 300; // slide height\r\n\r\n$loginId = 13; // id of the login document\r\n$manageId = 12; // id of the document that you put the manager snippet in\r\n$docId = $etomite->documentIdentifier;\r\n\r\n$columns = 3;\r\n$output = '''';\r\n\r\n// check db for gallery images\r\n\r\nif($result = $etomite->getIntTableRows($fields="*",$from="831gallery",$where=''docId=''.$docId,$sort=''gal_order'',$dir=''ASC'')){\r\n \r\n    $z=1;\r\n   $output .= "<table width=''100%'' cellpadding=''5'' class=''gallery-table''>\\n";\r\n       foreach($result as $item){\r\n          if($z==1){ $output .= "<tr>\\n"; }\r\n          $output .= "<td valign=''top'' align=''center''><a title=''".htmlentities(stripslashes($item[''title'']),ENT_QUOTES)."<br />".htmlentities(stripslashes($item[''description'']),ENT_QUOTES)."'' href=''".$www.$full.$item[''fn'']."'' rel=''lightbox[group]''><img alt=''".stripslashes($item[''title''])."'' src=''".$www.$thumbs."th_".$item[''fn'']."'' border=''0'' /></a><p style=''text-align:center;''>".stripslashes($item[''title''])."<br />".stripslashes($item[''description''])."</p></td>\\n";\r\n            if($z==$columns){ $output .= "</tr>\\n"; $z=0; }\r\n            $z++;\r\n       }\r\n   \r\n    if($z>1){\r\n   for($i=$z;$i<=$columns;$i++){\r\n       $output .= "<td>&nbsp;</td>";\r\n   }\r\n   if($z<$columns){ $output .= "</tr>\\n"; }\r\n   }\r\n   \r\n    $output .= "</table>\\n";\r\n   \r\n}else{\r\n  $output .= "<p>Currently there are no gallery images!</p>";\r\n}\r\nif($_SESSION[''validated'']!=1 && $_SESSION[''role'']!=1){\r\n  $output .= "<p>&nbsp;</p><p>&nbsp;</p><p align=''center''><a href=''".$etomite->makeUrl($loginId,'''',array())."''>Login to Manage Gallery</a></p>";\r\n}else{\r\n  $output .= "<p>&nbsp;</p><p>&nbsp;</p><div align=''center''><p style=''font-size:14px;''><a href=''".$etomite->makeUrl($manageId,'''',array(''galId''=>$docId))."''>Manage Gallery</a></p>".''<form method="post" action="''.$etomite->makeUrl($loginId,'''',array()).''">\r\n      <input type="submit" value="Logout" name="logout"> [ admin ]\r\n    </form>''."</div>";\r\n}\r\n\r\n\r\nreturn $output;\r\n', 0, 3);

INSERT INTO `{PREFIX}site_snippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES 
(114, '831_gallery_slider', '', '$www = $etomite->config[''www_base_path'']."assets/gallery/";\r\n$slidesDir = "slides/"; // slides images folder\r\n\r\n$totalBoxes = 1; // slide boxes on the front. (make sure you adjust your css to handle this)\r\n$totalSlides = 5; // total slides per box.\r\n\r\n$sliderChunk = "831_slider";\r\n\r\n$output = '''';\r\n$limit = $totalBoxes*$totalSlides; // total slides to get from the database\r\nini_set(''display_errors'',''On'');\r\n\r\nif($slides = $etomite->getIntTableRows($fields="*",$from="831gallery",$where="slide=1",$sort="RAND()",$dir="",$limit)){\r\n \r\n    if(count($slides)>0){\r\n       // need to check the totalBoxes again\r\n       $slideCount = count($slides);\r\n       $totalB = ceil($slideCount/$totalSlides);\r\n       if($totalB<$totalBoxes){ $totalBoxes=$totalB; }\r\n     $bC = 1; // box count\r\n       $z = 0; // slides array pointer\r\n     for($i=1;$i<=$totalBoxes;$i++){\r\n         if($bC==1){ $bx = ''''; }else{ $bx=$bC; } // for the first slide box we don''t use a count for the box\r\n          $output .= "<div class=''main-slide".$bC."''>"; // count always gets used on the main slide box\r\n         $slideOut = '''';\r\n           $slideNav = '''';\r\n           $sC = 1; // slide count\r\n         for($b=0;$b<$totalSlides;$b++){ // just loop the total slides amount\r\n                if($z<$slideCount){\r\n             $slideOut .= "<div class=''slide-wrapper''>\r\n            <img alt=''".$slide[''title'']."'' border=''0'' src=''".$www.$slidesDir.$slides[$z][''fn'']."'' />\r\n        </div>";\r\n                   $slideNav .= "<span class=''jFlowControl".$bx."''>".$sC."</span>";\r\n                  \r\n                    $sC++;\r\n                  $z++;\r\n                   }// end if for slide count to fix empty slides\r\n          } // end foreach for slides\r\n         $output .= $etomite->parseChunk($sliderChunk, array(''bC''=>$bx,''slideOut''=>$slideOut,''slideNav''=>$slideNav));\r\n          $output .= "</div>";\r\n            $bC++;\r\n      } // end for for boxes\r\n      \r\n    }\r\n\r\n}// end if for slides\r\n\r\n\r\n\r\nreturn $output;', 0, 3);


-- --------------------------------------------------------

--
-- Dumping data for table `{PREFIX}site_templates`
--

INSERT INTO `{PREFIX}site_templates` (`id`, `templatename`, `description`, `content`, `locked`) VALUES (1, 'AlexisPro Redux', 'Default template, designed by Helder :)', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" \r\n  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">\r\n<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">\r\n<head>\r\n  <title>[(site_name)] &raquo; [*pagetitle*]</title>\r\n  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />\r\n  [[GetKeywords]]\r\n  <style type="text/css">\r\n    @import url(''templates/AlexisProRedux/AlexisProRedux.css'');\r\n  </style>\r\n</head> \r\n\r\n<body>\r\n<table border="0" cellpadding="0" cellspacing="0" class="mainTable">\r\n  <tr class="fancyRow">\r\n    <td><span class="headers">&nbsp;<img \r\n      src="templates/AlexisProRedux/images/dot.gif" alt="" \r\n      style="margin-top: 1px;" />&nbsp;<a \r\n      href="[~[(site_start)]~]">[(site_name)]</a></span></td>\r\n    <td align="right"><span class="headers">[[PageTrail]]</span></td>\r\n  </tr>\r\n  <tr class="fancyRow2">\r\n    <td colspan="2" class="border-top-bottom smallText" \r\n      align="right">[[PoweredBy]]</td>\r\n  </tr>\r\n  <tr align="left" valign="top">\r\n    <td colspan="2"><table width="100%" border="0" \r\n      cellspacing="0" cellpadding="1">\r\n      <tr align="left" valign="top">\r\n        <td class="w22"><table width="100%" border="0" \r\n          cellpadding="0" cellspacing="0">\r\n          <tr>\r\n            <td align="center" valign="middle" class="logoBox"><a \r\n              href="[~[(site_start)]~]"><img \r\n              src="templates/AlexisProRedux/images/logoGradient.jpg" \r\n              width="131" height="121" alt="" /></a></td>\r\n          </tr>\r\n          <tr>\r\n            <td align="left" valign="top"><img \r\n              src="templates/AlexisProRedux/images/_tx_.gif" \r\n              height="4" alt="" /></td>\r\n          </tr>\r\n          <tr class="fancyRow2">\r\n            <td align="left" valign="top" class="navigationHead">Navigation</td>\r\n          </tr>\r\n          <tr style="padding: 0px; margin: 0px;">\r\n            <td align="left" valign="top" class="navigation" \r\n              style="padding: 0px; margin: 0px;"><img \r\n              src="templates/AlexisProRedux/images/_tx_.gif" \r\n              alt="" height="4" /><br />\r\n              [!MenuBuilder?id=0!]<img src="templates/AlexisProRedux/images/_tx_.gif" \r\n              height="4" alt="" /></td>\r\n          </tr>\r\n        </table></td>\r\n        <td class="pad" id="content">\r\n          <h1>[*longtitle*]</h1>\r\n          [*content*]</td>\r\n      </tr>\r\n    </table></td>\r\n  </tr>\r\n  <tr class="fancyRow2">\r\n    <td class="border-top-bottom smallText">&nbsp;</td>\r\n    <td class="border-top-bottom smallText" align="right">MySQL: \r\n      [^qt^], [^q^] request(s), PHP: [^p^], total: [^t^], \r\n      document retrieved from [^s^].</td>\r\n  </tr>\r\n</table>\r\n</body>\r\n</html>', 0);

INSERT INTO `{PREFIX}site_templates` (`id`, `templatename`, `description`, `content`, `locked`) VALUES (2, 'GoogleSiteMap_Template', 'Used to create a Google XML site map', '[!GoogleSiteMap_XML!]', 0);

INSERT INTO `{PREFIX}site_templates` (`id`, `templatename`, `description`, `content`, `locked`) VALUES (3, 'blank', '', '[*content*]', 0);

--
-- Dumping data for table `{PREFIX}site_htmlsnippets`
--

INSERT INTO `{PREFIX}site_htmlsnippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (1, 'W3C Validation', 'Displays W3C Markup Validation Service Icon/Link', '<p class="textcenter">\r\n    <a href="http://validator.w3.org/check?uri=referer"\r\n        onclick="window.open(''http://validator.w3.org/check?uri=referer''); return false;"><img\r\n        src="http://www.w3.org/Icons/valid-xhtml10"\r\n        alt="Valid XHTML 1.0 Strict" height="31" width="88" />\r\n    </a>\r\n    <a href="http://jigsaw.w3.org/css-validator/check/referer"\r\n        onclick="window.open(''http://jigsaw.w3.org/css-validator/check/referer''); return false;">\r\n        <img style="border:0;width:88px;height:31px"\r\n            src="http://jigsaw.w3.org/css-validator/images/vcss"\r\n            alt="Valid CSS!" />\r\n    </a>\r\n</p>', 0, 2);

INSERT INTO `{PREFIX}site_htmlsnippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (2, 'Javascript Example', '', '<span id="JSExample"></span>\r\n\r\n<script type="text/javascript">\r\n<!--\r\n  document.getElementById(''JSExample'').innerHTML = "[*description*]";\r\n//-->\r\n</script>\r\n', 0, 2);

INSERT INTO `{PREFIX}site_htmlsnippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (3, 'FixInertTags', 'Fixes inert tag appearance for documentation purposes.', '<script type="text/javascript">\r\n<!--\r\n\r\nfunction FixInertTags(t)\r\n{\r\n  /*\r\n  Created: 2008-04-18 by Ralph A. Dahlgren\r\n  Purpose: This function converts inert tags, containing un-natural spaces,\r\n  which have been entered for demonstation purposes, into natural looking\r\n  inert tags by using a blank image.\r\n  */\r\n  \r\n  // check to see if an element id was sent\r\n  if(t != null)\r\n  {\r\n    // if an id was sent, use that element\r\n    var e = document.getElementById(t);\r\n  }\r\n  else\r\n  {\r\n    // if no id was sent, use the body element\r\n    var e = document.getElementsByTagName(''body'')[0];\r\n  }\r\n\r\n  // fetch the element contents\r\n  var s = e.innerHTML;\r\n\r\n  // define the blank image to use, with width and height set to zero\r\n  var b = ''<img src="manager/media/images/blank.png" style="width:0; height:0;" alt="blank"/>'';\r\n\r\n  // perform sequential replacements\r\n  s = s.replace(/\\[ \\!/g, ''[''+b+''!'');\r\n  s = s.replace(/\\! \\]/g, ''!''+b+'']'');\r\n  s = s.replace(/\\[ \\[/g, ''[''+b+''['');\r\n  s = s.replace(/\\] \\]/g, '']''+b+'']'');\r\n  s = s.replace(/\\{ \\{/g, ''{''+b+''{'');\r\n  s = s.replace(/\\} \\}/g, ''}''+b+''}'');\r\n  s = s.replace(/\\[ \\*/g, ''[''+b+''*'');\r\n  s = s.replace(/\\* \\]/g, ''*''+b+'']'');\r\n  s = s.replace(/\\[ \\(/g, ''[''+b+''('');\r\n  s = s.replace(/\\) \\]/g, '')''+b+'']'');\r\n  s = s.replace(/\\[ \\~/g, ''[''+b+''~'');\r\n  s = s.replace(/\\~ \\]/g, ''~''+b+'']'');\r\n  s = s.replace(/\\{ \\!/g, ''{''+b+''!'');\r\n  s = s.replace(/\\! \\}/g, ''!''+b+''}'');\r\n  s = s.replace(/\\[ \\^/g, ''[''+b+''^'');\r\n  s = s.replace(/\\^ \\]/g, ''^''+b+'']'');\r\n\r\n  // write modified contents back to element\r\n  e.innerHTML = s;\r\n}\r\n\r\n// call the fixInertTags function\r\nFixInertTags();\r\n\r\n//-->\r\n</script>', 0, 2);

INSERT INTO `{PREFIX}site_htmlsnippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES (32, 'registration_form', 'Member Registration Form', '<script type="text/javascript">\r\n\r\nvar pwFlag = false; // password flag\r\nvar unFlag = false; // username flag\r\n\r\nfunction validateForm(){\r\n var inputs = $(''member'').getElements();\r\n   var error = null;\r\n   for(var i=0;i<inputs.length;i++){\r\n       if(inputs[i].hasClassName(''required'')){ // do some checking\r\n           if(inputs[i].id!=''frm_username'' && inputs[i].id!=''frm_password'' && inputs[i].id!=''pass_conf''){ // don''t check the username or password fields\r\n                var id = inputs[i].id;\r\n              var label = $(''label-''+id).innerHTML;\r\n             if(inputs[i].getValue()=='''' || inputs[i].getValue()==null){\r\n                   if(error==null){ error = ''''; }\r\n                    error += label+" is REQUIRED\\n";\r\n               }\r\n           }\r\n       }\r\n   }// end for\r\n \r\n    if(unFlag==false){ error += "Please Check your Username\\n"; }\r\n  if(pwFlag==false){ error += "Please Check your Password!\\n"; }\r\n \r\n    if(error!=null){\r\n        alert(error);\r\n       return false;\r\n   }\r\n   return true;\r\n}\r\n\r\n// check username\r\nfunction checkUser(){\r\n var u = $(''frm_username'').getValue() || "";\r\n   \r\n    // do some ajax to check\r\n    if(u=="" || u==" "){  \r\n      $(''unameCheck'').removeClassName(''uname-success'');\r\n       $(''unameCheck'').addClassName(''uname-error'');\r\n        $(''unameCheck'').update("Username is EMPTY");\r\n      return false; \r\n  }\r\n   new Ajax.Request(''/check-username'',\r\n     {\r\n     method:''post'',\r\n        parameters: {username: u},\r\n      onSuccess: function(transport){\r\n       var response = transport.responseText.evalJSON() || "no response text";\r\n         if(response.error!=""){ // that username is not available\r\n         $(''unameCheck'').removeClassName(''uname-success'');\r\n           $(''unameCheck'').addClassName(''uname-error'');\r\n            $(''unameCheck'').update(response.error);\r\n         }else{ // username available\r\n          $(''unameCheck'').removeClassName(''uname-error'');\r\n         $(''unameCheck'').addClassName(''uname-success'');\r\n          $(''unameCheck'').update(response.success);\r\n         unFlag = true;\r\n        }\r\n       \r\n      },\r\n      onFailure: function(){ alert(''Something went wrong...'') }\r\n   });\r\n}\r\n\r\n// check password confirmation\r\nfunction checkPassConf(){\r\n   var p = $(''frm_password'').getValue();\r\n var p2 = $(''pass_conf'').getValue();\r\n   if(p.length>14 || p.length<6){\r\n      $(''passLen'').addClassName(''conf-error'');\r\n        $(''passLen'').update("Password must be 6-14 characters!"); \r\n    }else{\r\n      $(''passLen'').removeClassName(''conf-error'');\r\n     $(''passLen'').update("( 6-14 characters )");\r\n   }\r\n   if(p=="" || p2=="" || p==" " || p2==" "){ return false; }\r\n   if(p2 != p){\r\n        $(''confCheck'').removeClassName(''conf-success'');\r\n     $(''confCheck'').addClassName(''conf-error'');\r\n      $(''confCheck'').update("Password values do not match!");   \r\n    }else{\r\n      $(''confCheck'').removeClassName(''conf-error'');\r\n       $(''confCheck'').addClassName(''conf-success'');\r\n        $(''confCheck'').update("Password values match!");\r\n      pwFlag = true;\r\n  }\r\n}\r\n\r\n</script>\r\n{error}\r\n<p><strong><em><u>Fields marked with "*" are REQUIRED!</u></em></strong></p>\r\n<form method="post" name="member" id="member" action="{form_action}" onsubmit="return validateForm();">\r\n<table width="100%" border="0" cellspacing="0" cellpadding="5">\r\n    <tr>\r\n        <td><strong>Username *:</strong></td>\r\n       <td><input class="required" type="text" id="frm_username" name="frm_username" value="{frm_username}" onkeyup="checkUser();" /> <span id="unameCheck" class="uname-error">( 6-14 characters )</span><!-- check using JS --></td>\r\n </tr>\r\n   <tr>\r\n        <td><strong>Password *:</strong></td>\r\n       <td><input type="password" name="frm_password" id="frm_password" value="" maxlength="14" onkeyup="checkPassConf();" /> <span id="passLen" class="">( 6-14 characters )</span></td>\r\n  </tr>\r\n   <tr>\r\n        <td><strong>Confirm Password *:</strong></td>\r\n       <td><input type="password" name="pass_conf" id="pass_conf" value="" maxlength="14" onkeyup="checkPassConf();" /> <span id="confCheck" class="conf-error"></span><!-- check using JS --></td>\r\n    </tr>\r\n   <tr>\r\n        <td><strong>First Name *:</strong><label id="label-frm_firstName" for="frm_firstName">First Name</label></td>\r\n       <td><input class="required" id="frm_firstName" type="text" name="frm_firstName" value="{frm_firstName}" maxlength="100" /></td>\r\n </tr>\r\n   <tr>\r\n        <td><strong>Last Name *:</strong><label id="label-frm_lastName" for="frm_lastName">Last Name</label></td>\r\n       <td><input class="required" id="frm_lastName" type="text" name="frm_lastName" value="{frm_lastName}" maxlength="100" /></td>\r\n    </tr>\r\n   <tr>\r\n        <td><strong>Email *:</strong><label id="label-frm_email" for="frm_email">Email</label></td>\r\n     <td><input class="required" id="frm_email" type="text" name="frm_email" value="{frm_email}" maxlength="100" /></td>\r\n </tr>\r\n   <tr>\r\n        <td><strong>Phone # *:</strong><label id="label-frm_phone" for="frm_phone">Phone #</label></td>\r\n     <td><input class="required" id="frm_phone" type="text" name="frm_phone" value="{frm_phone}" maxlength="14" /></td>\r\n  </tr>\r\n   <tr>\r\n        <td><strong>Phone # 2 :</strong></td>\r\n       <td><input id="frm_phone2" type="text" name="frm_phone2" value="{frm_phone2}" maxlength="14" /> ( optional 2nd number. ex: Toll Free number )</td>\r\n  </tr>\r\n   <tr>\r\n        <td><strong>Address *:</strong><label id="label-frm_address" for="frm_address">Address</label></td>\r\n     <td><input class="required" id="frm_address" type="text" name="frm_address" value="{frm_address}" maxlength="100" /></td>\r\n   </tr>\r\n   <tr>\r\n        <td><strong>City *:</strong><label id="label-frm_city" for="frm_city">City</label></td>\r\n     <td><input class="required" id="frm_city" type="text" name="frm_city" value="{frm_city}" maxlength="100" /></td>\r\n    </tr>\r\n   <tr>\r\n        <td><strong>State *:</strong><label id="label-frm_state" for="frm_state">State</label></td>\r\n     <td><select class="required" id="frm_state" name="frm_state">{frm_state}</select></td>\r\n  </tr>\r\n   <tr>\r\n        <td><strong>Zip Code *:</strong><label id="label-frm_zip" for="frm_zip">Zip Code</label></td>\r\n       <td><input class="required" id="frm_zip" type="text" name="frm_zip" value="{frm_zip}" maxlength="10" /> ( format: XXXXX or XXXXX-XXXX )</td>\r\n    </tr>\r\n   <tr>\r\n        <td colspan="2"><input type="submit" name="submit" value="Register" onclick="return validateForm();" /></td>\r\n    </tr>\r\n</table>\r\n</form>\r\n', 0, 2);

INSERT INTO `{PREFIX}site_htmlsnippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES
(36, 'jquery_head', '', '<script language="javascript" type="text/javascript" src="/js/jquery-1.3.2.min.js"></script>\r\n<script language="javascript" type="text/javascript" src="/js/new_update/jquery.flow.1.2.auto.js"></script>\r\n<link rel="stylesheet" href="/style.css" type="text/css" media="screen" />\r\n<script type="text/javascript">\r\n$(document).ready(function(){\r\n  $("#myController").jFlow({\r\n      slides: "#slides",\r\n      controller: ".jFlowControl", // must be class, use . sign\r\n       slideWrapper : "#jFlowSlide", // must be id, use # sign\r\n     selectedWrapper: "jFlowSelected",  // just pure text, no sign\r\n       auto: true,     //auto change slide, default true\r\n       width: "207px",\r\n     height: "300px",\r\n        duration: 800,\r\n      prev: ".jFlowPrev", // must be class, use . sign\r\n        next: ".jFlowNext", // must be class, use . sign\r\n        fade: true,\r\n     thumbs: false\r\n   });\r\n});\r\n</script>', 0, 4);

INSERT INTO `{PREFIX}site_htmlsnippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES 
(35, '831_slider', '', '<div class="jflow-content-slider{bC}">\r\n  <div id="slides{bC}">\r\n       {slideOut}\r\n      \r\n    </div>\r\n      \r\n    <div id="myController{bC}">\r\n     <span class="jFlowPrev{bC}">Prev</span>\r\n     {slideNav}\r\n      <span class="jFlowNext{bC}">Next</span>\r\n </div>\r\n  <div class="clear"></div>\r\n</div>', 0, 4);

INSERT INTO `{PREFIX}site_htmlsnippets` (`id`, `name`, `description`, `snippet`, `locked`, `section`) VALUES 
(34, 'fancy_uploader', '', '<!-- Framework CSS -->\r\n  <!-- <link rel="stylesheet" href="/assets/fancyupload/source/screen.css" type="text/css" media="screen, projection">\r\n    <link rel="stylesheet" href="/assets/fancyupload/source/print.css" type="text/css" media="print"> -->\r\n   <!--[if IE]><link rel="stylesheet" href="/assets/fancyupload/source/ie.css" type="text/css" media="screen, projection"><![endif]-->\r\n<!--[if lte IE 7]>\r\n   <script type="text/javascript" src="http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js"></script>\r\n<![endif]-->\r\n\r\n\r\n   <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/mootools/1.2.2/mootools.js"></script>\r\n\r\n  <script type="text/javascript" src="/assets/fancyupload/source/Swiff.Uploader.js"></script>\r\n\r\n <script type="text/javascript" src="/assets/fancyupload/source/Fx.ProgressBar.js"></script>\r\n\r\n <script type="text/javascript" src="/assets/fancyupload/source/Lang.js"></script>\r\n\r\n   <script type="text/javascript" src="/assets/fancyupload/source/FancyUpload2.js"></script>\r\n\r\n\r\n   <!-- See script.js -->\r\n  <script type="text/javascript">\r\n     //<![CDATA[\r\n\r\n     /**\r\n * FancyUpload Showcase\r\n *\r\n * @license     MIT License\r\n * @author       Harald Kirschner <mail [at] digitarald [dot] de>\r\n * @copyright   Authors\r\n */\r\n\r\nwindow.addEvent(''domready'', function() { // wait for the content\r\n\r\n    // our uploader instance \r\n   \r\n    var up = new FancyUpload2($(''demo-status''), $(''demo-list''), { // options object\r\n     // we console.log infos, remove that in production!!\r\n        verbose: true,\r\n      \r\n        // url is read from the form, so you just have to change one place\r\n      url: $(''form-demo'').action,\r\n       \r\n        // path to the SWF file\r\n     path: ''/assets/fancyupload/source/Swiff.Uploader.swf'',\r\n        \r\n        // remove that line to select all files, or edit it, add more items\r\n     typeFilter: {\r\n           ''Images (*.jpg, *.jpeg, *.gif, *.png)'': ''*.jpg; *.jpeg; *.gif; *.png''\r\n       },\r\n      \r\n        // this is our browse button, *target* is overlayed with the Flash movie\r\n        target: ''demo-browse'',\r\n        \r\n        // graceful degradation, onLoad is only called if all went well with Flash\r\n      onLoad: function() {\r\n            $(''demo-status'').removeClass(''hide''); // we show the actual UI\r\n          $(''demo-fallback'').destroy(); // ... and hide the plain form\r\n          \r\n            // We relay the interactions with the overlayed flash to the link\r\n           this.target.addEvents({\r\n             click: function() {\r\n                 return false;\r\n               },\r\n              mouseenter: function() {\r\n                    this.addClass(''hover'');\r\n               },\r\n              mouseleave: function() {\r\n                    this.removeClass(''hover'');\r\n                    this.blur();\r\n                },\r\n              mousedown: function() {\r\n                 this.focus();\r\n               }\r\n           });\r\n\r\n         // Interactions for the 2 other buttons\r\n         \r\n            $(''demo-clear'').addEvent(''click'', function() {\r\n              up.remove(); // remove all files\r\n                return false;\r\n           });\r\n\r\n         $(''demo-upload'').addEvent(''click'', function() {\r\n             up.start(); // start upload\r\n             return false;\r\n           });\r\n     },\r\n      \r\n        // Edit the following lines, it is your custom event handling\r\n       \r\n        /**\r\n      * Is called when files were not added, "files" is an array of invalid File classes.\r\n         * \r\n      * This example creates a list of error elements directly in the file list, which\r\n        * hide on click.\r\n        */ \r\n        onSelectFail: function(files) {\r\n         files.each(function(file) {\r\n             new Element(''li'', {\r\n                   ''class'': ''validation-error'',\r\n                    html: file.validationErrorMessage || file.validationError,\r\n                  title: MooTools.lang.get(''FancyUpload'', ''removeTitle''),\r\n                 events: {\r\n                       click: function() {\r\n                         this.destroy();\r\n                     }\r\n                   }\r\n               }).inject(this.list, ''top'');\r\n          }, this);\r\n       },\r\n      \r\n        /**\r\n      * This one was directly in FancyUpload2 before, the event makes it\r\n      * easier for you, to add your own response handling (you probably want\r\n      * to send something else than JSON or different items).\r\n         */\r\n     onFileSuccess: function(file, response) {\r\n           var json = new Hash(JSON.decode(response, true) || {});\r\n         \r\n            if (json.get(''status'') == ''1'') {\r\n                file.element.addClass(''file-success'');\r\n                file.info.set(''html'', ''<strong>Image was uploaded:</strong> '' + json.get(''width'') + '' x '' + json.get(''height'') + ''px, <em>'' + json.get(''mime'') + ''</em>)'');\r\n         } else {\r\n                file.element.addClass(''file-failed'');\r\n             file.info.set(''html'', ''<strong>An error occured:</strong> '' + (json.get(''error'') ? (json.get(''error'') + '' #'' + json.get(''code'')) : response));\r\n          }\r\n       },\r\n      \r\n        /**\r\n      * onFail is called when the Flash movie got bashed by some browser plugin\r\n       * like Adblock or Flashblock.\r\n       */\r\n     onFail: function(error) {\r\n           switch (error) {\r\n                case ''hidden'': // works after enabling the movie and clicking refresh\r\n                 alert(''To enable the embedded uploader, unblock it in your browser and refresh (see Adblock).'');\r\n                  break;\r\n              case ''blocked'': // This no *full* fail, it works after the user clicks the button\r\n                 alert(''To enable the embedded uploader, enable the blocked Flash movie (see Flashblock).'');\r\n                   break;\r\n              case ''empty'': // Oh oh, wrong path\r\n                    alert(''A required file was not found, please be patient and we fix this.'');\r\n                   break;\r\n              case ''flash'': // no flash 9+ :(\r\n                   alert(''To enable the embedded uploader, install the latest Adobe Flash plugin.'')\r\n          }\r\n       }\r\n       \r\n    });\r\n \r\n});\r\n     //]]>\r\n   </script>\r\n\r\n\r\n\r\n   <!-- See style.css -->\r\n  <style type="text/css">\r\n     /**\r\n * FancyUpload Showcase\r\n *\r\n * @license     MIT License\r\n * @author       Harald Kirschner <mail [at] digitarald [dot] de>\r\n * @copyright   Authors\r\n */\r\n\r\n/* CSS vs. Adblock tabs */\r\n.container { font-family:arial; font-size:12px; }\r\n.swiff-uploader-box a {\r\n    display: none !important;\r\n}\r\n\r\n/* .hover simulates the flash interactions */\r\na:hover, a.hover {\r\n   color: red;\r\n}\r\n\r\n#demo-status {\r\n  padding: 10px 15px;\r\n width: 420px;\r\n   border: 1px solid #eee;\r\n}\r\n\r\n#demo-status .progress {\r\n    background: url(/assets/fancyupload/assets/progress-bar/progress.gif) no-repeat;\r\n    background-position: +50% 0;\r\n    margin-right: 0.5em;\r\n    vertical-align: middle;\r\n}\r\n\r\n#demo-status .progress-text {\r\n   font-size: 0.9em;\r\n   font-weight: bold;\r\n}\r\n\r\n#demo-list {\r\n list-style: none;\r\n   width: 450px;\r\n   margin: 0;\r\n}\r\n\r\n#demo-list li.validation-error {\r\n padding-left: 44px;\r\n display: block;\r\n clear: left;\r\n    line-height: 40px;\r\n  color: #8a1f11;\r\n cursor: pointer;\r\n    border-bottom: 1px solid #fbc2c4;\r\n   background: #fbe3e4 url(/assets/fancyupload/assets/failed.png) no-repeat 4px 4px;\r\n}\r\n\r\n#demo-list li.file {\r\n  border-bottom: 1px solid #eee;\r\n  background: url(/assets/fancyupload/assets/file.png) no-repeat 4px 4px;\r\n overflow: auto;\r\n}\r\n#demo-list li.file.file-uploading {\r\n background-image: url(/assets/fancyupload/assets/uploading.png);\r\n    background-color: #D9DDE9;\r\n}\r\n#demo-list li.file.file-success {\r\n    background-image: url(/assets/fancyupload/assets/success.png);\r\n}\r\n#demo-list li.file.file-failed {\r\n background-image: url(/assets/fancyupload/assets/failed.png);\r\n}\r\n\r\n#demo-list li.file .file-name {\r\n   font-size: 1.2em;\r\n   margin-left: 44px;\r\n  display: block;\r\n clear: left;\r\n    line-height: 40px;\r\n  height: 40px;\r\n   font-weight: bold;\r\n}\r\n#demo-list li.file .file-size {\r\n  font-size: 0.9em;\r\n   line-height: 18px;\r\n  float: right;\r\n   margin-top: 2px;\r\n    margin-right: 6px;\r\n}\r\n#demo-list li.file .file-info {\r\n  display: block;\r\n margin-left: 44px;\r\n  font-size: 0.9em;\r\n   line-height: 20px;\r\n  clear\r\n}\r\n#demo-list li.file .file-remove {\r\n clear: right;\r\n   float: right;\r\n   line-height: 18px;\r\n  margin-right: 6px;\r\n} \r\n\r\n.container a, .container a:visited { color:#0066CC; text-decoration:none;  }\r\n.container a:hover { text-decoration:underline; color:#F00; }\r\n\r\n</style>\r\n\r\n\r\n<div style=''font-size:14px;font-style:italic;''><p>Click on "Browse Files" link to select the files to upload. <span style=''color:#F00;font-weight:bold;''>(HINT: you can ctrl-click or command-click on Mac to multi select files).</span> After you choose your images click the "Start Upload" link. Once all files are uploaded click on the "Process Gallery Images" link.</p></div>\r\n    <div class="container">\r\n     <h2>Queued Photo Uploader</h2>\r\n      <!-- See index.html -->\r\n     <div>\r\n           <form action="/assets/fancyupload/server/script.php" method="post" enctype="multipart/form-data" id="form-demo">\r\n\r\n    <fieldset id="demo-fallback">\r\n       <legend>File Upload</legend>\r\n        <p>\r\n         This form is just an example fallback for the unobtrusive behaviour of FancyUpload.\r\n         If this part is not changed, something must be wrong with your code.\r\n        </p>\r\n        <label for="demo-photoupload">\r\n          Upload a Photo:\r\n         <input type="file" name="Filedata" />\r\n       </label>\r\n    </fieldset>\r\n\r\n <div id="demo-status" class="hide">\r\n     <p>\r\n         <a href="#" id="demo-browse">Browse Files</a> |\r\n         <a href="#" id="demo-clear">Clear List</a> |\r\n            <a href="#" id="demo-upload">Start Upload</a>\r\n       </p>\r\n        <div>\r\n           <strong class="overall-title"></strong><br />\r\n           <img src="/assets/fancyupload/assets/progress-bar/bar.gif" class="progress overall-progress" />\r\n     </div>\r\n      <div>\r\n           <strong class="current-title"></strong><br />\r\n           <img src="/assets/fancyupload/assets/progress-bar/bar.gif" class="progress current-progress" />\r\n     </div>\r\n      <div class="current-text"></div>\r\n    </div>\r\n\r\n  <ul id="demo-list"></ul>\r\n\r\n</form>     </div>\r\n\r\n\r\n  </div>\r\n\r\n  <div class="container quiet" style="line-height: 5em;">\r\n     © 2008-2009 by <a href="http://digitarald.de/">Harald Kirschner</a> and available under <a href="http://www.opensource.org/licenses/mit-license.php">The MIT License</a>\r\n   </div>\r\n', 0, 4),
(33, '831_lightbox_js', '', '<script type="text/javascript" src="/manager/frames/scriptaculous/prototype.js" ></script>\r\n<script type="text/javascript" src="/manager/frames/scriptaculous/scriptaculous.js" ></script>\r\n<script type=''text/javascript'' src=''/assets/js/lightbox/lightbox-pt-1.6-compat.js''></script>\r\n<link rel="stylesheet" href="/assets/js/lightbox/lightbox.css" type="text/css" media="screen" />', 0, 4);


--
-- Table structure for table `{PREFIX}831gallery`
-- Added by John Carlson to ad the ability for the gallery plugin


CREATE TABLE IF NOT EXISTS `{PREFIX}831gallery` (
  `id` int(11) NOT NULL auto_increment,
  `fn` varchar(250) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `slide` char(1) NOT NULL default '0',
  `docId` int(11) NOT NULL,
  `gal_order` int(11) NOT NULL default '999',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='gallery setup by 831' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}template_variables`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}template_variables` (
  `tv_id` int(11) NOT NULL auto_increment,
  `field_name` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) NOT NULL,
  `type` enum('checkbox','radio','text','textarea','select','file') NOT NULL,
  `opts` text NOT NULL,
  `field_size` tinyint(3) NOT NULL,
  `field_max_size` tinyint(3) NOT NULL,
  `tv_order` tinyint(3) NOT NULL default '0',
  `default_val` text NOT NULL,
  `required` char(1) NOT NULL default '0',
  `output_type` enum('text','image','link','date') NOT NULL,
  PRIMARY KEY  (`tv_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}template_variable_templates`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}template_variable_templates` (
  `id` int(11) NOT NULL auto_increment,
  `template_id` int(11) NOT NULL,
  `tv_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{PREFIX}site_content_tv_val`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}site_content_tv_val` (
  `id` int(11) NOT NULL auto_increment,
  `doc_id` int(11) NOT NULL,
  `tv_id` int(11) NOT NULL,
  `tv_value` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `etomite_modules`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}modules` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `version` varchar(5) NOT NULL,
  `author` varchar(250) NOT NULL,
  `active` char(1) NOT NULL default '0',
  `admin_menu` char(1) NOT NULL default '0',
  `key` varchar(50) NOT NULL,
  `resources` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

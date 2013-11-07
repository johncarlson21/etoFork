-- Create table in target 

CREATE TABLE `{PREFIX}member_log`(
    `id` int(11) NOT NULL  auto_increment , 
    `mem_id` int(11) NOT NULL  , 
    `username` varchar(20) COLLATE latin1_swedish_ci NOT NULL  , 
    `ip` varchar(15) COLLATE latin1_swedish_ci NOT NULL  , 
    `dstamp` datetime NOT NULL  , 
    `document` mediumtext COLLATE latin1_swedish_ci NOT NULL  , 
    `action` mediumtext COLLATE latin1_swedish_ci NOT NULL  , 
    `message` mediumtext COLLATE latin1_swedish_ci NOT NULL  , 
    PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET='latin1' COMMENT='members area log of actions';


-- Create table in target 

CREATE TABLE `{PREFIX}member_validation`(
    `id` int(10) NOT NULL  auto_increment , 
    `firstName` varchar(100) COLLATE latin1_swedish_ci NOT NULL  , 
    `lastName` varchar(100) COLLATE latin1_swedish_ci NULL  , 
    `userName` varchar(100) COLLATE latin1_swedish_ci NULL  , 
    `email` varchar(100) COLLATE latin1_swedish_ci NULL  , 
    `phone` varchar(100) COLLATE latin1_swedish_ci NULL  , 
    `mobilephone` varchar(100) COLLATE latin1_swedish_ci NULL  , 
    `hash` varchar(100) COLLATE latin1_swedish_ci NULL  , 
    `password` varchar(100) COLLATE latin1_swedish_ci NULL  , 
    `role` int(10) NULL  , 
    `tstmp` timestamp NOT NULL  DEFAULT CURRENT_TIMESTAMP , 
    `address` varchar(100) COLLATE latin1_swedish_ci NOT NULL  , 
    `city` varchar(100) COLLATE latin1_swedish_ci NOT NULL  , 
    `state` varchar(100) COLLATE latin1_swedish_ci NOT NULL  , 
    `zip` varchar(100) COLLATE latin1_swedish_ci NOT NULL  , 
    PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET='latin1';


-- Create table in target

CREATE TABLE `{PREFIX}modules`(
    `id` int(11) NOT NULL  auto_increment , 
    `name` varchar(100) COLLATE latin1_swedish_ci NOT NULL  , 
    `description` text COLLATE latin1_swedish_ci NOT NULL  , 
    `version` varchar(5) COLLATE latin1_swedish_ci NOT NULL  , 
    `author` varchar(250) COLLATE latin1_swedish_ci NOT NULL  , 
    `active` char(1) COLLATE latin1_swedish_ci NOT NULL  DEFAULT '0' , 
    `admin_menu` char(1) COLLATE latin1_swedish_ci NOT NULL  DEFAULT '0' , 
    `internal_key` varchar(50) COLLATE latin1_swedish_ci NOT NULL  , 
    `resources` text COLLATE latin1_swedish_ci NOT NULL  , 
    PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET='latin1';


-- Alter table in target 

ALTER TABLE `{PREFIX}site_content` ADD COLUMN `meta_title` varchar(255)  COLLATE utf8_unicode_ci NOT NULL DEFAULT '';

ALTER TABLE `{PREFIX}site_content` ADD COLUMN `meta_description` varchar(255)  COLLATE utf8_unicode_ci NOT NULL DEFAULT '';

ALTER TABLE `{PREFIX}site_content` ADD COLUMN `meta_keywords` varchar(255)  COLLATE utf8_unicode_ci NOT NULL DEFAULT '';

-- Create table in target 

CREATE TABLE `{PREFIX}site_content_tv_val`(
    `id` int(11) NOT NULL  auto_increment , 
    `doc_id` int(11) NOT NULL  , 
    `tv_id` int(11) NOT NULL  , 
    `tv_value` text COLLATE latin1_swedish_ci NOT NULL  , 
    PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET='latin1';


-- Create table in target 

CREATE TABLE `{PREFIX}site_content_versions`(
    `id` int(10) NOT NULL  auto_increment , 
    `versionedon` int(20) NOT NULL  DEFAULT '0' , 
    `orig_id` int(10) NOT NULL  , 
    `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL  DEFAULT 'document' , 
    `contentType` varchar(50) COLLATE utf8_unicode_ci NOT NULL  DEFAULT 'text/html' , 
    `pagetitle` varchar(100) COLLATE utf8_unicode_ci NOT NULL  DEFAULT '' , 
    `longtitle` varchar(255) COLLATE utf8_unicode_ci NOT NULL  DEFAULT '' , 
    `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL  DEFAULT '' , 
    `alias` varchar(100) COLLATE utf8_unicode_ci NULL  DEFAULT '' , 
    `published` int(1) NOT NULL  DEFAULT '0' , 
    `pub_date` int(20) NOT NULL  DEFAULT '0' , 
    `unpub_date` int(20) NOT NULL  DEFAULT '0' , 
    `parent` int(10) NOT NULL  DEFAULT '0' , 
    `isfolder` int(1) NOT NULL  DEFAULT '0' , 
    `content` mediumtext COLLATE utf8_unicode_ci NOT NULL  , 
    `richtext` tinyint(1) NOT NULL  DEFAULT '1' , 
    `template` int(10) NOT NULL  DEFAULT '1' , 
    `menuindex` int(10) NOT NULL  DEFAULT '0' , 
    `searchable` int(1) NOT NULL  DEFAULT '1' , 
    `cacheable` int(1) NOT NULL  DEFAULT '1' , 
    `createdby` int(10) NOT NULL  DEFAULT '0' , 
    `createdon` int(20) NOT NULL  DEFAULT '0' , 
    `editedby` int(10) NOT NULL  DEFAULT '0' , 
    `editedon` int(20) NOT NULL  DEFAULT '0' , 
    `deleted` int(1) NOT NULL  DEFAULT '0' , 
    `deletedon` int(20) NOT NULL  DEFAULT '0' , 
    `deletedby` int(10) NOT NULL  DEFAULT '0' , 
    `authenticate` int(1) NOT NULL  DEFAULT '0' , 
    `showinmenu` int(1) NOT NULL  DEFAULT '1' , 
    `meta_title` varchar(255) COLLATE utf8_unicode_ci NULL  DEFAULT '' , 
    `meta_description` varchar(255) COLLATE utf8_unicode_ci NULL  DEFAULT '' , 
    `meta_keywords` varchar(255) COLLATE utf8_unicode_ci NULL  DEFAULT '' , 
    PRIMARY KEY (`id`) , 
    KEY `parent`(`parent`) , 
    FULLTEXT KEY `content_ft_idx`(`pagetitle`,`description`,`content`) 
) ENGINE=MyISAM DEFAULT CHARSET='latin1' COMMENT='Contains the site\'s document tree versions.';


-- Alter table in target 

ALTER TABLE `{PREFIX}site_htmlsnippets` ADD COLUMN `section` int(11)   NOT NULL COMMENT 'id from section table';

-- Create table in target 

CREATE TABLE `{PREFIX}site_htmlsnippets_versions`(
    `id` int(10) NOT NULL  auto_increment , 
    `date_mod` datetime NOT NULL  , 
    `htmlsnippet_id` int(10) NOT NULL  , 
    `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL  DEFAULT '' , 
    `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL  DEFAULT 'Chunk' , 
    `snippet` mediumtext COLLATE utf8_unicode_ci NOT NULL  , 
    `locked` tinyint(4) NOT NULL  DEFAULT '0' , 
    `section` int(11) NOT NULL  COMMENT 'id from section table' , 
    PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET='latin1' COMMENT='Contains the site\'s chunks.';


-- Create table in target 

CREATE TABLE `{PREFIX}site_section`(
    `id` int(11) NOT NULL  auto_increment , 
    `name` varchar(150) COLLATE latin1_swedish_ci NOT NULL  , 
    `description` varchar(250) COLLATE latin1_swedish_ci NOT NULL  , 
    `section_type` enum('snippet','chunk') COLLATE latin1_swedish_ci NOT NULL  , 
    `sort_order` char(2) COLLATE latin1_swedish_ci NOT NULL  DEFAULT '1' , 
    PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET='latin1' COMMENT='table for snippet and chunk sections';


-- Alter table in target 

ALTER TABLE `{PREFIX}site_snippets` ADD COLUMN `section` int(11)   NOT NULL COMMENT 'id from section table';

-- Create table in target 

CREATE TABLE `{PREFIX}site_snippets_versions`(
    `id` int(10) NOT NULL  auto_increment , 
    `date_mod` datetime NOT NULL  , 
    `snippet_id` int(10) NOT NULL  , 
    `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL  DEFAULT '' , 
    `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL  DEFAULT 'Snippet' , 
    `snippet` mediumtext COLLATE utf8_unicode_ci NOT NULL  , 
    `locked` tinyint(4) NOT NULL  DEFAULT '0' , 
    `section` int(11) NOT NULL  COMMENT 'id from section table' , 
    PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET='latin1' COMMENT='Contains the site\'s snippets.';


-- Create table in target 

CREATE TABLE `{PREFIX}template_variable_templates`(
    `id` int(11) NOT NULL  auto_increment , 
    `template_id` int(11) NOT NULL  , 
    `tv_id` int(11) NOT NULL  , 
    PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET='latin1';


-- Create table in target 

CREATE TABLE `{PREFIX}template_variables`(
    `tv_id` int(11) NOT NULL  auto_increment , 
    `field_name` varchar(50) COLLATE latin1_swedish_ci NOT NULL  , 
    `name` varchar(150) COLLATE latin1_swedish_ci NOT NULL  , 
    `description` varchar(250) COLLATE latin1_swedish_ci NOT NULL  , 
    `type` enum('checkbox','radio','text','textarea','select','file') COLLATE latin1_swedish_ci NOT NULL  , 
    `opts` text COLLATE latin1_swedish_ci NOT NULL  , 
    `field_size` tinyint(3) NOT NULL  , 
    `field_max_size` tinyint(3) NOT NULL  , 
    `tv_order` tinyint(3) NOT NULL  DEFAULT '0' , 
    `default_val` text COLLATE latin1_swedish_ci NOT NULL  , 
    `required` char(1) COLLATE latin1_swedish_ci NOT NULL  DEFAULT '0' , 
    `output_type` enum('text','image','link','date') COLLATE latin1_swedish_ci NOT NULL  , 
    PRIMARY KEY (`tv_id`) 
) ENGINE=MyISAM DEFAULT CHARSET='latin1';


-- Alter table in target 

ALTER TABLE `{PREFIX}user_attributes` ADD COLUMN `address` varchar(100)  COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `{PREFIX}user_attributes` ADD COLUMN `city` varchar(100)  COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `{PREFIX}user_attributes` ADD COLUMN `state` varchar(100)  COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `{PREFIX}user_attributes` ADD COLUMN `zip` varchar(100)  COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `{PREFIX}user_attributes` ADD COLUMN `mailmessages` int(1)   NOT NULL DEFAULT '0';

-- Alter table in target 

ALTER TABLE `{PREFIX}user_messages` CHANGE `type` `type` varchar(50)  COLLATE utf8_unicode_ci NOT NULL DEFAULT '';

-- Create table in target 

CREATE TABLE `{PREFIX}web_user_roles`(
    `id` int(11) NOT NULL  auto_increment , 
    `name` varchar(150) COLLATE latin1_swedish_ci NOT NULL  , 
    `description` varchar(500) COLLATE latin1_swedish_ci NOT NULL  , 
    PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET='latin1' COMMENT='frontend user roles';


-- Create table in target

CREATE TABLE `{PREFIX}web_users`(
    `id` int(10) NOT NULL  auto_increment , 
    `firstName` varchar(100) COLLATE latin1_swedish_ci NOT NULL  , 
    `lastName` varchar(100) COLLATE latin1_swedish_ci NULL  , 
    `username` varchar(100) COLLATE latin1_swedish_ci NULL  , 
    `email` varchar(100) COLLATE latin1_swedish_ci NULL  , 
    `phone` varchar(100) COLLATE latin1_swedish_ci NULL  , 
    `phone2` varchar(100) COLLATE latin1_swedish_ci NOT NULL  , 
    `hash` varchar(100) COLLATE latin1_swedish_ci NULL  , 
    `password` varchar(100) COLLATE latin1_swedish_ci NULL  , 
    `role` int(10) NULL  DEFAULT '1' COMMENT 'default of 1 = normal member' , 
    `tstmp` timestamp NOT NULL  DEFAULT CURRENT_TIMESTAMP , 
    `address` varchar(100) COLLATE latin1_swedish_ci NOT NULL  , 
    `city` varchar(100) COLLATE latin1_swedish_ci NOT NULL  , 
    `state` varchar(100) COLLATE latin1_swedish_ci NOT NULL  , 
    `zip` varchar(100) COLLATE latin1_swedish_ci NOT NULL  , 
    `company_name` varchar(250) COLLATE latin1_swedish_ci NOT NULL  , 
    `fax` varchar(100) COLLATE latin1_swedish_ci NOT NULL  , 
    `website` varchar(250) COLLATE latin1_swedish_ci NOT NULL  , 
    `logo` varchar(200) COLLATE latin1_swedish_ci NOT NULL  , 
    `description` text COLLATE latin1_swedish_ci NOT NULL  , 
    `status` char(1) COLLATE latin1_swedish_ci NOT NULL  DEFAULT '0' , 
    `date_joined` int(11) NOT NULL  , 
    `lastlogin` datetime NOT NULL  , 
    `furl` varchar(250) COLLATE latin1_swedish_ci NOT NULL  COMMENT 'friendly url for dealers' , 
    `show_contact_info` char(1) COLLATE latin1_swedish_ci NOT NULL  DEFAULT '0' , 
    `package_id` int(11) NOT NULL  COMMENT 'package id for dealers' , 
    PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET='latin1' COMMENT='frontend web users';

INSERT INTO `{PREFIX}system_settings` (`setting_name`, `setting_value`) VALUES ('zend_urls', '1');

--
-- Table structure for table `{PREFIX}system_events`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}system_events` (
  `id` int(11) NOT NULL auto_increment,
  `event_name` varchar(50) NOT NULL,
  `module_name` varchar(100) NOT NULL,
  `method_name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

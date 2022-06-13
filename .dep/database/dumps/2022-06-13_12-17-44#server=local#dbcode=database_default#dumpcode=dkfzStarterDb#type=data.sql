-- MariaDB dump 10.19  Distrib 10.5.12-MariaDB, for debian-linux-gnu (aarch64)
--
-- Host: db    Database: db
-- ------------------------------------------------------
-- Server version	10.3.34-MariaDB-1:10.3.34+maria~focal-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `backend_layout`
--

DROP TABLE IF EXISTS `backend_layout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backend_layout` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `config` text COLLATE utf8_unicode_ci NOT NULL,
  `icon` text COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backend_layout`
--

LOCK TABLES `backend_layout` WRITE;
/*!40000 ALTER TABLE `backend_layout` DISABLE KEYS */;
/*!40000 ALTER TABLE `backend_layout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `be_dashboards`
--

DROP TABLE IF EXISTS `be_dashboards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `be_dashboards` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `identifier` varchar(120) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `widgets` text COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `be_dashboards`
--

LOCK TABLES `be_dashboards` WRITE;
/*!40000 ALTER TABLE `be_dashboards` DISABLE KEYS */;
INSERT INTO `be_dashboards` VALUES (1,0,1654616891,1654616891,2,0,0,0,0,'1ebf9dd42f96425948126dc01af7d102f55a2586','My dashboard','{\"746bbdc6ba535c6135a4174ea02858acc4526c23\":{\"identifier\":\"t3information\"},\"c63310cb0c43a21aea3b5fc3d09a99f6212efa17\":{\"identifier\":\"docGettingStarted\"}}');
/*!40000 ALTER TABLE `be_dashboards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `be_groups`
--

DROP TABLE IF EXISTS `be_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `be_groups` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `non_exclude_fields` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `explicit_allowdeny` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `allowed_languages` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `custom_options` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `db_mountpoints` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `pagetypes_select` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tables_select` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tables_modify` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `groupMods` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `availableWidgets` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `mfa_providers` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_mountpoints` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_permissions` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `TSconfig` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `subgroup` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `workspace_perms` smallint(6) NOT NULL DEFAULT 1,
  `category_perms` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `be_groups`
--

LOCK TABLES `be_groups` WRITE;
/*!40000 ALTER TABLE `be_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `be_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `be_users`
--

DROP TABLE IF EXISTS `be_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `be_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `disable` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `avatar` int(10) unsigned NOT NULL DEFAULT 0,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `admin` smallint(5) unsigned NOT NULL DEFAULT 0,
  `usergroup` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `db_mountpoints` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `options` smallint(5) unsigned NOT NULL DEFAULT 0,
  `realName` varchar(80) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `userMods` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `allowed_languages` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `uc` mediumblob DEFAULT NULL,
  `file_mountpoints` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_permissions` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `workspace_perms` smallint(6) NOT NULL DEFAULT 1,
  `TSconfig` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastlogin` int(10) unsigned NOT NULL DEFAULT 0,
  `workspace_id` int(11) NOT NULL DEFAULT 0,
  `mfa` mediumblob DEFAULT NULL,
  `category_perms` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_reset_token` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  KEY `username` (`username`),
  KEY `parent` (`pid`,`deleted`,`disable`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `be_users`
--

LOCK TABLES `be_users` WRITE;
/*!40000 ALTER TABLE `be_users` DISABLE KEYS */;
INSERT INTO `be_users` VALUES (1,0,1654616878,1654616878,0,0,0,0,0,NULL,'_cli_',0,'$argon2i$v=19$m=65536,t=16,p=1$NFRGZjVNNVhacFdNWmY4SA$K7u/Wu34rsSxrPKdzOCGu8bOuu58UEEgiBFTd1sWqjg',1,NULL,'default','',NULL,0,'',NULL,'','a:9:{s:14:\"interfaceSetup\";s:0:\"\";s:10:\"moduleData\";a:0:{}s:14:\"emailMeAtLogin\";i:0;s:8:\"titleLen\";i:50;s:8:\"edit_RTE\";s:1:\"1\";s:20:\"edit_docModuleUpload\";s:1:\"1\";s:25:\"resizeTextareas_MaxHeight\";i:500;s:4:\"lang\";s:7:\"default\";s:19:\"firstLoginTimeStamp\";i:1654616878;}',NULL,NULL,1,NULL,0,0,NULL,NULL,''),(2,0,1655121401,1654616878,0,0,0,0,0,NULL,'admin',0,'$argon2i$v=19$m=65536,t=16,p=1$MWtDNnpOU2E0dC9nLnpQag$He08vjkZmNS61rgMjn/G/mwesb/6JDtds4eSsM3NU8M',1,NULL,'de','',NULL,0,'',NULL,'','a:25:{s:14:\"interfaceSetup\";s:0:\"\";s:10:\"moduleData\";a:14:{s:28:\"dashboard/current_dashboard/\";s:40:\"1ebf9dd42f96425948126dc01af7d102f55a2586\";s:10:\"web_layout\";a:3:{s:8:\"function\";s:1:\"1\";s:8:\"language\";s:1:\"0\";s:19:\"constant_editor_cat\";N;}s:57:\"TYPO3\\CMS\\Backend\\Utility\\BackendUtility::getUpdateSignal\";a:0:{}s:10:\"FormEngine\";a:2:{i:0;a:9:{s:32:\"3f2235bc4910c51c022e349c2901620c\";a:4:{i:0;s:29:\"Organisation &amp; Management\";i:1;a:5:{s:4:\"edit\";a:1:{s:5:\"pages\";a:1:{i:9;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:28:\"&edit%5Bpages%5D%5B9%5D=edit\";i:3;a:5:{s:5:\"table\";s:5:\"pages\";s:3:\"uid\";i:9;s:3:\"pid\";i:4;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"7d9e144e24486a6668d151356ea4c9d4\";a:4:{i:0;s:13:\"Unser Zentrum\";i:1;a:5:{s:4:\"edit\";a:1:{s:5:\"pages\";a:1:{i:4;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:28:\"&edit%5Bpages%5D%5B4%5D=edit\";i:3;a:5:{s:5:\"table\";s:5:\"pages\";s:3:\"uid\";i:4;s:3:\"pid\";i:1;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"3af505b920348c1a79bf62ea28cbec90\";a:4:{i:0;s:9:\"Aktuelles\";i:1;a:5:{s:4:\"edit\";a:1:{s:5:\"pages\";a:1:{i:5;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:28:\"&edit%5Bpages%5D%5B5%5D=edit\";i:3;a:5:{s:5:\"table\";s:5:\"pages\";s:3:\"uid\";i:5;s:3:\"pid\";i:1;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"750043f9a02785cc6557d2efe33fd403\";a:4:{i:0;s:17:\"Mein Arbeitsplatz\";i:1;a:5:{s:4:\"edit\";a:1:{s:5:\"pages\";a:1:{i:6;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:28:\"&edit%5Bpages%5D%5B6%5D=edit\";i:3;a:5:{s:5:\"table\";s:5:\"pages\";s:3:\"uid\";i:6;s:3:\"pid\";i:1;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"639c80047818aac48db3b25ab8d3d802\";a:4:{i:0;s:8:\"Services\";i:1;a:5:{s:4:\"edit\";a:1:{s:5:\"pages\";a:1:{i:7;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:28:\"&edit%5Bpages%5D%5B7%5D=edit\";i:3;a:5:{s:5:\"table\";s:5:\"pages\";s:3:\"uid\";i:7;s:3:\"pid\";i:1;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"f2cda034b8fdf31e30dd385cd7fc0887\";a:4:{i:0;s:22:\"Mitarbeiterverzeichnis\";i:1;a:5:{s:4:\"edit\";a:1:{s:5:\"pages\";a:1:{i:8;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:28:\"&edit%5Bpages%5D%5B8%5D=edit\";i:3;a:5:{s:5:\"table\";s:5:\"pages\";s:3:\"uid\";i:8;s:3:\"pid\";i:1;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"d6ca31e10971676b728645b6ee7a7845\";a:4:{i:0;s:12:\"Wissenschaft\";i:1;a:5:{s:4:\"edit\";a:1:{s:5:\"pages\";a:1:{i:85;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:29:\"&edit%5Bpages%5D%5B85%5D=edit\";i:3;a:5:{s:5:\"table\";s:5:\"pages\";s:3:\"uid\";i:85;s:3:\"pid\";i:20;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"8ec48f9617323862ff8ffc5877eeb194\";a:4:{i:0;s:14:\"Innenansichten\";i:1;a:5:{s:4:\"edit\";a:1:{s:5:\"pages\";a:1:{i:86;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:29:\"&edit%5Bpages%5D%5B86%5D=edit\";i:3;a:5:{s:5:\"table\";s:5:\"pages\";s:3:\"uid\";i:86;s:3:\"pid\";i:20;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"0076e013598220b774a6df00dbb43950\";a:4:{i:0;s:12:\"Wissenschaft\";i:1;a:5:{s:4:\"edit\";a:1:{s:5:\"pages\";a:1:{s:17:\"85,86,87,88,89,90\";s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";s:13:\"shortcut_mode\";s:6:\"noView\";N;}i:2;s:80:\"&edit%5Bpages%5D%5B85%2C86%2C87%2C88%2C89%2C90%5D=edit&columnsOnly=shortcut_mode\";i:3;a:5:{s:5:\"table\";s:5:\"pages\";s:3:\"uid\";i:85;s:3:\"pid\";i:20;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}}i:1;s:32:\"7d9e144e24486a6668d151356ea4c9d4\";}s:6:\"web_ts\";a:6:{s:8:\"function\";s:87:\"TYPO3\\CMS\\Tstemplate\\Controller\\TypoScriptTemplateObjectBrowserModuleFunctionController\";s:8:\"language\";N;s:19:\"constant_editor_cat\";s:0:\"\";s:15:\"ts_browser_type\";s:5:\"const\";s:16:\"ts_browser_const\";s:1:\"0\";s:23:\"ts_browser_showComments\";s:1:\"1\";}s:47:\"TYPO3\\CMS\\Belog\\Controller\\BackendLogController\";s:337:\"O:39:\"TYPO3\\CMS\\Belog\\Domain\\Model\\Constraint\":11:{s:14:\"\0*\0userOrGroup\";s:1:\"0\";s:9:\"\0*\0number\";i:20;s:15:\"\0*\0workspaceUid\";i:-99;s:10:\"\0*\0channel\";s:3:\"php\";s:8:\"\0*\0level\";s:5:\"debug\";s:17:\"\0*\0startTimestamp\";i:0;s:15:\"\0*\0endTimestamp\";i:0;s:18:\"\0*\0manualDateStart\";N;s:17:\"\0*\0manualDateStop\";N;s:9:\"\0*\0pageId\";i:0;s:8:\"\0*\0depth\";i:0;}\";s:16:\"opendocs::recent\";a:4:{s:32:\"6ab45e4762a935c8e812af0c5d61a236\";a:4:{i:0;s:12:\"Wissenschaft\";i:1;a:5:{s:4:\"edit\";a:1:{s:5:\"pages\";a:1:{s:17:\"85,86,87,88,89,90\";s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";s:7:\"doktype\";s:6:\"noView\";N;}i:2;s:74:\"&edit%5Bpages%5D%5B85%2C86%2C87%2C88%2C89%2C90%5D=edit&columnsOnly=doktype\";i:3;a:5:{s:5:\"table\";s:5:\"pages\";s:3:\"uid\";i:85;s:3:\"pid\";i:20;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"d6ca31e10971676b728645b6ee7a7845\";a:4:{i:0;s:12:\"Wissenschaft\";i:1;a:5:{s:4:\"edit\";a:1:{s:5:\"pages\";a:1:{i:85;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:29:\"&edit%5Bpages%5D%5B85%5D=edit\";i:3;a:5:{s:5:\"table\";s:5:\"pages\";s:3:\"uid\";i:85;s:3:\"pid\";i:20;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"c312013d83c1a6ad7fec8b36a37ba3c8\";a:4:{i:0;s:0:\"\";i:1;a:5:{s:4:\"edit\";a:1:{s:10:\"tt_content\";a:1:{i:1;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:33:\"&edit%5Btt_content%5D%5B1%5D=edit\";i:3;a:5:{s:5:\"table\";s:10:\"tt_content\";s:3:\"uid\";i:1;s:3:\"pid\";i:2;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"696addfecc296b326ff6e9f04c7ff3e1\";a:4:{i:0;s:4:\"Home\";i:1;a:5:{s:4:\"edit\";a:1:{s:5:\"pages\";a:1:{i:1;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:28:\"&edit%5Bpages%5D%5B1%5D=edit\";i:3;a:5:{s:5:\"table\";s:5:\"pages\";s:3:\"uid\";i:1;s:3:\"pid\";i:0;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}}s:13:\"system_config\";a:2:{s:4:\"tree\";s:8:\"confVars\";s:11:\"regexSearch\";b:0;}s:8:\"web_info\";a:4:{s:8:\"function\";s:60:\"TYPO3\\CMS\\Info\\Controller\\InfoPageTyposcriptConfigController\";s:8:\"language\";N;s:19:\"constant_editor_cat\";N;s:12:\"tsconf_parts\";s:1:\"0\";}s:9:\"clipboard\";a:5:{s:6:\"normal\";a:2:{s:2:\"el\";a:0:{}s:4:\"mode\";s:0:\"\";}s:5:\"tab_1\";a:0:{}s:5:\"tab_2\";a:0:{}s:5:\"tab_3\";a:0:{}s:7:\"current\";s:6:\"normal\";}s:9:\"tx_beuser\";a:2:{s:15:\"compareUserList\";a:0:{}s:6:\"demand\";a:5:{s:8:\"userName\";s:0:\"\";s:8:\"userType\";i:0;s:6:\"status\";i:0;s:6:\"logins\";i:0;s:16:\"backendUserGroup\";i:0;}}s:16:\"browse_links.php\";N;s:8:\"web_list\";a:3:{s:8:\"function\";N;s:8:\"language\";N;s:19:\"constant_editor_cat\";N;}s:18:\"list/displayFields\";a:1:{s:5:\"pages\";a:4:{i:0;s:5:\"title\";i:1;s:13:\"shortcut_mode\";i:2;s:8:\"shortcut\";i:3;s:7:\"doktype\";}}}s:14:\"emailMeAtLogin\";i:0;s:8:\"titleLen\";s:2:\"50\";s:8:\"edit_RTE\";i:1;s:20:\"edit_docModuleUpload\";i:1;s:25:\"resizeTextareas_MaxHeight\";s:3:\"500\";s:4:\"lang\";s:2:\"de\";s:19:\"firstLoginTimeStamp\";i:1654616888;s:15:\"moduleSessionID\";a:13:{s:28:\"dashboard/current_dashboard/\";s:40:\"a0cad17b51927602639a9577f4c579dac8fdf9c9\";s:10:\"web_layout\";s:40:\"b32cfdbb56d1416a1327bfd9bdbcdbe71ed63adf\";s:57:\"TYPO3\\CMS\\Backend\\Utility\\BackendUtility::getUpdateSignal\";s:40:\"b32cfdbb56d1416a1327bfd9bdbcdbe71ed63adf\";s:10:\"FormEngine\";s:40:\"b32cfdbb56d1416a1327bfd9bdbcdbe71ed63adf\";s:6:\"web_ts\";s:40:\"fa693bb363ccac349f638a2bc42157ea89bdb266\";s:47:\"TYPO3\\CMS\\Belog\\Controller\\BackendLogController\";s:40:\"fa693bb363ccac349f638a2bc42157ea89bdb266\";s:16:\"opendocs::recent\";s:40:\"b32cfdbb56d1416a1327bfd9bdbcdbe71ed63adf\";s:8:\"web_info\";s:40:\"fa693bb363ccac349f638a2bc42157ea89bdb266\";s:9:\"clipboard\";s:40:\"66cc2f8827e8275fe3033e9632f9ad2fbc8eb580\";s:9:\"tx_beuser\";s:40:\"66cc2f8827e8275fe3033e9632f9ad2fbc8eb580\";s:16:\"browse_links.php\";s:40:\"b32cfdbb56d1416a1327bfd9bdbcdbe71ed63adf\";s:8:\"web_list\";s:40:\"b32cfdbb56d1416a1327bfd9bdbcdbe71ed63adf\";s:18:\"list/displayFields\";s:40:\"b32cfdbb56d1416a1327bfd9bdbcdbe71ed63adf\";}s:17:\"BackendComponents\";a:1:{s:6:\"States\";a:1:{s:8:\"Pagetree\";a:1:{s:9:\"stateHash\";a:12:{s:3:\"0_0\";s:1:\"1\";s:3:\"0_1\";s:1:\"1\";s:3:\"0_2\";s:1:\"1\";s:3:\"0_4\";s:1:\"1\";s:3:\"0_8\";s:1:\"1\";s:4:\"0_13\";s:1:\"1\";s:3:\"0_5\";s:1:\"1\";s:3:\"0_6\";s:1:\"1\";s:4:\"0_29\";s:1:\"0\";s:3:\"0_7\";s:1:\"1\";s:4:\"0_27\";s:1:\"1\";s:4:\"0_20\";s:1:\"1\";}}}}s:17:\"systeminformation\";s:45:\"{\"system_BelogLog\":{\"lastAccess\":1654765638}}\";s:8:\"realName\";s:0:\"\";s:5:\"email\";s:0:\"\";s:8:\"password\";s:0:\"\";s:9:\"password2\";s:0:\"\";s:6:\"avatar\";s:0:\"\";s:11:\"startModule\";s:0:\"\";s:25:\"showHiddenFilesAndFolders\";i:0;s:10:\"copyLevels\";s:0:\"\";s:18:\"resetConfiguration\";s:0:\"\";s:12:\"mfaProviders\";s:0:\"\";s:18:\"backendTitleFormat\";s:10:\"titleFirst\";s:10:\"inlineView\";s:35:\"{\"site\":{\"1\":{\"site_route\":[\"0\"]}}}\";s:10:\"modulemenu\";s:2:\"{}\";}',NULL,NULL,1,NULL,1655112522,0,NULL,NULL,'');
/*!40000 ALTER TABLE `be_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fe_groups`
--

DROP TABLE IF EXISTS `fe_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fe_groups` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tx_extbase_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `subgroup` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `TSconfig` text COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fe_groups`
--

LOCK TABLES `fe_groups` WRITE;
/*!40000 ALTER TABLE `fe_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `fe_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fe_users`
--

DROP TABLE IF EXISTS `fe_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fe_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `disable` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tx_extbase_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `usergroup` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(160) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `middle_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `telephone` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fax` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `uc` blob DEFAULT NULL,
  `title` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `zip` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `country` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `www` varchar(80) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `company` varchar(80) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `TSconfig` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastlogin` int(10) unsigned NOT NULL DEFAULT 0,
  `is_online` int(10) unsigned NOT NULL DEFAULT 0,
  `mfa` mediumblob DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`username`(100)),
  KEY `username` (`username`(100)),
  KEY `is_online` (`is_online`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fe_users`
--

LOCK TABLES `fe_users` WRITE;
/*!40000 ALTER TABLE `fe_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `fe_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `fe_group` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT 0,
  `rowDescription` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `editlock` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_source` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `perms_userid` int(10) unsigned NOT NULL DEFAULT 0,
  `perms_groupid` int(10) unsigned NOT NULL DEFAULT 0,
  `perms_user` smallint(5) unsigned NOT NULL DEFAULT 0,
  `perms_group` smallint(5) unsigned NOT NULL DEFAULT 0,
  `perms_everybody` smallint(5) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(2048) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doktype` int(10) unsigned NOT NULL DEFAULT 0,
  `TSconfig` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_siteroot` smallint(6) NOT NULL DEFAULT 0,
  `php_tree_stop` smallint(6) NOT NULL DEFAULT 0,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `shortcut` int(10) unsigned NOT NULL DEFAULT 0,
  `shortcut_mode` int(10) unsigned NOT NULL DEFAULT 0,
  `subtitle` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `layout` int(10) unsigned NOT NULL DEFAULT 0,
  `target` varchar(80) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `media` int(10) unsigned NOT NULL DEFAULT 0,
  `lastUpdated` int(10) unsigned NOT NULL DEFAULT 0,
  `keywords` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `cache_timeout` int(10) unsigned NOT NULL DEFAULT 0,
  `cache_tags` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `newUntil` int(10) unsigned NOT NULL DEFAULT 0,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_search` smallint(5) unsigned NOT NULL DEFAULT 0,
  `SYS_LASTCHANGED` int(10) unsigned NOT NULL DEFAULT 0,
  `abstract` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `module` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `extendToSubpages` smallint(5) unsigned NOT NULL DEFAULT 0,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `author_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nav_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nav_hide` smallint(6) NOT NULL DEFAULT 0,
  `content_from_pid` int(10) unsigned NOT NULL DEFAULT 0,
  `mount_pid` int(10) unsigned NOT NULL DEFAULT 0,
  `mount_pid_ol` smallint(6) NOT NULL DEFAULT 0,
  `l18n_cfg` smallint(6) NOT NULL DEFAULT 0,
  `fe_login_mode` smallint(6) NOT NULL DEFAULT 0,
  `backend_layout` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `backend_layout_next_level` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tsconfig_includes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `categories` int(10) unsigned NOT NULL DEFAULT 0,
  `tx_impexp_origuid` int(11) NOT NULL DEFAULT 0,
  `seo_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `no_index` smallint(6) NOT NULL DEFAULT 0,
  `no_follow` smallint(6) NOT NULL DEFAULT 0,
  `og_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `og_description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `og_image` int(10) unsigned NOT NULL DEFAULT 0,
  `twitter_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `twitter_description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter_image` int(10) unsigned NOT NULL DEFAULT 0,
  `twitter_card` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `canonical_link` varchar(2048) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sitemap_priority` decimal(2,1) NOT NULL DEFAULT 0.5,
  `sitemap_changefreq` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  KEY `determineSiteRoot` (`is_siteroot`),
  KEY `language_identifier` (`l10n_parent`,`sys_language_uid`),
  KEY `slug` (`slug`(127)),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `translation_source` (`l10n_source`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,0,1655104737,1654616898,2,0,0,0,0,'',256,NULL,0,0,0,0,NULL,0,'{\"doktype\":\"\",\"title\":\"\",\"slug\":\"\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":\"\",\"no_index\":\"\",\"no_follow\":\"\",\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"\",\"og_title\":\"\",\"og_description\":\"\",\"og_image\":\"\",\"twitter_title\":\"\",\"twitter_description\":\"\",\"twitter_image\":\"\",\"twitter_card\":\"\",\"abstract\":\"\",\"keywords\":\"\",\"author\":\"\",\"author_email\":\"\",\"lastUpdated\":\"\",\"layout\":\"\",\"newUntil\":\"\",\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"content_from_pid\":\"\",\"target\":\"\",\"cache_timeout\":\"\",\"cache_tags\":\"\",\"is_siteroot\":\"\",\"no_search\":\"\",\"php_tree_stop\":\"\",\"module\":\"\",\"media\":\"\",\"tsconfig_includes\":\"\",\"TSconfig\":\"\",\"l18n_cfg\":\"\",\"hidden\":\"\",\"nav_hide\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"extendToSubpages\":\"\",\"fe_group\":\"\",\"fe_login_mode\":\"\",\"editlock\":\"\",\"categories\":\"\",\"rowDescription\":\"\"}',0,0,0,0,2,0,31,27,0,'Home','/',1,'',1,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1655104737,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(2,1,1655104084,1654766942,2,0,0,0,0,'',256,NULL,0,0,0,0,NULL,0,'{\"doktype\":\"\",\"title\":\"\",\"slug\":\"\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":\"\",\"no_index\":\"\",\"no_follow\":\"\",\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"\",\"og_title\":\"\",\"og_description\":\"\",\"og_image\":\"\",\"twitter_title\":\"\",\"twitter_description\":\"\",\"twitter_image\":\"\",\"twitter_card\":\"\",\"abstract\":\"\",\"keywords\":\"\",\"author\":\"\",\"author_email\":\"\",\"lastUpdated\":\"\",\"layout\":\"\",\"newUntil\":\"\",\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"content_from_pid\":\"\",\"target\":\"\",\"cache_timeout\":\"\",\"cache_tags\":\"\",\"is_siteroot\":\"\",\"no_search\":\"\",\"php_tree_stop\":\"\",\"module\":\"\",\"media\":\"\",\"tsconfig_includes\":\"\",\"TSconfig\":\"\",\"l18n_cfg\":\"\",\"hidden\":\"\",\"nav_hide\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"extendToSubpages\":\"\",\"fe_group\":\"\",\"fe_login_mode\":\"\",\"editlock\":\"\",\"categories\":\"\",\"rowDescription\":\"\"}',0,0,0,0,2,0,31,27,0,'Login','/login',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1655104084,NULL,'',0,'','','',1,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(3,2,1655104298,1655101737,2,1,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,'{\"hidden\":\"\"}',0,0,0,0,2,0,31,27,0,'Jobs','/login/jobs',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1655103305,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(4,1,1655119918,1655103213,2,0,0,0,0,'',128,NULL,0,0,0,0,NULL,0,'{\"doktype\":\"\",\"title\":\"\",\"slug\":\"\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":\"\",\"no_index\":\"\",\"no_follow\":\"\",\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"\",\"og_title\":\"\",\"og_description\":\"\",\"og_image\":\"\",\"twitter_title\":\"\",\"twitter_description\":\"\",\"twitter_image\":\"\",\"twitter_card\":\"\",\"abstract\":\"\",\"keywords\":\"\",\"author\":\"\",\"author_email\":\"\",\"lastUpdated\":\"\",\"layout\":\"\",\"newUntil\":\"\",\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"content_from_pid\":\"\",\"target\":\"\",\"cache_timeout\":\"\",\"cache_tags\":\"\",\"is_siteroot\":\"\",\"no_search\":\"\",\"php_tree_stop\":\"\",\"module\":\"\",\"media\":\"\",\"tsconfig_includes\":\"\",\"TSconfig\":\"\",\"l18n_cfg\":\"\",\"hidden\":\"\",\"nav_hide\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"extendToSubpages\":\"\",\"fe_group\":\"\",\"fe_login_mode\":\"\",\"editlock\":\"\",\"categories\":\"\",\"rowDescription\":\"\"}',0,0,0,0,2,0,31,27,0,'Unser Zentrum','/unser-zentrum',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(5,1,1655104158,1655103213,2,0,0,0,0,'',192,NULL,0,0,0,0,NULL,0,'{\"doktype\":\"\",\"title\":\"\",\"slug\":\"\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":\"\",\"no_index\":\"\",\"no_follow\":\"\",\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"\",\"og_title\":\"\",\"og_description\":\"\",\"og_image\":\"\",\"twitter_title\":\"\",\"twitter_description\":\"\",\"twitter_image\":\"\",\"twitter_card\":\"\",\"abstract\":\"\",\"keywords\":\"\",\"author\":\"\",\"author_email\":\"\",\"lastUpdated\":\"\",\"layout\":\"\",\"newUntil\":\"\",\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"content_from_pid\":\"\",\"target\":\"\",\"cache_timeout\":\"\",\"cache_tags\":\"\",\"is_siteroot\":\"\",\"no_search\":\"\",\"php_tree_stop\":\"\",\"module\":\"\",\"media\":\"\",\"tsconfig_includes\":\"\",\"TSconfig\":\"\",\"l18n_cfg\":\"\",\"hidden\":\"\",\"nav_hide\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"extendToSubpages\":\"\",\"fe_group\":\"\",\"fe_login_mode\":\"\",\"editlock\":\"\",\"categories\":\"\",\"rowDescription\":\"\"}',0,0,0,0,2,0,31,27,0,'Aktuelles','/aktuelles',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(6,1,1655119930,1655103213,2,0,0,0,0,'',224,NULL,0,0,0,0,NULL,0,'{\"doktype\":\"\",\"title\":\"\",\"slug\":\"\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":\"\",\"no_index\":\"\",\"no_follow\":\"\",\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"\",\"og_title\":\"\",\"og_description\":\"\",\"og_image\":\"\",\"twitter_title\":\"\",\"twitter_description\":\"\",\"twitter_image\":\"\",\"twitter_card\":\"\",\"abstract\":\"\",\"keywords\":\"\",\"author\":\"\",\"author_email\":\"\",\"lastUpdated\":\"\",\"layout\":\"\",\"newUntil\":\"\",\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"content_from_pid\":\"\",\"target\":\"\",\"cache_timeout\":\"\",\"cache_tags\":\"\",\"is_siteroot\":\"\",\"no_search\":\"\",\"php_tree_stop\":\"\",\"module\":\"\",\"media\":\"\",\"tsconfig_includes\":\"\",\"TSconfig\":\"\",\"l18n_cfg\":\"\",\"hidden\":\"\",\"nav_hide\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"extendToSubpages\":\"\",\"fe_group\":\"\",\"fe_login_mode\":\"\",\"editlock\":\"\",\"categories\":\"\",\"rowDescription\":\"\"}',0,0,0,0,2,0,31,27,0,'Mein Arbeitsplatz','/mein-arbeitsplatz',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(7,1,1655119942,1655103213,2,0,0,0,0,'',240,NULL,0,0,0,0,NULL,0,'{\"doktype\":\"\",\"title\":\"\",\"slug\":\"\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":\"\",\"no_index\":\"\",\"no_follow\":\"\",\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"\",\"og_title\":\"\",\"og_description\":\"\",\"og_image\":\"\",\"twitter_title\":\"\",\"twitter_description\":\"\",\"twitter_image\":\"\",\"twitter_card\":\"\",\"abstract\":\"\",\"keywords\":\"\",\"author\":\"\",\"author_email\":\"\",\"lastUpdated\":\"\",\"layout\":\"\",\"newUntil\":\"\",\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"content_from_pid\":\"\",\"target\":\"\",\"cache_timeout\":\"\",\"cache_tags\":\"\",\"is_siteroot\":\"\",\"no_search\":\"\",\"php_tree_stop\":\"\",\"module\":\"\",\"media\":\"\",\"tsconfig_includes\":\"\",\"TSconfig\":\"\",\"l18n_cfg\":\"\",\"hidden\":\"\",\"nav_hide\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"extendToSubpages\":\"\",\"fe_group\":\"\",\"fe_login_mode\":\"\",\"editlock\":\"\",\"categories\":\"\",\"rowDescription\":\"\"}',0,0,0,0,2,0,31,27,0,'Services','/services',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(8,1,1655119949,1655103213,2,0,0,0,0,'',248,NULL,0,0,0,0,NULL,0,'{\"doktype\":\"\",\"title\":\"\",\"slug\":\"\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":\"\",\"no_index\":\"\",\"no_follow\":\"\",\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"\",\"og_title\":\"\",\"og_description\":\"\",\"og_image\":\"\",\"twitter_title\":\"\",\"twitter_description\":\"\",\"twitter_image\":\"\",\"twitter_card\":\"\",\"abstract\":\"\",\"keywords\":\"\",\"author\":\"\",\"author_email\":\"\",\"lastUpdated\":\"\",\"layout\":\"\",\"newUntil\":\"\",\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"content_from_pid\":\"\",\"target\":\"\",\"cache_timeout\":\"\",\"cache_tags\":\"\",\"is_siteroot\":\"\",\"no_search\":\"\",\"php_tree_stop\":\"\",\"module\":\"\",\"media\":\"\",\"tsconfig_includes\":\"\",\"TSconfig\":\"\",\"l18n_cfg\":\"\",\"hidden\":\"\",\"nav_hide\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"extendToSubpages\":\"\",\"fe_group\":\"\",\"fe_login_mode\":\"\",\"editlock\":\"\",\"categories\":\"\",\"rowDescription\":\"\"}',0,0,0,0,2,0,31,27,0,'Mitarbeiterverzeichnis','/mitarbeiterverzeichnis',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(9,4,1655119918,1655103275,2,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Organisation & Management','/unser-zentrum/organisation-management',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1655119918,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(10,4,1655119918,1655103275,2,0,0,0,0,'0',512,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Wissenschaftliche Einheiten','/unser-zentrum/wissenschaftliche-einheiten',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(11,4,1655119918,1655103275,2,0,0,0,0,'0',768,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Administration & Stabsstellen','/unser-zentrum/administration-stabsstellen',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(12,4,1655119918,1655103275,2,0,0,0,0,'0',1024,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Kooperationen','/unser-zentrum/kooperationen',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(13,4,1655119918,1655103275,2,0,0,0,0,'0',1280,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Gremien & Vertretungen','/unser-zentrum/gremien-vertretungen',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(14,13,1655119918,1655103366,2,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Mitarbeitervertretung','/unser-zentrum/gremien-vertretungen/mitarbeitervertretung',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(15,13,1655119918,1655103366,2,0,0,0,0,'0',512,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Wissenschaftlicher Rat','/unser-zentrum/gremien-vertretungen/wissenschaftlicher-rat',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1655119918,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(16,13,1655119918,1655103366,2,0,0,0,0,'0',768,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'International Review Board','/unser-zentrum/gremien-vertretungen/international-review-board',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(17,13,1655119918,1655103366,2,0,0,0,0,'0',1024,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Tierschutzausschuss','/unser-zentrum/gremien-vertretungen/tierschutzausschuss',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(18,13,1655119918,1655103366,2,0,0,0,0,'0',1280,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Changengleichheit','/unser-zentrum/gremien-vertretungen/changengleichheit',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(19,13,1655119918,1655103366,2,0,0,0,0,'0',1536,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Beiräte','/unser-zentrum/gremien-vertretungen/beiraete',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(20,5,1655104158,1655103432,2,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'News','/aktuelles/news',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(21,5,1655104158,1655103432,2,0,0,0,0,'0',512,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Kurzmeldungen','/aktuelles/kurzmeldungen',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(22,5,1655104158,1655103432,2,0,0,0,0,'0',768,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Veranstaltungen','/aktuelles/veranstaltungen',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(23,5,1655104158,1655103432,2,0,0,0,0,'0',1024,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Aus- und Weiterbildung heute','/aktuelles/aus-und-weiterbildung-heute',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(24,5,1655104158,1655103432,2,0,0,0,0,'0',1280,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Speiseplan Casino','/aktuelles/speiseplan-casino',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(25,5,1655104158,1655103432,2,0,0,0,0,'0',1536,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Stellenangebote','/aktuelles/stellenangebote',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1655103432,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(26,5,1655104158,1655103432,2,0,0,0,0,'0',1792,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Schwarzes Brett','/aktuelles/schwarzes-brett',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(27,6,1655119930,1655103538,2,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Organisatorisches','/mein-arbeitsplatz/organisatorisches',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(28,6,1655119930,1655103538,2,0,0,0,0,'0',512,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Gesundheit','/mein-arbeitsplatz/gesundheit',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(29,6,1655119930,1655103538,2,0,0,0,0,'0',768,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Weiterbildung & Karriere','/mein-arbeitsplatz/weiterbildung-karriere',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(30,6,1655119930,1655103538,2,0,0,0,0,'0',1024,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'DKFZ Kultur','/mein-arbeitsplatz/dkfz-kultur',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(31,6,1655119930,1655103538,2,0,0,0,0,'0',1280,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Sicherheit','/mein-arbeitsplatz/sicherheit',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(32,27,1655119930,1655103586,2,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Mitarbeiterportal','/mein-arbeitsplatz/organisatorisches/mitarbeiterportal',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(33,27,1655119930,1655103586,2,0,0,0,0,'0',512,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Personalservice','/mein-arbeitsplatz/organisatorisches/personalservice',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(34,27,1655119930,1655103586,2,0,0,0,0,'0',768,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Infos für neue Mitarbeiter','/mein-arbeitsplatz/organisatorisches/infos-fuer-neue-mitarbeiter',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(35,27,1655119930,1655103586,2,0,0,0,0,'0',1024,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Info A-Z','/mein-arbeitsplatz/organisatorisches/info-a-z',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(36,28,1655119930,1655103636,2,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Betriebsärztlicher Dienst','/mein-arbeitsplatz/gesundheit/betriebsaerztlicher-dienst',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(37,28,1655119930,1655103636,2,0,0,0,0,'0',512,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Berufsbegleitende Maßnahmen','/mein-arbeitsplatz/gesundheit/berufsbegleitende-massnahmen',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(38,28,1655119930,1655103636,2,0,0,0,0,'0',768,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Mental Health','/mein-arbeitsplatz/gesundheit/mental-health',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(39,28,1655119930,1655103636,2,0,0,0,0,'0',1024,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Krebsinformationsdienst','/mein-arbeitsplatz/gesundheit/krebsinformationsdienst',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(40,28,1655119930,1655103636,2,0,0,0,0,'0',1280,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Krebsprävention','/mein-arbeitsplatz/gesundheit/krebspraevention',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(41,29,1655119930,1655103671,2,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Ausbildung','/mein-arbeitsplatz/weiterbildung-karriere/ausbildung',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1655103671,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(42,29,1655119930,1655103671,2,0,0,0,0,'0',512,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Weiterbildung','/mein-arbeitsplatz/weiterbildung-karriere/weiterbildung',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(43,29,1655119930,1655103671,2,0,0,0,0,'0',768,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Personalentwicklung','/mein-arbeitsplatz/weiterbildung-karriere/personalentwicklung',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(44,29,1655119930,1655103671,2,0,0,0,0,'0',1024,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Carrerservice','/mein-arbeitsplatz/weiterbildung-karriere/carrerservice',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(45,29,1655119930,1655103671,2,0,0,0,0,'0',1280,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Förderprogramme','/mein-arbeitsplatz/weiterbildung-karriere/foerderprogramme',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(46,29,1655119930,1655103678,2,0,0,0,0,'0',1536,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'DKFZ Akademie','/mein-arbeitsplatz/weiterbildung-karriere/default-title',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(47,30,1655119930,1655103744,2,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Beruf & Familie','/mein-arbeitsplatz/dkfz-kultur/beruf-familie',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(48,30,1655119930,1655103744,2,0,0,0,0,'0',512,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Chancengleichheit','/mein-arbeitsplatz/dkfz-kultur/chancengleichheit',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(49,30,1655119930,1655103744,2,0,0,0,0,'0',768,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Compliance','/mein-arbeitsplatz/dkfz-kultur/compliance',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(50,30,1655119930,1655103744,2,0,0,0,0,'0',1024,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Netzwerke','/mein-arbeitsplatz/dkfz-kultur/netzwerke',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(51,30,1655119930,1655103744,2,0,0,0,0,'0',1280,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Nachhaltigkeit','/mein-arbeitsplatz/dkfz-kultur/nachhaltigkeit',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(52,30,1655119930,1655103744,2,0,0,0,0,'0',1536,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Konflikte','/mein-arbeitsplatz/dkfz-kultur/konflikte',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(53,31,1655119930,1655103795,2,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Sicherheit','/mein-arbeitsplatz/sicherheit/sicherheit',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(54,31,1655119930,1655103795,2,0,0,0,0,'0',512,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Biologische Sicherheit','/mein-arbeitsplatz/sicherheit/biologische-sicherheit',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(55,31,1655119930,1655103795,2,0,0,0,0,'0',768,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Infos zu Notfällen','/mein-arbeitsplatz/sicherheit/infos-zu-notfaellen',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(56,31,1655119930,1655103795,2,0,0,0,0,'0',1024,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Strahlenschutz & Dosimetrie','/mein-arbeitsplatz/sicherheit/strahlenschutz-dosimetrie',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(57,31,1655119930,1655103795,2,0,0,0,0,'0',1280,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'IT-Sicherheit','/mein-arbeitsplatz/sicherheit/it-sicherheit',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(58,7,1655119942,1655103831,2,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'IT-Service','/services/it-service',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(59,7,1655119942,1655103831,2,0,0,0,0,'0',512,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Services & Dienste','/services/services-dienste',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(60,7,1655119942,1655103831,2,0,0,0,0,'0',768,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Wissenschaftliche Dienste','/services/wissenschaftliche-dienste',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(61,58,1655119942,1655103848,2,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Service Center','/services/it-service/service-center',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(62,58,1655119942,1655103848,2,0,0,0,0,'0',512,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'User Portal','/services/it-service/user-portal',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(63,58,1655119942,1655103848,2,0,0,0,0,'0',768,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'WIKI','/services/it-service/wiki',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(64,59,1655119942,1655103918,2,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Abfall','/services/services-dienste/abfall',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(65,59,1655119942,1655103918,2,0,0,0,0,'0',512,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Buchung von Ressourcen','/services/services-dienste/buchung-von-ressourcen',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(66,59,1655119942,1655103918,2,0,0,0,0,'0',768,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Corporate Design','/services/services-dienste/corporate-design',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(67,59,1655119942,1655103918,2,0,0,0,0,'0',1024,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Einkauf','/services/services-dienste/einkauf',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(68,59,1655119942,1655103918,2,0,0,0,0,'0',1280,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Finanzen','/services/services-dienste/finanzen',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(69,59,1655119942,1655103918,2,0,0,0,0,'0',1536,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Geräte','/services/services-dienste/geraete',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(70,59,1655119942,1655103918,2,0,0,0,0,'0',1792,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Hausverwaltung','/services/services-dienste/hausverwaltung',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(71,59,1655119942,1655103918,2,0,0,0,0,'0',2048,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Recht','/services/services-dienste/recht',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(72,59,1655119942,1655103918,2,0,0,0,0,'0',2304,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Technik','/services/services-dienste/technik',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(73,59,1655119942,1655103918,2,0,0,0,0,'0',2560,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Veranstaltungen','/services/services-dienste/veranstaltungen',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(74,59,1655119942,1655103918,2,0,0,0,0,'0',2816,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Warenannahme','/services/services-dienste/warenannahme',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(75,59,1655119942,1655103918,2,0,0,0,0,'0',3072,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Warenlager','/services/services-dienste/warenlager',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(76,59,1655119942,1655103918,2,0,0,0,0,'0',3328,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Platzhalter','/services/services-dienste/platzhalter',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(77,60,1655119942,1655104047,2,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Core Facilies','/services/wissenschaftliche-dienste/core-facilies',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(78,60,1655119942,1655104047,2,0,0,0,0,'0',512,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'SNOMED CT@DKFZ','/services/wissenschaftliche-dienste/snomed-ctdkfz',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(79,60,1655119942,1655104047,2,0,0,0,0,'0',768,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Drittmittel','/services/wissenschaftliche-dienste/drittmittel',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(80,60,1655119942,1655104047,2,0,0,0,0,'0',1024,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Innovation Management','/services/wissenschaftliche-dienste/innovation-management',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(81,60,1655119942,1655104047,2,0,0,0,0,'0',1280,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'QM Klinischer Forschung','/services/wissenschaftliche-dienste/qm-klinischer-forschung',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(82,60,1655119942,1655104047,2,0,0,0,0,'0',1536,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Clinical Trial Office','/services/wissenschaftliche-dienste/clinical-trial-office',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(83,60,1655119942,1655104047,2,0,0,0,0,'0',1792,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Scientific Computing Infrastructure','/services/wissenschaftliche-dienste/scientific-computing-infrastructure',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(84,60,1655119942,1655104047,2,0,0,0,0,'0',2048,NULL,0,0,0,0,NULL,0,'{\"slug\":\"\"}',0,0,0,0,2,0,31,27,0,'Carl Zeiss App. C','/services/wissenschaftliche-dienste/carl-zeiss-app-c',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(85,20,1655121220,1655118462,2,0,0,0,0,'',64,NULL,0,0,0,0,NULL,0,'{\"shortcut_mode\":\"\"}',0,0,0,0,2,0,31,27,0,'Wissenschaft','/aktuelles/news/wissenschaft',4,NULL,0,0,'',0,3,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(86,20,1655121220,1655120933,2,0,0,0,0,'0',128,NULL,0,0,0,0,NULL,0,'{\"shortcut_mode\":\"\"}',0,0,0,0,2,0,31,27,0,'Innenansichten','/aktuelles/news/innenansichten',4,NULL,0,0,'',0,3,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(87,20,1655121220,1655120933,2,0,0,0,0,'0',192,NULL,0,0,0,0,NULL,0,'{\"shortcut_mode\":\"\"}',0,0,0,0,2,0,31,27,0,'Ausgezeichnet','/aktuelles/news/ausgezeichnet',4,NULL,0,0,'',0,3,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(88,20,1655121220,1655120933,2,0,0,0,0,'0',224,NULL,0,0,0,0,NULL,0,'{\"shortcut_mode\":\"\"}',0,0,0,0,2,0,31,27,0,'Kooperationen','/aktuelles/news/kooperationen',4,NULL,0,0,'',0,3,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(89,20,1655121220,1655120933,2,0,0,0,0,'0',240,NULL,0,0,0,0,NULL,0,'{\"shortcut_mode\":\"\"}',0,0,0,0,2,0,31,27,0,'Ankündigungen','/aktuelles/news/ankuendigungen',4,NULL,0,0,'',0,3,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,''),(90,20,1655121220,1655120933,2,0,0,0,0,'0',248,NULL,0,0,0,0,NULL,0,'{\"shortcut_mode\":\"\"}',0,0,0,0,2,0,31,27,0,'Sonstiges','/aktuelles/news/sonstiges',4,NULL,0,0,'',0,3,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_be_shortcuts`
--

DROP TABLE IF EXISTS `sys_be_shortcuts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_be_shortcuts` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT 0,
  `route` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `arguments` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sorting` int(11) NOT NULL DEFAULT 0,
  `sc_group` smallint(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `event` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_be_shortcuts`
--

LOCK TABLES `sys_be_shortcuts` WRITE;
/*!40000 ALTER TABLE `sys_be_shortcuts` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_be_shortcuts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_category`
--

DROP TABLE IF EXISTS `sys_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_category` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `title` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `items` int(11) NOT NULL DEFAULT 0,
  `parent` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `category_parent` (`parent`),
  KEY `category_list` (`pid`,`deleted`,`sys_language_uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_category`
--

LOCK TABLES `sys_category` WRITE;
/*!40000 ALTER TABLE `sys_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_category_record_mm`
--

DROP TABLE IF EXISTS `sys_category_record_mm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_category_record_mm` (
  `uid_local` int(10) unsigned NOT NULL DEFAULT 0,
  `uid_foreign` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting_foreign` int(10) unsigned NOT NULL DEFAULT 0,
  `tablenames` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fieldname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  KEY `uid_local` (`uid_local`),
  KEY `uid_foreign` (`uid_foreign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_category_record_mm`
--

LOCK TABLES `sys_category_record_mm` WRITE;
/*!40000 ALTER TABLE `sys_category_record_mm` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_category_record_mm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_file`
--

DROP TABLE IF EXISTS `sys_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `last_indexed` int(11) NOT NULL DEFAULT 0,
  `missing` smallint(6) NOT NULL DEFAULT 0,
  `storage` int(11) NOT NULL DEFAULT 0,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `metadata` int(11) NOT NULL DEFAULT 0,
  `identifier` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `identifier_hash` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `folder_hash` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `extension` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `mime_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `sha1` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `size` bigint(20) unsigned NOT NULL DEFAULT 0,
  `creation_date` int(11) NOT NULL DEFAULT 0,
  `modification_date` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `sel01` (`storage`,`identifier_hash`),
  KEY `folder` (`storage`,`folder_hash`),
  KEY `tstamp` (`tstamp`),
  KEY `lastindex` (`last_indexed`),
  KEY `sha1` (`sha1`),
  KEY `parent` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_file`
--

LOCK TABLES `sys_file` WRITE;
/*!40000 ALTER TABLE `sys_file` DISABLE KEYS */;
INSERT INTO `sys_file` VALUES (1,0,1654763594,0,0,0,'2',0,'/_assets/0b6dd5feb2097ed0ac0e13df5f7783a1/Images/logo/logo.svg','235018fc7a2b84300e2c49ca536757a298270444','23f77cd24e1c1736686b1e05bdac99329cc63c0a','svg','image/svg+xml','logo.svg','89a492f01798a6716f158267a9e225cf3605789a',5517,1654697909,1654697909),(2,0,1654763643,0,0,0,'2',0,'/_assets/0b6dd5feb2097ed0ac0e13df5f7783a1/Images/examples/user.jpg','4d8e494f84664794c8befe246fb2650c7a0b5730','9feda1b80a5e892dfea8f20028dfac2c1d7daf62','jpg','image/jpeg','user.jpg','c626010bc377a17e725b35ac9bd9f2856d22ef6d',9036,1654697909,1654697909);
/*!40000 ALTER TABLE `sys_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_file_collection`
--

DROP TABLE IF EXISTS `sys_file_collection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file_collection` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `title` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'static',
  `files` int(11) NOT NULL DEFAULT 0,
  `storage` int(11) NOT NULL DEFAULT 0,
  `folder` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `recursive` smallint(6) NOT NULL DEFAULT 0,
  `category` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_file_collection`
--

LOCK TABLES `sys_file_collection` WRITE;
/*!40000 ALTER TABLE `sys_file_collection` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_file_collection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_file_metadata`
--

DROP TABLE IF EXISTS `sys_file_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file_metadata` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `file` int(11) NOT NULL DEFAULT 0,
  `title` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `width` int(11) NOT NULL DEFAULT 0,
  `height` int(11) NOT NULL DEFAULT 0,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `alternative` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `categories` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `file` (`file`),
  KEY `fal_filelist` (`l10n_parent`,`sys_language_uid`),
  KEY `parent` (`pid`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_file_metadata`
--

LOCK TABLES `sys_file_metadata` WRITE;
/*!40000 ALTER TABLE `sys_file_metadata` DISABLE KEYS */;
INSERT INTO `sys_file_metadata` VALUES (1,0,1654763593,1654763593,2,0,0,NULL,0,'',0,0,0,0,1,NULL,100,100,NULL,NULL,0),(2,0,1654763643,1654763643,2,0,0,NULL,0,'',0,0,0,0,2,NULL,150,150,NULL,NULL,0);
/*!40000 ALTER TABLE `sys_file_metadata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_file_reference`
--

DROP TABLE IF EXISTS `sys_file_reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file_reference` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `uid_local` int(11) NOT NULL DEFAULT 0,
  `uid_foreign` int(11) NOT NULL DEFAULT 0,
  `tablenames` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fieldname` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sorting_foreign` int(11) NOT NULL DEFAULT 0,
  `table_local` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `alternative` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` varchar(1024) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `crop` varchar(4000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `autoplay` smallint(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `tablenames_fieldname` (`tablenames`(32),`fieldname`(12)),
  KEY `deleted` (`deleted`),
  KEY `uid_local` (`uid_local`),
  KEY `uid_foreign` (`uid_foreign`),
  KEY `combined_1` (`l10n_parent`,`t3ver_oid`,`t3ver_wsid`,`t3ver_state`,`deleted`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_file_reference`
--

LOCK TABLES `sys_file_reference` WRITE;
/*!40000 ALTER TABLE `sys_file_reference` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_file_reference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_file_storage`
--

DROP TABLE IF EXISTS `sys_file_storage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file_storage` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `driver` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `configuration` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_default` smallint(6) NOT NULL DEFAULT 0,
  `is_browsable` smallint(6) NOT NULL DEFAULT 0,
  `is_public` smallint(6) NOT NULL DEFAULT 0,
  `is_writable` smallint(6) NOT NULL DEFAULT 0,
  `is_online` smallint(6) NOT NULL DEFAULT 1,
  `auto_extract_metadata` smallint(6) NOT NULL DEFAULT 1,
  `processingfolder` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_file_storage`
--

LOCK TABLES `sys_file_storage` WRITE;
/*!40000 ALTER TABLE `sys_file_storage` DISABLE KEYS */;
INSERT INTO `sys_file_storage` VALUES (1,0,1654616900,1654616900,0,0,'This is the local fileadmin/ directory. This storage mount has been created automatically by TYPO3.','fileadmin','Local','<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"basePath\">\n                    <value index=\"vDEF\">fileadmin/</value>\n                </field>\n                <field index=\"pathType\">\n                    <value index=\"vDEF\">relative</value>\n                </field>\n                <field index=\"caseSensitive\">\n                    <value index=\"vDEF\">1</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>',1,1,1,1,1,1,NULL);
/*!40000 ALTER TABLE `sys_file_storage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_filemounts`
--

DROP TABLE IF EXISTS `sys_filemounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_filemounts` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `base` int(10) unsigned NOT NULL DEFAULT 0,
  `read_only` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_filemounts`
--

LOCK TABLES `sys_filemounts` WRITE;
/*!40000 ALTER TABLE `sys_filemounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_filemounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_language`
--

DROP TABLE IF EXISTS `sys_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_language` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `title` varchar(80) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `flag` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `language_isocode` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_language`
--

LOCK TABLES `sys_language` WRITE;
/*!40000 ALTER TABLE `sys_language` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_lockedrecords`
--

DROP TABLE IF EXISTS `sys_lockedrecords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_lockedrecords` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `record_table` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `record_uid` int(11) NOT NULL DEFAULT 0,
  `record_pid` int(11) NOT NULL DEFAULT 0,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `feuserid` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `event` (`userid`,`tstamp`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_lockedrecords`
--

LOCK TABLES `sys_lockedrecords` WRITE;
/*!40000 ALTER TABLE `sys_lockedrecords` DISABLE KEYS */;
INSERT INTO `sys_lockedrecords` VALUES (76,2,1655122404,'pages',4,0,'admin',0);
/*!40000 ALTER TABLE `sys_lockedrecords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_news`
--

DROP TABLE IF EXISTS `sys_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_news` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `content` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_news`
--

LOCK TABLES `sys_news` WRITE;
/*!40000 ALTER TABLE `sys_news` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_redirect`
--

DROP TABLE IF EXISTS `sys_redirect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_redirect` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `updatedon` int(10) unsigned NOT NULL DEFAULT 0,
  `createdon` int(10) unsigned NOT NULL DEFAULT 0,
  `createdby` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `disabled` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `source_host` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `source_path` varchar(2048) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `is_regexp` smallint(5) unsigned NOT NULL DEFAULT 0,
  `protected` smallint(5) unsigned NOT NULL DEFAULT 0,
  `force_https` smallint(5) unsigned NOT NULL DEFAULT 0,
  `respect_query_parameters` smallint(5) unsigned NOT NULL DEFAULT 0,
  `keep_query_parameters` smallint(5) unsigned NOT NULL DEFAULT 0,
  `target` varchar(2048) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `target_statuscode` int(11) NOT NULL DEFAULT 307,
  `hitcount` int(11) NOT NULL DEFAULT 0,
  `lasthiton` int(11) NOT NULL DEFAULT 0,
  `disable_hitcount` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `index_source` (`source_host`(80),`source_path`(80)),
  KEY `parent` (`pid`,`deleted`,`disabled`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_redirect`
--

LOCK TABLES `sys_redirect` WRITE;
/*!40000 ALTER TABLE `sys_redirect` DISABLE KEYS */;
INSERT INTO `sys_redirect` VALUES (1,5,1655104163,1655104158,2,1,0,0,0,'xm-dkfz-net.ddev.site','/login/aktuelles',0,0,0,0,0,'t3://page?uid=5&_language=0',307,0,0,0),(2,5,1655104163,1655104158,2,1,0,0,0,'xm-dkfz-net.ddev.site','/login/aktuelles/news',0,0,0,0,0,'t3://page?uid=20&_language=0',307,0,0,0),(3,5,1655104163,1655104158,2,1,0,0,0,'xm-dkfz-net.ddev.site','/login/aktuelles/kurzmeldungen',0,0,0,0,0,'t3://page?uid=21&_language=0',307,0,0,0),(4,5,1655104163,1655104158,2,1,0,0,0,'xm-dkfz-net.ddev.site','/login/aktuelles/veranstaltungen',0,0,0,0,0,'t3://page?uid=22&_language=0',307,0,0,0),(5,5,1655104163,1655104158,2,1,0,0,0,'xm-dkfz-net.ddev.site','/login/aktuelles/aus-und-weiterbildung-heute',0,0,0,0,0,'t3://page?uid=23&_language=0',307,0,0,0),(6,5,1655104163,1655104158,2,1,0,0,0,'xm-dkfz-net.ddev.site','/login/aktuelles/speiseplan-casino',0,0,0,0,0,'t3://page?uid=24&_language=0',307,0,0,0),(7,5,1655104163,1655104158,2,1,0,0,0,'xm-dkfz-net.ddev.site','/login/aktuelles/stellenangebote',0,0,0,0,0,'t3://page?uid=25&_language=0',307,0,0,0),(8,5,1655104163,1655104158,2,1,0,0,0,'xm-dkfz-net.ddev.site','/login/aktuelles/schwarzes-brett',0,0,0,0,0,'t3://page?uid=26&_language=0',307,0,0,0),(9,4,1655119922,1655119918,2,1,0,0,0,'*','/login/unser-zentrum',0,0,0,0,0,'t3://page?uid=4&_language=0',307,0,0,0),(10,4,1655119922,1655119918,2,1,0,0,0,'*','/login/unser-zentrum/organisation-management',0,0,0,0,0,'t3://page?uid=9&_language=0',307,0,0,0),(11,4,1655119922,1655119918,2,1,0,0,0,'*','/login/unser-zentrum/wissenschaftliche-einheiten',0,0,0,0,0,'t3://page?uid=10&_language=0',307,0,0,0),(12,4,1655119922,1655119918,2,1,0,0,0,'*','/login/unser-zentrum/administration-stabsstellen',0,0,0,0,0,'t3://page?uid=11&_language=0',307,0,0,0),(13,4,1655119922,1655119918,2,1,0,0,0,'*','/login/unser-zentrum/kooperationen',0,0,0,0,0,'t3://page?uid=12&_language=0',307,0,0,0),(14,4,1655119922,1655119918,2,1,0,0,0,'*','/login/unser-zentrum/gremien-vertretungen',0,0,0,0,0,'t3://page?uid=13&_language=0',307,0,0,0),(15,4,1655119922,1655119918,2,1,0,0,0,'*','/login/unser-zentrum/gremien-vertretungen/mitarbeitervertretung',0,0,0,0,0,'t3://page?uid=14&_language=0',307,0,0,0),(16,4,1655119922,1655119918,2,1,0,0,0,'*','/login/unser-zentrum/gremien-vertretungen/wissenschaftlicher-rat',0,0,0,0,0,'t3://page?uid=15&_language=0',307,0,0,0),(17,4,1655119922,1655119918,2,1,0,0,0,'*','/login/unser-zentrum/gremien-vertretungen/international-review-board',0,0,0,0,0,'t3://page?uid=16&_language=0',307,0,0,0),(18,4,1655119922,1655119918,2,1,0,0,0,'*','/login/unser-zentrum/gremien-vertretungen/tierschutzausschuss',0,0,0,0,0,'t3://page?uid=17&_language=0',307,0,0,0),(19,4,1655119922,1655119918,2,1,0,0,0,'*','/login/unser-zentrum/gremien-vertretungen/changengleichheit',0,0,0,0,0,'t3://page?uid=18&_language=0',307,0,0,0),(20,4,1655119922,1655119918,2,1,0,0,0,'*','/login/unser-zentrum/gremien-vertretungen/beiraete',0,0,0,0,0,'t3://page?uid=19&_language=0',307,0,0,0),(21,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz',0,0,0,0,0,'t3://page?uid=6&_language=0',307,0,0,0),(22,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/organisatorisches',0,0,0,0,0,'t3://page?uid=27&_language=0',307,0,0,0),(23,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/organisatorisches/mitarbeiterportal',0,0,0,0,0,'t3://page?uid=32&_language=0',307,0,0,0),(24,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/organisatorisches/personalservice',0,0,0,0,0,'t3://page?uid=33&_language=0',307,0,0,0),(25,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/organisatorisches/infos-fuer-neue-mitarbeiter',0,0,0,0,0,'t3://page?uid=34&_language=0',307,0,0,0),(26,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/organisatorisches/info-a-z',0,0,0,0,0,'t3://page?uid=35&_language=0',307,0,0,0),(27,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/gesundheit',0,0,0,0,0,'t3://page?uid=28&_language=0',307,0,0,0),(28,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/gesundheit/betriebsaerztlicher-dienst',0,0,0,0,0,'t3://page?uid=36&_language=0',307,0,0,0),(29,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/gesundheit/berufsbegleitende-massnahmen',0,0,0,0,0,'t3://page?uid=37&_language=0',307,0,0,0),(30,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/gesundheit/mental-health',0,0,0,0,0,'t3://page?uid=38&_language=0',307,0,0,0),(31,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/gesundheit/krebsinformationsdienst',0,0,0,0,0,'t3://page?uid=39&_language=0',307,0,0,0),(32,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/gesundheit/krebspraevention',0,0,0,0,0,'t3://page?uid=40&_language=0',307,0,0,0),(33,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/weiterbildung-karriere',0,0,0,0,0,'t3://page?uid=29&_language=0',307,0,0,0),(34,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/weiterbildung-karriere/ausbildung',0,0,0,0,0,'t3://page?uid=41&_language=0',307,0,0,0),(35,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/weiterbildung-karriere/weiterbildung',0,0,0,0,0,'t3://page?uid=42&_language=0',307,0,0,0),(36,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/weiterbildung-karriere/personalentwicklung',0,0,0,0,0,'t3://page?uid=43&_language=0',307,0,0,0),(37,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/weiterbildung-karriere/carrerservice',0,0,0,0,0,'t3://page?uid=44&_language=0',307,0,0,0),(38,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/weiterbildung-karriere/foerderprogramme',0,0,0,0,0,'t3://page?uid=45&_language=0',307,0,0,0),(39,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/weiterbildung-karriere/default-title',0,0,0,0,0,'t3://page?uid=46&_language=0',307,0,0,0),(40,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/dkfz-kultur',0,0,0,0,0,'t3://page?uid=30&_language=0',307,0,0,0),(41,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/dkfz-kultur/beruf-familie',0,0,0,0,0,'t3://page?uid=47&_language=0',307,0,0,0),(42,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/dkfz-kultur/chancengleichheit',0,0,0,0,0,'t3://page?uid=48&_language=0',307,0,0,0),(43,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/dkfz-kultur/compliance',0,0,0,0,0,'t3://page?uid=49&_language=0',307,0,0,0),(44,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/dkfz-kultur/netzwerke',0,0,0,0,0,'t3://page?uid=50&_language=0',307,0,0,0),(45,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/dkfz-kultur/nachhaltigkeit',0,0,0,0,0,'t3://page?uid=51&_language=0',307,0,0,0),(46,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/dkfz-kultur/konflikte',0,0,0,0,0,'t3://page?uid=52&_language=0',307,0,0,0),(47,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/sicherheit',0,0,0,0,0,'t3://page?uid=31&_language=0',307,0,0,0),(48,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/sicherheit/sicherheit',0,0,0,0,0,'t3://page?uid=53&_language=0',307,0,0,0),(49,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/sicherheit/biologische-sicherheit',0,0,0,0,0,'t3://page?uid=54&_language=0',307,0,0,0),(50,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/sicherheit/infos-zu-notfaellen',0,0,0,0,0,'t3://page?uid=55&_language=0',307,0,0,0),(51,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/sicherheit/strahlenschutz-dosimetrie',0,0,0,0,0,'t3://page?uid=56&_language=0',307,0,0,0),(52,6,1655119934,1655119930,2,1,0,0,0,'*','/login/mein-arbeitsplatz/sicherheit/it-sicherheit',0,0,0,0,0,'t3://page?uid=57&_language=0',307,0,0,0),(53,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services',0,0,0,0,0,'t3://page?uid=7&_language=0',307,0,0,0),(54,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/it-service',0,0,0,0,0,'t3://page?uid=58&_language=0',307,0,0,0),(55,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/it-service/service-center',0,0,0,0,0,'t3://page?uid=61&_language=0',307,0,0,0),(56,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/it-service/user-portal',0,0,0,0,0,'t3://page?uid=62&_language=0',307,0,0,0),(57,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/it-service/wiki',0,0,0,0,0,'t3://page?uid=63&_language=0',307,0,0,0),(58,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/services-dienste',0,0,0,0,0,'t3://page?uid=59&_language=0',307,0,0,0),(59,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/services-dienste/abfall',0,0,0,0,0,'t3://page?uid=64&_language=0',307,0,0,0),(60,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/services-dienste/buchung-von-ressourcen',0,0,0,0,0,'t3://page?uid=65&_language=0',307,0,0,0),(61,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/services-dienste/corporate-design',0,0,0,0,0,'t3://page?uid=66&_language=0',307,0,0,0),(62,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/services-dienste/einkauf',0,0,0,0,0,'t3://page?uid=67&_language=0',307,0,0,0),(63,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/services-dienste/finanzen',0,0,0,0,0,'t3://page?uid=68&_language=0',307,0,0,0),(64,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/services-dienste/geraete',0,0,0,0,0,'t3://page?uid=69&_language=0',307,0,0,0),(65,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/services-dienste/hausverwaltung',0,0,0,0,0,'t3://page?uid=70&_language=0',307,0,0,0),(66,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/services-dienste/recht',0,0,0,0,0,'t3://page?uid=71&_language=0',307,0,0,0),(67,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/services-dienste/technik',0,0,0,0,0,'t3://page?uid=72&_language=0',307,0,0,0),(68,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/services-dienste/veranstaltungen',0,0,0,0,0,'t3://page?uid=73&_language=0',307,0,0,0),(69,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/services-dienste/warenannahme',0,0,0,0,0,'t3://page?uid=74&_language=0',307,0,0,0),(70,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/services-dienste/warenlager',0,0,0,0,0,'t3://page?uid=75&_language=0',307,0,0,0),(71,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/services-dienste/platzhalter',0,0,0,0,0,'t3://page?uid=76&_language=0',307,0,0,0),(72,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/wissenschaftliche-dienste',0,0,0,0,0,'t3://page?uid=60&_language=0',307,0,0,0),(73,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/wissenschaftliche-dienste/core-facilies',0,0,0,0,0,'t3://page?uid=77&_language=0',307,0,0,0),(74,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/wissenschaftliche-dienste/snomed-ctdkfz',0,0,0,0,0,'t3://page?uid=78&_language=0',307,0,0,0),(75,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/wissenschaftliche-dienste/drittmittel',0,0,0,0,0,'t3://page?uid=79&_language=0',307,0,0,0),(76,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/wissenschaftliche-dienste/innovation-management',0,0,0,0,0,'t3://page?uid=80&_language=0',307,0,0,0),(77,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/wissenschaftliche-dienste/qm-klinischer-forschung',0,0,0,0,0,'t3://page?uid=81&_language=0',307,0,0,0),(78,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/wissenschaftliche-dienste/clinical-trial-office',0,0,0,0,0,'t3://page?uid=82&_language=0',307,0,0,0),(79,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/wissenschaftliche-dienste/scientific-computing-infrastructure',0,0,0,0,0,'t3://page?uid=83&_language=0',307,0,0,0),(80,7,1655119951,1655119942,2,1,0,0,0,'*','/login/services/wissenschaftliche-dienste/carl-zeiss-app-c',0,0,0,0,0,'t3://page?uid=84&_language=0',307,0,0,0),(81,8,1655119952,1655119949,2,1,0,0,0,'*','/login/mitarbeiterverzeichnis',0,0,0,0,0,'t3://page?uid=8&_language=0',307,0,0,0);
/*!40000 ALTER TABLE `sys_redirect` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_registry`
--

DROP TABLE IF EXISTS `sys_registry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_registry` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_namespace` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `entry_key` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `entry_value` mediumblob DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `entry_identifier` (`entry_namespace`,`entry_key`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_registry`
--

LOCK TABLES `sys_registry` WRITE;
/*!40000 ALTER TABLE `sys_registry` DISABLE KEYS */;
INSERT INTO `sys_registry` VALUES (1,'core','formProtectionSessionToken:2','s:64:\"d554d6a100c607f846e1c9b1dee49d9f6021c0ef557e242c36a710ec780aa4bc\";'),(2,'languagePacks','de-bw_static_template','i:1655121365;'),(3,'languagePacks','de-xm_dkfz_net_jobs','i:1655121369;'),(4,'languagePacks','de-xm_mail_catcher','i:1655121369;'),(5,'languagePacks','de','i:1655121374;');
/*!40000 ALTER TABLE `sys_registry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_template`
--

DROP TABLE IF EXISTS `sys_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_template` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `root` smallint(5) unsigned NOT NULL DEFAULT 0,
  `clear` smallint(5) unsigned NOT NULL DEFAULT 0,
  `include_static_file` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `constants` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `config` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `basedOn` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `includeStaticAfterBasedOn` smallint(5) unsigned NOT NULL DEFAULT 0,
  `static_file_mode` smallint(5) unsigned NOT NULL DEFAULT 0,
  `tx_impexp_origuid` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `roottemplate` (`deleted`,`hidden`,`root`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_template`
--

LOCK TABLES `sys_template` WRITE;
/*!40000 ALTER TABLE `sys_template` DISABLE KEYS */;
INSERT INTO `sys_template` VALUES (1,1,1654616953,1654616914,2,0,0,0,0,256,NULL,0,0,0,0,0,'MAIN',1,3,NULL,'@import \'EXT:xm_dkfz_net_site/Configuration/TypoScript/constants.typoscript\'','@import \'EXT:xm_dkfz_net_site/Configuration/TypoScript/setup.typoscript\'','',0,0,0);
/*!40000 ALTER TABLE `sys_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tt_content`
--

DROP TABLE IF EXISTS `tt_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tt_content` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rowDescription` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `pid` int(11) NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `fe_group` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT 0,
  `editlock` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l18n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_source` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `l18n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `CType` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `header` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `header_position` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `bodytext` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `bullets_type` smallint(5) unsigned NOT NULL DEFAULT 0,
  `uploads_description` smallint(5) unsigned NOT NULL DEFAULT 0,
  `uploads_type` smallint(5) unsigned NOT NULL DEFAULT 0,
  `assets` int(10) unsigned NOT NULL DEFAULT 0,
  `image` int(10) unsigned NOT NULL DEFAULT 0,
  `imagewidth` int(10) unsigned NOT NULL DEFAULT 0,
  `imageorient` smallint(5) unsigned NOT NULL DEFAULT 0,
  `imagecols` smallint(5) unsigned NOT NULL DEFAULT 0,
  `imageborder` smallint(5) unsigned NOT NULL DEFAULT 0,
  `media` int(10) unsigned NOT NULL DEFAULT 0,
  `layout` int(10) unsigned NOT NULL DEFAULT 0,
  `frame_class` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `cols` int(10) unsigned NOT NULL DEFAULT 0,
  `space_before_class` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `space_after_class` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `records` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `pages` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `colPos` int(10) unsigned NOT NULL DEFAULT 0,
  `subheader` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `header_link` varchar(1024) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image_zoom` smallint(5) unsigned NOT NULL DEFAULT 0,
  `header_layout` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `list_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sectionIndex` smallint(5) unsigned NOT NULL DEFAULT 0,
  `linkToTop` smallint(5) unsigned NOT NULL DEFAULT 0,
  `file_collections` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `filelink_size` smallint(5) unsigned NOT NULL DEFAULT 0,
  `filelink_sorting` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `filelink_sorting_direction` varchar(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `target` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `date` int(10) unsigned NOT NULL DEFAULT 0,
  `recursive` smallint(5) unsigned NOT NULL DEFAULT 0,
  `imageheight` int(10) unsigned NOT NULL DEFAULT 0,
  `pi_flexform` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `accessibility_title` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `accessibility_bypass` smallint(5) unsigned NOT NULL DEFAULT 0,
  `accessibility_bypass_text` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `category_field` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `table_class` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `table_caption` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `table_delimiter` smallint(5) unsigned NOT NULL DEFAULT 0,
  `table_enclosure` smallint(5) unsigned NOT NULL DEFAULT 0,
  `table_header_position` smallint(5) unsigned NOT NULL DEFAULT 0,
  `table_tfoot` smallint(5) unsigned NOT NULL DEFAULT 0,
  `categories` int(10) unsigned NOT NULL DEFAULT 0,
  `selected_categories` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `tx_impexp_origuid` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`sorting`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`),
  KEY `language` (`l18n_parent`,`sys_language_uid`),
  KEY `translation_source` (`l10n_source`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tt_content`
--

LOCK TABLES `tt_content` WRITE;
/*!40000 ALTER TABLE `tt_content` DISABLE KEYS */;
INSERT INTO `tt_content` VALUES (1,'',1,1655104114,1654840627,2,0,0,0,0,'',256,0,0,0,0,NULL,0,'{\"colPos\":\"\",\"sys_language_uid\":\"\"}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','',NULL,'',0,'','',0,'0','xmdkfznetjobs_latestjobs',1,0,NULL,0,'','','',0,0,0,NULL,'',0,'','','',NULL,124,0,0,0,0,NULL,0),(2,'',25,1655104098,1655101926,2,0,0,0,0,'',256,0,0,0,0,NULL,0,'{\"colPos\":\"\",\"sys_language_uid\":\"\"}',0,0,0,0,'list','All Jobs','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','',NULL,'',0,'','',0,'0','xmdkfznetjobs_listjobs',1,0,NULL,0,'','','',0,0,0,NULL,'',0,'','','',NULL,124,0,0,0,0,NULL,0);
/*!40000 ALTER TABLE `tt_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tx_impexp_presets`
--

DROP TABLE IF EXISTS `tx_impexp_presets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_impexp_presets` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `user_uid` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `public` smallint(6) NOT NULL DEFAULT 0,
  `item_uid` int(11) NOT NULL DEFAULT 0,
  `preset_data` blob DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `lookup` (`item_uid`),
  KEY `parent` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tx_impexp_presets`
--

LOCK TABLES `tx_impexp_presets` WRITE;
/*!40000 ALTER TABLE `tx_impexp_presets` DISABLE KEYS */;
/*!40000 ALTER TABLE `tx_impexp_presets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tx_scheduler_task`
--

DROP TABLE IF EXISTS `tx_scheduler_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_scheduler_task` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `disable` smallint(5) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `nextexecution` int(10) unsigned NOT NULL DEFAULT 0,
  `lastexecution_time` int(10) unsigned NOT NULL DEFAULT 0,
  `lastexecution_failure` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastexecution_context` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `serialized_task_object` mediumblob DEFAULT NULL,
  `serialized_executions` mediumblob DEFAULT NULL,
  `task_group` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `index_nextexecution` (`nextexecution`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tx_scheduler_task`
--

LOCK TABLES `tx_scheduler_task` WRITE;
/*!40000 ALTER TABLE `tx_scheduler_task` DISABLE KEYS */;
/*!40000 ALTER TABLE `tx_scheduler_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tx_scheduler_task_group`
--

DROP TABLE IF EXISTS `tx_scheduler_task_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_scheduler_task_group` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `groupName` varchar(80) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tx_scheduler_task_group`
--

LOCK TABLES `tx_scheduler_task_group` WRITE;
/*!40000 ALTER TABLE `tx_scheduler_task_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `tx_scheduler_task_group` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-06-13 12:17:44

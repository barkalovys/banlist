/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50523
Source Host           : localhost:3306
Source Database       : banlist

Target Server Type    : MYSQL
Target Server Version : 50523
File Encoding         : 65001

Date: 2014-09-15 16:35:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for banlist
-- ----------------------------
DROP TABLE IF EXISTS `banlist`;
CREATE TABLE `banlist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `reason` text,
  `block_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `unblock_date` timestamp NULL DEFAULT NULL,
  `is_blocked` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of banlist
-- ----------------------------
INSERT INTO `banlist` VALUES ('2', '11.234.22.12', 'спам', '2014-09-15 14:59:34', '2014-10-29 14:59:32', '0');
INSERT INTO `banlist` VALUES ('43', '234.22.33.44', 'спам', '2014-09-15 15:11:45', '2014-09-15 16:04:22', '0');
INSERT INTO `banlist` VALUES ('44', '12.33.44.55', 'spam', '2014-09-15 16:24:46', null, '1');
INSERT INTO `banlist` VALUES ('45', '122.44.55.66', 'еще спам', '2014-09-15 16:24:51', '2014-09-15 16:24:52', '0');
INSERT INTO `banlist` VALUES ('50', '43.33.22.11', 'спам', '2014-09-15 00:27:38', '2014-09-15 00:27:39', '0');
INSERT INTO `banlist` VALUES ('51', '22.44.55.11', 'тест', '2014-09-15 16:24:47', '2014-09-15 16:24:48', '0');
INSERT INTO `banlist` VALUES ('52', '11.44.55.66', 'test', '2014-09-15 16:24:48', '2014-09-15 16:24:49', '0');
INSERT INTO `banlist` VALUES ('53', '23.55.66.77', 'spam', '2014-09-15 15:01:33', '2014-09-15 16:04:20', '0');
INSERT INTO `banlist` VALUES ('54', '11.55.66.1', 'test', '2014-09-18 22:42:27', null, '1');

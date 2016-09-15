/*
Navicat MariaDB Data Transfer

Source Server         : local
Source Server Version : 100113
Source Host           : localhost:3306
Source Database       : mvc

Target Server Type    : MariaDB
Target Server Version : 100113
File Encoding         : 65001

Date: 2016-09-07 15:23:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for messages
-- ----------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of messages
-- ----------------------------
INSERT INTO `messages` VALUES ('1', 'ÐœÐ¸ÑˆÐ°', 'mishamart@rambler.ru', 'HI!');
INSERT INTO `messages` VALUES ('2', 'Microsoft', '3610974@rambler.ru', 'Hello World!');
INSERT INTO `messages` VALUES ('3', 'ÐœÐ¸Ñ…Ð°Ð¸Ð»', '3610974@rambler.ru', 'ÐŸÑ€Ð¸Ð²ÐµÑ‚!');
INSERT INTO `messages` VALUES ('4', 'ÐœÐ¸Ñ…Ð°Ð¸Ð»', '3610974@rambler.ru', 'ÐŸÑ€Ð¸Ð²ÐµÑ‚!');
INSERT INTO `messages` VALUES ('5', 'Anna', 'mishamart7@mail.ru', 'Killo');
INSERT INTO `messages` VALUES ('6', 'aehaeHewhE', 'AERHARH@MAIL.RU', 'ARHARHAH');
INSERT INTO `messages` VALUES ('7', '', '', '');
INSERT INTO `messages` VALUES ('8', '', '', '');
INSERT INTO `messages` VALUES ('9', '', '', '');

-- ----------------------------
-- Table structure for pages
-- ----------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text,
  `is_published` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pages
-- ----------------------------
INSERT INTO `pages` VALUES ('1', 'about', 'About Us - TEST', 'Test content from Misha ', '1');
INSERT INTO `pages` VALUES ('2', 'test', 'Test page', 'Another test content      ', '1');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(45) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(45) NOT NULL DEFAULT 'admin',
  `password` char(32) NOT NULL,
  `is_active` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', 'admin@mvc.com', 'admin', '94b43bd453e6add7c00874fba8be7729', '1');

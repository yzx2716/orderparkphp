/*
SQLyog Ultimate v11.24 (32 bit)
MySQL - 5.5.40 : Database - order_park
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`order_park` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `order_park`;

/*Table structure for table `op_operate` */

DROP TABLE IF EXISTS `op_operate`;

CREATE TABLE `op_operate` (
  `operate_id` int(10) NOT NULL AUTO_INCREMENT,
  `park_id` int(10) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `order_id` int(10) DEFAULT NULL,
  `tran_type` enum('enter','out') DEFAULT NULL,
  `state` tinyint(1) DEFAULT '1',
  `client_state` tinyint(1) DEFAULT '0' COMMENT '0:未操作 1：已放行',
  `add_time` datetime DEFAULT NULL,
  `client_time` datetime DEFAULT NULL COMMENT 'client操作时间',
  PRIMARY KEY (`operate_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `op_order` */

DROP TABLE IF EXISTS `op_order`;

CREATE TABLE `op_order` (
  `order_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `park_id` int(10) NOT NULL,
  `to_date` date NOT NULL,
  `day_type` enum('am','pm','whole','times') DEFAULT NULL,
  `state` tinyint(1) DEFAULT '1' COMMENT '0：已取消 1：正常 2：已入库 3：已出库',
  `add_time` datetime DEFAULT NULL,
  `upd_time` datetime DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `ix_user` (`user_id`),
  KEY `ix_park` (`park_id`,`to_date`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `op_park` */

DROP TABLE IF EXISTS `op_park`;

CREATE TABLE `op_park` (
  `park_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `id_card` varchar(10) DEFAULT NULL,
  `add_time` datetime DEFAULT NULL,
  PRIMARY KEY (`park_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;

/*Table structure for table `op_scan` */

DROP TABLE IF EXISTS `op_scan`;

CREATE TABLE `op_scan` (
  `scan_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `park_id` int(10) NOT NULL,
  `order_id` int(10) DEFAULT NULL,
  `tran_type` enum('enter','out') DEFAULT 'enter',
  `state` tinyint(1) DEFAULT '1' COMMENT '1:默认 2；有效 3：完成',
  `add_time` datetime DEFAULT NULL,
  `upd_time` datetime DEFAULT NULL,
  PRIMARY KEY (`scan_id`),
  KEY `idx_park_id` (`park_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

/*Table structure for table `op_user` */

DROP TABLE IF EXISTS `op_user`;

CREATE TABLE `op_user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `wechat_id` varchar(20) NOT NULL,
  `nick_name` varchar(50) DEFAULT NULL,
  `add_time` datetime DEFAULT NULL,
  `upd_time` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `ix_wechat` (`wechat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

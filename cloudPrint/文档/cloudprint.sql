/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : cloudprint

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-08-10 17:50:34
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `cp_admin`
-- ----------------------------
DROP TABLE IF EXISTS `cp_admin`;
CREATE TABLE `cp_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adminName` varchar(20) NOT NULL,
  `adminPsw` varchar(20) NOT NULL,
  `authority` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of cp_admin
-- ----------------------------
INSERT INTO `cp_admin` VALUES ('1', 'admin', 'admin', null);

-- ----------------------------
-- Table structure for `cp_business_info`
-- ----------------------------
DROP TABLE IF EXISTS `cp_business_info`;
CREATE TABLE `cp_business_info` (
  `businessId` int(11) NOT NULL AUTO_INCREMENT,
  `businessNick` varchar(20) NOT NULL,
  `businessName` varchar(20) NOT NULL,
  `businessPsw` varchar(20) NOT NULL,
  `businessEmail` varchar(50) NOT NULL,
  `businessAddress` varchar(50) NOT NULL,
  `businessPhone` char(11) NOT NULL,
  `regTime` date NOT NULL,
  `lastTime` date DEFAULT NULL,
  `finishOrders` int(11) NOT NULL,
  `unfinishOrders` int(11) DEFAULT NULL,
  `runStatus` tinyint(1) DEFAULT NULL,
  `busyStatus` varchar(10) NOT NULL,
  `shopName` varchar(100) NOT NULL,
  `serveStar` tinyint(4) DEFAULT NULL,
  `shopInfo` text,
  `runInfo` varchar(10) DEFAULT NULL,
  `canPrintType` text,
  `totalIncome` decimal(10,1) NOT NULL,
  PRIMARY KEY (`businessId`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of cp_business_info
-- ----------------------------
INSERT INTO `cp_business_info` VALUES ('1', '123', '66', '123123', 'aaaa', '123aaa', '123', '2015-07-22', '2015-07-21', '1', '0', '0', '正常', '胡巴打印店', '3', '234aaaaaaaaaa', null, '234', '0.0');
INSERT INTO `cp_business_info` VALUES ('3', 'test', 'te', 'test', 'et', '123', '23', '2015-07-22', null, '0', '0', '1', '正常', '星火打印店', '1', null, null, null, '0.0');
INSERT INTO `cp_business_info` VALUES ('4', 'aa', 'we', 'aa', '123', '123', '231', '2015-07-22', null, '0', '0', '0', '暂不接单', '丰台打印店', '5', '', null, '', '0.0');
INSERT INTO `cp_business_info` VALUES ('5', 'mick', '123', '231', '2', '2', '231', '2015-07-22', null, '0', '0', '1', '正常', '安信打印店', '4', null, null, null, '0.0');

-- ----------------------------
-- Table structure for `cp_comment`
-- ----------------------------
DROP TABLE IF EXISTS `cp_comment`;
CREATE TABLE `cp_comment` (
  `judgeId` int(11) NOT NULL,
  `userPhone` char(11) NOT NULL,
  `userName` varchar(20) NOT NULL,
  `businessId` int(11) NOT NULL,
  `comment` text NOT NULL,
  `comTime` date NOT NULL,
  `busReply` text NOT NULL,
  PRIMARY KEY (`judgeId`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of cp_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `cp_doc_format`
-- ----------------------------
DROP TABLE IF EXISTS `cp_doc_format`;
CREATE TABLE `cp_doc_format` (
  `id` int(11) NOT NULL,
  `format` varchar(20) NOT NULL,
  `desc` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of cp_doc_format
-- ----------------------------
INSERT INTO `cp_doc_format` VALUES ('1', 'doc', null);
INSERT INTO `cp_doc_format` VALUES ('2', 'pdf', '');
INSERT INTO `cp_doc_format` VALUES ('3', 'docx', '');
INSERT INTO `cp_doc_format` VALUES ('4', 'pdf', '');
INSERT INTO `cp_doc_format` VALUES ('5', 'xlsx', '');
INSERT INTO `cp_doc_format` VALUES ('6', 'ppt', '');
INSERT INTO `cp_doc_format` VALUES ('7', 'pptx', '');
INSERT INTO `cp_doc_format` VALUES ('8', 'wps', '');
INSERT INTO `cp_doc_format` VALUES ('9', 'gif', '');
INSERT INTO `cp_doc_format` VALUES ('10', 'jpg', '');
INSERT INTO `cp_doc_format` VALUES ('11', 'png', '');

-- ----------------------------
-- Table structure for `cp_doc_info`
-- ----------------------------
DROP TABLE IF EXISTS `cp_doc_info`;
CREATE TABLE `cp_doc_info` (
  `docId` int(11) NOT NULL AUTO_INCREMENT,
  `docName` varchar(100) NOT NULL,
  `docNewName` varchar(100) NOT NULL,
  `docBelongNick` varchar(20) NOT NULL,
  `docBelongPhone` char(11) NOT NULL,
  `docLocation` varchar(255) NOT NULL,
  `docSize` varchar(20) NOT NULL,
  `docTime` datetime NOT NULL,
  `docFormat` int(11) NOT NULL,
  `docDesc` text,
  PRIMARY KEY (`docId`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of cp_doc_info
-- ----------------------------
INSERT INTO `cp_doc_info` VALUES ('4', '商家注册流程图.doc', '632263d7d2628167c24687e4361d24ba.doc', '', '', '632263d7d2628167c24687e4361d24ba.doc', '16.85', '2015-07-28 18:44:16', '1', null);
INSERT INTO `cp_doc_info` VALUES ('3', '支持文档格式_1.doc', '3bf3ba9be119950e79239f15570ff5a1.doc', '1234', '123123', '3bf3ba9be119950e79239f15570ff5a1.doc', '12.5', '2015-07-27 14:07:00', '1', null);
INSERT INTO `cp_doc_info` VALUES ('7', '学生数据库信息表2.doc', '6f4dadff23b54146eadba0e968253256.doc', '1234', '123123', '6f4dadff23b54146eadba0e968253256.doc', '53', '2015-07-29 11:27:48', '1', null);
INSERT INTO `cp_doc_info` VALUES ('8', '价格表.doc', '02e5b5639a5f6a652a4490fc457e089e.doc', 'sdasda', '123', '02e5b5639a5f6a652a4490fc457e089e.doc', '94.5', '2015-07-30 16:10:28', '1', null);
INSERT INTO `cp_doc_info` VALUES ('9', '购物理念.doc', 'eef58d6e52a067b9845359ad54171515.doc', '2528', '2528', 'eef58d6e52a067b9845359ad54171515.doc', '39', '2015-07-31 11:13:51', '1', null);
INSERT INTO `cp_doc_info` VALUES ('10', 'DHCPv6中继配置.docx', '9b4fc95dceb46f4fe66734643795daf6.docx', 'ZHUWUWEI', '13164174757', '9b4fc95dceb46f4fe66734643795daf6.docx', '9.89', '2015-07-31 12:47:20', '0', null);
INSERT INTO `cp_doc_info` VALUES ('12', '商家注册流程图.doc', '0a27281f904e8a8766890e9b8dc0fcc4.doc', '123', '123', '0a27281f904e8a8766890e9b8dc0fcc4.doc', '16.85', '2015-07-31 12:59:01', '1', null);
INSERT INTO `cp_doc_info` VALUES ('13', '微信数据字典.doc', '3b55e0b5f334c69c7c22284f676d62f5.doc', '123', '123', '3b55e0b5f334c69c7c22284f676d62f5.doc', '80.5', '2015-07-31 14:36:14', '1', null);
INSERT INTO `cp_doc_info` VALUES ('14', '附件三：云打印筹备运营预算.xlsx', 'c9f843f590704ab542e3eb7403a0a477.xlsx', '2528', '2528', 'c9f843f590704ab542e3eb7403a0a477.xlsx', '7.73', '2015-07-31 15:14:47', '0', null);
INSERT INTO `cp_doc_info` VALUES ('15', '附件三：云打印筹备运营预算.xlsx', 'a6fa6e422e3524b72c36e9fc8a990946.xlsx', '2528', '2528', 'a6fa6e422e3524b72c36e9fc8a990946.xlsx', '7.73', '2015-07-31 15:16:24', '0', null);
INSERT INTO `cp_doc_info` VALUES ('16', '附件三：云打印筹备运营预算.xlsx', 'b96f0c79a46642ea1879bc6de6ffe893.xlsx', '2528', '2528', 'b96f0c79a46642ea1879bc6de6ffe893.xlsx', '7.73', '2015-07-31 15:17:12', '0', null);
INSERT INTO `cp_doc_info` VALUES ('17', '附件三：云打印筹备运营预算.xlsx', 'f0392e274c62048601b6536a8eaefb8f.xlsx', '2528', '2528', 'f0392e274c62048601b6536a8eaefb8f.xlsx', '7.73', '2015-07-31 15:18:17', '0', null);
INSERT INTO `cp_doc_info` VALUES ('18', '附件三：云打印筹备运营预算.xlsx', 'fc25f06b45f9c19ad727c1c916377670.xlsx', '2528', '2528', 'fc25f06b45f9c19ad727c1c916377670.xlsx', '7.73', '2015-07-31 15:21:07', '0', null);
INSERT INTO `cp_doc_info` VALUES ('19', '假期信息系统专项开发工作计划与进展报告提纲.doc', 'c4c860156952c59bbe25c80a3c520f0a.doc', '2528', '2528', 'c4c860156952c59bbe25c80a3c520f0a.doc', '1699', '2015-07-31 15:24:15', '1', null);
INSERT INTO `cp_doc_info` VALUES ('20', '电商分类.xlsx', 'bb6eabe36dbce886ceb5cd4eb2d9b600.xlsx', '2528', '2528', 'bb6eabe36dbce886ceb5cd4eb2d9b600.xlsx', '10.31', '2015-07-31 15:25:12', '0', null);
INSERT INTO `cp_doc_info` VALUES ('22', '11.doc', '7394cb620c8f4262ade6052d5387bf8d.doc', '2', '2', '7394cb620c8f4262ade6052d5387bf8d.doc', '10.5', '2015-07-31 17:26:32', '1', null);
INSERT INTO `cp_doc_info` VALUES ('23', '价格表.doc', 'a86fd86b9ec7cf1eabc47739df62f484.doc', '123', '123', 'a86fd86b9ec7cf1eabc47739df62f484.doc', '94.5', '2015-08-03 17:25:33', '1', null);
INSERT INTO `cp_doc_info` VALUES ('24', '价格表.doc', 'd1ff0b1f52ba518c947847f84e2c933d.doc', '123', '123', 'd1ff0b1f52ba518c947847f84e2c933d.doc', '94.5', '2015-08-03 18:11:15', '1', null);
INSERT INTO `cp_doc_info` VALUES ('29', '123.doc', '243119531762e84db8fac3c63f841774.doc', '123', '123', '243119531762e84db8fac3c63f841774.doc', '10', '2015-08-04 16:59:41', '1', null);
INSERT INTO `cp_doc_info` VALUES ('30', '云打印设计方案（精简）.doc', 'ee7dba78a6c8c0192e0f7ea7e55f062b.doc', '123', '123', 'ee7dba78a6c8c0192e0f7ea7e55f062b.doc', '155.05', '2015-08-05 10:27:11', '1', null);

-- ----------------------------
-- Table structure for `cp_order`
-- ----------------------------
DROP TABLE IF EXISTS `cp_order`;
CREATE TABLE `cp_order` (
  `orderId` int(11) NOT NULL AUTO_INCREMENT,
  `docName` varchar(50) NOT NULL,
  `docId` int(11) NOT NULL,
  `userPhone` varchar(11) NOT NULL,
  `userNick` varchar(20) NOT NULL,
  `userName` varchar(20) NOT NULL,
  `makeTime` datetime NOT NULL,
  `upTime` datetime NOT NULL,
  `shopName` varchar(50) NOT NULL,
  `docFormat` int(4) NOT NULL,
  `businessId` int(8) NOT NULL,
  `message` text,
  `price` decimal(10,1) NOT NULL,
  `doublePrint` tinyint(4) NOT NULL,
  `colorPrint` tinyint(1) NOT NULL,
  `printCopis` int(5) NOT NULL,
  `payStatus` tinyint(1) DEFAULT NULL,
  `printStatus` tinyint(1) NOT NULL,
  PRIMARY KEY (`orderId`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of cp_order
-- ----------------------------
INSERT INTO `cp_order` VALUES ('30', '附件三：云打印筹备运营预算.xlsx', '17', '2528', '2528', '', '2015-07-31 15:31:47', '2015-07-31 15:18:17', '胡巴打印店', '0', '1', '', '0.0', '0', '0', '1', '0', '1');
INSERT INTO `cp_order` VALUES ('7', '支持文档格式_1.doc', '3', '123123', '1234', '13', '2015-07-29 11:55:31', '2015-07-27 14:07:00', '安信打印店', '1', '5', '', '0.0', '1', '1', '2', '0', '1');
INSERT INTO `cp_order` VALUES ('8', '商家注册流程图.doc', '4', '123123', '1234', '13', '2015-07-29 15:00:03', '2015-07-28 18:44:16', '胡巴打印店', '1', '1', '', '0.0', '1', '0', '2', '0', '1');
INSERT INTO `cp_order` VALUES ('9', '价格表.doc', '8', '123', 'sdasda', '', '2015-07-30 16:10:43', '2015-07-30 16:10:28', '胡巴打印店', '1', '1', '', '0.0', '1', '0', '3', '0', '1');
INSERT INTO `cp_order` VALUES ('11', '价格表.doc', '8', '123', '123', '152', '2015-07-31 11:38:48', '2015-07-30 16:10:28', '胡巴打印店', '1', '1', '', '0.0', '1', '1', '2', '0', '1');
INSERT INTO `cp_order` VALUES ('13', '价格表.doc', '8', '123', '123', '152', '2015-07-31 11:43:17', '2015-07-30 16:10:28', '胡巴打印店', '1', '1', '', '0.0', '1', '0', '2', '0', '1');
INSERT INTO `cp_order` VALUES ('48', '11.doc', '22', '2', '2', '2', '2015-07-31 17:27:33', '2015-07-31 17:26:32', '胡巴打印店', '1', '1', '', '0.0', '0', '0', '2', '0', '1');
INSERT INTO `cp_order` VALUES ('24', '电商分类.xlsx', '20', '2528', '2528', '', '2015-07-31 15:25:29', '2015-07-31 15:25:12', '安信打印店', '0', '5', '', '0.0', '0', '1', '10', '0', '1');
INSERT INTO `cp_order` VALUES ('15', '价格表.doc', '8', '123', '123', '152', '2015-07-31 12:02:06', '2015-07-30 16:10:28', '星火打印店', '1', '3', '', '0.0', '1', '1', '2', '0', '1');
INSERT INTO `cp_order` VALUES ('16', 'DHCPv6中继配置.docx', '10', '13164174757', 'ZHUWUWEI', '', '2015-07-31 12:47:38', '2015-07-31 12:47:20', '胡巴打印店', '0', '1', '', '0.0', '0', '0', '0', '0', '1');
INSERT INTO `cp_order` VALUES ('21', '购物理念.doc', '9', '2528', '2528', '', '2015-07-31 15:04:06', '2015-07-31 11:13:51', '胡巴打印店', '1', '1', '你好   请在首页留空白', '0.0', '0', '1', '0', '0', '1');
INSERT INTO `cp_order` VALUES ('22', '附件三：云打印筹备运营预算.xlsx', '14', '2528', '2528', '', '2015-07-31 15:23:27', '2015-07-31 15:14:47', '安信打印店', '0', '5', '', '0.0', '0', '1', '10', '0', '1');
INSERT INTO `cp_order` VALUES ('49', '价格表.doc', '24', '123', '123', '152', '2015-08-03 18:11:33', '2015-08-03 18:11:15', '胡巴打印店', '1', '1', 'd ', '0.0', '1', '0', '4', '0', '1');
INSERT INTO `cp_order` VALUES ('25', '附件三：云打印筹备运营预算.xlsx', '18', '2528', '2528', '', '2015-07-31 15:27:49', '2015-07-31 15:21:07', '安信打印店', '0', '5', '', '0.0', '0', '1', '10', '0', '1');
INSERT INTO `cp_order` VALUES ('26', '附件三：云打印筹备运营预算.xlsx', '15', '2528', '2528', '', '2015-07-31 15:28:34', '2015-07-31 15:16:24', '星火打印店', '0', '3', '', '0.0', '0', '1', '2', '0', '0');
INSERT INTO `cp_order` VALUES ('27', '附件三：云打印筹备运营预算.xlsx', '16', '2528', '2528', '', '2015-07-31 15:28:59', '2015-07-31 15:17:12', '丰台打印店', '0', '4', '', '0.0', '0', '1', '1', '0', '0');
INSERT INTO `cp_order` VALUES ('28', '附件三：云打印筹备运营预算.xlsx', '17', '2528', '2528', '', '2015-07-31 15:29:34', '2015-07-31 15:18:17', '丰台打印店', '0', '4', '', '0.0', '0', '1', '0', '0', '0');
INSERT INTO `cp_order` VALUES ('29', '附件三：云打印筹备运营预算.xlsx', '17', '2528', '2528', '', '2015-07-31 15:29:58', '2015-07-31 15:18:17', '丰台打印店', '0', '4', '', '0.0', '0', '1', '0', '0', '0');
INSERT INTO `cp_order` VALUES ('31', '附件三：云打印筹备运营预算.xlsx', '17', '2528', '2528', '', '2015-07-31 15:32:47', '2015-07-31 15:18:17', '丰台打印店', '0', '4', '', '0.0', '0', '1', '0', '0', '0');
INSERT INTO `cp_order` VALUES ('32', '附件三：云打印筹备运营预算.xlsx', '17', '2528', '2528', '', '2015-07-31 15:32:54', '2015-07-31 15:18:17', '丰台打印店', '0', '4', '', '0.0', '0', '1', '0', '0', '0');
INSERT INTO `cp_order` VALUES ('33', '附件三：云打印筹备运营预算.xlsx', '17', '2528', '2528', '2528', '2015-07-31 15:39:36', '2015-07-31 15:18:17', '丰台打印店', '0', '4', '', '0.0', '0', '1', '0', '0', '0');
INSERT INTO `cp_order` VALUES ('34', '附件三：云打印筹备运营预算.xlsx', '17', '2528', '2528', '2528', '2015-07-31 15:39:43', '2015-07-31 15:18:17', '丰台打印店', '0', '4', '', '0.0', '0', '1', '0', '0', '0');
INSERT INTO `cp_order` VALUES ('35', '附件三：云打印筹备运营预算.xlsx', '17', '2528', '2528', '2528', '2015-07-31 15:39:52', '2015-07-31 15:18:17', '丰台打印店', '0', '4', '', '0.0', '0', '1', '0', '0', '0');
INSERT INTO `cp_order` VALUES ('36', '附件三：云打印筹备运营预算.xlsx', '17', '2528', '2528', '2528', '2015-07-31 15:39:58', '2015-07-31 15:18:17', '丰台打印店', '0', '4', '', '0.0', '0', '1', '0', '0', '0');
INSERT INTO `cp_order` VALUES ('37', '', '0', '2528', '2528', '2528', '2015-07-31 15:41:50', '0000-00-00 00:00:00', '', '0', '0', null, '0.0', '0', '0', '0', '0', '0');
INSERT INTO `cp_order` VALUES ('38', '', '0', '2528', '2528', '2528', '2015-07-31 15:41:53', '0000-00-00 00:00:00', '', '0', '0', null, '0.0', '0', '0', '0', '0', '0');
INSERT INTO `cp_order` VALUES ('39', '', '0', '2528', '2528', '2528', '2015-07-31 15:41:56', '0000-00-00 00:00:00', '', '0', '0', null, '0.0', '0', '0', '0', '0', '0');
INSERT INTO `cp_order` VALUES ('40', '', '0', '2528', '2528', '2528', '2015-07-31 15:41:59', '0000-00-00 00:00:00', '', '0', '0', null, '0.0', '0', '0', '0', '0', '0');
INSERT INTO `cp_order` VALUES ('41', '', '0', '2528', '2528', '2528', '2015-07-31 15:42:02', '0000-00-00 00:00:00', '', '0', '0', null, '0.0', '0', '0', '0', '0', '0');
INSERT INTO `cp_order` VALUES ('42', '附件三：云打印筹备运营预算.xlsx', '17', '2528', '2528', '2528', '2015-07-31 15:43:14', '2015-07-31 15:18:17', '丰台打印店', '0', '4', '', '0.0', '0', '1', '0', '0', '0');
INSERT INTO `cp_order` VALUES ('43', '', '0', '2528', '2528', '2528', '2015-07-31 15:43:17', '0000-00-00 00:00:00', '', '0', '0', null, '0.0', '0', '0', '0', '0', '0');
INSERT INTO `cp_order` VALUES ('44', '', '0', '2528', '2528', '2528', '2015-07-31 15:43:20', '0000-00-00 00:00:00', '', '0', '0', null, '0.0', '0', '0', '0', '0', '0');
INSERT INTO `cp_order` VALUES ('45', '微信数据字典.doc', '13', '123', '123', '152', '2015-07-31 15:44:41', '2015-07-31 14:36:14', '丰台打印店', '1', '4', '', '0.0', '0', '1', '2', '0', '0');
INSERT INTO `cp_order` VALUES ('46', '商家注册流程图.doc', '12', '123', '123', '152', '2015-07-31 15:45:11', '2015-07-31 12:59:01', '胡巴打印店', '1', '1', '', '0.0', '0', '1', '1', '0', '1');
INSERT INTO `cp_order` VALUES ('47', '附件三：云打印筹备运营预算.xlsx', '15', '2528', '2528', '2528', '2015-07-31 15:45:38', '2015-07-31 15:16:24', '安信打印店', '0', '5', '', '0.0', '0', '1', '1', '0', '1');
INSERT INTO `cp_order` VALUES ('50', '商家注册流程图.doc', '12', '123', '123', '152', '2015-08-04 10:26:37', '2015-07-31 12:59:01', '丰台打印店', '1', '4', '', '0.0', '0', '1', '1', '0', '0');
INSERT INTO `cp_order` VALUES ('57', '123.doc', '29', '123', '123', '152', '2015-08-04 17:01:35', '2015-08-04 16:59:41', '胡巴打印店', '1', '1', '', '0.0', '0', '1', '1', '0', '1');
INSERT INTO `cp_order` VALUES ('52', '价格表.doc', '8', '123', '123', '152', '2015-08-04 10:31:35', '2015-07-30 16:10:28', '星火打印店', '1', '3', '', '0.0', '0', '1', '1', '0', '0');
INSERT INTO `cp_order` VALUES ('58', '商家注册流程图.doc', '12', '123', '123', '王五', '2015-08-06 14:54:16', '2015-07-31 12:59:01', '胡巴打印店', '1', '1', '', '0.0', '0', '1', '2', '0', '1');

-- ----------------------------
-- Table structure for `cp_page_size`
-- ----------------------------
DROP TABLE IF EXISTS `cp_page_size`;
CREATE TABLE `cp_page_size` (
  `id` int(11) NOT NULL,
  `size` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of cp_page_size
-- ----------------------------

-- ----------------------------
-- Table structure for `cp_page_type`
-- ----------------------------
DROP TABLE IF EXISTS `cp_page_type`;
CREATE TABLE `cp_page_type` (
  `id` int(11) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of cp_page_type
-- ----------------------------

-- ----------------------------
-- Table structure for `cp_print_price`
-- ----------------------------
DROP TABLE IF EXISTS `cp_print_price`;
CREATE TABLE `cp_print_price` (
  `id` int(11) NOT NULL,
  `businessId` int(11) DEFAULT NULL,
  `pageSize` tinyint(4) DEFAULT NULL,
  `doublePrint` tinyint(2) DEFAULT NULL,
  `colorPrint` tinyint(1) DEFAULT NULL,
  `printTypeNum` int(11) DEFAULT NULL,
  `pageType` int(5) DEFAULT NULL,
  `price` decimal(10,1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of cp_print_price
-- ----------------------------

-- ----------------------------
-- Table structure for `cp_print_type`
-- ----------------------------
DROP TABLE IF EXISTS `cp_print_type`;
CREATE TABLE `cp_print_type` (
  `id` int(11) NOT NULL,
  `businessId` int(11) DEFAULT NULL,
  `printType` varchar(20) DEFAULT NULL,
  `price` decimal(10,1) DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of cp_print_type
-- ----------------------------

-- ----------------------------
-- Table structure for `cp_school_name`
-- ----------------------------
DROP TABLE IF EXISTS `cp_school_name`;
CREATE TABLE `cp_school_name` (
  `id` int(11) NOT NULL,
  `locationId` varchar(20) DEFAULT NULL,
  `schoolName` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of cp_school_name
-- ----------------------------

-- ----------------------------
-- Table structure for `cp_user`
-- ----------------------------
DROP TABLE IF EXISTS `cp_user`;
CREATE TABLE `cp_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userNick` varchar(20) NOT NULL,
  `userPhone` varchar(11) NOT NULL,
  `userPsw` varchar(20) NOT NULL,
  `userName` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `status` varchar(10) DEFAULT NULL,
  `schoolName` varchar(20) NOT NULL,
  `regTime` datetime NOT NULL,
  `lastTime` datetime DEFAULT NULL,
  `finishOrders` int(11) unsigned NOT NULL,
  `unfinishOrders` int(11) unsigned NOT NULL,
  `totalMoney` decimal(10,1) NOT NULL,
  PRIMARY KEY (`id`,`userPhone`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of cp_user
-- ----------------------------
INSERT INTO `cp_user` VALUES ('3', 'Mick', '18882322212', '123', '张三', '2321', '正常', '彩印', '0000-00-00 00:00:00', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('4', 'OHHHHH', '13532322', '1', 'lalalal', '123', '正常', '312', '0000-00-00 00:00:00', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('13', 'aaaaaaaaaaa', '333333', '333', '33', '33', '正常', '33', '2015-07-20 15:27:02', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('17', '23123', '432', '342', '432', '43', '正常', '', '2015-07-21 10:52:04', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('7', 'ere', '17545', 'er', '44', '34', '封停', 'asdf', '2015-07-20 10:15:33', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('8', '4534', '4535', '452', '345', '131', '正常', '大学', '2015-07-20 10:17:05', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('15', 'erwreqw', '231321', 'qwer', 'q12', '21', '封停', '123', '2015-07-20 16:00:17', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('10', '1234', '123123', '123123', '13', '32', '正常', '', '2015-07-20 10:24:05', null, '2', '0', '0.0');
INSERT INTO `cp_user` VALUES ('11', 'tetwet', '13532322', 'wt', '21', '123', '正常', '', '2015-07-20 10:25:14', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('12', 'qeqwe', '232323', '`12', '2312', '12', '正常', '', '2015-07-20 15:25:24', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('16', '8989', '8768768', '987', '7', '7', '正常', '', '2015-07-20 16:00:34', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('18', '324', '342', '234', '432', '234', '正常', '', '2015-07-21 16:20:06', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('44', '', '784', '', '', '', '正常', '', '2015-07-31 11:07:54', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('20', 'test2', '12313123144', '123123', 'mick', '12312', '正常', '123', '2015-07-23 13:22:28', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('21', '111', '12123333', 'aaaaaa', 'aaaa', 'qq', '正常', 'qq', '2015-07-23 14:27:19', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('22', '111', '12123333', 'aaaaaa', 'aaaa', 'qq', '正常', 'qq', '2015-07-23 14:28:40', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('23', '111', '12123333', 'aaaaaa', 'aaaa', 'qq', '正常', 'qq', '2015-07-23 14:35:47', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('24', '111', '12123333', 'aaaaaa', 'aaaa', 'qq', '正常', 'qq', '2015-07-23 14:42:48', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('45', '2528', '2528', '2528', '2528', '2528', '封停', '2528', '2015-07-31 11:08:27', null, '9', '0', '0.0');
INSERT INTO `cp_user` VALUES ('29', '11', '12312', 'aa', '13', 'aaaaa', '正常', 'aa', '2015-07-23 14:54:48', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('30', '11', '12312', 'aa', '13', 'aaaaa', '正常', 'aa', '2015-07-23 14:57:22', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('31', '11', '12312', 'aa', '13', 'aaaaa', '正常', 'aa', '2015-07-23 14:58:36', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('34', 'aaaaaa', '1929323', '111111', 'dfsdf', 'erewr', '正常', 'werwer', '2015-07-28 18:43:21', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('33', '45324', '000000', '231', '4325', '3241', '正常', '34', '2015-07-23 15:11:35', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('35', 'helddd', '18883282222', '123', '王五', '123@qq.com', '正常', 'dfd', '2015-07-30 16:32:51', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('36', '222222', '111111', '111111', '1231', '112312@qq.com', '正常', '123', '2015-07-30 16:38:57', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('37', '123', '999', '999', 'ne', '123@qq.com', '正常', '00', '2015-07-30 16:42:27', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('38', '888', '888', '888', '888', '88', '正常', '88', '2015-07-30 16:43:41', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('39', '77', '77', '7', '7', '', '正常', '', '2015-07-30 16:44:55', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('40', '22', '22', '22', 'hahah', '112312@qq.com', '正常', '', '2015-07-30 16:48:10', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('41', '123', '123', '123123', '王五', '256@qq.com', '正常', '重邮', '2015-07-30 16:53:30', null, '6', '0', '0.0');
INSERT INTO `cp_user` VALUES ('42', '123', '954', '321', '2', '456', '正常', '111', '2015-07-30 16:58:05', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('43', '123', '954', '321', '2', '456', '正常', '111', '2015-07-30 16:58:06', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('50', '2', '3', '', '', '', '正常', '', '2015-07-31 17:12:51', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('49', 'ZHUWUWEI', '13164174757', 'ZHUWUWEI', '', '', '正常', '', '2015-07-31 12:08:11', null, '1', '0', '0.0');
INSERT INTO `cp_user` VALUES ('51', '2', '2', '2', '2', '2', '正常', '2', '2015-07-31 17:25:01', null, '1', '0', '0.0');
INSERT INTO `cp_user` VALUES ('52', '123123', '18882322213', '123123', '123123', '112312@qq.com', '正常', '1232123', '2015-08-03 17:57:03', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('53', '123123', '18883282222', '123121', '123123', '123@qq.com', '正常', '1231231', '2015-08-03 18:00:12', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('54', '123213', '18882322216', '123123', '123123', '123213@qq.com', '正常', '123123', '2015-08-04 15:12:46', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('55', 'aaaaaa', '18882322210', 'aaaaaa', 'aaaaaa', 'aaaaaa@qq.com', '正常', 'asdfasdfs', '2015-08-04 15:22:53', null, '0', '0', '0.0');
INSERT INTO `cp_user` VALUES ('56', '123123', '18882322219', '123123', '1231231', '123@qq.com', '正常', '12312313', '2015-08-04 15:27:13', null, '0', '0', '0.0');

-- ----------------------------
-- Table structure for `cp_web_info`
-- ----------------------------
DROP TABLE IF EXISTS `cp_web_info`;
CREATE TABLE `cp_web_info` (
  `id` int(11) NOT NULL,
  `webname` varchar(20) DEFAULT NULL,
  `picLocation` text,
  `webInfo` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of cp_web_info
-- ----------------------------

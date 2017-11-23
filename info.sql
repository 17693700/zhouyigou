/*
Navicat MySQL Data Transfer

Source Server         : 本地数据库
Source Server Version : 50714
Source Host           : 127.0.0.1:3306
Source Database       : info

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2017-08-31 16:19:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for t_admin
-- ----------------------------
DROP TABLE IF EXISTS `t_admin`;
CREATE TABLE `t_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `login_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of t_admin
-- ----------------------------
INSERT INTO `t_admin` VALUES ('10', 'boss', 'c8fGgGhPnh8LD/ttGSD60rIdyScT2vbMjHFAfKSXVBI=', '2017-08-31 15:10:50');
INSERT INTO `t_admin` VALUES ('11', 'jian', 'c8fGgGhPnh8LD/ttGSD60rIdyScT2vbMjHFAfKSXVBI=', '2017-08-17 16:51:52');
INSERT INTO `t_admin` VALUES ('12', 'chujian', 'c8fGgGhPnh8LD/ttGSD60rIdyScT2vbMjHFAfKSXVBI=', '2017-08-17 16:51:25');

-- ----------------------------
-- Table structure for t_advertisement
-- ----------------------------
DROP TABLE IF EXISTS `t_advertisement`;
CREATE TABLE `t_advertisement` (
  `adv_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `end_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `remark` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`adv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of t_advertisement
-- ----------------------------

-- ----------------------------
-- Table structure for t_advertisement_type
-- ----------------------------
DROP TABLE IF EXISTS `t_advertisement_type`;
CREATE TABLE `t_advertisement_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `type_name` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of t_advertisement_type
-- ----------------------------

-- ----------------------------
-- Table structure for t_article_catalog
-- ----------------------------
DROP TABLE IF EXISTS `t_article_catalog`;
CREATE TABLE `t_article_catalog` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `cat_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `order_id` int(11) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of t_article_catalog
-- ----------------------------
INSERT INTO `t_article_catalog` VALUES ('1', '0', '行业新闻', '1');
INSERT INTO `t_article_catalog` VALUES ('2', '0', '展会信息', '2');

-- ----------------------------
-- Table structure for t_article_thread
-- ----------------------------
DROP TABLE IF EXISTS `t_article_thread`;
CREATE TABLE `t_article_thread` (
  `thread_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `title` varchar(155) COLLATE utf8_unicode_ci NOT NULL,
  `summary` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `order_id` int(11) NOT NULL,
  `is_hot` double NOT NULL,
  `clicks` int(11) NOT NULL,
  `page_keywords` varchar(155) COLLATE utf8_unicode_ci NOT NULL,
  `page_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `addtime` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`thread_id`),
  KEY `order_id` (`order_id`) USING BTREE,
  KEY `is_hot` (`is_hot`),
  KEY `clicks` (`clicks`),
  KEY `addtime` (`addtime`),
  KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of t_article_thread
-- ----------------------------
INSERT INTO `t_article_thread` VALUES ('1', '1', '1', '2', '', '20170811\\e3cfdacc504d87c37ab32dccfe91e77e.gif', '<p>222</p>', '2', '0', '0', '', '', '2017-08-11 16:27:58');
INSERT INTO `t_article_thread` VALUES ('3', '2', '文章那个11111111111111112', '这是摘要2', '', '20170811\\63d7cd509912e1e1b32d0c6ec364eee7.jpg', '<p>这是文章内容2</p>', '22', '0', '0', '', '', '2017-08-23 18:01:33');
INSERT INTO `t_article_thread` VALUES ('4', '1', '11', '3', '', '20170814\\335d6c47bedf53bc4ca5a783b1a06ea2.jpg', '<p>333444</p>', '2', '0', '0', '', '', '2017-08-14 11:12:56');
INSERT INTO `t_article_thread` VALUES ('5', '1', '11', '2', '', '20170815\\23647ca2364b502324944045a8a7c322.jpg', '<p>23</p>', '2', '0', '0', '', '', '2017-08-23 17:35:08');

-- ----------------------------
-- Table structure for t_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `t_auth_group`;
CREATE TABLE `t_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_auth_group
-- ----------------------------
INSERT INTO `t_auth_group` VALUES ('1', '超级管理员', '1', '25,26,28,31,32,33,37,38,39,42,45,46,48,49,50,51,52,53,54');
INSERT INTO `t_auth_group` VALUES ('2', '高级管理员', '1', '42,45,46,48,49,50,51,52,53,54');
INSERT INTO `t_auth_group` VALUES ('3', '普通管理员', '1', '42');

-- ----------------------------
-- Table structure for t_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `t_auth_group_access`;
CREATE TABLE `t_auth_group_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_auth_group_access
-- ----------------------------
INSERT INTO `t_auth_group_access` VALUES ('5', '10', '1');
INSERT INTO `t_auth_group_access` VALUES ('6', '11', '3');
INSERT INTO `t_auth_group_access` VALUES ('7', '12', '2');

-- ----------------------------
-- Table structure for t_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `t_auth_rule`;
CREATE TABLE `t_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(50) NOT NULL DEFAULT '',
  `name` char(80) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_auth_rule
-- ----------------------------
INSERT INTO `t_auth_rule` VALUES ('25', '管理员列表', 'index/admin/admin_list', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('26', '管理员添加', 'index/admin/admin_add', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('28', '管理员修改', 'index/admin/admin_edit', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('31', '权限节点列表', 'index/admin/admin_permission', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('32', '权限节点添加', 'index/admin/admin_permission_add', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('33', '权限节点修改', 'index/admin/admin_permission_edit', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('37', '角色列表', 'index/admin/admin_role', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('38', '角色添加', 'index/admin/admin_role_add', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('39', '角色修改', 'index/admin/admin_role_edit', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('42', '文章修改', 'index/article/article_edit', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('45', '品牌修改', 'index/info/info_brand_edit', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('46', '采购信息列表', 'index/info/info_list', '1', '0', '');
INSERT INTO `t_auth_rule` VALUES ('48', '采购信息修改', 'index/info/info_edit', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('49', '分类修改', 'index/info/info_catagory_edit', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('50', '用户列表', 'index/user/user_list', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('51', '用户修改', 'index/user/user_edit', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('52', '用户添加', 'index/user/user_add', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('53', '用户修改密码', 'index/user/user_change_password', '1', '1', '');
INSERT INTO `t_auth_rule` VALUES ('54', '分类添加', 'index/user/catagory_add', '1', '1', '');

-- ----------------------------
-- Table structure for t_friendlink
-- ----------------------------
DROP TABLE IF EXISTS `t_friendlink`;
CREATE TABLE `t_friendlink` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `brand_id` int(11) NOT NULL,
  `title` varchar(155) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_id` int(11) NOT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of t_friendlink
-- ----------------------------

-- ----------------------------
-- Table structure for t_info
-- ----------------------------
DROP TABLE IF EXISTS `t_info`;
CREATE TABLE `t_info` (
  `info_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL COMMENT '分类ID',
  `brand_id` int(11) NOT NULL COMMENT '品牌ID',
  `user_type` enum('管理员','用户') COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `model_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '型号',
  `participle` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '全文索引分词',
  `image` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '图片',
  `number` int(11) NOT NULL COMMENT '数量',
  `quality` varchar(55) COLLATE utf8_unicode_ci NOT NULL COMMENT '质量',
  `pack` varchar(55) COLLATE utf8_unicode_ci NOT NULL COMMENT '包装',
  `invoice` enum('增值税专用发票','普通发票','无发票') COLLATE utf8_unicode_ci NOT NULL COMMENT '发票要求',
  `supplier` enum('不限','工厂','经销商','贸易商','代理商') COLLATE utf8_unicode_ci NOT NULL COMMENT '供货商要求',
  `term` int(11) NOT NULL COMMENT '交货期限（从下单到发货的时间）',
  `start_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '开始时间',
  `end_time` datetime NOT NULL,
  `addtime` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '生成时间',
  `remark` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `is_show` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`info_id`),
  KEY `brand_id` (`brand_id`) USING BTREE,
  KEY `cat_id` (`cat_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `model_name` (`model_name`) USING BTREE,
  KEY `addtime` (`addtime`) USING BTREE,
  KEY `is_show` (`is_show`),
  KEY `participle` (`participle`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of t_info
-- ----------------------------
INSERT INTO `t_info` VALUES ('1', '18', '1', '管理员', '1', '6205轴承', '6205 轴 承 ', '20170814\\c9412bfa80253836fec05e3429a1e276.jpg', '1000', '不锈钢', '纸盒', '增值税专用发票', '经销商', '0', '2017-08-24 11:55:20', '1900-01-01 00:00:00', '2017-08-23 09:39:52', '这是摘要\r\n', '1');
INSERT INTO `t_info` VALUES ('2', '2', '3', '管理员', '1', '12', '12 ', '20170814\\d16d0d0e1bffa43c414e88548591d920.jpg', '111', '1', '1', '普通发票', '工厂', '0', '2017-08-24 11:56:00', '1900-01-01 00:00:00', '2017-07-01 09:39:52', '1', '1');
INSERT INTO `t_info` VALUES ('3', '1', '5', '管理员', '1', '洛轴', '洛 轴 ', '20170815\\4771ee9211a7a055cdcb123c4ad43bdb.jpg', '23', '一般', '木箱', '普通发票', '工厂', '1', '2017-08-22 09:39:57', '1900-01-01 00:00:00', '2017-08-22 09:39:57', '这是摘要', '1');
INSERT INTO `t_info` VALUES ('12', '2', '1', '管理员', '4', '是', '是 ', '', '11', '', '', '无发票', '不限', '0', '2017-08-24 13:50:43', '1900-01-01 00:00:00', '2017-08-23 09:39:59', '申请 撒是', '0');

-- ----------------------------
-- Table structure for t_info_brand
-- ----------------------------
DROP TABLE IF EXISTS `t_info_brand`;
CREATE TABLE `t_info_brand` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `brand_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `brand_note` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `brand_type` enum('国产','进口') COLLATE utf8_unicode_ci NOT NULL,
  `order_id` int(11) NOT NULL,
  `page_title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `page_keywords` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `page_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`brand_id`),
  KEY `brand_type` (`brand_type`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of t_info_brand
-- ----------------------------
INSERT INTO `t_info_brand` VALUES ('1', 'SKF', '20170814\\cbfe1a2f477e222b273a4af4187fc7a4.jpg', '斯凯孚轴承', '进口', '1', '', '', '');
INSERT INTO `t_info_brand` VALUES ('2', 'FAG', '20170814\\1c081d546d4cb7dba02d51930e5a500d.jpg', 'FAG轴承\r\n', '进口', '2', '', '', '');
INSERT INTO `t_info_brand` VALUES ('3', 'LYC', '20170814\\2199068688a8e6c7ce5cd6e59b807b8a.jpg', '中国洛阳轴承', '国产', '1', '', '', '');
INSERT INTO `t_info_brand` VALUES ('5', 'tp', '20170814\\5cbcd187415ebb0c1e53ad7fa2c3b677.jpg', 'tppppppp', '国产', '1', '', '', '');

-- ----------------------------
-- Table structure for t_info_catalog
-- ----------------------------
DROP TABLE IF EXISTS `t_info_catalog`;
CREATE TABLE `t_info_catalog` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `level` int(2) NOT NULL,
  `order_id` int(11) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of t_info_catalog
-- ----------------------------
INSERT INTO `t_info_catalog` VALUES ('1', '国产品牌', '0', '1', '1');
INSERT INTO `t_info_catalog` VALUES ('2', '进口品牌', '0', '1', '2');
INSERT INTO `t_info_catalog` VALUES ('18', '深沟球轴承', '1', '2', '1');
INSERT INTO `t_info_catalog` VALUES ('19', '调心球轴承', '1', '2', '2');
INSERT INTO `t_info_catalog` VALUES ('20', '英制圆锥滚子轴承', '2', '2', '1');
INSERT INTO `t_info_catalog` VALUES ('26', '角接触球轴承', '1', '2', '3');
INSERT INTO `t_info_catalog` VALUES ('28', '直线轴承', '2', '2', '999');

-- ----------------------------
-- Table structure for t_info_offer
-- ----------------------------
DROP TABLE IF EXISTS `t_info_offer`;
CREATE TABLE `t_info_offer` (
  `offer_id` int(11) NOT NULL AUTO_INCREMENT,
  `info_id` int(11) NOT NULL COMMENT '采购信息ID',
  `purchase_id` int(11) NOT NULL COMMENT '采购商ID',
  `supply_id` int(11) NOT NULL COMMENT '供应商ID',
  `original_price` decimal(10,2) NOT NULL COMMENT '原价格',
  `number` int(22) NOT NULL COMMENT '数量',
  `freight` decimal(10,2) NOT NULL COMMENT '运费',
  `tax` decimal(10,2) NOT NULL COMMENT '税',
  `total_price` decimal(10,2) NOT NULL COMMENT '总价格',
  `remark` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `is_deal` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  `addtime` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`offer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of t_info_offer
-- ----------------------------
INSERT INTO `t_info_offer` VALUES ('1', '1', '1', '5', '66.00', '100', '15.00', '2.00', '6660.00', '备注', '0', '2017-08-24 14:28:04');
INSERT INTO `t_info_offer` VALUES ('2', '1', '1', '4', '60.00', '100', '0.00', '2.00', '6000.00', '这是最便宜了', '1', '2017-08-19 14:44:37');

-- ----------------------------
-- Table structure for t_site_config
-- ----------------------------
DROP TABLE IF EXISTS `t_site_config`;
CREATE TABLE `t_site_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sitename` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `page_keywords` varchar(155) COLLATE utf8_unicode_ci NOT NULL,
  `page_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `copyright` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `record_number` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `statistical_code` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of t_site_config
-- ----------------------------
INSERT INTO `t_site_config` VALUES ('4', '轴易购', '这是网站关键词', '这是网站描述', '这是版权信息', '这是备案号', '这是统计代码');

-- ----------------------------
-- Table structure for t_user
-- ----------------------------
DROP TABLE IF EXISTS `t_user`;
CREATE TABLE `t_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `wechat_id` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `account` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('工厂','经销商','贸易商','代理商') COLLATE utf8_unicode_ci DEFAULT NULL,
  `head_image` varchar(155) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `sex` enum('男','女') COLLATE utf8_unicode_ci NOT NULL DEFAULT '男',
  `position` varchar(55) COLLATE utf8_unicode_ci NOT NULL COMMENT '职位',
  `mobile` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `qq` int(22) NOT NULL,
  `email` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `prev_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '上次登录时间',
  `addtime` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `remark` varchar(155) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of t_user
-- ----------------------------
INSERT INTO `t_user` VALUES ('1', '0', '', 'chujian', 'dfECUbv2d9Lwlj+mCO9ggWQuhsONbFh6MFcuJGkN5IY=', '经销商', '20170815\\00a21de98668e3fa5a1667f54ee126c0.jpg', '初见', '男', '程序员', '18636121323', '123456', '17693700', '17693700@qq.com', '2017-08-15 17:23:13', '2017-08-15 17:23:13', '这是备注。');
INSERT INTO `t_user` VALUES ('4', '0', '', '43345345', 'dfECUbv2d9Lwlj+mCO9ggWQuhsONbFh6MFcuJGkN5IY=', '工厂', '20170815\\e929b3822b9a3c88f1221382a6304994.png', '3', '女', '32', '211111', '32', '0', '213', '2017-08-15 16:44:32', '2017-08-15 16:44:32', '32');
INSERT INTO `t_user` VALUES ('5', '0', '', 'hot dog', 'dfECUbv2d9Lwlj+mCO9ggWQuhsONbFh6MFcuJGkN5IY=', '工厂', '', '', '男', '', '', '', '0', '', '2017-08-21 09:24:53', '2017-08-21 09:24:53', '');

-- ----------------------------
-- Table structure for t_user_address
-- ----------------------------
DROP TABLE IF EXISTS `t_user_address`;
CREATE TABLE `t_user_address` (
  `add_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `recv_name` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `recv_province` varchar(33) COLLATE utf8_unicode_ci NOT NULL,
  `recv_city` varchar(33) COLLATE utf8_unicode_ci NOT NULL,
  `recv_area` varchar(33) COLLATE utf8_unicode_ci NOT NULL,
  `recv_tel` varchar(22) COLLATE utf8_unicode_ci NOT NULL,
  `is_default` enum('1','0') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`add_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of t_user_address
-- ----------------------------

-- ----------------------------
-- Table structure for t_user_company
-- ----------------------------
DROP TABLE IF EXISTS `t_user_company`;
CREATE TABLE `t_user_company` (
  `company_id` int(1) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(88) COLLATE utf8_unicode_ci NOT NULL,
  `company_logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `company_type` varchar(22) COLLATE utf8_unicode_ci NOT NULL,
  `company_profile` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '公司简介',
  `corporation` varchar(22) COLLATE utf8_unicode_ci NOT NULL COMMENT '公司法人代表',
  `id_number` int(22) NOT NULL COMMENT '法人身份证号',
  `id_face_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '法人身份证正面照',
  `id_back_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '法人身份证背面照',
  `scale` int(5) NOT NULL COMMENT '公司规模',
  `year_turnover` int(11) NOT NULL COMMENT '年营业额',
  `registered_capital` int(11) NOT NULL COMMENT '注册资本',
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `business_license_number` int(55) NOT NULL COMMENT '营业执照编号',
  `business_license_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '营业执照照片',
  `linkman` varchar(55) COLLATE utf8_unicode_ci NOT NULL COMMENT '公司联系人',
  `mobile` int(11) NOT NULL,
  `tel` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '传真',
  PRIMARY KEY (`company_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of t_user_company
-- ----------------------------

-- ----------------------------
-- Table structure for t_user_message
-- ----------------------------
DROP TABLE IF EXISTS `t_user_message`;
CREATE TABLE `t_user_message` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_read` double(1,0) NOT NULL,
  `addtime` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`msg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of t_user_message
-- ----------------------------

-- ----------------------------
-- Table structure for t_user_order
-- ----------------------------
DROP TABLE IF EXISTS `t_user_order`;
CREATE TABLE `t_user_order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_code` int(55) NOT NULL COMMENT '订单号',
  `shop_id` int(11) NOT NULL COMMENT '商家ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `info_id` int(11) NOT NULL COMMENT '采购信息ID',
  `buy_number` int(11) NOT NULL COMMENT '购买数量',
  `unit_price` decimal(10,2) NOT NULL COMMENT '单价',
  `freight` decimal(10,2) NOT NULL COMMENT '运费',
  `tax` decimal(10,2) NOT NULL COMMENT '税收',
  `total_price` decimal(10,2) NOT NULL COMMENT '总价',
  `pay_type` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '付款方式',
  `static` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '订单状态',
  `recv_province` varchar(55) COLLATE utf8_unicode_ci NOT NULL COMMENT '收货省份',
  `recv_city` varchar(55) COLLATE utf8_unicode_ci NOT NULL COMMENT '收货城市',
  `recv_area` varchar(55) COLLATE utf8_unicode_ci NOT NULL COMMENT '收货地区',
  `recv_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `recv_name` varchar(55) COLLATE utf8_unicode_ci NOT NULL COMMENT '收货人',
  `recv_tel` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '收货电话',
  `addtime` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of t_user_order
-- ----------------------------
INSERT INTO `t_user_order` VALUES ('1', '1212323423', '1', '4', '2', '1000', '6.00', '12.00', '2.00', '5888.00', 'alipay', '2', '山西省', '太原市', '小店区', '太原市小店区大马村111号', '张三', '18636121323', '2017-08-22 10:39:51');
INSERT INTO `t_user_order` VALUES ('2', '1121423423', '4', '1', '12', '10', '55.00', '12.00', '2.00', '536.00', 'tenpay', '1', '江苏省', '南京市', '雨花区', '南京市雨花区2号', '帅帅', '121212121', '2017-08-15 10:03:58');

-- ----------------------------
-- Table structure for t_vip
-- ----------------------------
DROP TABLE IF EXISTS `t_vip`;
CREATE TABLE `t_vip` (
  `vip_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `membership_fee` decimal(11,2) NOT NULL COMMENT '会员费',
  `length_of_time` int(11) NOT NULL COMMENT '会员时长（天）',
  `start_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `end_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `addtime` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`vip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of t_vip
-- ----------------------------

-- ----------------------------
-- Table structure for t_wechat_config
-- ----------------------------
DROP TABLE IF EXISTS `t_wechat_config`;
CREATE TABLE `t_wechat_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `appid` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `appsecret` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `subscribe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `recruit_content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of t_wechat_config
-- ----------------------------
SET FOREIGN_KEY_CHECKS=1;

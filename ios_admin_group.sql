/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50616
Source Host           : localhost:3306
Source Database       : jfq

Target Server Type    : MYSQL
Target Server Version : 50616
File Encoding         : 65001

Date: 2014-07-25 18:22:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ios_admin_group`
-- ----------------------------
DROP TABLE IF EXISTS `ios_admin_group`;
CREATE TABLE `ios_admin_group` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT '组id',
  `group_name` varchar(100) NOT NULL COMMENT '组名',
  `privilege` text NOT NULL COMMENT '用户权限列表,分号分隔',
  `create_time` datetime DEFAULT NULL COMMENT '时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ios_admin_group
-- ----------------------------
INSERT INTO `ios_admin_group` VALUES ('1', '商务', 'report;report::index;platclick;platclick::index;offer;offer::report;offer::edit;offer::index;click;click::index;appprice;appprice::index;app::report;app::edit;app::index;api;api::index;adv::report;adv::edit;adv::index;active;active::index', '2014-07-08 16:21:37', '2014-07-10 11:34:28');
INSERT INTO `ios_admin_group` VALUES ('2', '技术', 'user;user::privilege;user::update;user::edit;user::insert;user::index;report;report::index;privilege;privilege::update;privilege::edit;privilege::insert;privilege::index;platclick;platclick::index;offer;offer::report;offer::update;offer::edit;offer::insert;offer::index;click;click::index;appprice;appprice::update;appprice::edit;appprice::insert;appprice::index;app;app::report;app::delete;app::update;app::edit;app::insert;app::index;api;api::index;group;group::update;group::edit;group::insert;group::index;adv;adv::report;adv::update;adv::edit;adv::insert;adv::index;active;active::index', '2014-07-09 17:40:23', '2014-07-10 11:32:44');
INSERT INTO `ios_admin_group` VALUES ('3', '普通用户', 'report;report::index;platclick;platclick::index;offer::index;offer::report;click;click::index;app::index;app::report;api;api::index;adv::index;adv::report;active;active::index', '2014-07-10 14:57:10', null);
INSERT INTO `ios_admin_group` VALUES ('4', '经理', 'user;user::index;user::insert;user::edit;user::update;user::privilege;report;report::index;privilege;privilege::index;privilege::insert;privilege::edit;privilege::update;platclick;platclick::index;offer;offer::index;offer::insert;offer::edit;offer::update;offer::report;click;click::index;appprice;appprice::index;appprice::insert;appprice::edit;appprice::update;app;app::index;app::insert;app::edit;app::update;app::delete;app::report;api;api::index;group;group::index;group::insert;group::edit;group::update;adv;adv::index;adv::insert;adv::edit;adv::update;adv::report;active;active::index', '2014-07-11 10:34:10', null);

-- ----------------------------
-- Table structure for `ios_admin_user`
-- ----------------------------
DROP TABLE IF EXISTS `ios_admin_user`;
CREATE TABLE `ios_admin_user` (
  `id` int(12) unsigned NOT NULL COMMENT '用户id',
  `user_name` varchar(100) NOT NULL COMMENT '用户名',
  `email` varchar(200) DEFAULT NULL COMMENT '邮箱',
  `group_id` int(12) unsigned NOT NULL COMMENT '用户组id',
  `privilege` text NOT NULL COMMENT '用户权限列表,分号分隔',
  `create_time` datetime DEFAULT NULL COMMENT '注册时间',
  `last_login_time` datetime DEFAULT NULL COMMENT '上次登录时间',
  `is_super_admin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是超级管理员,超级管理员不受用户权限的限制',
  PRIMARY KEY (`id`),
  KEY `user_name` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ios_admin_user
-- ----------------------------
INSERT INTO `ios_admin_user` VALUES ('10', 'joe', 'joe_wang@cn.bposolutions.com', '2', '', '2014-07-11 14:00:42', '2014-07-11 14:00:42', '0');
INSERT INTO `ios_admin_user` VALUES ('3835', 'dean', 'jerryhsa@126.com', '4', '', '2014-07-11 14:54:47', '2014-07-11 14:54:47', '0');
INSERT INTO `ios_admin_user` VALUES ('6287', '875832422@qq.com', '875832422@qq.com', '3', '', '2014-07-15 11:42:11', '2014-07-15 11:42:11', '0');
INSERT INTO `ios_admin_user` VALUES ('216014', 'punk', '63772968@qq.com', '1', '', '2014-07-11 16:59:01', '2014-07-11 16:59:01', '0');
INSERT INTO `ios_admin_user` VALUES ('217169', 'zw1232002', 'zw1232002@gmail.com', '2', 'user::index;user::insert;user::edit;user::update;user::privilege;report;report::index;privilege;privilege::index;privilege::insert;privilege::edit;privilege::update;platclick;platclick::index;offer;offer::index;offer::insert;offer::edit;offer::update;offer::report;click;click::index;appprice;appprice::index;appprice::insert;appprice::edit;appprice::update;app;app::index;app::insert;app::edit;app::update;app::delete;app::report;api;api::index;group;group::index;group::insert;group::edit;group::update;adv;adv::index;adv::insert;adv::edit;adv::update;adv::report;active;active::index', '2014-07-10 15:53:50', '2014-07-10 15:53:50', '1');

-- ----------------------------
-- Table structure for `ios_admin_user_privilege`
-- ----------------------------
DROP TABLE IF EXISTS `ios_admin_user_privilege`;
CREATE TABLE `ios_admin_user_privilege` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `module_name` varchar(100) NOT NULL COMMENT '对应系统中的模块或者方法名',
  `privilege_name` text NOT NULL COMMENT '权限名称',
  `parent` int(12) unsigned DEFAULT '0' COMMENT '父级',
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_name` (`module_name`,`parent`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ios_admin_user_privilege
-- ----------------------------
INSERT INTO `ios_admin_user_privilege` VALUES ('12', 'active', '激活管理', '0');
INSERT INTO `ios_admin_user_privilege` VALUES ('16', 'adv', '广告主管理', '0');
INSERT INTO `ios_admin_user_privilege` VALUES ('17', 'adv::index', '广告主列表', '16');
INSERT INTO `ios_admin_user_privilege` VALUES ('18', 'adv::insert', '新增广告主', '16');
INSERT INTO `ios_admin_user_privilege` VALUES ('19', 'adv::edit', '查看/编辑广告主', '16');
INSERT INTO `ios_admin_user_privilege` VALUES ('20', 'adv::update', '修改广告主', '16');
INSERT INTO `ios_admin_user_privilege` VALUES ('21', 'adv::report', '广告主报表', '16');
INSERT INTO `ios_admin_user_privilege` VALUES ('22', 'group', '用户组管理', '0');
INSERT INTO `ios_admin_user_privilege` VALUES ('23', 'group::index', '用户组列表', '22');
INSERT INTO `ios_admin_user_privilege` VALUES ('24', 'group::insert', '新增用户组', '22');
INSERT INTO `ios_admin_user_privilege` VALUES ('25', 'group::edit', '查看/编辑用户组', '22');
INSERT INTO `ios_admin_user_privilege` VALUES ('26', 'group::update', '修改用户组', '22');
INSERT INTO `ios_admin_user_privilege` VALUES ('27', 'active::index', '激活列表', '12');
INSERT INTO `ios_admin_user_privilege` VALUES ('28', 'api', '接口地址管理', '0');
INSERT INTO `ios_admin_user_privilege` VALUES ('29', 'api::index', '接口地址生成', '28');
INSERT INTO `ios_admin_user_privilege` VALUES ('30', 'app', '渠道管理', '0');
INSERT INTO `ios_admin_user_privilege` VALUES ('31', 'app::index', '渠道列表', '30');
INSERT INTO `ios_admin_user_privilege` VALUES ('32', 'app::insert', '新增渠道', '30');
INSERT INTO `ios_admin_user_privilege` VALUES ('33', 'app::edit', '查看/编辑渠道', '30');
INSERT INTO `ios_admin_user_privilege` VALUES ('34', 'app::update', '修改渠道', '30');
INSERT INTO `ios_admin_user_privilege` VALUES ('35', 'app::delete', '删除渠道', '30');
INSERT INTO `ios_admin_user_privilege` VALUES ('36', 'app::report', '渠道报表', '30');
INSERT INTO `ios_admin_user_privilege` VALUES ('37', 'appprice', '渠道价格管理', '0');
INSERT INTO `ios_admin_user_privilege` VALUES ('38', 'appprice::index', '渠道价格列表', '37');
INSERT INTO `ios_admin_user_privilege` VALUES ('39', 'appprice::insert', '新增渠道价格', '37');
INSERT INTO `ios_admin_user_privilege` VALUES ('40', 'appprice::edit', '查看/编辑渠道价格', '37');
INSERT INTO `ios_admin_user_privilege` VALUES ('41', 'appprice::update', '修改渠道价格', '37');
INSERT INTO `ios_admin_user_privilege` VALUES ('42', 'click', '点击管理', '0');
INSERT INTO `ios_admin_user_privilege` VALUES ('43', 'click::index', '点击列表', '42');
INSERT INTO `ios_admin_user_privilege` VALUES ('44', 'offer', '广告管理', '0');
INSERT INTO `ios_admin_user_privilege` VALUES ('45', 'offer::index', '广告列表', '44');
INSERT INTO `ios_admin_user_privilege` VALUES ('46', 'offer::insert', '新增广告', '44');
INSERT INTO `ios_admin_user_privilege` VALUES ('47', 'offer::edit', '查看/编辑广告', '44');
INSERT INTO `ios_admin_user_privilege` VALUES ('48', 'offer::update', '修改广告', '44');
INSERT INTO `ios_admin_user_privilege` VALUES ('49', 'offer::report', '广告报表', '44');
INSERT INTO `ios_admin_user_privilege` VALUES ('50', 'platclick', '平台通知点击管理', '0');
INSERT INTO `ios_admin_user_privilege` VALUES ('51', 'platclick::index', '平台通知点击列表', '50');
INSERT INTO `ios_admin_user_privilege` VALUES ('52', 'privilege', '权限管理', '0');
INSERT INTO `ios_admin_user_privilege` VALUES ('53', 'privilege::index', '权限列表', '52');
INSERT INTO `ios_admin_user_privilege` VALUES ('54', 'privilege::insert', '新增权限', '52');
INSERT INTO `ios_admin_user_privilege` VALUES ('55', 'privilege::edit', '查看/编辑权限', '52');
INSERT INTO `ios_admin_user_privilege` VALUES ('56', 'privilege::update', '修改权限', '52');
INSERT INTO `ios_admin_user_privilege` VALUES ('57', 'report', '报表管理', '0');
INSERT INTO `ios_admin_user_privilege` VALUES ('58', 'report::index', '每日报表', '57');
INSERT INTO `ios_admin_user_privilege` VALUES ('59', 'user', '用户管理', '0');
INSERT INTO `ios_admin_user_privilege` VALUES ('60', 'user::index', '用户列表', '59');
INSERT INTO `ios_admin_user_privilege` VALUES ('61', 'user::insert', '新增用户', '59');
INSERT INTO `ios_admin_user_privilege` VALUES ('62', 'user::edit', '查看/编辑用户', '59');
INSERT INTO `ios_admin_user_privilege` VALUES ('63', 'user::update', '修改用户', '59');
INSERT INTO `ios_admin_user_privilege` VALUES ('64', 'user::privilege', '用户权限管理', '59');

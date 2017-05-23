-- --------------------------------------------------------
-- 主机:                           127.0.0.1
-- 服务器版本:                        5.5.53 - MySQL Community Server (GPL)
-- 服务器操作系统:                      Win32
-- HeidiSQL 版本:                  9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- 导出 taurasi 的数据库结构
CREATE DATABASE IF NOT EXISTS `kmanager` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `kmanager`;

-- 导出  表 taurasi.km_actions 结构
CREATE TABLE IF NOT EXISTS `km_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `code` varchar(255) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL COMMENT '上级操作',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- 正在导出表  taurasi.km_actions 的数据：~6 rows (大约)
/*!40000 ALTER TABLE `km_actions` DISABLE KEYS */;
INSERT INTO `km_actions` (`id`, `name`, `code`, `pid`) VALUES
	(1, '控制面板', '10000', NULL),
	(2, '系统设置', '10001', NULL),
	(3, '日志管理', '10002', NULL),
	(4, '博文管理', '20000', NULL),
	(5, '博文列表', '20001', NULL),
	(6, '分类列表', '20002', NULL);
/*!40000 ALTER TABLE `km_actions` ENABLE KEYS */;

-- 导出  表 taurasi.km_admin 结构
CREATE TABLE IF NOT EXISTS `km_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(10) DEFAULT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `salt` varchar(5) DEFAULT NULL COMMENT '密码盐',
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `last_login_ip` varchar(20) DEFAULT NULL,
  `last_login_time` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- 正在导出表  taurasi.km_admin 的数据：~2 rows (大约)
/*!40000 ALTER TABLE `km_admin` DISABLE KEYS */;
INSERT INTO `km_admin` (`id`, `title`, `user_name`, `image`, `password`, `salt`, `mobile`, `email`, `last_login_ip`, `last_login_time`, `status`, `create_time`) VALUES
	(1, '超级管理员', 'admin', NULL, '9db06bcff9248837f86d1a6bcf41c9e7', 'EUSM', '13883096587', 'ww@qq.com', '127.0.0.1', 1495527960, 1, NULL),
	(5, '测试员', 'test', NULL, '14e1b600b1fd579f47433b88e8d85291', 'EUSM', '13883096587', 'ww@qq.com', '127.0.0.1', 1487660729, 1, NULL);
/*!40000 ALTER TABLE `km_admin` ENABLE KEYS */;

-- 导出  表 taurasi.km_blog 结构
CREATE TABLE IF NOT EXISTS `km_blog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `author` varchar(255) NOT NULL COMMENT '作者',
  `update_time` int(11) unsigned NOT NULL COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `status` tinyint(1) unsigned zerofill DEFAULT NULL COMMENT '状态：-1：删除，0：草稿，1：发布，2：置顶，3推荐',
  `cate_id` tinyint(1) unsigned DEFAULT NULL COMMENT '分类Id',
  `image` varchar(50) DEFAULT NULL COMMENT '封面',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  taurasi.km_blog 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `km_blog` DISABLE KEYS */;
/*!40000 ALTER TABLE `km_blog` ENABLE KEYS */;

-- 导出  表 taurasi.km_cate 结构
CREATE TABLE IF NOT EXISTS `km_cate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增分类id',
  `cate_name` varchar(50) DEFAULT NULL COMMENT '分类名称',
  `cate_type` tinyint(2) unsigned zerofill DEFAULT '00' COMMENT '分类类型，默认0：blog',
  `create_time` int(11) unsigned zerofill DEFAULT '00000000000' COMMENT '创建时间',
  `update_time` int(11) unsigned zerofill DEFAULT '00000000000' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分类表';

-- 正在导出表  taurasi.km_cate 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `km_cate` DISABLE KEYS */;
/*!40000 ALTER TABLE `km_cate` ENABLE KEYS */;

-- 导出  表 taurasi.km_log 结构
CREATE TABLE IF NOT EXISTS `km_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '日志id',
  `portal` char(100) NOT NULL DEFAULT '' COMMENT '入口',
  `controller` char(100) NOT NULL DEFAULT '' COMMENT '控制器',
  `action` char(100) NOT NULL DEFAULT '' COMMENT '动作',
  `get` text NOT NULL COMMENT 'get参数',
  `post` text NOT NULL COMMENT 'post参数',
  `message` varchar(255) NOT NULL DEFAULT '' COMMENT '信息',
  `ip` char(100) NOT NULL DEFAULT '' COMMENT 'ip地址',
  `user_agent` char(200) NOT NULL DEFAULT '' COMMENT '用户代理',
  `referer` char(100) NOT NULL DEFAULT '' COMMENT 'referer',
  `account_id` int(10) NOT NULL DEFAULT '0' COMMENT '帐号id',
  `account_name` char(100) NOT NULL DEFAULT '' COMMENT '帐号名',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日志表';

-- 正在导出表  taurasi.km_log 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `km_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `km_log` ENABLE KEYS */;

-- 导出  表 taurasi.km_login_record 结构
CREATE TABLE IF NOT EXISTS `km_login_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- 正在导出表  taurasi.km_login_record 的数据：~2 rows (大约)
/*!40000 ALTER TABLE `km_login_record` DISABLE KEYS */;
INSERT INTO `km_login_record` (`id`, `admin_id`, `create_time`, `ip`, `ip_address`) VALUES
	(13, 6, 1467184321, '127.0.0.1', '本机地址'),
	(14, 6, 1467184370, '127.0.0.1', '本机地址');
/*!40000 ALTER TABLE `km_login_record` ENABLE KEYS */;

-- 导出  表 taurasi.km_log_crash 结构
CREATE TABLE IF NOT EXISTS `km_log_crash` (
  `crash_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '异常日志id',
  `ip` varchar(20) NOT NULL DEFAULT '' COMMENT 'IP',
  `host_name` varchar(100) NOT NULL DEFAULT '' COMMENT '服务器名',
  `level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '级别',
  `file` varchar(256) NOT NULL DEFAULT '' COMMENT '文件',
  `line` int(10) NOT NULL DEFAULT '0' COMMENT '行数',
  `message` text NOT NULL COMMENT '信息',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`crash_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='异常日志表';

-- 正在导出表  taurasi.km_log_crash 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `km_log_crash` DISABLE KEYS */;
/*!40000 ALTER TABLE `km_log_crash` ENABLE KEYS */;

-- 导出  表 taurasi.km_menus 结构
CREATE TABLE IF NOT EXISTS `km_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '菜单名称',
  `icon` varchar(255) DEFAULT NULL COMMENT '菜单图标css类名',
  `action_id` int(11) DEFAULT NULL COMMENT '操作id',
  `uri` varchar(50) DEFAULT NULL COMMENT '操作uri',
  `type` tinyint(1) DEFAULT '0' COMMENT '菜单类型，0后台菜单，1、前台菜单',
  `is_child` tinyint(1) DEFAULT '1' COMMENT '是否为子菜单，1子菜单，-1不是子菜单',
  `status` tinyint(1) unsigned zerofill DEFAULT '1' COMMENT '是否启用，1启用，-1禁用',
  `pid` int(11) unsigned DEFAULT '0' COMMENT '菜单父级',
  `weight` int(11) DEFAULT '99' COMMENT '权重（值越大排序越前）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- 正在导出表  taurasi.km_menus 的数据：~13 rows (大约)
/*!40000 ALTER TABLE `km_menus` DISABLE KEYS */;
INSERT INTO `km_menus` (`id`, `name`, `icon`, `action_id`, `uri`, `type`, `is_child`, `status`, `pid`, `weight`) VALUES
	(1, '控制面板', 'fa-dashboard', 0, '', 0, 1, 1, 0, 99),
	(2, '系统设置', 'fa-circle-o', 0, '/index/index', 0, 1, 1, 1, 99),
	(3, '日志管理', 'fa-circle-o', 0, '/log/index', 0, 1, 1, 1, 94),
	(4, '博文管理', 'fa-files-o', 0, '', 0, 1, 1, 0, 99),
	(5, '博文列表', 'fa-circle-o', 0, '/blog/index', 0, 1, 1, 4, 99),
	(6, '分类列表', 'fa-circle-o', 0, '/bcate/index', 0, 1, 1, 4, 99),
	(7, '用户管理', 'fa-circle-o', 0, '/user/index', 0, 1, 1, 1, 96),
	(8, '角色管理', 'fa-circle-o', 0, '/role/index', 0, 1, 1, 1, 95),
	(9, '菜单管理', 'fa-circle-o', 0, '/menu/index', 0, 1, 1, 1, 97),
	(10, '个人设置', 'fa-circle-o', 0, '/index/profile', 0, 1, 1, 1, 98),
	(11, '主页', '', 0, 'http://wjlike.com', 1, -1, 1, 0, 99),
	(12, '分类', '', 0, 'http://wjlike.com', 1, -1, 1, 0, 99),
	(13, '视频', '', 0, 'http://wjlike.com', 1, 1, 1, 12, 99);
/*!40000 ALTER TABLE `km_menus` ENABLE KEYS */;

-- 导出  表 taurasi.km_roles 结构
CREATE TABLE IF NOT EXISTS `km_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `status` int(11) DEFAULT '1' COMMENT '状态,1启用',
  `intro` varchar(255) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- 正在导出表  taurasi.km_roles 的数据：~2 rows (大约)
/*!40000 ALTER TABLE `km_roles` DISABLE KEYS */;
INSERT INTO `km_roles` (`id`, `name`, `status`, `intro`) VALUES
	(1, '超级管理', 1, '所有权限'),
	(2, '普通管理', 1, '普通用户');
/*!40000 ALTER TABLE `km_roles` ENABLE KEYS */;

-- 导出  表 taurasi.km_role_action 结构
CREATE TABLE IF NOT EXISTS `km_role_action` (
  `role_id` int(11) unsigned NOT NULL,
  `action_id` int(11) unsigned DEFAULT NULL,
  KEY `Index 1` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 正在导出表  taurasi.km_role_action 的数据：5 rows
/*!40000 ALTER TABLE `km_role_action` DISABLE KEYS */;
INSERT INTO `km_role_action` (`role_id`, `action_id`) VALUES
	(2, 1),
	(2, 2),
	(2, 3),
	(2, 4),
	(2, 5);
/*!40000 ALTER TABLE `km_role_action` ENABLE KEYS */;

-- 导出  表 taurasi.km_role_user 结构
CREATE TABLE IF NOT EXISTS `km_role_user` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 正在导出表  taurasi.km_role_user 的数据：1 rows
/*!40000 ALTER TABLE `km_role_user` DISABLE KEYS */;
INSERT INTO `km_role_user` (`user_id`, `role_id`) VALUES
	(1, 1);
/*!40000 ALTER TABLE `km_role_user` ENABLE KEYS */;

-- 导出  表 taurasi.km_session 结构
CREATE TABLE IF NOT EXISTS `km_session` (
  `session_id` varchar(24) NOT NULL DEFAULT '' COMMENT 'Session id',
  `last_active` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Last active time',
  `contents` varchar(1000) NOT NULL DEFAULT '' COMMENT 'Session data',
  PRIMARY KEY (`session_id`),
  KEY `last_active` (`last_active`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='会话信息表';

-- 正在导出表  taurasi.km_session 的数据：2 rows
/*!40000 ALTER TABLE `km_session` DISABLE KEYS */;
INSERT INTO `km_session` (`session_id`, `last_active`, `contents`) VALUES
	('5923fba2648a86-80611028', 1495530402, 'YToxOntzOjExOiJsYXN0X2FjdGl2ZSI7aToxNDk1NTMwNDAyO30='),
	('5923ea1a0d2386-82885409', 1495529335, 'YTo3OntzOjExOiJsYXN0X2FjdGl2ZSI7aToxNDk1NTI5MzM1O3M6ODoidXNlcm5hbWUiO3M6NToiYWRtaW4iO3M6NToidGl0bGUiO3M6MTU6Iui2hee6p+euoeeQhuWRmCI7czo5OiJsb2dpbnRpbWUiO2k6MTQ5NTUyNzk2MDtzOjI6ImlkIjtzOjE6IjEiO3M6Njoicm9sZWlkIjtzOjE6IjEiO3M6NToibG9naW4iO2k6MTt9');
/*!40000 ALTER TABLE `km_session` ENABLE KEYS */;

-- 导出  表 taurasi.km_users 结构
CREATE TABLE IF NOT EXISTS `km_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '登录密码，md5加密',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `truename` varchar(50) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '登录邮箱',
  `user_url` varchar(100) NOT NULL DEFAULT '' COMMENT '用户个人网站',
  `avatar` varchar(255) DEFAULT NULL COMMENT '用户头像，相对于upload/avatar目录',
  `sex` smallint(1) DEFAULT '0' COMMENT '性别；0：保密，1：男；2：女',
  `birthday` date DEFAULT '2000-01-01' COMMENT '生日',
  `signature` varchar(255) DEFAULT NULL COMMENT '个性签名',
  `last_login_ip` varchar(16) DEFAULT NULL COMMENT '最后登录ip',
  `last_login_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '最后登录时间',
  `create_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '注册时间',
  `activation_key` varchar(60) NOT NULL DEFAULT '' COMMENT '激活码',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '用户状态 0：禁用； 1：正常 ；2：未验证',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `type` smallint(1) DEFAULT '1' COMMENT '用户类型，1:admin ;2:会员',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  PRIMARY KEY (`id`),
  KEY `username_key` (`username`),
  KEY `nickname` (`nickname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

-- 正在导出表  taurasi.km_users 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `km_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `km_users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50547
Source Host           : 127.0.0.1:3306
Source Database       : music_php

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2018-09-02 10:02:19
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for player
-- ----------------------------
DROP TABLE IF EXISTS `player`;
CREATE TABLE `player` (
  `id` varchar(32) NOT NULL,
  `name` varchar(30) DEFAULT NULL COMMENT '播放器名称',
  `user_id` varchar(32) DEFAULT NULL COMMENT '关联用户id',
  `auto_player` int(1) DEFAULT NULL COMMENT '是否自动播放',
  `random_player` int(1) DEFAULT NULL COMMENT '是否随机播放',
  `default_volume` int(3) DEFAULT NULL COMMENT '默认音量',
  `show_lrc` int(1) DEFAULT NULL COMMENT '是否显示歌词',
  `site_name` varchar(30) DEFAULT NULL COMMENT '站点名称  用于播放器显示名称',
  `greeting` varchar(30) DEFAULT NULL COMMENT '欢迎语',
  `show_greeting` int(1) DEFAULT NULL COMMENT '是否显示欢迎语',
  `default_album` int(3) DEFAULT NULL COMMENT '默认专辑',
  `background` int(1) DEFAULT NULL COMMENT '模糊背景是否开启',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of player
-- ----------------------------
INSERT INTO `player` VALUES ('2e086053d2f44e79a489e38085e9edb6', '我的播放器', '1', '0', '1', '80', '1', 'IT技术宅', '欢迎光临寒舍！', '1', '0', '1', null);

-- ----------------------------
-- Table structure for player_song_sheet
-- ----------------------------
DROP TABLE IF EXISTS `player_song_sheet`;
CREATE TABLE `player_song_sheet` (
  `player_id` varchar(32) DEFAULT NULL COMMENT '播放器id',
  `song_sheet_id` varchar(32) DEFAULT NULL COMMENT '歌单id',
  `taxis` int(3) DEFAULT NULL COMMENT '排序'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of player_song_sheet
-- ----------------------------
INSERT INTO `player_song_sheet` VALUES ('2e086053d2f44e79a489e38085e9edb6', '367e5a67e63d40d4898d22fdcdc889d7', '0');

-- ----------------------------
-- Table structure for song
-- ----------------------------
DROP TABLE IF EXISTS `song`;
CREATE TABLE `song` (
  `id` varchar(32) NOT NULL,
  `song_id` varchar(32) DEFAULT NULL COMMENT '歌曲id',
  `song_sheet_id` varchar(32) DEFAULT NULL COMMENT '所属歌单',
  `name` varchar(100) DEFAULT NULL COMMENT '歌曲名称',
  `type` varchar(10) DEFAULT NULL COMMENT '歌曲类型',
  `album_name` varchar(100) DEFAULT NULL COMMENT '专辑名称',
  `artist_name` varchar(100) DEFAULT NULL COMMENT '歌手名称',
  `album_cover` varchar(100) DEFAULT NULL COMMENT '专辑图片',
  `location` varchar(150) DEFAULT NULL COMMENT '歌曲地址',
  `lyric` varchar(100) DEFAULT NULL COMMENT '歌词地址',
  `taxis` int(3) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of song
-- ----------------------------
INSERT INTO `song` VALUES ('8d571066fc0547baaa334d324d40fb60', '566442496', '367e5a67e63d40d4898d22fdcdc889d7', '浪人琵琶', 'wy', '胡66', '胡66', 'http://p1.music.126.net/G5YxAyt9812z9MROfWarkg==/109951163318974870.jpg?param=300x300', null, null, '4');
INSERT INTO `song` VALUES ('913ea1c36fa94408b304859162beb8ea', '001EzHH003Zw8Z', '367e5a67e63d40d4898d22fdcdc889d7', '贫穷或富有', 'qq', '贫穷或富有', '李荣浩', 'http://y.gtimg.cn/music/photo_new/T002R300x300M000000nHX4F1OiYhL.jpg', null, null, '0');
INSERT INTO `song` VALUES ('bc35fc9bb1014d5cbe8d2318e311450c', '428116208', '367e5a67e63d40d4898d22fdcdc889d7', '双截棍 ', 'netease', '周建华作品集', '周建华', 'http://p1.music.126.net/q5iMmP15ItYdcVBgtB0ZCQ==/18684001091229391.jpg?param=300x300', null, null, '3');
INSERT INTO `song` VALUES ('d1a456fd39024cc8bbcf63c66007510e', '5282665', '367e5a67e63d40d4898d22fdcdc889d7', '爱', 'wy', '飞碟1991-这一年3', '小虎队', 'http://p1.music.126.net/GWn7Q2q9OrUPK50HZ8yC5w==/75866302334129.jpg?param=300x300', null, null, '2');
INSERT INTO `song` VALUES ('fe82cac5dc35482bb189a4d8d8728751', '3A20D868FAC7AABD11EEEA8474CB47A4', '367e5a67e63d40d4898d22fdcdc889d7', '不想想你', 'kg', 'undefined', '张杰', 'http://imge.kugou.com/stdmusic/150/20180828/20180828211005772299.jpg', null, null, '1');

-- ----------------------------
-- Table structure for song_sheet
-- ----------------------------
DROP TABLE IF EXISTS `song_sheet`;
CREATE TABLE `song_sheet` (
  `id` varchar(32) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `sheet_id` varchar(20) DEFAULT NULL,
  `user_id` varchar(32) DEFAULT NULL COMMENT '歌单所属用户',
  `name` varchar(30) DEFAULT NULL COMMENT '歌单名称',
  `author` varchar(30) DEFAULT NULL COMMENT '歌单作者',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of song_sheet
-- ----------------------------
INSERT INTO `song_sheet` VALUES ('367e5a67e63d40d4898d22fdcdc889d7', 'sdtj', 'sdtj', '1', '我喜欢的歌', 'zing', '2018-09-01 17:36:15');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` varchar(32) NOT NULL,
  `username` varchar(20) DEFAULT NULL COMMENT '用户账号',
  `password` varchar(32) DEFAULT NULL COMMENT '用户密码',
  `qq` varchar(15) DEFAULT NULL COMMENT 'QQ号码',
  `email` varchar(30) DEFAULT NULL COMMENT '邮箱地址',
  `status` int(1) DEFAULT '1' COMMENT '状态 1:启用 0:禁用',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `create_ip` varchar(32) DEFAULT NULL COMMENT '创建时ip',
  `last_login_time` datetime DEFAULT NULL COMMENT '上次登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'admin', '202cb962ac59075b964b07152d234b70', '1', '1', '1', '2018-08-30 09:50:10', '1', '2018-09-01 21:52:48');
/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : 测试

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-12-22 16:32:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for games_sdddp
-- ----------------------------
DROP TABLE IF EXISTS `games_sdddp`;
CREATE TABLE `games_sdddp` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) DEFAULT NULL COMMENT '微信openid',
  `nickname` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '微信昵称',
  `headimgurl` varchar(200) DEFAULT NULL COMMENT '微信头像',
  `score` int(20) NOT NULL DEFAULT '0' COMMENT '游戏积分',
  `heart` int(10) DEFAULT NULL COMMENT '心',
  `belong` varchar(100) DEFAULT NULL COMMENT '分享给我的',
  `count` int(10) DEFAULT '0' COMMENT '计次',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=111 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of games_sdddp
-- ----------------------------
INSERT INTO `games_sdddp` VALUES ('88', 'oXOgU1EUB22HStJph0x_710VDBR8', '昵称1', 'http://wx.qlogo.cn/mmopen/vi_32/g4vibWFiazOIkPNbd7sbfB48MRXPbibcwwuAFichcnfa9icvuWlPyNNFyxukhyKP06FCabruteVx4IUhqNM9gR7xQmw/0', '100', '10', null, '32', '2017-12-22 16:30:38');
INSERT INTO `games_sdddp` VALUES ('89', 'oXOgU1ENxVtZ0v5Rxjluh0I_6OiE', '昵称2', 'http://wx.qlogo.cn/mmopen/vi_32/D184EF4WqWh5YQHG2PpIQNB3I0NDYkvY2nlLjurTvRVh2IsllxTYiaicvAqpWbvrumiaMIQf4LdHo0Y0haKFicDouw/0', '101', '10', null, '145', '2017-12-22 16:30:35');
INSERT INTO `games_sdddp` VALUES ('90', 'oXOgU1I-K5WWQYxJidcxEWGOkHsk', '昵称3', 'http://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83eps8nysPPibb0anPrjAhcRUgG0LVwL9piclsQ5fwoVkwA5FDXDbokl0XFo3xLjicygypJfYbnf5opRaQ/0', '102', '10', null, '60', '2017-12-22 16:30:35');
INSERT INTO `games_sdddp` VALUES ('91', 'oXOgU1LEmNu7eHa7iXh8FEMWbX1A', '昵称4', 'http://wx.qlogo.cn/mmopen/vi_32/64qnQqmrjTXGXbicF2ibatSxd7DicFIicKaWficppQDncmoCibfwcPUTMqM8LSjyYZsiauGRNscRTvdINEg7DOwrYusqA/0', '103', '10', 'abc', '3', '2017-12-22 16:30:34');
INSERT INTO `games_sdddp` VALUES ('92', 'oXOgU1EnCx0W0pZJLpWLgxuZb2vw', '昵称5', 'http://wx.qlogo.cn/mmopen/vi_32/BTIJmzZ6GnMR0D3199k0X79zG5iaeT9cT1ZQDqt8B3dmc47y3OFKlLmx8iaSgDaTx2mlouyGNZzMYCj2kGvbnDkw/0', '104', '10', 'abc', '26', '2017-12-22 16:30:34');
INSERT INTO `games_sdddp` VALUES ('93', 'oXOgU1LhLXL1KPLVv1IXrVBTJ0W0', '昵称6', 'http://wx.qlogo.cn/mmopen/vi_32/a1bQ14JY3hKhQ0VWIg1BpJRiacKstCuUD7PCPynaTcDdeXxibakiciaARicF51FWtjEQxcD4iaQ8qiaiaWHzfAibyFtwBAg/0', '105', '10', null, '22', '2017-12-22 16:30:33');
INSERT INTO `games_sdddp` VALUES ('94', 'oXOgU1INsICmh0cAIfl7EcoVEnuE', '昵称7', 'http://wx.qlogo.cn/mmopen/vi_32/iadrPKsUSUO3caqTgnknxEatzgpyHXTcFjWVRZ8r3KcYMW3PuGicxTp7LonTDUBFp4nCM9JkrIlRt45cwq1sH8aw/0', '106', '10', null, '40', '2017-12-22 16:30:32');
INSERT INTO `games_sdddp` VALUES ('95', 'oXOgU1PDOIHZ00uIrjz7LtZNv294', '昵称8', 'http://wx.qlogo.cn/mmopen/vi_32/2uoErwoZk8ogL3v5ngkA6mwzhtzHmIAcSxgdrWu4LdM5Lv74CuOickqTy9xZXQHDsyOtmhKvebNKZ5icZuxDv8gQ/0', '107', '10', null, '7', '2017-12-22 16:30:31');
INSERT INTO `games_sdddp` VALUES ('96', 'oXOgU1AmCAtvJhDnZkGJc-KtHONA', '昵称9', 'http://wx.qlogo.cn/mmopen/vi_32/O2BAfPYJ2ibGgZ8GbmsdxJicibvbSVDDC1JSZ7Yh8ALyro2CEKhxwLn4flqwB5bLBJEIyN4ABUIUScJUgOQT1iaANQ/0', '108', '10', null, '1', '2017-12-22 16:30:31');
INSERT INTO `games_sdddp` VALUES ('97', 'oXOgU1MNYNvS0g3Wp8pNaZvLc-nw', '昵称10', 'http://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83epsjmZxMQ8nP7fcbB53LuYWvwc6QUv8MgX9iaXfEfv2sd39M2SwBvib0BVicq2JQj8ia05fBL1EBTuWibw/0', '109', '10', null, '1', '2017-12-22 16:30:30');
INSERT INTO `games_sdddp` VALUES ('98', 'oXOgU1G3ydjNaysjepgaDZf8wcyg', '昵称11', 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLLeKVmTFdR2jajUHsgQBjOsYm93gDAiagw7OsDicIgcPjhxdjibbc8CBQudicyUo5vzrFfg17FibuoMRQ/0', '110', '10', null, '1', '2017-12-22 16:30:30');
INSERT INTO `games_sdddp` VALUES ('110', 'abc', 'abc', 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLLeKVmTFdR2jajUHsgQBjOsYm93gDAiagw7OsDicIgcPjhxdjibbc8CBQudicyUo5vzrFfg17FibuoMRQ/0', '17', '10', '0', '2', '2017-12-22 16:30:29');

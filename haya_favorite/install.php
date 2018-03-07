<?php

/**
 * 帖子收藏插件 安装程序
 *
 * @create 2018-1-25
 * @author deatil
 */
 
!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;

$sql = "
CREATE TABLE {$tablepre}haya_favorite (
	`tid` int(11) NOT NULL COMMENT '帖子ID',
	`uid` int(11) NOT NULL COMMENT '用户ID',
	`create_date` int(10) NULL DEFAULT '0' COMMENT '添加时间',
	`create_ip` int(10) NULL DEFAULT '0' COMMENT '添加IP',
	KEY `tid` (`tid`),
	KEY `uid` (`uid`),
	KEY `tid_uid` (`tid`, `uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$r = db_exec($sql);
$r === FALSE AND message(-1, '创建表结构失败'); // 中断，安装失败。

$sql = "
ALTER TABLE {$tablepre}thread ADD COLUMN haya_favorites int(11) NULL DEFAULT '0' COMMENT '收藏数';
";
$r = db_exec($sql);
$r === FALSE AND message(-1, '创建表结构失败'); // 中断，安装失败。

// 添加插件配置
$haya_favorite_config = array(
	"user_favorite" => 10,
	"user_favorite_sort" => 'desc',
	"show_hot_favorite" => 0,
	"hot_favorite" => 10,
	"hot_favorite_time" => 86400,
);
setting_set('haya_favorite', $haya_favorite_config); 

?>
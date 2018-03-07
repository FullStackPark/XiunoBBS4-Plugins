<?php

/*
	Xiuno BBS 4.0.0 插件：帖子更新日志
	admin/plugin-install-xn_post_update_log.htm
*/

!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;
$sql = "CREATE TABLE IF NOT EXISTS {$tablepre}post_update_log (
	logid int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
	pid tinyint(1) NOT NULL DEFAULT '0' COMMENT '帖子ID',
	uid tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户ID',
	message char(40) NOT NULL DEFAULT '' COMMENT '帖子内容',
	create_date char(40) NOT NULL DEFAULT '' COMMENT '创建时间',
	PRIMARY KEY (logid),
	KEY (pid),
	KEY (uid)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";

$r = db_exec($sql);

?>
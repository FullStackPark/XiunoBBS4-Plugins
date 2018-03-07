<?php

/*
	Xiuno BBS 4.0 主题高亮
*/

!defined('DEBUG') AND exit('Forbidden');

$setting = setting_get('huux_hlight');
if(empty($setting)) {
	$setting = array('hlight_s1c'=>'#D9534D','hlight_s1w'=>'normal','hlight_s1n'=>'','hlight_s2c'=>'#F0AD4E','hlight_s2w'=>'normal','hlight_s2n'=>'','hlight_s3c'=>'#5BC0DE','hlight_s3w'=>'normal','hlight_s3n'=>'','hlight_s4c'=>'#5CB85C','hlight_s4w'=>'normal','hlight_s4n'=>'','hlight_s5c'=>'#337AB7','hlight_s5w'=>'normal','hlight_s5n'=>'','hlight_io'=>0,'hlight_n_io'=>0);
	setting_set('huux_hlight', $setting);
}

$tablepre = $db->tablepre;
$sql = "ALTER TABLE {$tablepre}thread ADD COLUMN hlight tinyint(3) unsigned NOT NULL default '0';";
db_exec($sql);

?>
<?php

function post_update_log_find_by_pid($pid) {
	$arrlist = db_find('post_update_log', array('pid'=>$pid), array('logid'=>1), 1, 1000, 'logid', array('logid', 'pid', 'uid', 'create_date'));
	return $arrlist;
}

function post_update_log_find_last_by_pid($pid) {
	$arr = db_find_one('post_update_log', array('pid'=>$pid), array('logid'=>-1));
	return $arr;
}

function post_update_log_read($logid) {
	$arr = db_find_one('post_update_log', array('logid'=>$logid));
	post_update_log_format($arr);
	return $arr;
}

function post_update_log_create($log) {
	// 如果 100 秒内连续创建，则忽略
	$pid = $log['pid'];
	$arr = post_update_log_find_last_by_pid($pid);
	// 十分钟内编辑只算一条
	if($arr && $log['create_date'] - $arr['create_date'] < 600) {
		return 0;
	}
	$r = db_create('post_update_log', array(
		'pid'=>$arr['pid'],
		'uid'=>$arr['uid'],
		'create_date'=>$arr['create_date'],
		'message'=>$arr['message'],
	));
	return $r;
}


function post_update_log_delete($logid) {
	$r = db_delete('post_update_log', array('logid'=>$logid));
	return $r;
}

function post_update_log_format(&$arr) {
	if(empty($arr)) return;
	$arr['user'] = user_read($arr['uid']);
	$arr['create_date_fmt'] = humandate($arr['create_date']); 
}
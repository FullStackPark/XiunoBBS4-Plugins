<?php

/**
 * 帖子收藏 函数文件
 */

function haya_favorite_create($arr) {
	$r = db_create('haya_favorite', $arr);
	return $r;
}

function haya_favorite_delete($cond = array()) {
	$r = db_delete('haya_favorite', $cond);
	return $r;
}

function haya_favorite_update($id, $arr) {
	$r = db_update('haya_favorite', array(
		'id' => $id
	), $arr);
	return $r;
}

function haya_favorite_maxid() {
	return db_maxid('haya_favorite', 'id');
}

function haya_favorite_count($cond = array()) {
	$n = db_count('haya_favorite', $cond);
	return $n;
}

function haya_favorite_find(
	$cond = array(), 
	$orderby = array(), 
	$page = 1, 
	$pagesize = 20
) {
	$favorites = db_find('haya_favorite', $cond, $orderby, $page, $pagesize);
	
	if (!empty($favorites)) {
		foreach ($favorites as & $favorite) {
			$favorite['favorite_uid'] = $favorite['uid'];
			$favorite['favorite_tid'] = $favorite['tid'];
			$thread = thread_read($favorite['tid']);
			$favorite = array_merge($favorite, $thread);
		}
	}	
	
	return $favorites;
}

function haya_favorite_find_by_uid_and_tid($uid, $tid) {
	$r = db_find('haya_favorite', array('uid'=>$uid,'tid'=>$tid));
	return empty($r) ? false : true;
}

function haya_favorite_by_tid($tid) {
	$haya_favorites = haya_favorite_find(array('tid' => $tid), array('create_date' => -1), 1, 15); 
	
	$haya_favorite_users = [];
	if (!empty($haya_favorites)) { 
		foreach($haya_favorites as $haya_favorite){ 
			$haya_favorite_user = user_read($haya_favorite['favorite_uid']);
			$haya_favorite_users[] = '<a href="'.url('user-'.$haya_favorite_user['uid']).'" target="_blank" title="'.$haya_favorite_user['username'].'">'.$haya_favorite_user['username'].'</a>';
		}
	}
	
	return $haya_favorite_users;
}

function haya_favorite_delete_by_tid($tid) {
	$r = db_delete('haya_favorite', array('tid' => $tid));
	return $r;
}


// haya_favorites + 1
function haya_favorite_thread_user_favorites($tid, $n = 1) {
	global $conf;

	$db = $_SERVER['db'];
	$tablepre = $db->tablepre;	
	
	if (!$conf['update_views_on']) return TRUE;
	$sqladd = strpos($conf['db']['type'], 'mysql') === FALSE ? '' : ' LOW_PRIORITY';
	$r = db_exec("UPDATE$sqladd `{$tablepre}thread` SET haya_favorites=haya_favorites+$n WHERE tid='$tid'");
	return $r;
}

// 依据帖子收藏数排序，过期时间 1天 = 24 * 60 * 60
function haya_favorite_hot_threads_base_user_favorite($pagesize = 10, $life_time = 86400) {
	$favorites = cache_get('haya_favorite_favorites');

	if ($favorites === NULL) {
		$favorites = thread__find(array('haya_favorites' => array(">" => 0)), array('haya_favorites' => -1), 1, $pagesize);
	
		cache_set('haya_favorite_favorites', $favorites, $life_time);
	}	
	
	return $favorites;	
}

function haya_favorite_hot_threads($num = 10) {	
	$db = $_SERVER['db'];
	$tablepre = $db->tablepre;	
	
	$sql = "
		SELECT favorite.uid as favorite_uid, 
			favorite.tid as favorite_tid, 
			thread.*,
			count(favorite.uid) as thread_count,
			((max(favorite.create_date) + min(favorite.create_date)) /(max(favorite.create_date) - min(favorite.create_date)) * 0.2 + count(favorite.uid) * 0.3 + thread.views * 0.2 + thread.posts * 0.3) as rank
		FROM {$tablepre}haya_favorite as favorite
		LEFT JOIN {$tablepre}thread as thread
			ON favorite.tid = thread.tid
		GROUP BY favorite.tid
		ORDER BY rank DESC, 
			thread_count DESC, 
			thread.last_date DESC
		LIMIT {$num}
	";
	$favorites = db_sql_find($sql);		
	
	return $favorites;
}

// 热门帖子收藏 - 带缓存，过期时间 1天 = 24 * 60 * 60
function haya_favorite_hot_threads_base_cache($num = 10, $life_time = 86400) {
	$favorites = cache_get('haya_favorite_favorites');
	
	if ($favorites === NULL) {
		$favorites = haya_favorite_hot_threads($num);		
		
		cache_set('haya_favorite_favorites', $favorites, $life_time);
	}
	
	return $favorites;	
}


?>

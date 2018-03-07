<?php
exit;

elseif ($action == 'favorite') {

	$header['title'] = "我的收藏 - " . $conf['sitename'];
	
	if ($method == 'GET') {
		
		$haya_favorite_config = setting_get('haya_favorite');
		
		$pagesize = intval($haya_favorite_config['user_favorite']);
		$page = param(2, 1);
		$cond['uid'] = $uid; 
		
		$haya_favorite_count = haya_favorite_count($cond);
		$threadlist = haya_favorite_find($cond, array('create_date' => -1), $page, $pagesize);
		$pagination = pagination(url("my-favorite-{page}"), $haya_favorite_count, $page, $pagesize);
		
		include _include(APP_PATH.'plugin/haya_favorite/view/htm/my_favorite.htm');

	} else {	

		$action = param('action', 'add');
		$tid = param('tid');
		if (!$user) {
			message(0, '只有登录后才能够收藏！');
		}

		$thread = thread_read($tid);
		empty($thread) AND message(0, lang('thread_not_exists'));
		$haya_check_favorite = haya_favorite_find_by_uid_and_tid($uid, $tid);
		
		if ($action == 'add') {
			if (!empty($haya_check_favorite)) {
				message(0, '你已经收藏过该帖子了！');
			}
			
			haya_favorite_create(array(
				'tid' => $tid, 
				'uid' => $user['uid'],
				'create_date' => time(),
				'create_ip' => $longip,
			));
			
			haya_favorite_thread_user_favorites($tid, 1);
			
			$haya_favorite_users = haya_favorite_by_tid($tid);
			$haya_favorite_user = implode('、', $haya_favorite_users);
			$haya_favorite_count = haya_favorite_count(array('tid' => $tid));

			$haya_favorite_msg = array(
				'users' => $haya_favorite_user,
				'count' => $haya_favorite_count,
				'msg' => '收藏帖子成功！',
			);
			
			message(1, $haya_favorite_msg);
		} elseif ($action == 'del') {
			if (empty($haya_check_favorite)) {
				message(0, '你还没有收藏过该帖子！');
			}
			
			haya_favorite_delete(array('tid' => $tid, 'uid' => $user['uid']));
			
			haya_favorite_thread_user_favorites($tid, -1);
			
			$haya_favorite_users = haya_favorite_by_tid($tid);
			$haya_favorite_user = implode('、', $haya_favorite_users);
			$haya_favorite_count = haya_favorite_count(array('tid' => $tid));
			
			$haya_favorite_msg = array(
				'users' => $haya_favorite_user,
				'count' => $haya_favorite_count,
				'msg' => '取消收藏帖子成功！',
			);
			
			message(1, $haya_favorite_msg);
		}
		
	}

}

elseif ($action == 'favorites') {
	
	$header['title'] = "我的收藏 - " . $conf['sitename'];
	
	$haya_favorite_config = setting_get('haya_favorite');
	
	if (strtolower($haya_favorite_config['user_favorite_sort']) == 'asc') {
		$user_favorite_sort = 'asc';
	} else {
		$user_favorite_sort = 'desc';
	}
	
	$orderby = param('orderby', $user_favorite_sort);
	if (strtolower($orderby) == 'asc') {
		$orderby_config = array('create_date' => 1);
	} else {
		$orderby_config = array('create_date' => -1);
	}
	
	$pagesize = intval($haya_favorite_config['user_favorite']);
	$page = param(2, 1);
	$cond['uid'] = $uid; 
	
	$haya_favorite_count = haya_favorite_count($cond);
	$threadlist = haya_favorite_find($cond, $orderby_config, $page, $pagesize);
	$pagination = pagination(url("my-favorites-{page}", array("orderby" => $orderby)), $haya_favorite_count, $page, $pagesize);
	
	include _include(APP_PATH.'plugin/haya_favorite/view/htm/my_favorites.htm');	
}


?>
<?php

!defined('DEBUG') and exit('Access Denied.');

$header['title'] = '帖子收藏设置';

if ($method == 'GET') {
	
	$config = setting_get('haya_favorite');
	
	include _include(APP_PATH.'plugin/haya_favorite/view/htm/setting.htm');
	
} else {
	
	$config = array();
	
	$config['user_favorite'] = param('user_favorite', 10);
	$config['user_favorite_sort'] = param('user_favorite_sort', 'desc');
	$config['hot_favorite'] = param('hot_favorite', 10);
	$config['hot_favorite_time'] = param('hot_favorite_time', 86400);
	$config['show_hot_favorite'] = param('show_hot_favorite', 0);
	setting_set('haya_favorite', $config); 
	
	$clear_hot_favorite = param('clear_hot_favorite', 0);
	if ($clear_hot_favorite == 1) {
		cache_delete('haya_favorite_favorites');
	}
	
	message(0, jump('设置修改成功', url('plugin-setting-haya_favorite')));
}

?>
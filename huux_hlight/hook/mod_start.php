<?php exit;

if($action == 'hlight') {

	$tids = param(2);
	$arr = explode('_', $tids);
	$tidarr = param_force($arr, array(0));
	empty($tidarr) AND message(-1, lang('please_choose_thread'));
	$hlight = param('hlight');

	$threadlist = thread_find_by_tids($tidarr);
	foreach($threadlist as &$thread) {
		$fid = $thread['fid'];
		$tid = $thread['tid'];
		if(forum_access_mod($fid, $gid, 'allowtop')) {
			db_update('thread', array('tid'=>$tid), array('hlight' => $hlight));
		}
	}	
	message(0, lang('set_completely'));	
}
?>
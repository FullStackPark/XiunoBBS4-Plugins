<?php

!defined('DEBUG') AND exit('Access Denied.');

$setting = setting_get('huux_hlight');

if($method == 'GET') {

		$input = array();
		$weight_sl = array('normal'=>'normal', '300'=>'light', '700'=>'bold');
		for ($i=1; $i < 6 ; $i++) { 
			$input['hlight_s'.$i.'c'] = form_text('hlight_s'.$i.'c', $setting['hlight_s'.$i.'c']);
			$input['hlight_s'.$i.'n'] = form_text('hlight_s'.$i.'n', $setting['hlight_s'.$i.'n']);
			$input['hlight_s'.$i.'w'] = form_select('hlight_s'.$i.'w', $weight_sl, $setting['hlight_s'.$i.'w']);			
		}
			
		$input['hlight_n_io'] = form_radio_yes_no('hlight_n_io', $setting['hlight_n_io']);
		include _include(APP_PATH.'plugin/huux_hlight/setting.htm');
		
} else {

	    for ($i=1; $i < 6 ; $i++) { 
		    $setting['hlight_s'.$i.'c'] = param('hlight_s'.$i.'c', '', FALSE);
		    $setting['hlight_s'.$i.'w'] = param('hlight_s'.$i.'w', '', FALSE);		
		    $setting['hlight_s'.$i.'n'] = param('hlight_s'.$i.'n', '', FALSE);		
		}

		$setting['hlight_n_io'] = param('hlight_n_io', 0);
	    setting_set('huux_hlight', $setting); 
		message(0, lang('save_successfully'));
		
}

?>
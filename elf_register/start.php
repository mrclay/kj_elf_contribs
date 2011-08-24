<?php
elgg_register_event_handler('init','system','elf_register_init');

function elf_register_init() {		
	elgg_register_library('elgg:elf_register', elgg_get_plugins_path() . 'elf_register/models/model.php');
	
	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('elf_register','elf_register_page_handler');
	
	$elf_register_js = elgg_get_simplecache_url('js', 'elf_register/manage_display_name');
	elgg_register_js('elgg.elf_register.displayName', $elf_register_js);
	
	// add to the default css
	elgg_extend_view('css/elgg', 'elf_register/css');
	
	elgg_register_plugin_hook_handler('usersettings:save', 'user', 'elf_register_user_settings_save');
	
	$action_path = elgg_get_plugins_path() . 'elf_register/actions';
	// over-ride register action
	elgg_register_action('register', "$action_path/register.php", 'public');
	// add second register action
	elgg_register_action('elf_register/register2', "$action_path/elf_register/register2.php", 'public');
	// over-ride useradd action
	elgg_register_action('useradd', elgg_get_plugins_path() ."elf_register/actions/useradd.php", 'admin');
	// over-ride validate action
	// TODO: is this action still relevant?
	elgg_register_action('uservalidationbyemail/validate', elgg_get_plugins_path() . "elf_register/actions/uservalidationbyemail/validate.php",'public');
	// over-ride requestnewpassword action
	elgg_register_action('user/requestnewpassword', elgg_get_plugins_path() . "elf_register/actions/user/requestnewpassword.php",'public');
}

/**
 * Page handler
 *
 * @param array $page Array of page elements, forwarded by the page handling mechanism
 */
function elf_register_page_handler($page) {
	elgg_load_library('elgg:elf_register');
	
	if (isset($page[0])) {
		if ($page[0] == 'confirm') {
			if ((isset($page[1]))) {
				$guid = $page[1];
			} 
			if ((isset($page[2]))) {
				$code = $page[2];
			}
			echo elf_register_handle_confirm_page($guid,$code);
		} 
	}
}

function elf_register_user_settings_save() {
	$user_id = get_input('guid');

	if (!$user_id) {
		$user = elgg_get_logged_in_user_entity();
	} else {
		$user = get_entity($user_id);
	}
	$first_name = trim(get_input('first_name',''));
	$last_name = trim(get_input('last_name',''));
	
	if (!$first_name || !$last_name) {
		register_error(elgg_echo('elf_register:register:error:missing_first_or_last_name'));
		return FALSE;
	} else {
		if ($first_name != $user->first_name || $last_name != $user->last_name){
			$user->first_name = $first_name;
			$user->last_name = $last_name;
			system_message(elgg_echo('elf_register:register:name_change'));
		}
	}
	
	return TRUE;
}

<?php
/**
 * Action to request a new password.
 *
 * @package Elgg.Core
 * @subpackage User.Account
 * 
 * Modified for elf_register
 */

elgg_load_library('elgg:elf_register');

$username = get_input('username');

if (strpos($username, '@') !== FALSE && ($users = get_user_by_email($username))) {
	$user = $users[0];
} else {
	$username = elf_register_get_username_from_dcf_id($username);
    $user = get_user_by_username($username);
}

if ($user) {
	if (send_new_password_request($user->guid)) {
		system_message(elgg_echo('user:password:resetreq:success'));
	} else {
		register_error(elgg_echo('user:password:resetreq:fail'));
	}
} else {
	register_error(elgg_echo('user:username:notfound', array($username)));
}

forward();

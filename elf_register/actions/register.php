<?php
/**
 * Elgg registration action
 *
 * @package Elgg.Core
 * @subpackage User.Account
 */

elgg_load_library('elgg:elf_register');

elgg_make_sticky_form('register');

// Get variables

$email = get_input('email');
$dcf_id = get_input('dcf_id');
$first_name = get_input('first_name');
$last_name = get_input('last_name');
$friend_guid = (int) get_input('friend_guid', 0);
$invitecode = get_input('invitecode');

// need to make sure that the email address and dcf id are valid
// also first name and last name should not be null

if (elgg_get_config('allow_registration')) {
	if (elf_register_save_and_validate($first_name,$last_name,$email,$dcf_id,$friend_guid,$invitecode)) {	
		system_message(elgg_echo('elf_register:registerok'));
	} else {
		forward('register');
	}
} else {
	register_error(elgg_echo('registerdisabled'));
}

forward();

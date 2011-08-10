<?php
/**
 * Validate a user or users by guid
 *
 * @package Elgg.Core.Plugin
 * @subpackage UserValidationByEmail
 * 
 * Note by Kevin Jardine, Radagast Solutions, July 2011
 * There seems to be no user validation event so I'm over-riding this action to update
 * the elf_users table on successful validation
 */

$user_guids = get_input('user_guids');
$error = FALSE;

if (!$user_guids) {
	register_error(elgg_echo('uservalidationbyemail:errors:unknown_users'));
	forward(REFERRER);
}

$access = access_get_show_hidden_status();
access_show_hidden_entities(TRUE);

foreach ($user_guids as $guid) {
	$user = get_entity($guid);
	if (!$user instanceof ElggUser) {
		$error = TRUE;
		continue;
	}

	// only validate if not validated
	$is_validated = elgg_get_user_validation_status($guid);
	$validate_success = elgg_set_user_validation_status($guid, TRUE, 'manual');

	if ($is_validated !== FALSE || !($validate_success && $user->enable())) {
		$error = TRUE;
		continue;
	} else {
		// success, so update elf_users table
		if ($user->dcf_id) {
			elgg_load_library('elgg:shibalike');
			shibalike_update_elf_users_table($user->dcf_id,$user->username);
		}
	}
}

access_show_hidden_entities($access);

if (count($user_guids) == 1) {
	$message_txt = elgg_echo('uservalidationbyemail:messages:validated_user');
	$error_txt = elgg_echo('uservalidationbyemail:errors:could_not_validate_user');
} else {
	$message_txt = elgg_echo('uservalidationbyemail:messages:validated_users');
	$error_txt = elgg_echo('uservalidationbyemail:errors:could_not_validate_users');
}

if ($error) {
	register_error($error_txt);
} else {
	if (count($user_guids) == 1) {
        // new ELF user! 
        if (is_file($_SERVER['DOCUMENT_ROOT'] . '/moodle/elf-redirector.php')) {
            // forward to log into moodle, then my courses
            forward('http://' . $_SERVER['SERVER_NAME'] . '/moodle/elf-redirector.php?dest=myCourses&loggedIn');
        }
    }
    system_message($message_txt);
}

forward(REFERRER);
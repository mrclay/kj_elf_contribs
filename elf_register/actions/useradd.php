<?php
/**
 * Elgg add action
 *
 * @package Elgg
 * @subpackage Core
 */

elgg_load_library('elgg:elf_register');

elgg_make_sticky_form('useradd');

// Get variables
$username = get_input('username');
$password = get_input('password');
$password2 = get_input('password2');
$email = get_input('email');
$name = get_input('name');
$first_name = get_input('first_name');
$last_name = get_input('last_name');

if (trim($password) === '' || ($password !== $password2)) {
    register_error(elgg_echo('RegistrationException:PasswordMismatch'));
    forward(REFERER);
}

$admin = get_input('admin');
if (is_array($admin)) {
	$admin = $admin[0];
}

// For now, just try and register the user
try {
	$guid = register_user($username, $password, $name, $email, TRUE);

	if (((trim($password) != "") && (strcmp($password, $password2) == 0)) && ($guid)) {
		$new_user = get_entity($guid);
		$new_user->first_name = $first_name;
		$new_user->last_name = $last_name;

        $dcf_id = 'elf_' . $username;
		
		elf_register_insert_into_elf_users_table($dcf_id, $email, $username);

		if (($guid) && ($admin)) {
			$new_user->makeAdmin();
		}

		elgg_clear_sticky_form('useradd');

		$new_user->admin_created = TRUE;
		// @todo ugh, saving a guid as metadata!
		$new_user->created_by_guid = elgg_get_logged_in_user_guid();

        $elggSite = elgg_get_site_entity();

        if (! preg_match('/@(madeup|example)\\.(com|org)$/', $email)) {
            $subject = elgg_echo('useradd:subject');
            $body = elgg_echo('elf_register:useradd:body', array(
                $name,
                $elggSite->name,
                $elggSite->url,
                $username,
                $password,
                $dcf_id,
            ));
            notify_user($new_user->guid, $elggSite->guid, $subject, $body);
        }

		system_message(elgg_echo("adduser:ok", array($elggSite->name)));
        system_message(elgg_echo("elf_register:adduser:dcf_id_note", array($dcf_id)));

	} else {
		register_error(elgg_echo("adduser:bad"));
	}
} catch (RegistrationException $r) {
	register_error($r->getMessage());
}

forward(REFERER);

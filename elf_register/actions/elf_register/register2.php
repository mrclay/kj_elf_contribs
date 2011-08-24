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
$username = get_input('username');
$password = get_input('password');
$password2 = get_input('password2');

$first_name = trim(get_input('first_name'));
$last_name = trim(get_input('last_name'));
$name = trim(get_input('name'));
$friend_guid = (int) get_input('friend_guid', 0);
$invitecode = get_input('invitecode');

$register_data_guid = get_input('register_data_guid');
$validation_code = get_input('validation_code');

$access_status = elgg_get_ignore_access();
elgg_set_ignore_access(TRUE);

$data = elf_register_get_validation_entity($register_data_guid, $validation_code);
$email = $data->email;
$dcf_id = $data->dcf_id;

if (!$data) {
	register_error(elgg_echo('elf_register:invalid_code_error'));
	elgg_set_ignore_access($access_status);
	forward();
}

if (!$first_name || !$last_name) {
	register_error(elgg_echo('elf_register:register:error:missing_first_or_last_name'));
	elgg_set_ignore_access($access_status);
	forward(REFERER);
}

if (!elf_register_validate_username($username)) {
	register_error(elgg_echo('elf_register:register:error:invalid_username'));
	elgg_set_ignore_access($access_status);
	forward(REFERER);
}

if (elgg_get_config('allow_registration')) {
	try {
		if (trim($password) == "" || trim($password2) == "") {
			throw new RegistrationException(elgg_echo('RegistrationException:EmptyPassword'));
		}

		if (strcmp($password, $password2) != 0) {
			throw new RegistrationException(elgg_echo('RegistrationException:PasswordMismatch'));
		}

		$guid = register_user($username, $password, $name, $email, false, $friend_guid, $invitecode);

		if ($guid) {
			elgg_clear_sticky_form('register');
			
			// delete temporary registration data
			$data->delete();
			
			$new_user = get_entity($guid);
			$new_user->dcf_id = $dcf_id;
			$new_user->first_name = $first_name;
			$new_user->last_name = $last_name;
			
			elf_register_update_elf_users_table($dcf_id,$username);
			
			// restore previous permissions status
			elgg_set_ignore_access($access_status);

			// allow plugins to respond to self registration
			// note: To catch all new users, even those created by an admin,
			// register for the create, user event instead.
			// only passing vars that aren't in ElggUser.
			$params = array(
				'user' => $new_user,
				'password' => $password,
				'friend_guid' => $friend_guid,
				'invitecode' => $invitecode
			);

			// @todo should registration be allowed no matter what the plugins return?
			if (!elgg_trigger_plugin_hook('register', 'user', $params, TRUE)) {
				$new_user->delete();
				// @todo this is a generic messages. We could have plugins
				// throw a RegistrationException, but that is very odd
				// for the plugin hooks system.
				throw new RegistrationException(elgg_echo('registerbad'));
			}
			
			// this plugin handles email validation before this stage, so go ahead and validate the new user
			
			elgg_set_user_validation_status($guid, true, 'email');

			system_message(elgg_echo("registerok", array(elgg_get_site_entity()->name)));

			// if exception thrown, this probably means there is a validation
			// plugin that has disabled the user
			try {
				login($new_user);
				// new ELF user! 
                if (is_file($_SERVER['DOCUMENT_ROOT'] . '/elf-paths.php')) {
                    // forward to log into moodle, then my courses
                    forward('http://' . $_SERVER['SERVER_NAME'] . '/moodle/auth/shibboleth/?dest=/courses/');
                } else {
                    forward();
                }
			} catch (LoginException $e) {
				// do nothing
			}

			// Forward on success, assume everything else is an error...
			forward();
		} else {
			register_error(elgg_echo("registerbad"));
		}
	} catch (RegistrationException $r) {
		register_error($r->getMessage());
	}
} else {
	register_error(elgg_echo('registerdisabled'));
}

forward(REFERER);
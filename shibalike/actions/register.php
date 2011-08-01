<?php
/**
 * Elgg registration action
 *
 * @package Elgg.Core
 * @subpackage User.Account
 * 
 * modified by Kevin Jardine, Radagast Solutions
 */

elgg_load_library('elgg:shibalike');

elgg_make_sticky_form('register');

// Get variables
$username = get_input('username');
$name = get_input('name');
$password = get_input('password');
$password2 = get_input('password2');
$email = get_input('email');
$dcf_id = get_input('dcf_id');
$first_name = get_input('first_name');
$last_name = get_input('last_name');

$friend_guid = (int) get_input('friend_guid', 0);
$invitecode = get_input('invitecode');

// do some plugin-specific

if (elgg_get_config('allow_registration')) {
	$error = FALSE;
	
	// DCF sanity checking
	// The JavaScript form handling should have already prevented these errors.
	// These checks are just sanity checking for error conditions that should never actually occur.
	
	if (!$email && !$dcf_id) {
		$error = TRUE;
		register_error(elgg_echo('shibalike:register:error:either_dcf_id_or_email_required'));
	} else if ($dcf_id && !($email = shibalike_get_email_from_dcf_id($dcf_id))) {
		$error = TRUE;
		register(elgg_echo('shibalike:register:error:invalid_dcf_id'));
	} else if (!$dcf_id && !($dcf_id = shibalike_get_dcf_id_from_email($email)) && !elgg_is_admin_logged_in()) {
		$error = TRUE;
		register_error(elgg_echo('shibalike:register:error:invalid_email'));
	}
	if ($error) {
		forward(REFERER);
		exit;
	}
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
			
			$new_user = get_entity($guid);
			
			// TODO: make sure that these $new_user updates don't clash with Elgg's permission system
			$new_user->dcf_id = $dcf_id;
			$new_user->first_name = $first_name;
			$new_user->last_name = $last_name;

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

			system_message(elgg_echo("registerok", array(elgg_get_site_entity()->name)));

			// if exception thrown, this probably means there is a validation
			// plugin that has disabled the user
			try {
				login($new_user);
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
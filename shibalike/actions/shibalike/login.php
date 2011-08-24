<?php
/**
 * Shibalike login action
 *
 */

elgg_load_library('elgg:shibalike');
if (elgg_plugin_exists('elf_register')) {
	elgg_load_library('elgg:elf_register');
}

// set forward url
if (isset($_SESSION['last_forward_from']) && $_SESSION['last_forward_from']) {
	$forward_url = $_SESSION['last_forward_from'];
	unset($_SESSION['last_forward_from']);
} elseif (get_input('returntoreferer')) {
	$forward_url = REFERER;
} else {
	// forward to My Courses
    if (is_file($_SERVER['DOCUMENT_ROOT'] . '/elf-paths.php')) {
        $elfPaths = (require $_SERVER['DOCUMENT_ROOT'] . '/elf-paths.php');
        $forward_url = 'http://' . $_SERVER['SERVER_NAME'] . $elfPaths['moodle'] . 'auth/shibboleth/';
    } else {
        $forward_url = '';
    }
}

$username = get_input('username');
$email = get_input('email');
$password = get_input("password");
$persistent = get_input("persistent", FALSE);
$result = FALSE;

$errorDestination = '';
if (is_file($_SERVER['DOCUMENT_ROOT'] . '/elf-paths.php')) {
    // within ELF, login error page IS homepage
    $errorDestination = 'http://' . $_SERVER['SERVER_NAME'] . '/';
}

if (empty($username) || empty($password)) {
	register_error(elgg_echo('login:empty'));
	forward($errorDestination);
}

// check if logging in with email address
if (strpos($username, '@') !== FALSE && ($users = get_user_by_email($username))) {
	$username = $users[0]->username;
} else {
	if (elgg_plugin_exists('elf_register')) {
		$username = elf_register_get_username_from_dcf_id($username);
	}   
}

$result = elgg_authenticate($username, $password);
if ($result !== true) {
	register_error($result);
	forward($errorDestination);
}

$user = get_user_by_username($username);
if (!$user) {
	register_error(elgg_echo('login:baduser'));
	forward($errorDestination);
}

try {
	login($user, $persistent);
} catch (LoginException $e) {
	register_error($e->getMessage());
	forward($errorDestination);
}

//system_message(elgg_echo('loginok'));
forward($forward_url);

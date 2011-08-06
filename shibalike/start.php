<?php
set_include_path(realpath(__DIR__ . '/myclasses') . PATH_SEPARATOR  . get_include_path());

function shibalike_autoload($className) {
    $className = ltrim($className, '\\');
    $path = str_replace(array('_', '\\'), DIRECTORY_SEPARATOR, $className) . '.php';
    foreach (explode(PATH_SEPARATOR, get_include_path()) as $includePath) {
        if (is_readable("$includePath/$path")) {
            require "$includePath/$path";
            return;
        }
    }
}

spl_autoload_register('shibalike_autoload');

elgg_register_event_handler('init', 'system', 'shibalike_init');

function shibalike_init() {
	elgg_register_library('elgg:shibalike', elgg_get_plugins_path() . 'shibalike/models/model.php');
	
	// register shibalike's JavaScript
	$blog_js = elgg_get_simplecache_url('js', 'shibalike/process_registration');
	elgg_register_js('elgg.shibalike', $blog_js);
	
	// add to the admin css
	elgg_extend_view('css/admin', 'shibalike/css');
	
	// Mark users as authenticated upon Elgg login
	elgg_register_event_handler('login','user','shibalike_handle_login');
	
	// Validate username
	elgg_register_plugin_hook_handler('registeruser:validate:username', 'all', 'shibalike_validate_username',600);
	
	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('shibalike','shibalike_page_handler');
	
	// Register page handler to validate users
	// This doesn't need to be an action because security is handled by the validation codes.
	elgg_register_page_handler('uservalidationbyemail', 'shibalike_uservalidationbyemail_page_handler');
	
	elgg_register_page_handler('register','shibalike_register_page_handler');
	
	// register actions
	$action_path = elgg_get_plugins_path() . 'shibalike/actions/shibalike';
	elgg_register_action('shibalike/login', "$action_path/login.php",'public');
	elgg_register_action('shibalike/process_registration', "$action_path/process_registration.php",'public');
	// over-ride register action
	elgg_register_action('register', elgg_get_plugins_path() ."shibalike/actions/register.php", 'public');
	// over-ride useradd action
	elgg_register_action('useradd', elgg_get_plugins_path() ."shibalike/actions/useradd.php", 'admin');
	// over-ride login action
	elgg_register_action('login', "$action_path/login.php", 'public');
	// over-ride validate action
	elgg_register_action('uservalidationbyemail/validate', elgg_get_plugins_path() . "shibalike/actions/uservalidationbyemail/validate.php",'public');
}

function shibalike_register_page_handler($page) {
	elgg_load_library('elgg:shibalike');
	shibalike_handle_registration_page();
}


function shibalike_page_handler($page) {		
	if (isset($page[0])) {
		if ($page[0] == 'login') {
			echo elgg_view_page('',elgg_view_form('shibalike/login'),'shibalike_iframe');
		} else if (($page[0] == 'moodle_confirmation') && isset($page[1])) {
			elgg_load_library('elgg:shibalike');
			shibalike_moodle_confirmation($page[1]);
		}
	}
}

/**
 * Checks sent passed validation code and user guids and validates the user.
 *
 * @param array $page
 */
function shibalike_uservalidationbyemail_page_handler($page) {

	if (isset($page[0]) && $page[0] == 'confirm') {
		$code = sanitise_string(get_input('c', FALSE));
		$user_guid = get_input('u', FALSE);

		// new users are not enabled by default.
		$access_status = access_get_show_hidden_status();
		access_show_hidden_entities(true);

		$user = get_entity($user_guid);

		if (($code) && ($user)) {
			if (uservalidationbyemail_validate_email($user_guid, $code)) {

				elgg_push_context('uservalidationbyemail_validate_user');
				system_message(elgg_echo('email:confirm:success'));
				$user = get_entity($user_guid);
				$user->enable();
				if ($user->dcf_id) {
					elgg_load_library('elgg:shibalike');
					shibalike_update_elf_users_table($user->dcf_id,$user->username);
				}
				elgg_pop_context();

				login($user);
			} else {
				register_error(elgg_echo('email:confirm:fail'));
			}
		} else {
			register_error(elgg_echo('email:confirm:fail'));
		}

		access_show_hidden_entities($access_status);
	} else {
		register_error(elgg_echo('email:confirm:fail'));
	}

	forward();
}

function shibalike_handle_login($event, $type, $user) {
	$idp = new Shibalike\IdP(shibalike_getStateManager(), shibalike_getAttrStore(), shibalike_getConfig());
	$idp->markAsAuthenticated($user->username);
    if ($idp->getAuthRequest()) {
        // auth was requested outside Elgg, redirect there
        $idp->redirect();
    }
}

function shibalike_getStateManager() {
    $storage = new Shibalike\Util\UserlandSession\Storage\Files('SHIBALIKE_BASIC', array('path' => '/tmp'));
    $session = Shibalike\Util\UserlandSession::factory($storage);
    return new Shibalike\StateManager\UserlandSession($session);
}

function shibalike_getAttrStore() {
    return new Shibalike\Attr\Store\ElggStore();
}

function shibalike_getConfig() {
    $config = new Shibalike\Config();
    // Elgg IS the IdP, so this doesn't matter
    $config->idpUrl = 'idp.php';
    return $config;
}

/**
	Called by 'registeruser:validate:username' plugin hook
 */
function shibalike_validate_username($hook, $type, $value, $params) {

	// white list for allowed characters (numbers, letters and period only)
	$whitelist = '/[^a-zA-Z0-9.]/';

	if (preg_match($whitelist, $params['username']) > 0) {
		return FALSE;
	} else {
		return $value;
	}
}

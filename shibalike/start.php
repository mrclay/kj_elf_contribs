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
	
	// add to the admin css
	elgg_extend_view('css/admin', 'shibalike/css');
    
    // add to the default css
	elgg_extend_view('css/elgg', 'shibalike/css');
	
	// Mark users as authenticated upon Elgg login
	elgg_register_event_handler('login','user','shibalike_handle_login');
	
	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('shibalike','shibalike_page_handler');
	
	// register actions
	$action_path = elgg_get_plugins_path() . 'shibalike/actions/shibalike';
	elgg_register_action('shibalike/login', "$action_path/login.php",'public');

	// over-ride login action
	elgg_register_action('login', "$action_path/login.php", 'public');	
}

function shibalike_page_handler($page) {		
	if (isset($page[0])) {
		if ($page[0] == 'login') {
            // regular login form is fine
            $loginIframe = elgg_view_page('',elgg_view_form('login'),'shibalike_iframe');
            // rewrite action so full HTTPS login is possible w/o warning
            $loginIframe = preg_replace('@ action="http://[^/]+/@', ' action="/', $loginIframe);
            echo $loginIframe;
		} else if (($page[0] == 'moodle_confirmation') && isset($page[1])) {
			elgg_load_library('elgg:shibalike');
			shibalike_moodle_confirmation($page[1]);
		}
	}
}

function shibalike_handle_login($event, $type, $user) {
	$idp = new Shibalike\IdP(shibalike_getStateManager(), shibalike_getAttrStore(), shibalike_getConfig());
	$idp->markAsAuthenticated($user->username);
    if ($idp->getAuthRequest()) {
        // auth was requested outside Elgg, redirect there
        $idp->redirect();
    }
}

/**
 * @return Shibalike\Util\UserlandSession
 */
function shibalike_getUserlandSession() {
    static $session = null;
    if (! $session) {
        $storage = new Shibalike\Util\UserlandSession\Storage\Files('SHIBALIKE_BASIC', array('path' => '/tmp'));
        $session = Shibalike\Util\UserlandSession::factory($storage);
    }
    return $session;
}

function shibalike_getStateManager() {
    $session = shibalike_getUserlandSession();
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

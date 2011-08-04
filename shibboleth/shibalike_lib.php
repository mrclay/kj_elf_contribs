<?php
$pluginconfig   = get_config('auth/shibboleth');
// define the Elgg URL - remember the closing slash!
#define('ELGG_URL', "http://localhost/elgg18/");
define('ELGG_URL', $pluginconfig->elggurl);
// define the Shibalike source directory
#define('SHIBALIKE_DIR', "C:/xampp/htdocs/elgg18/mod/shibalike/myclasses");
define('SHIBALIKE_DIR', $pluginconfig->libdir);

set_include_path(realpath(SHIBALIKE_DIR) . PATH_SEPARATOR  . get_include_path());

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

// function is triggered on user creation
function shibalike_inform_elgg($user) {
	$username = $user->username;
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,ELGG_URL."shibalike/moodle_confirmation/".$username);
    
    curl_exec ($ch);
    curl_close ($ch); 
    
    return TRUE;
}

function shibalike_getStateManager() {
    $storage = new Shibalike\Util\UserlandSession\Storage\Files('SHIBALIKE_BASIC', array('path' => '/tmp'));
    $session = Shibalike\Util\UserlandSession::factory($storage);
    return new Shibalike\StateManager\UserlandSession($session);
}

function shibalike_getConfig() {
    $config = new Shibalike\Config();
    // does the idpUrl matter for Elgg?
    $config->idpUrl = 'http://localhost/elgg18/';
    return $config;
}

function shibalike_require_valid_user() {
	$sp = new Shibalike\SP(shibalike_getStateManager(),shibalike_getConfig()); 
	$sp->requireValidUser();
}
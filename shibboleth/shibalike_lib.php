<?php
$pluginconfig   = get_config('auth/shibboleth');

// define the Elgg URL
define('ELGG_URL', rtrim($pluginconfig->elggurl, '/') . '/');

// define the Shibalike source directory
define('SHIBALIKE_DIR', rtrim($pluginconfig->libdir, '/'));

// might already be in include_path
if (SHIBALIKE_DIR !== '') {
    set_include_path(realpath(SHIBALIKE_DIR) . PATH_SEPARATOR  . get_include_path());
}

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
    file_get_contents(ELGG_URL."shibalike/moodle_confirmation/".$username);
    return TRUE;
}

function shibalike_getStateManager() {
    $storage = new Shibalike\Util\UserlandSession\Storage\Files('SHIBALIKE_BASIC', array('path' => sys_get_temp_dir()));
    $session = Shibalike\Util\UserlandSession::factory($storage);
    return new Shibalike\StateManager\UserlandSession($session);
}

function shibalike_getConfig() {
    $config = new Shibalike\Config();
    $config->idpUrl = '/';
    return $config;
}

function shibalike_require_valid_user() {
	$sp = new Shibalike\SP(shibalike_getStateManager(),shibalike_getConfig()); 
	$sp->requireValidUser();
}
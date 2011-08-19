<?php


function shibalike_get_moodle_config($config_file_name) {
	
	$config = (require($config_file_name));
	
	return $config;
}

function shibalike_moodle_confirmation($username) {
	$username = sanitize_string($username);
	$dbprefix = shibalike_get_moodle_db_prefix();
	$query = "SELECT id FROM {$dbprefix}user WHERE username = \"$username\"";
	$result =  shibalike_run_moodle_query($query);
	if ($result) {
		$id = $result[0]['id'];
		$user = get_user_by_username($username);
		if($user) {
			$user->moodleUserId = $id;
		}
	}
}

function shibalike_get_moodle_db_prefix() {
	$config_file_name = elgg_get_plugin_setting('moodle_config_file_name','shibalike');
	if ($config_file_name && file_exists($config_file_name)) {
		$config = shibalike_get_moodle_config($config_file_name);
		if ($config) {
			return $config['dbprefix'];
		}			
	}
}

function shibalike_run_moodle_query($query,$select=TRUE) {

	global $CONFIG;

	static $cnx, $moodle_db_host, $moodle_db_user, $moodle_db_password, $moodle_db_name;

	if (!$cnx) {
		$config_file_name = elgg_get_plugin_setting('moodle_config_file_name','shibalike');
		error_log($config_file_name);
		if ($config_file_name && file_exists($config_file_name)) {
			$config = shibalike_get_moodle_config($config_file_name);
			error_log("Moodle config".print_r($config,TRUE));
			if (!$config) {
				return FALSE;
			}			
		}
			
		$cnx = mysql_connect($config['db']['host'].":".$config['db']['port'], $config['db']['username'], $config['db']['password']);
	}
		

	if(!$cnx) {
		error_log('Could not connect to database: ' . mysql_error());
		return FALSE;
	}

	if(!mysql_select_db($config['db']['dbname'], $cnx))	{			
		error_log('Could not select database: ' . mysql_error());
		return FALSE;
	}

	if(!$rs = mysql_query($query, $cnx)) {
		error_log('Could not execute query: ' . mysql_error());
		return FALSE;
	}
	 
	if ($select) {		 
		$rows = array();

		while ($row = mysql_fetch_assoc($rs))
		{
			$rows[] = $row;
		}
		// switch back to Elgg database
		$elgg_cnx = mysql_connect($CONFIG->dbhost, $CONFIG->dbuser, $CONFIG->dbpass);
		mysql_select_db($CONFIG->dbname, $elgg_cnx);

		return $rows;
	} else {
		return TRUE;
	}
}

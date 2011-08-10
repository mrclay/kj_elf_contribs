<?php
function shibalike_get_email_from_dcf_id($dcf_id) {	
	//$db_prefix = elgg_get_config('dbprefix');
	$dcf_id = sanitize_string($dcf_id);
	$query = "SELECT email FROM elf_users WHERE dcf_id = \"$dcf_id\"";
	$data = get_data($query);
	if ($data) {
		return $data[0]->email;
	} else {
		return FALSE;
	}
}

function shibalike_get_dcf_id_from_email($email) {
	$email = sanitize_string($email);
	$query = "SELECT dcf_id FROM elf_users WHERE email = \"$email\"";
	$data = get_data($query);
	if ($data) {
		return $data[0]->dcf_id;
	} else {
		return FALSE;
	}
}

function shibalike_update_elf_users_table($dcf_id,$username) {
	$dcf_id = sanitize_string($dcf_id);
	$username = sanitize_string($username);
	$query = "UPDATE elf_users SET username = '{$username}' WHERE dcf_id = '{$dcf_id}'";
	return update_data($query);
}

function shibalike_get_moodle_config($config_file_name) {
	
	$config = (require($config_file_name));
	
	return $config;
}

function shibalike_moodle_confirmation($username) {
	$username = sanitize_string($username);
	error_log("called from moodle:".$username);
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

function shibalike_handle_registration_page() {
	/**
	 * Assembles and outputs the registration page.
	 *
	 * Since 1.8, registration can be disabled via administration.  If this is
	 * the case, calls to this page will forward to the network front page.
	 *
	 * If the user is logged in, this page will forward to the network
	 * front page.
	 *
	 * @package Elgg.Core
	 * @subpackage Registration
	 * 
	 * modified by Kevin Jardine, Radagast Solutions, 2011
	 */
	
	elgg_load_js('elgg.shibalike');
	
	// check new registration allowed
	if (elgg_get_config('allow_registration') == false) {
		register_error(elgg_echo('registerdisabled'));
		forward();
	}
	
	$friend_guid = (int) get_input('friend_guid', 0);
	$invitecode = get_input('invitecode');
	
	// only logged out people need to register
	if (elgg_is_logged_in()) {
		forward();
	}
	
	$title = elgg_echo("register");
	
	$content = elgg_view_title($title);
	
	// create the registration url - including switching to https if configured
	$register_url = elgg_get_site_url() . 'action/register';
	if (elgg_get_config('https_login')) {
		$register_url = str_replace("http:", "https:", $register_url);
	}
	$form_params = array('action' => $register_url,'name'=>'registration_form');
	
	$body_params = array(
		'friend_guid' => $friend_guid,
		'invitecode' => $invitecode
	);
	$content .= elgg_view_form('register', $form_params, $body_params);
	
	$body = elgg_view_layout("one_column", array('content' => $content));
	
	echo elgg_view_page($title, $body);
}

function shibalike_process_registration_data($email,$dcf_id,$first_name,$last_name,$password,$password2) {
	
	global $CONFIG;
	
	if (!isset($CONFIG->min_password_length)) {
		$min_password_length = 6;
	} else {
		$min_password_length = $CONFIG->min_password_length;
	}
	$data = new stdClass();
	$email = trim($email);
	$dcf_id = trim($dcf_id);
	$first_name = trim($first_name);
	$last_name = trim($last_name);
	if ($password != $password2) {
		$data->error = TRUE;
		$data->error_message = elgg_echo('shibalike:register:error:mismatched_password');
	} else if (strlen($password) < $min_password_length) {
		$data->error = TRUE;
		$data->error_message = elgg_echo('shibalike:register:error:bad_password');
	} else if (!$first_name || !$last_name) {
		$data->error = TRUE;
		$data->error_message = elgg_echo('shibalike:register:error:missing_first_or_last_name');
	} else if (!$email && !$dcf_id) {
		$data->error = TRUE;
		$data->error_message = elgg_echo('shibalike:register:error:either_dcf_id_or_email_required');
	} else if ($dcf_id && !($email = shibalike_get_email_from_dcf_id($dcf_id))) {
		$data->error = TRUE;
		$data->error_message = elgg_echo('shibalike:register:error:invalid_dcf_id');
	} else if (!$dcf_id && !($dcf_id = shibalike_get_dcf_id_from_email($email)) && !elgg_is_admin_logged_in()) {
		$data->error = TRUE;
		$data->error_message = elgg_echo('shibalike:register:error:invalid_email');
	} else {		
		$username = shibalike_generate_username($first_name,$last_name);
		if ($username) {
			$data->error = FALSE;
			
			$data->email = $email;
			$data->dcf_id = $dcf_id;
			$data->username = $username;
			$data->display_name = "$first_name $last_name";
		} else {
			$data->error = TRUE;
			$data->error_message = elgg_echo('shibalike:register:error:could_not_generate_username');
		}
	}
	
	return $data;
}

function shibalike_generate_username($first_name,$last_name) {
	global $CONFIG;
	
	setlocale(LC_ALL, 'en_US.UTF8');
	
	$existing_username = FALSE;
	
	// convert to ASCII
	
	$ascii_first_name = iconv("utf-8","ascii//TRANSLIT",$first_name);
	$ascii_last_name = iconv("utf-8","ascii//TRANSLIT",$last_name);
	
	// replace everything except letters, numbers and periods
	
	$whitelist = '/[^a-zA-Z0-9.]/';
	
	$ascii_first_name = preg_replace($whitelist,'',$ascii_first_name);
	$ascii_last_name = preg_replace($whitelist,'',$ascii_last_name);
	
	// construct a preliminary username
	
	$username = strtolower(substr($ascii_first_name,0,1).$ascii_last_name);
	
	// pad out very short usernames
	
	if (isset($CONFIG->minusername)) {
		$minusername = $CONFIG->minusername;
	} else {
		$minusername = 4;
	}
	
	if (strlen($username) < $minusername) {
		$missing_length = $minusername-strlen($username);
		// pad with 0 to avoid usernames with "xxx" :)
		$username .= substr("000000",0,$missing_length);
	}
	
	// add numbers at the end if necessary
	
	if ($user = get_user_by_username($username)) {
		$existing_username = TRUE;
		for($i=1;$i<100;$i++) {
	    	$new_username = $username.$i;
	    	if (!($user = get_user_by_username($new_username))) {
	    		$existing_username = FALSE;
	    		$username = $new_username;
	    		break;
	    	}	    	
	    }
	}
	    
	if ($existing_username) {
		return FALSE;
	} else {
		return $username;
	}
}

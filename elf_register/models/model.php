<?php
function elf_register_save_and_validate($first_name,$last_name,$email,$dcf_id,$friend_guid,$invitecode) {
	
	$first_name = trim($first_name);
	$last_name = trim($last_name);
	$email = trim($email);
	$dcf_id = trim($dcf_id);
	
	if (!$first_name || !$last_name) {
		register_error(elgg_echo('elf_register:register:error:missing_first_or_last_name'));
		return FALSE;
	} else if (!$email && !$dcf_id) {
		register_error(elgg_echo('elf_register:register:error:either_dcf_id_or_email_required'));
		return FALSE;
	} else if ($dcf_id && !($email = elf_register_get_email_from_dcf_id($dcf_id))) {
		register_error(elgg_echo('elf_register:register:error:invalid_dcf_id'));
		return FALSE;
	} else if (!$dcf_id && !($dcf_id = elf_register_get_dcf_id_from_email($email))) {
		register_error(elgg_echo('elf_register:register:error:invalid_email'));
		return FALSE;
	}
	
	// input data is OK, so save it temporarily and send the
	// validation email
	
	// temporary data is owned by an admin but we don't care who
	$admins = elgg_get_admins();
	$admin = $admins[0];
	
	$register_data = new ElggObject();
	$register_data->subtype = 'elf_register_data';
	$register_data->owner_guid = $admin->guid;
	$register_data->container_guid = $admin->guid;
	$register_data->access_id = ACCESS_PRIVATE;
	$register_data->first_name = $first_name;
	$register_data->last_name = $last_name;
	$register_data->email = $email;
	$register_data->dcf_id = $dcf_id;
	$register_data->friend_guid = $friend_guid;
	$register_data->invitecode = $invitecode;
	
	$register_data->validation_code = elf_register_generate_code($email);
	
	// need to turn off Elgg's permission system to save and access data
	// owned by someone else (an admin in this case)
	
	$access_status = elgg_get_ignore_access();
	elgg_set_ignore_access(TRUE);
	$register_data->save();
	
	$name = "$first_name $last_name";
	
	elf_register_request_validation($register_data->guid,$name,$email,$register_data->validation_code);
	
	// turn the permission system back on
	elgg_set_ignore_access($access_status);
	
	return TRUE;	
}

function elf_register_generate_code($email_address) {

	$site_url = elgg_get_site_url();

	// Note I bind to site URL, this is important on multisite!
	return md5($email_address . $site_url . get_site_secret());
}
	
/**
 * Request user validation email.
 * Send email out to the address and request a confirmation.
 */
function elf_register_request_validation($guid,$name,$email,$code) {

	$site = elgg_get_site_entity();

	// Work out validate link
	$link = "{$site->url}elf_register/confirm/$guid/$code";

	// Send validation email
	$subject = elgg_echo('email:validate:subject', array($name, $site->name));
	$body = elgg_echo('email:validate:body', array($name, $site->name, $link, $site->name, $site->url));
	$from = "{$site->name} <{$site->email}>";
	$to = "{$name} <{$email}>";
	elgg_send_email($from, $to, $subject, $body);
}

/**
 * Get validation data
 *
 * @param int    $data guid
 * @param string $code
 * @return bool
 */
function elf_register_get_validation_entity($guid, $code) {
	$entity = get_entity($guid);
	
	if (elgg_instanceof($entity, 'object', 'elf_register_data')) {
		if ($code == $entity->validation_code) {
			return $entity;
		}
	}
	return FALSE;
}

function elf_register_handle_confirm_page($guid,$code) {
	
	// only logged out people need to register
	if (elgg_is_logged_in()) {
		forward();
	}
	
	// check new registration allowed
	if (elgg_get_config('allow_registration') == false) {
		register_error(elgg_echo('registerdisabled'));
		forward();
	}
	
	$access_status = elgg_get_ignore_access();
	elgg_set_ignore_access(TRUE);
	
	$data = elf_register_get_validation_entity($guid, $code);
	if (!$data) {
		register_error(elgg_echo('elf_register:invalid_code_error'));
		forward();
	}

	$title = elgg_echo('elf_register:complete_register_title');
	
	$content = elgg_view_title($title);
	
	// create the registration action url - including switching to https if configured
	$register_url = elgg_get_site_url() . 'action/elf_register/register2';
	if (elgg_get_config('https_login')) {
		$register_url = str_replace("http:", "https:", $register_url);
	}
	$form_params = array(
		'action' => $register_url,
		'class' => 'elgg-form-account float',
		'id' => 'register-complete-form',
		'name' => 'register_complete_form'
	);
	
	// TODO: needs sticky form
	
	$body_params = array(
		'friend_guid' => $data->friend_guid,
		'invitecode' => $data->invitecode,
		'register_data_guid' => $guid,
		'code' => $code,
		'first_name' => $data->first_name,
		'last_name' => $data->last_name,
		'username' => elf_register_generate_username($data->first_name,$data->last_name),
		'name' => "{$data->first_name} {$data->last_name}",
	);
	
	elgg_set_ignore_access($access_status);
	$content .= elgg_view_form('elf_register/register2', $form_params, $body_params);
	
	$content .= elgg_view('help/register');
	
	$body = elgg_view_layout("one_column", array('content' => $content));
	
	echo elgg_view_page($title, $body);
}

function elf_register_get_email_from_dcf_id($dcf_id) {	
	$dcf_id = sanitize_string($dcf_id);
	$query = "SELECT email FROM elf_users WHERE dcf_id = \"$dcf_id\"";
	$data = get_data($query);
	if ($data) {
		return $data[0]->email;
	} else {
		return FALSE;
	}
}

function elf_register_get_dcf_id_from_email($email) {
	$email = sanitize_string($email);
	$query = "SELECT dcf_id FROM elf_users WHERE email = \"$email\"";
	$data = get_data($query);
	if ($data) {
		return $data[0]->dcf_id;
	} else {
		return FALSE;
	}
}

function elf_register_get_username_from_dcf_id($dcf_id) {
	$dcf_id = sanitize_string($dcf_id);
	$query = "SELECT username FROM elf_users WHERE dcf_id = \"$dcf_id\"";
	$data = get_data($query);
	if ($data) {
		return $data[0]->username;
	} else {
		return FALSE;
	}
}

function elf_register_update_elf_users_table($dcf_id,$username) {
	$dcf_id = sanitize_string($dcf_id);
	$username = sanitize_string($username);
	$query = "UPDATE elf_users SET username = '{$username}' WHERE dcf_id = '{$dcf_id}'";
	return update_data($query);
}

function elf_register_insert_into_elf_users_table($dcf_id,$email,$username) {
    if (FALSE !== elf_register_get_email_from_dcf_id($dcf_id)) {
        return true;
    }
    $dcf_id = sanitize_string($dcf_id);
	$email = sanitize_string($email);
	$username = sanitize_string($username);
	$query = "INSERT INTO elf_users(dcf_id,email,username) VALUES ('$dcf_id','$email','$username')";
	return insert_data($query);
}

function elf_register_generate_username($first_name,$last_name) {
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

function elf_register_validate_username($username) {

	// white list for allowed characters (numbers, letters and period only)
	$whitelist = '/[^a-zA-Z0-9.]/';

	if (preg_match($whitelist, $username) > 0) {
		return FALSE;
	} else {
		return TRUE;
	}
}

<?php
/**
 * Shibalike language file
 * 
 * @package ElggGUIDTool
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2009
 * @link http://elgg.com/
 */

$english = array(

/**
 * Login form text
 */

	'shibalike:login:explanation' => 'Please enter your password and either your DCF ID or email address to login.',
	'shibalike:login:dcf_id:title' => 'DCF ID',
	'shibalike:login:email:title' => 'Email',

	'loginusername' => "DCF ID or email",

/**
 * Registration text
 */

	'shibalike:register:explanation' => 'Please enter your name, a password and either your email address or DCF ID to register.',
	'shibalike:register:first_name:label' => "First name",
	'shibalike:register:last_name:label' => "Last name",
	'shibalike:register:dcf_id:label' => "DCF ID",
	'shibalike:register:error:missing_first_or_last_name' => "Error: you must supply a first and last name.",
	'shibalike:register:error:either_dcf_id_or_email_required' => "Error: you must supply a DCF ID or an email address.",
	'shibalike:register:error:invalid_dcf_id' => "Error: you must supply a valid DCF ID.",
	'shibalike:register:error:invalid_email' => "Error: you must supply a valid registered email address.",
	'shibalike:register:error:mismatched_password' => "Your passwords do not match.",
	'shibalike:register:error:bad_password' => "Your password is invalid.",
	'shibalike:register:error:could_not_generate_username' => "Error: there seems to be a problem with the format of your first or last names.",
	'shibalike:register:continue' => "Continue",
	'shibalike:register:username:label' => "Username",
	'shibalike:register:username:explanation' => "Your username will be seen by other users in the ELF Community site. "
		."We've offered a suggestion, but you may change it. Your username must contain only lowercase English alphabet "
		."letters and numbers, starting with a letter.",
	'shibalike:register:display_name:label' => "Display name",
	'shibalike:register:display_name:explanation' => "Your display name will be how ELF Community Members will see you. We've offered a suggestion, but you may change it.",

/**
 * Request password text text
 */
	'shibalike:user:password:text' => 'To request a new password, enter your email address or DCF ID below and click the Request button.',	
	'shibalike:requestnewpassword:id_field' => "Email or DCF ID",
    
    'user:password:resetreq:success' => 'Please check your email to continue.',
    'user:password:success' => "Your password has been changed.",
    'email:resetreq:body' => "Hi %s,

Someone (from IP address %s) has requested a new password for your account.

If you requested this, click on the link below, otherwise ignore this message.

%s
",
    'email:resetpassword:body' => "Hi %s,

Your password has been reset to: %s

Please log in with this password and change it under Community > Settings.",
		
/**
 * User settings text
 */
	'shibalike:your_name:settings:title' => "Your name",
		
/**
 * Admin settings text
 */
	'shibalike:settings:moodle_config_file_name:title' => "Moodle configuration file location",
    'shibalike:adduser:dcf_id_note' => "This user's DCF_ID will be: %s",
    'shibalike:useradd:body' => '
%s,

A user account has been created for you at %s. To log in, visit:

%s

And log in with these user credentials:

DCF_ID: %s
Password: %s

You will be logged in as "%s", and you should change your password immediately.
',

);
				
add_translation("en",$english);
?>
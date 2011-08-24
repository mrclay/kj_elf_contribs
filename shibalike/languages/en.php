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
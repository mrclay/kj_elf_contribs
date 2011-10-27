<?php

$english = array(

	'email:validate:subject' => "%s, please confirm your email address for %s",
	'email:validate:body' => "%s,

Please click on the link below to continue your %s registration:

%s

If you can't click on the link, copy and paste it into your browser manually.

%s
%s
",
	'email:confirm:success' => "You have confirmed your email address!",
	'email:confirm:fail' => "Your email address could not be verified...",

	'elf_register:invalid_code_error' => "Error: invalid validation code. Please check your link and try again.",
	'elf_register:registerok' => "To continue your registration, please check your e-mail.",
	'elf_register:complete_register_title' => "Complete your registration",	

	'elf_register:register:explanation' => 'Please enter your email address (or DCF ID), and first and last names to begin your registration.',
	'elf_register:register:first_name:label' => "First name",
	'elf_register:register:last_name:label' => "Last name",
	'elf_register:register:dcf_id:label' => "DCF ID",
	'elf_register:register:error:missing_first_or_last_name' => "Error: you must supply a first and last name.",
	'elf_register:register:error:either_dcf_id_or_email_required' => "Error: you must supply a DCF ID or an email address.",
	'elf_register:register:error:invalid_dcf_id' => "Error: you must supply a valid DCF ID.",
	'elf_register:register:error:invalid_email' => "Error: you must supply a valid registered email address.",
	'elf_register:register:error:mismatched_password' => "Your passwords do not match.",
	'elf_register:register:error:bad_password' => "Your password is invalid.",
	'elf_register:register:error:invalid_username' => "Your username must contain only lowercase English alphabet letters and numbers, starting with a letter.",
	'elf_register:register:error:could_not_generate_username' => "Error: there seems to be a problem with the format of your first or last names.",
	'elf_register:register:username:label' => "Username",
	'elf_register:register:username:explanation' => "Your username will be seen by other users in the Leaf Community site. "
		."We've offered a suggestion, but you may change it. Your username must contain only lowercase English alphabet "
		."letters and numbers, starting with a letter.",
	'elf_register:register:display_name:label' => "Display name",
	'elf_register:register:display_name:explanation' => "Your display name will be how ELF Community Members will see you. We've offered a suggestion, but you may change it.",
	'elf_register:register:name_change' => "Your first and last name have been changed.",
    'elf_register:register:continue' => "Continue...",
    'elf_register:register:sending_email' => "An invitation will be e-mailed to you.",

	/**
 * Request password text text
 */
	'elf_register:user:password:text' => 'To request a new password, enter your email address or DCF ID below and click the Request button.',	
	'elf_register:requestnewpassword:id_field' => "Email or DCF ID",
    
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
	'elf_register:your_name:settings:title' => "Your name",
		
/**
* Admin settings text
*/

    'elf_register:adduser:dcf_id_note' => "This user's DCF_ID will be: %s",
    'elf_register:useradd:body' => '
%s,

A user account has been created for you at %s. To log in, visit:

%s

And log in with these user credentials:

DCF_ID: %s
Password: %s

You will be logged in as "%s", and you should change your password immediately.
',


);

	add_translation("en", $english);


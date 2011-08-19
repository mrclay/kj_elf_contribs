<?php

$english = array(

	'email:validate:subject' => "%s please confirm your email address for %s",
	'email:validate:body' => "%s,

Before you can start using %s, you must confirm your email address.

Please confirm your email address by clicking on the link below:

%s

If you can't click on the link, copy and paste it to your browser manually.

%s
%s
",
	'email:confirm:success' => "You have confirmed your email address!",
	'email:confirm:fail' => "Your email address could not be verified...",

	'elf_register:invalid_code_error' => "Error: invalid validation code. Please check your link and try again.",
	'elf_register:registerok' => "To activate your account, please confirm your email address by clicking on the link we just sent you.",
	'elf_register:complete_register_title' => "Complete your registration",	

	'elf_register:register:explanation' => 'Please enter either your email address or DCF ID to register, along with your first and last names.',
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
	'elf_register:register:username:explanation' => "Your username will be seen by other users in the ELF Community site. "
		."We've offered a suggestion, but you may change it. Your username must contain only lowercase English alphabet "
		."letters and numbers, starting with a letter.",
	'elf_register:register:display_name:label' => "Display name",
	'elf_register:register:display_name:explanation' => "Your display name will be how ELF Community Members will see you. We've offered a suggestion, but you may change it.",
	'elf_register:register:name_change' => "Your first and last name have been changed.",
		
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


);

	add_translation("en", $english);

<?php
	/**
	 * Elgg recaptcha language pack extension.
	 * 
	 * @package qli_recaptcha
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author RPGRealms
	 * @copyright RPGRealms 2010
	 */

	// Note, the captcha:lang may not actually do anything currently
	// And would be strictly for audio
	
	$english = array(
	
		'captcha:privatekey' => "reCAPTCHA Private Key",
		'captcha:publickey' => "reCAPTCHA Public Key",
		'captcha:captchafail' => "The reCAPTCHA wasn't entered correctly. Go back and try it again.", 
		'captcha:instructions_visual' => "Type the two words:",
		'captcha:instructions_audio' => "Type what you hear:",
		'captcha:play_again' => "Play sound again",
		'captcha:cant_hear_this' => "Download sound as MP3",
		'captcha:visual_challenge' => "Get a visual challenge",
		'captcha:audio_challenge' => "Get an audio challenge",
		'captcha:refresh_btn' => "Get a new challenge",
		'captcha:help_btn' => "Help",
		'captcha:incorrect_try_again' => "Incorrect. Try again.",
		'captcha:lang' => "en", 					
		'captcha:theme' => "red",

	);
	add_translation("en",$english);
	
?>
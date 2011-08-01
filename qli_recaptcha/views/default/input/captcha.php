<?php
	/**
	 * Elgg recaptcha plugin graphics generator
	 * 
	 * @package ElggCaptcha
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author RPGRealms
	 * @copyright RPGRealms 2010
	 * 
	 * Modified for Elgg 1.8 by Kevin Jardine, Radagast Solutions, 2011
	 */

	elgg_load_library('elgg:recaptcha');
	$publickey = elgg_get_plugin_setting('publickey', 'qli_recaptcha');

	$reCAPTCHA = "	<script type='text/javascript'>
		var RecaptchaOptions = {
			custom_translations : {
					instructions_visual : '" . elgg_echo('captcha:instructions_visual') . "',
					instructions_audio : '" . elgg_echo('captcha:instructions_audio') . "',
					play_again : '" . elgg_echo('captcha:play_again') . "',
					cant_hear_this : '" . elgg_echo('captcha:cant_here_this') . "',
					visual_challenge : '" . elgg_echo('captcha:visual_challenge') . "',
					audio_challenge : '" . elgg_echo('captcha:audio_challenge') . "',
					refresh_btn : '" . elgg_echo('captcha:refresh_btn') . "',
					help_btn : '" . elgg_echo('captcha:help_btn') . "',
					incorrect_try_again : '" . elgg_echo('captcha:incorrect_try_again') . "',
					},
			lang : '" . elgg_echo('captcha:language') . "', // Unavailable while writing this code (just for audio challenge)
			theme : '" . elgg_echo('captcha:theme') . "',
			};
	</script>
";
	$reCAPTCHA .= recaptcha_get_html($publickey);
		
	echo $reCAPTCHA;
	
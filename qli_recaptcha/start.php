<?php
	/**
	 * Elgg recaptcha plugin
	 * 
	 * @package ElggCaptcha
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author RPGRealms
	 * @copyright RPGRealms 2010
	 * 
	 * Modified for Elgg 1.8 by Kevin Jardine, Radagast Solutions, 2011
	 */

	elgg_register_event_handler('init','system','captcha_init');
	
	function captcha_init()	{
		
		elgg_register_library('elgg:recaptcha', elgg_get_plugins_path() . 'qli_recaptcha/models/recaptchalib.php');
		
		// Register page handler for captcha functionality
		//elgg_register_page_handler('captcha','captcha_page_handler');
		
		// Register a function that provides some default override actions
		elgg_register_plugin_hook_handler('actionlist', 'captcha', 'captcha_actionlist_hook');
		
		// Register actions to intercept
		$actions = array();
		$actions = elgg_trigger_plugin_hook('actionlist', 'captcha', null, $actions);
		
		if (($actions) && (is_array($actions)))
		{
			foreach ($actions as $action)
				elgg_register_plugin_hook_handler("action", $action, "captcha_verify_action_hook");
		}
	}
	
	/**
	 * Verify a captcha based on the input value entered by the user and the seed token passed.
	 *
	 * @param string $recaptcha_challenge_field
	 * @param string $recaptcha_response_field
	 * @return bool
	 */
	function captcha_verify_captcha($recaptcha_challenge_field, $recaptcha_response_field)
	{

	 	elgg_load_library('elgg:recaptcha');
		$privatekey = elgg_get_plugin_setting('privatekey', 'qli_recaptcha');
		$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $recaptcha_challenge_field,
                                $recaptcha_response_field);

		if (!$resp->is_valid) {
			return false;
		}else{
			return true;
		}		
	}
	
	/**
	 * Listen to the action plugin hook and check the captcha.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function captcha_verify_action_hook($hook, $entity_type, $returnvalue, $params)
	{
		$recaptcha_challenge_field = get_input('recaptcha_challenge_field');
		$recaptcha_response_field = get_input('recaptcha_response_field');
		
		if (captcha_verify_captcha($recaptcha_challenge_field, $recaptcha_response_field)){
			return true;
		}
		register_error(elgg_echo('captcha:captchafail'));
		
		if ($entity_type == "register") {
			// keep the current form values
			elgg_make_sticky_form('register');
		}
			
		return false;
	}
	
	/**
	 * This function returns an array of actions the captcha will expect a captcha for, other plugins may
	 * add their own to this list thereby extending the use.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function captcha_actionlist_hook($hook, $entity_type, $returnvalue, $params)
	{
		if (!is_array($returnvalue))
			$returnvalue = array();
			
		$returnvalue[] = 'register';
		$returnvalue[] = 'user/requestnewpassword';
			
		return $returnvalue;
	}
	
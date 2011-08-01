<?php
/**
 * User Status
 * 
 * @package User Status
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Brett Profitt
 * @copyright Brett Profitt 2009
 * @link http://eschoolconsultants.com
 */

// create default settings if needed.
$publickey = get_plugin_setting('publickey', 'qli_recaptcha');
if (!$publickey) {
	set_plugin_setting('publickey', '', 'qli_recaptcha');
}
$privatekey = get_plugin_setting('privatekey', 'qli_recaptcha');
if (!$privatekey) {
	set_plugin_setting('privatekey', '', 'qli_recaptcha');
}

echo '<p><label>' . elgg_echo('captcha:privatekey') . '</label> <input type="text" name="params[privatekey]" value="' . $privatekey . '"></p>';
echo '<p><label>' . elgg_echo('captcha:publickey') . '</label> <input type="text" name="params[publickey]" value="' . $publickey . '"></p>';
 
?>
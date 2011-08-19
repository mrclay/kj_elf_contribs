<?php
/**
 * Provide a way of setting your email
 *
 * @package Elgg
 * @subpackage Core
 * 
 * Modified by Kevin Jardine, Radagast Solutions 2011
 * to hide the email address change form.
 */

$user = elgg_get_page_owner_entity();

if ($user) {
	echo elgg_view('input/hidden',array('name' => 'email', 'value' => $user->email));
}

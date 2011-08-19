<?php
/**
 * Elgg register form
 *
 * @package Elgg
 * @subpackage Core
 * 
 * Version for the Shibalike plugin
 */

// TODO - with sticky forms are the get_input fields needed any more?
// TODO - add the JS magic to refresh the form with suggested username and display name
$password = $password2 = '';
$username = get_input('u');
$email = get_input('e');
$dcf_id = get_input('dcf_id');
$first_name = get_input('first_name');
$last_name = get_input('last_name');
$in_second_part = '';
$name = '';

$admin_option = false;
if (elgg_is_admin_logged_in() && isset($vars['show_admin'])) {
	$admin_option = true;
}

if (elgg_is_sticky_form('register')) {
	extract(elgg_get_sticky_values('register'));
	elgg_clear_sticky_form('register');
	if (is_array($admin)) {
		$admin = $admin[0];
	}
}

?>
<div id="registration_first_part">
<p><?php echo elgg_echo('shibalike:register:explanation'); ?></p>
<div class="yui3-g">
    <div class="yui3-u" style="width:300px">
        <label><?php echo elgg_echo('email'); ?></label><br />
        <?php
        echo elgg_view('input/text', array(
            'name' => 'email',
            'value' => $email,
        ));
        ?>
    </div>
    <div class="yui3-u" style="vertical-align:middle; width:50px; text-align: center">
        <p><i>OR</i></p>
    </div>
    <div class="mtm yui3-u" style="width:140px; margin-top: 0">
        <label><?php echo elgg_echo('shibalike:register:dcf_id:label'); ?></label><br />
        <?php
        echo elgg_view('input/text', array(
            'name' => 'dcf_id',
            'value' => $dcf_id,
        ));
        ?>
    </div>
</div>

<div>
	<label><?php echo elgg_echo('shibalike:register:first_name:label'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'first_name',
		'value' => $first_name,
	));
	?>
</div>
<div>
	<label><?php echo elgg_echo('shibalike:register:last_name:label'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'last_name',
		'value' => $last_name,
	));
	?>
</div>
<div>
	<label><?php echo elgg_echo('password'); ?></label><br />
	<?php
	echo elgg_view('input/password', array(
		'name' => 'password',
		'value' => $password,
	));
	?>
</div>
<div>
	<label><?php echo elgg_echo('passwordagain'); ?></label><br />
	<?php
	echo elgg_view('input/password', array(
		'name' => 'password2',
		'value' => $password2,
	));
	?>
</div>
<br />
<?php echo elgg_view('input/captcha'); ?>

<?php
echo elgg_view('input/button', array('id' => 'continue_button', 'value' => elgg_echo('shibalike:register:continue')));
?>
</div>
<div id="registration_second_part">
<div>
	<label><?php echo elgg_echo('shibalike:register:username:label'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'username',
		'value' => $username,
	));
	?>
	<br />
	<?php echo elgg_echo('shibalike:register:username:explanation'); ?>
	<br /><br />
</div>
<div>
	<label><?php echo elgg_echo('shibalike:register:display_name:label'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'name',
		'value' => $name,
	));
	?>
	<br />
	<?php echo elgg_echo('shibalike:register:display_name:explanation'); ?>
	<br /><br />
</div>
<?php

// Add captcha hook
echo elgg_view('input/captcha');

if ($admin_option) {
	echo elgg_view('input/checkboxes', array(
		'name' => "admin",
		'options' => array(elgg_echo('admin_option')),
	));
}
echo elgg_view('input/hidden', array('name' => 'in_second_part','value'=>$in_second_part));
echo elgg_view('input/hidden', array('name' => 'friend_guid', 'value' => $vars['friend_guid']));
echo elgg_view('input/hidden', array('name' => 'invitecode', 'value' => $vars['invitecode']));
echo '<br />';
echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('register')));
?>
</div>
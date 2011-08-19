<?php
/**
 * Elgg register form
 *
 * @package Elgg
 * @subpackage Core
 * 
 * rewritten by Kevin Jardine, Radagast Solutions
 */

$password = $password2 = '';
$username = $vars['username'];
$name = $vars['name'];
$first_name = $vars['first_name'];
$last_name = $vars['last_name'];

if (elgg_is_sticky_form('register')) {
	extract(elgg_get_sticky_values('register'));
	elgg_clear_sticky_form('register');
}

?>
<div>
	<label><?php echo elgg_echo('elf_register:register:first_name:label'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'first_name',
		'value' => $first_name,
	));
	?>
</div>
<div>
	<label><?php echo elgg_echo('elf_register:register:last_name:label'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'last_name',
		'value' => $last_name,
	));
	?>
</div>
<div>
	<label><?php echo elgg_echo('elf_register:register:username:label'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'username',
		'value' => $username,
	));
	?>
	<br />
	<?php echo elgg_echo('elf_register:register:username:explanation'); ?>
	<br /><br />
</div>
<div>
	<label><?php echo elgg_echo('elf_register:register:display_name:label'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'name',
		'value' => $name,
	));
	?>
	<br />
	<?php echo elgg_echo('elf_register:register:display_name:explanation'); ?>
	<br /><br />
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

<?php
// view to extend to add more fields to the registration form
echo elgg_view('register/extend');

echo elgg_view('input/hidden', array('name' => 'validation_code', 'value' => $vars['code']));
echo elgg_view('input/hidden', array('name' => 'register_data_guid', 'value' => $vars['register_data_guid']));
echo elgg_view('input/hidden', array('name' => 'friend_guid', 'value' => $vars['friend_guid']));
echo elgg_view('input/hidden', array('name' => 'invitecode', 'value' => $vars['invitecode']));
echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('submit')));
?>
<script type="text/javascript">
	$(function() {
		$('input[name=name]').focus();
	});
</script>
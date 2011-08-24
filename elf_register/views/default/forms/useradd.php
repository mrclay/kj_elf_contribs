<?php
/**
 * Elgg add user form.
 *
 * @package Elgg
 * @subpackage Core
 * 
 */

elgg_load_js('elgg.elf_register.displayName');

$name = $username = $email = $password = $password2 = $admin = '';

if (elgg_is_sticky_form('useradd')) {
	extract(elgg_get_sticky_values('useradd'));
	elgg_clear_sticky_form('useradd');
	if (is_array($admin)) {
		$admin = $admin[0];
	}
}

$admin_option = false;
if ((elgg_get_logged_in_user_entity()->isAdmin()) && ($vars['show_admin'])) {
	$admin_option = true;
}
?>
<div>
	<label><?php echo elgg_echo('elf_register:register:first_name:label');?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'first_name',
		'value' => $first_name,
		'autocomplete'=>'off',
		'id'=>'elf-register-first-name',
	));
	?>
</div>
<div>
	<label><?php echo elgg_echo('elf_register:register:last_name:label');?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'last_name',
		'value' => $last_name,
		'autocomplete'=>'off',
		'id'=>'elf-register-last-name',
	));
	?>
</div>
<div>
	<label><?php echo elgg_echo('name');?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'name',
		'value' => $name,
		'autocomplete'=>'off',
		'id'=>'elf-register-display-name',
	));
	?>
</div>
<div>
	<label><?php echo elgg_echo('username'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'username',
		'value' => $username,
	));
	?>
</div>
<div>
	<label><?php echo elgg_echo('email'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'email',
		'value' => $email,
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

<?php 
if ($admin_option) {
	echo "<div>";
	echo elgg_view('input/checkboxes', array(
		'name' => "admin",
		'options' => array(elgg_echo('admin_option') => 1),
		'value' => $admin,
	));
	echo "</div>";
}
?>

<div class="elgg-foot">
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('register'))); ?>
</div>
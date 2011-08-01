<?php
/**
 * Shibalike login form
 *
 * @package Shibalike
 */

// TODO: Need to write code to replace the forgotten password page as well
?>

<p><?php echo elgg_echo('shibalike:login:explanation'); ?></p>

<div>
	<label><?php echo elgg_echo('shibalike:login:dcf_id:title'); ?></label>
	<?php echo elgg_view('input/text', array('name' => 'dcf_id')); ?>
</div>
<div>
	<label><?php echo elgg_echo('shibalike:login:email:title'); ?></label>
	<?php echo elgg_view('input/text', array('name' => 'email')); ?>
</div>
<div>
	<label><?php echo elgg_echo('password'); ?></label>
	<?php echo elgg_view('input/password', array('name' => 'password')); ?>
</div>

<div>
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('login'))); ?>

	<label class="right mtm">
		<input type="checkbox" name="persistent" value="true" />
		<?php echo elgg_echo('user:persistent'); ?>
	</label>
	
	<?php 
	if ($vars['returntoreferer']) { 
		echo elgg_view('input/hidden', array('name' => 'returntoreferer', 'value' => 'true'));
	}
	?>
</div>

<ul class="elgg-menu elgg-menu-footer">
<?php
	if (elgg_get_config('allow_registration')) {
		echo '<li><a class="registration_link" href="' . elgg_get_site_url() . 'register">' . elgg_echo('register') . '</a></li>';
	}
?>
	<li><a class="forgotten_password_link" href="<?php echo elgg_get_site_url(); ?>pages/account/forgotten_password.php">
		<?php echo elgg_echo('user:password:lost'); ?>
	</a></li>
</ul>
<?php
/**
 * Elgg register form
 *
 * @package Elgg
 * @subpackage Core
 * 
 * Modified by Kevin Jardine, Radgast Solutions to just collect the name and email address
 * 
 */

$email = get_input('e');
$name = get_input('n');

if (elgg_is_sticky_form('register')) {
	extract(elgg_get_sticky_values('register'));
	elgg_clear_sticky_form('register');
}

?>
<p><?php echo elgg_echo('elf_register:register:explanation'); ?></p>
<div class="yui3-u" style="width:300px">
        <label><?php echo elgg_echo('email'); ?></label><br />
        <?php
        echo elgg_view('input/text', array(
            'name' => 'email',
            'value' => $email,
        ));
        ?>
</div>
<div class="yui3-u" style="width:50px; padding-top:10px; text-align: center">
	<p><i>OR</i></p>
</div>
<div class="yui3-u" style="width:140px; margin-top: 0">
        <label><?php echo elgg_echo('elf_register:register:dcf_id:label'); ?></label><br />
        <?php
        echo elgg_view('input/text', array(
            'name' => 'dcf_id',
            'value' => $dcf_id,
        ));
        ?>
</div>
<div style="width:300px">
	<label><?php echo elgg_echo('elf_register:register:first_name:label'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'first_name',
		'value' => $first_name,
	));
	?>
</div>
<div style="width:300px">
	<label><?php echo elgg_echo('elf_register:register:last_name:label'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'last_name',
		'value' => $last_name,
	));
	?>
</div>
<?php
// Add captcha hook
echo elgg_view('input/captcha');

echo elgg_view('input/hidden', array('name' => 'friend_guid', 'value' => $vars['friend_guid']));
echo elgg_view('input/hidden', array('name' => 'invitecode', 'value' => $vars['invitecode']));

echo "<p>"
     . elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('elf_register:register:continue')))
     . " <span style='margin-left:5px'>" . elgg_echo('elf_register:register:sending_email') . "</span>"
     . "</p>";
?>
<script type="text/javascript">
	$(function() {
		$('input[name=name]').focus();
	});
</script>
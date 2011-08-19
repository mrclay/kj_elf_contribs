<?php
/**
 * Provide a way of setting your full name.
 *
 * @package Elgg
 * @subpackage Core


 */

elgg_load_js('elgg.elf_register.displayName');

$user = elgg_get_page_owner_entity();

// all hidden, but necessary for properly updating user details
echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $user->guid));

?>
<div class="elgg-module elgg-module-info">
	<div class="elgg-head">
		<h3><?php echo elgg_echo('elf_register:your_name:settings:title'); ?></h3>
	</div>
	<div class="elgg-body">
		<p>
			<?php echo elgg_echo('elf_register:register:first_name:label'); ?>:
			<?php
			echo elgg_view('input/text',array('autocomplete'=>'off','id'=>'elf-register-first-name','name' => 'first_name', 'value' => $user->first_name));
			?>
		</p>
		<p>
			<?php echo elgg_echo('elf_register:register:last_name:label'); ?>:
			<?php
			echo elgg_view('input/text',array('autocomplete'=>'off','id'=>'elf-register-last-name','name' => 'last_name', 'value' => $user->last_name));
			?>
		</p>
		<p>
			<?php echo elgg_echo('elf_register:register:display_name:label'); ?>:
			<?php
			echo elgg_view('input/text',array('autocomplete'=>'off','id'=>'elf-register-display-name','name' => 'name', 'value' => $user->name));
			?>
		</p>
	</div>
</div>
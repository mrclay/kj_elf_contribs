<?php
/**
 * Provide a way of setting your full name.
 *
 * @package Elgg
 * @subpackage Core


 */

elgg_load_js('elgg.shibalike.displayName');

$user = elgg_get_page_owner_entity();

// all hidden, but necessary for properly updating user details
echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $user->guid));

?>
<div class="elgg-module elgg-module-info">
	<div class="elgg-head">
		<h3><?php echo elgg_echo('shibalike:your_name:settings:title'); ?></h3>
	</div>
	<div class="elgg-body">
		<p>
			<?php echo elgg_echo('shibalike:register:first_name:label'); ?>:
			<?php
			echo elgg_view('input/text',array('autocomplete'=>'off','id'=>'shibalike-first-name','name' => 'first_name', 'value' => $user->first_name));
			?>
		</p>
		<p>
			<?php echo elgg_echo('shibalike:register:last_name:label'); ?>:
			<?php
			echo elgg_view('input/text',array('autocomplete'=>'off','id'=>'shibalike-last-name','name' => 'last_name', 'value' => $user->last_name));
			?>
		</p>
		<p>
			<?php echo elgg_echo('shibalike:register:display_name:label'); ?>:
			<?php
			echo elgg_view('input/text',array('autocomplete'=>'off','id'=>'shibalike-display-name','name' => 'name', 'value' => $user->name));
			?>
		</p>
	</div>
</div>
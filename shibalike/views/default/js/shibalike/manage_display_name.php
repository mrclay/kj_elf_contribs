<?php
/**
 * Manage display name JS
 *
 * @package Shibalike
 */
?>
//<script>
elgg.provide('elgg.shibalike.displayName');

elgg.shibalike.displayName.adjustDisplayName = function(event) {

	// update the username and display name fields
	firstName = $('#shibalike-first-name').val();
	lastName = $('#shibalike-last-name').val();
	$('#shibalike-display-name').val(firstName + ' ' + lastName);
}

elgg.shibalike.displayName.init = function() {
	$('#shibalike-first-name').keyup(elgg.shibalike.displayName.adjustDisplayName);
	$('#shibalike-last-name').keyup(elgg.shibalike.displayName.adjustDisplayName);
	$('#shibalike-first-name').change(elgg.shibalike.displayName.adjustDisplayName);
	$('#shibalike-last-name').change(elgg.shibalike.displayName.adjustDisplayName);
	$('#shibalike-first-name').mousedown(elgg.shibalike.displayName.adjustDisplayName);
	$('#shibalike-last-name').mousedown(elgg.shibalike.displayName.adjustDisplayName);
	$('#shibalike-first-name').bind('input', elgg.shibalike.displayName.adjustDisplayName);
	$('#shibalike-last-name').bind('input', elgg.shibalike.displayName.adjustDisplayName);
};

elgg.register_hook_handler('init', 'system', elgg.shibalike.displayName.init);
//</script>

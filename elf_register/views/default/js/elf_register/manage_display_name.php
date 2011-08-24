<?php
/**
 * Manage display name JS
 *
 * @package ELF register
 */
?>
//<script>
elgg.provide('elgg.elf_register.displayName');

elgg.elf_register.displayName.adjustDisplayName = function(event) {

	// update the username and display name fields
	firstName = $('#elf-register-first-name').val();
	lastName = $('#elf-register-last-name').val();
	$('#elf-register-display-name').val(firstName + ' ' + lastName);
}

elgg.elf_register.displayName.init = function() {
	$('#elf-register-first-name').keyup(elgg.elf_register.displayName.adjustDisplayName);
	$('#elf-register-last-name').keyup(elgg.elf_register.displayName.adjustDisplayName);
	$('#elf-register-first-name').change(elgg.elf_register.displayName.adjustDisplayName);
	$('#elf-register-last-name').change(elgg.elf_register.displayName.adjustDisplayName);
	$('#elf-register-first-name').mousedown(elgg.elf_register.displayName.adjustDisplayName);
	$('#elf-register-last-name').mousedown(elgg.elf_register.displayName.adjustDisplayName);
	$('#elf-register-first-name').bind('input', elgg.elf_register.displayName.adjustDisplayName);
	$('#elf-register-last-name').bind('input', elgg.elf_register.displayName.adjustDisplayName);
};

elgg.register_hook_handler('init', 'system', elgg.elf_register.displayName.init);
//</script>

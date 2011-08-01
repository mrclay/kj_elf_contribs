<?php
/**
 * Process registration ajax
 *
 * @package Shibalike
 */
?>
elgg.provide('elgg.shibalike');

elgg.shibalike.processRegistrationCallback = function(data, resultStatus, XHR) {
	o = data.output;
	if (resultStatus == 'success' && o.error != true) {
		
		var form = $('form[name=registration_form]');

		// update the username and display name fields
		form.find('input[name=username]').val(o.username);
		form.find('input[name=name]').val(o.display_name);
		
		// display the second part of the form
		form.find('input[name=in_second_part]').val('yes');
		$('#registration_first_part').hide();
		$('#registration_second_part').show();
		
	} else {
		elgg.register_error(o.error_message,10000);
	}
}

elgg.shibalike.processRegistration = function() {
	
	var form = $('form[name=registration_form]');
	
	var actionURL = elgg.config.wwwroot + "action/shibalike/process_registration";
	var postData = form.serializeArray();

	$.post(actionURL, postData, elgg.shibalike.processRegistrationCallback, 'json');
}

elgg.shibalike.init = function() {
	$('#continue_button').click(elgg.shibalike.processRegistration);
	var form = $('form[name=registration_form]');
	if (form.find('input[name=in_second_part]').val() == 'yes') {
		$('#registration_first_part').hide();
		$('#registration_second_part').show();
	} else {
		$('#registration_first_part').show();
		$('#registration_second_part').hide();
	}		
};

elgg.register_hook_handler('init', 'system', elgg.shibalike.init);

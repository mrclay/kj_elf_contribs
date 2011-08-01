<?php
elgg_load_library('elgg:shibalike');
$email = get_input('email');
$dcf_id = get_input('dcf_id');
$first_name = get_input('first_name');
$last_name = get_input('last_name');
$password = get_input('password');
$password2 = get_input('password2');
$data = shibalike_process_registration_data($email,$dcf_id,$first_name,$last_name,$password,$password2);
echo json_encode($data);
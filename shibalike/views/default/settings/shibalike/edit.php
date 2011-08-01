<?php
$body = '';

$moodle_config_file_name = elgg_get_plugin_setting('moodle_config_file_name', 'shibalike');

$body .= elgg_echo('shibalike:settings:moodle_config_file_name:title');
$body .= '<br />';
$body .= elgg_view('input/text',array('name'=>'params[moodle_config_file_name]','value'=>$moodle_config_file_name, 'class'=>'shibalike_settings'));

echo $body;

<?php

$handlers = array (
    	'user_created' => array (
        'handlerfile'      => '/auth/shibboleth/shibalike_lib.php',
        'handlerfunction'  => 'shibalike_inform_elgg',
        'schedule'         => 'instant'
    )
);
<?php

namespace Shibalike\Attr\Store;

use Shibalike\Attr\IStore;

class ElggStore implements IStore {

    public function __construct()
    {
        if (! function_exists('elgg_is_logged_in')) {
            throw new Exception('An active Elgg environment is required.');
        }
    }

    /**
     * @param string $username
     * @return array
     */
    public function fetchAttrs($username)
    {
    	$user = get_user_by_username($username);
    	if (!$user) {
    		return null;
    	} else {
    		// for now DCF email and Elgg email are the same
    		$dcfEmail = $user->email;

		    return array('dcfUserId' => $user->dcf_id,
		    'dcfEmail' => $dcfEmail,
		    'dcfFirstName'  => $user->first_name,
		    'dcfLastName' =>  $user->last_name,
		    'elggUserId' => $user->guid,
		    'elggUsername' => $user->username,
		    'elggFullName' =>  $user->name,
		    'elggEmail' =>  $dcfEmail,
		    'moodleUserId' => $user->moodleUserId,
		    );    	
    	}

	}
}
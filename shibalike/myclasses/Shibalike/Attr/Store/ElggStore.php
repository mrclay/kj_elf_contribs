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

            $dcfUserId = $user->dcf_id;
            // if this is an admin-created user, give them a dcf_id, but don't save it
            if (empty($dcfUserId)) {
                $dcfUserId = $user->username;
            }
            $first = $user->first_name;
            $last = $user->last_name;
            // if this is an admin-created user, hack their first/last and save it
            if (empty($first)) {
                list($first, $last) = preg_split('@\\s+@', $user->name, 2);
                $user->first_name = $first;
                $user->last_name = $last;
                $user->save();
            }
		    return array(
                'dcfUserId' => $dcfUserId,
                'dcfEmail' => $dcfEmail,
                'dcfFirstName'  => $first,
                'dcfLastName' =>  $last,
                'elggUserId' => $user->guid,
                'elggUsername' => $user->username,
                'elggFullName' =>  $user->name,
                'elggEmail' =>  $dcfEmail,
                'moodleUserId' => $user->moodleUserId,
		    );    	
    	}

	}
}
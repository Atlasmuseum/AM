<?php

/**
 * @file
 * @ingroup API
 */

if ( !defined( 'API_EXCHANGE' ) ) {
  die( "This file is part of the app exchange API. It is not a valid entry point.\n" );
}

/**
 * valid_user	Check if connection to the API is correct.
 * 						If connection is correct, check if a user exists on the wiki.
 *						Called by 'validuser' action parameter
 *
 * @param		string	$webserviceid	id to the webservice
 * @param		string	$webservicepass	password to the webservice
 * @param		string	$username	user id
 * @param		string	$userpass	user password
 * 
 * @return	string	result message:
 *									- 'Success' if user exists
 *									- Error message otherwise
 */
function valid_user($webserviceid, $webservicepass, $username, $userpass) {
	// Result structure to send back
	$result = array(
		'validuser' => array(
			'username'	=> $username,
			'result'		=> 'undefined'
		)
	);

	// Check webservice connection
	$webservice_result = check_webservice($webserviceid, $webservicepass);
	if ($webservice_result != null)
		// Incorrect webservice connection
		$result['validuser']['result'] = $webservice_result;
	else
		// Webservice connection ok: check user
		$result['validuser']['result'] = check_user($username, $userpass);

	return json_encode($result);
}

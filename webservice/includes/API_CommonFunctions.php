<?php

/**
 * @file
 * @ingroup API
 */

if ( !defined( 'API_EXCHANGE' ) ) {
  die( "This file is part of the app exchange API. It is not a valid entry point.\n" );
}

function get_param($param_name) {
		if(isset($_GET[$param_name]) AND !empty($_GET[$param_name]))
			return $_GET[$param_name];
		else
			return null;
	}

/**
 * check_webservice	check whether id and pass to the API is correct of not
 *
 * @param		string	$webserviceid	
 * @param		string	$webservicepass
 * 
 * @return	string	Error message if id or pass are incorrect
 *									null if ok
 */
function check_webservice($webserviceid, $webservicepass) {
	
	global $app_g_WEBSERVICE_ID;
	global $app_g_WEBSERVICE_PASS;

	if ($webserviceid == null)
		return "WebServiceNoId";
	else
	if ($webserviceid != $app_g_WEBSERVICE_ID)
		return "WebServiceWrongId";
	else
	if ($webservicepass == null)
	 	return "WebServiceNoPass";
	else
	if ($webservicepass != $app_g_WEBSERVICE_PASS)
	 	return "WebServiceWrongPass";
	else
		return null;
}

/**
 * check_user	check if a user exists on the wiki
 *
 * @param		string	$username	user id
 * @param		string	$userpass	user password
 * 
 * @return	string	result message
 */
function check_user($username, $userpass) {
	global $app_g_API_URL;
	global $app_g_COOKIEPREFIX;
	global $app_g_SESSIONID;

	// First login attempt to Mediawiki API
	// in order to get a token and a session cookie
	$postdata = http_build_query(
		array(
			'action'			=> 'login',
			'lgname'			=> $username,
			'lgpassword'	=> $userpass,
			'format'			=> 'json'
		)
	);
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => 'Content-type: application/x-www-form-urlencoded',
			'content' => $postdata
		)
	);
	$context  = stream_context_create($opts);
	$page = json_decode(file_get_contents($app_g_API_URL, false, $context));
	
	$token = $page->{'login'}->{'token'};
	$app_g_COOKIEPREFIX = $page->{'login'}->{'cookieprefix'};
	$app_g_SESSIONID = $page->{'login'}->{'sessionid'};

	// Second login attempt to Mediawiki API
	// this time, with token and session cookie
	$postdata = http_build_query(
		array(
			'action' => 'login',
			'lgname' => $username,
			'lgpassword' => $userpass,
			'format' => 'json',
			'lgtoken' => $token
		)
	);
	$page = post_data($postdata);

	return $page->{'login'}->{'result'};
}

/**
 * get_token	gets an edit token
 *
 * @return	string	edit token
 */
function get_token() {
	$postdata = http_build_query(
		array(
			'action'			=> 'tokens',
			'format'			=> 'json'
		)
	);
	$page = post_data($postdata);
	$token = $page->{'tokens'}->{'edittoken'};

	return $token;
}

/**
 * post_data	sends a POST request
 *
 * @param		array	$postdata	parameters to send
 * 
 * @return	array	result
 */
function post_data($postdata) {
	global $app_g_API_URL;
	global $app_g_COOKIEPREFIX;
	global $app_g_SESSIONID;
	
	if ($app_g_COOKIEPREFIX != '')
		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => "Content-type:application/x-www-form-urlencoded\r\n".
										 "Cookie:".$app_g_COOKIEPREFIX."_session=".$app_g_SESSIONID,
				'content' => $postdata
			)
		);
	else 
		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => "Content-type:application/x-www-form-urlencoded",
				'content' => $postdata
			)
		);
	$context  = stream_context_create($opts);
	$page = json_decode(file_get_contents($app_g_API_URL, false, $context));

	return $page;	
}

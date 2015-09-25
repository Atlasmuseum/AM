<?php

/**
 * @file
 * @ingroup API
 */

if ( !defined( 'API_EXCHANGE' ) ) {
  die( "This file is part of the app exchange API. It is not a valid entry point.\n" );
}

/**
 * extract_creadits
 *
 * @return	string
 */
function extract_credits($webserviceid, $webservicepass) {
	global $app_g_BASE_URL;
	return $app_g_BASE_URL.'/webservice/credits.json';
}

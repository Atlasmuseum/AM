<?php

/**
 * @file
 * @ingroup API
 */

if ( !defined( 'API_EXCHANGE' ) ) {
  die( "This file is part of the app exchange API. It is not a valid entry point.\n" );
}

/**
 * notice_field
 *
 * @param		$webserviceid		string
 * @param		$webservicepass	string
 * @param		$field					string
 * @return	string
 */
function get_field_type($field) {
	global $app_g_FIELDTYPE;

	return $app_g_FIELDTYPE[ $field ];
}

function get_field_values($field) {
	return 'undefined';
}

<?php

/**
 * @file
 * @ingroup API
 */

if ( !defined( 'API_EXCHANGE' ) ) {
  die( "This file is part of the app exchange API. It is not a valid entry point.\n" );
}

/**
 * get_content	returns wiki content of a page
 *
 * @param		string	$id	page id to return
 * 
 * @return	string	page content
 */
function get_content($id) {
	global $app_g_API_URL;

	$postdata = http_build_query(
		array(
			'action'			=> 'query',
			'prop'      	=> 'revisions',
			'rvprop'      => 'content',
			'pageids'			=> $id,
			'format'			=> 'json'
		)
	);
	
	$content = json_decode(file_get_contents($app_g_API_URL."?".$postdata));
	$data = $content->{'query'}->{'pages'}->{$id}->{'revisions'}[0]->{'*'};

	return $data;
}

/****************************************************************************/

/**
 * get_notice_data	returns the value of a specific field for a given page id
 *
 * @param		string	$notice	page id
 * @param		string	$field	name of the field
 * 
 * @return	string	
 */
function get_notice_data($notice, $field) {
	$value = 'undefined';

	$content = get_content($notice);
	$pattern = "/".$field."/";
	$data = preg_grep($pattern, explode("\n", $content));
	foreach ($data as &$value) {
		$value = ereg_replace("^[^=]*=","", $value);
		break;
	}

	return $value;
}

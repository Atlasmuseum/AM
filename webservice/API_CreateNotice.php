<?php

/**
 * @file
 * @ingroup API
 */

if ( !defined( 'API_EXCHANGE' ) ) {
  die( "This file is part of the app exchange API. It is not a valid entry point.\n" );
}

/**
 * create_notice
 * 
 * @return	string
 */
function create_notice() {
	$title = "New Page (".date("Y-m-d H:i:s").")";
	$text  = "{{Notice d'œuvre\n|titre= \nartiste= \n}}";

	// Post new content
	$postdata = http_build_query(
		array(
			'action'			=> 'edit',
			'format'			=> 'json',
			'title'       => $title,
			'text'			  => $text,
			'token'       => get_token()
		)
	);
	$page = post_data($postdata);
	return $page->{'edit'}->{'pageid'};

	#return 'undefined';
}

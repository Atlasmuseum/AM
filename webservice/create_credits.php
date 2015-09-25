<?php
/**
 This is just a test
 */

/****************************************************************************/

define( 'API_VERSION', '0.1' );
define( 'API_EXCHANGE', '1' );

// Default settings
require_once dirname( __FILE__ ) . '/API_Settings.php';

// Common functions
require_once dirname( __FILE__ ) . '/includes/API_CommonFunctions.php';

/****************************************************************************/

function get_files() {
	global $app_g_API_URL;

	$apcontinue = "";
	$cnt = 0;
	do {
		if (empty($apcontinue)) {
			$postdata = http_build_query(
				array(
					'action'			=> 'query',
					'list'      	=> 'allpages',
					'apnamespace'	=> 6,
					'aplimit'			=> 500,
					'format'			=> 'json'
				)
			);
		} else {
			$postdata = http_build_query(
				array(
					'action'			=> 'query',
					'list'      	=> 'allpages',
					'apnamespace'	=> 6,
					'aplimit'			=> 500,
					'apcontinue'	=> $apcontinue,
					'format'			=> 'json'
				)
			);
		}
		$content = json_decode(file_get_contents($app_g_API_URL."?".$postdata));
		$data = $content->{'query'}->{'allpages'};
		$cnt =0;
		foreach ($data as &$value) {
			$pageid = $value->{'pageid'};
			$title  = $value->{'title'};
			$credit = get_content($pageid);
			if ($cnt > 0) print ",";
			print "{\"pageid\":".$pageid.",\"title\":\"".$title."\",\"credit\":\"".$credit."\"}";
		}

		if (isset($content->{'query-continue'})) {
			$apcontinue = json_encode($content->{'query-continue'}->{'allpages'}->{'apcontinue'});
			$apcontinue = substr($apcontinue, 1, strlen($apcontinue)-2);
		}
		else
			$apcontinue = "";
		$cnt++;
	} while (!empty($apcontinue) && !is_null($apcontinue));

	return null;
}

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
	$data = 'credits: '.$content->{'query'}->{'pages'}->{$id}->{'revisions'}[0]->{'*'};

	return $data;
}

/****************************************************************************/

print "{\"extractcredits\":{";
get_files();
print "}}";

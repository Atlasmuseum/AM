<?php

/**
 * @file
 * @ingroup API
 */

if ( !defined( 'API_EXCHANGE' ) ) {
  die( "This file is part of the app exchange API. It is not a valid entry point.\n" );
}

/**
 * upload_file
 *
 * @param		$filename				string
 * @return	string
 */
function upload_file($filename) {
	global $app_g_IMAGES_URL;
	
	$postdata = http_build_query(
		array(
			'action'			=> 'upload',
			'url'					=> $app_g_IMAGES_URL.'/'.$filename,
			'filename'		=> $filename,
			'token'				=> get_token(),
			'format'			=> 'json'
		)
	);
	$page = post_data($postdata);
	return $page->{'upload'}->{'result'};
}

/**
 * set_notice_data
 *
 * @param		$id					string
 * @param		$field					string
 * @param		$date						string
 * @param		$value					string
 * @param		$operation			string
 * @param		$argument				string
 * @return	string
 */
function set_notice_data($id, $field, $date, $value, $operation, $argument) {
	global $app_g_API_URL;

	// Get page content
	$postdata = http_build_query(
		array(
			'action'			=> 'query',
			'prop'      	=> 'revisions',
			'rvprop'      => 'content',
			'pageids'			=> $id,
			'format'			=> 'json'
		)
	);
	$data = json_decode(file_get_contents($app_g_API_URL."?".$postdata))->{'query'}->{'pages'}->{$id};
	
	if ($data->{'title'} == null)
		// This page doesn't exist -> WrongNotice
		return "WrongNotice";
	else {
		#echo json_encode($data)."<br />";
		#return;
		$content = $data->{'revisions'}[0]->{'*'};
		if ($content == null || $content == "")
			// No content -> EmptyNotice 
			return "EmptyNotice ";
		else
		if ($operation == null || $operation == "")
			// No operation specified -> EmptyOperation
			return "EmptyOperation";
		else
		if ($operation != "add" && $operation != "replace" && $operation !="delete")
			// Wrong operation -> WrongOperation
			return "WrongOperation";
		else
		if ($field == null || $field == "")
			// No field -> EmptyField
			return "EmptyField ";
		else  {
			// Split content into fields and values
			$start_text = eregi_replace("^([^\|]*).*$", "\\1", $content);
			$end_text   = eregi_replace("^.*(}}[^}]*)$", "\\1", $content);
			
			$content = eregi_replace("^[^\|]*", "", $content);
			$content = eregi_replace("}}[^}]*$", "", $content);
			$content = eregi_replace("([^\n])\|([^\n\|=]*)=", "\\1\n|\\2=", $content);
			$content = split("\n",$content);
			$fields = array();
			foreach ($content as &$i) {
				$i = eregi_replace("^\|", "", $i);
				$f = eregi_replace("^([^=]*)=(.*)$", "\\1", $i);
				$v = eregi_replace("^([^=]*)=(.*)$", "\\2", $i);
				$fields[$f] = $v;
			}

			// Do whatever action must be done
			if ($operation == "replace") {
				if ($fields[$field] == "" || $fields[$field] == null)
					return "WrongField";
				else
					$fields[$field] = $value;
			} else
			if ($operation == "add") {
				if ($fields[$field] != "" && $fields[$field] != null)
					$fields[$field] .= ", ".$value;
				else {
					$fields[$field] = $value;
					upload_file($filename);
				}
			} else
			if ($operation == "delete") {
				if ($fields[$field] == "" || $fields[$field] == null)
					return "WrongField";
				else
					unset($fields[$field]);
			}

			// Construct new content
			$content = $start_text;
			foreach ($fields as $key => $element)
			if ($key != "" && $key != null) {
				$content .= "|".$key."=".$element."\n";
			}
			$content .= $end_text;
			
			// Post new content
			$postdata = http_build_query(
				array(
					'action'			=> 'edit',
					'pageid'			=> $id,
					'format'			=> 'json',
					'text'			  => $content,
					'token'       => get_token()
				)
			);
			$page = post_data($postdata);
			return $page->{'edit'}->{'result'};
		}
	}
}

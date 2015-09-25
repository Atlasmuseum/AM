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

function upload_file($filename) {
	global $app_g_API_URL;
	global $app_g_COOKIEPREFIX;
	global $app_g_SESSIONID;
	$fileHandle = fopen("http://atlasmuseum.irisa.fr/images/".$filename, "rb");
	$fileContents = stream_get_contents($fileHandle);
	fclose($fileHandle);

	$postdata = http_build_query(
		array(
			'action'			=> 'upload',
			'filename'   	=> $filename,
			'token'				=> get_token(),
			'file'      	=> $fileContents,
			'format'			=> 'json'
		)
	);
	
	$eol = "\r\n";
	$header = ''; 
	$mime_boundary=md5(time());
	
	$params = array ('action'=>'upload',
					'filename'=>$filename,
					'file'=>$fileContents,
					'token'=>get_token(),
					'format'=>'json');	
	//parameters 	
	foreach ($params as $key=>$value){
			$data .= '--' . $mime_boundary . $eol;
			$data .= 'Content-Disposition: form-data; name="' . $key . '"' . $eol;
			$data .= 'Content-Type: text/plain; charset=UTF-8' .  $eol;
			$data .= 'Content-Transfer-Encoding: 8bit' .  $eol . $eol;
			$data .= $value . $eol;
		}
 
	//file
	$data = '';
	$data .= '--' . $mime_boundary . $eol;
	$data .= 'Content-Disposition: form-data; name="file"; filename="'.$filename.'"' . $eol; //Filename here
	$data .= 'Content-Type: application/octet-stream; charset=UTF-8' . $eol;
	$data .= 'Content-Transfer-Encoding: binary' . $eol . $eol;
	$data .= 'test' . $eol;
	$data .= "--" . $mime_boundary . "--" . $eol . $eol; // finish with two eol's
	print $data."<br />";
	
	//headers
	$header .= 'User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)' . $eol;
	$header .= 'Content-Type: multipart/form-data; boundary='.$mime_boundary . $eol;
	$header .= 'Host: atlasmuseum.irisa.fr/www'. $eol;
	$header .= 'Cookie: '. $app_g_COOKIEPREFIX."_session=".$app_g_SESSIONID . $eol;
	$header .= 'Content-Length: ' . strlen($data) . $eol;
	$header .= 'Connection: Keep-Alive';
	
	print $header."<br />";
	
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => $header,
			'content' => $data
		)
	);
	$context  = stream_context_create($opts);
	$content = @file_get_contents($app_g_API_URL, false, $context);
	print $content;
	//$content = json_decode(file_get_contents($app_g_API_URL, FILE_TEXT, $context));
	//print json_encode($content);

	return null;
}

function upload_url($filename) {
	global $app_g_API_URL;
	$postdata = http_build_query(
		array(
			'action'			=> 'upload',
			'url'					=> 'http://atlasmuseum.irisa.fr/images/1A49895.JPG',
			'filename'		=> 'test_1A49895.JPG',
			'token'				=> get_token(),
			'format'			=> 'json'
		)
	);
	//$content = json_decode(file_get_contents($app_g_API_URL."?".$postdata));
	return json_encode(post_data($postdata););
}

/****************************************************************************/

check_user("TestPoulpy", "Poulpy");

print "upload<br />";

//upload_file('1A49895.JPG');
upload_url('');

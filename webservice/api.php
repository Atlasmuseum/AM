<?php
/**
 * Main entry point for the api dedicated to app exchange.
 */

/**
 * @defgroup API App exchange API
 */
 
/****************************************************************************/

define( 'API_VERSION', '0.1' );
define( 'API_EXCHANGE', '1' );

// Default settings
require_once dirname( __FILE__ ) . '/API_Settings.php';

// Common functions
require_once dirname( __FILE__ ) . '/includes/API_CommonFunctions.php';

// Action functions
require_once dirname( __FILE__ ) . '/includes/API_CreateNotice.php';
require_once dirname( __FILE__ ) . '/includes/API_ExtractCredits.php';
require_once dirname( __FILE__ ) . '/includes/API_ExtractXMLWiki.php';
require_once dirname( __FILE__ ) . '/includes/API_GetNoticeData.php';
require_once dirname( __FILE__ ) . '/includes/API_NoticeField.php';
require_once dirname( __FILE__ ) . '/includes/API_SetNoticeData.php';

/****************************************************************************/

$action = get_param('action');
$result = array();
$webservice_result = null;
$login_result = null;
$process_result = null;

# Webservice id control
if ($app_g_WEBSERVICE_REQUIRED[$action]) {
	$webserviceid 	= get_param('webserviceid');
	$webservicepass = get_param('webservicepass');
	
	$webservice_result = check_webservice($webserviceid, $webservicepass);
}

# Login control
if ($app_g_LOGIN_REQUIRED[$action]) {
	$username	= get_param('username');
	$userpass = get_param('userpass');

	$result[$action]['username'] = $username;
	$login_result = check_user($username, $userpass);
}

# Processing action
switch ($action) {
	case 'validuser':
		$process_result = $login_result;
		break;

	case 'getnoticedata':
		$result[$action]['notice'] = get_param('notice');
		$result[$action]['field']  = get_param('field');
		if ($webservice_result == null && $login_result == 'Success') {
			$result[$action]['value'] = get_notice_data($result[$action]['notice'], $result[$action]['field']);
			$process_result = 'Success';
		}
		else
			$result[$action]['value'] = 'undefined';
		break;

	case 'setnoticedata':
		$result[$action]['notice'] = get_param('notice');
		$result[$action]['field']  = get_param('field');
		$result[$action]['value']  = get_param('value');
		$date			 = get_param('date');
		$operation = get_param('operation');
		$argument  = get_param('argument');

		if ($webservice_result == null && $login_result == 'Success') {
			$process_result = set_notice_data($result[$action]['notice'], $result[$action]['field'], $date, $result[$action]['value'], $operation, $argument);
		}
		break;

	case 'createnotice':
		if ($webservice_result == null && $login_result == 'Success') {
			$result[$action]['notice'] = create_notice();
			$process_result = 'Success';
		}
		else
			$result[$action]['notice'] = 'undefined';
		break;

	case 'noticefield':
		$result[$action]['field'] = get_param('field');

		if ($webservice_result == null) {
			$result[$action]['fieldtype']  = get_field_type($result[$action]['field']);
			$result[$action]['fieldvalue'] = get_field_values($result[$action]['field']);
			$process_result = 'Success';
		} else {
			$result[$action]['fieldtype']  = 'undefined';
			$result[$action]['fieldvalue'] = 'undefined';
		}
		break;
		
	case 'extractcredits':
		if ($webservice_result == null) {
			$result[$action]['filename'] = extract_credits();
			$process_result = 'Success';
		}
		else {
			$result[$action]['filename'] = 'undefined';
			$result[$action]['value'] = 'undefined';
		}
		break;

	case 'extractxmlwiki':
		if ($webservice_result == null) {
			$result[$action]['filename'] = extract_xml_wiki();
			$process_result = 'Success';
		}
		else {
			$result[$action]['filename'] = 'undefined';
			$result[$action]['value'] = 'undefined';
		}
		break;
	default:
}

# Putting everything together:
#  1. webservice control result
#  2. if ok, login control result, if required
#  3. if ok, process result
if ($webservice_result != null)
	$result[$action]['result'] = $webservice_result;
else if ($app_g_LOGIN_REQUIRED[$action] && $login_result != 'Success')
	$result[$action]['result'] = $login_result;
else if ($process_result != null)
	$result[$action]['result'] = $process_result;

# Print result
echo json_encode($result);

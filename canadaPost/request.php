<?php
 /**
 * Sample code for the GetMerchantRegistrationToken Canada Post service.
 * 
 * The GetMerchantRegistrationToken service returns a unique registration token that is used
 * to launch a merchant into the Canada Post sign-up process.
 *
 * This sample is configured to access the Developer Program sandbox environment. 
 * Use your development key username and password for the web service credentials.
 * 
 **/

// Your username and password are imported from the following file
// CPCWS_Platforms_PHP_Samples\REST\platforms\user.ini
$userProperties = parse_ini_file(realpath(dirname($_SERVER['SCRIPT_FILENAME'])) . '/../user.ini');

$username = "532480e548f1a09d";
$password = "76d0546f8d76f6628685fb";

$return_url =  "http://intense-sea-3193.herokuapp.com/canadaPost/response.php";
$token_id="1111111111111111111111";
$platform="101010";

 
//https://ct.soa-gw.canadapost.ca/rs/ship/price
//https://ct.soa-gw.canadapost.ca/ot/token/1111111111111111111111';

// REST URI
$service_url = 'https://ct.soa-gw.canadapost.ca/merchant';

$xmlRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<mailing-scenario xmlns="http://www.canadapost.ca/ws/ship/rate-v2">
  <return-url>{$return_url}</return-url>
  <token-id>
    {$token_id}
  </token-id>
  <platform-id>{$platform}</platform-id>
</mailing-scenario>
XML;

$curl = curl_init($service_url); // Create REST Request
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, '');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($curl, CURLOPT_USERPWD, $username . ':' . $password);

curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept:application/vnd.cpc.registration+xml', 'Accept-Language:en-CA'));
$curl_response = curl_exec($curl); // Execute REST Request
if(curl_errno($curl)){
	echo 'Curl error: ' . curl_error($curl) . "\n";
}

echo 'HTTP Response Status: ' . curl_getinfo($curl,CURLINFO_HTTP_CODE) . "<hr>";

curl_close($curl);

var_dump($curl_response);
exit;

// Example of using SimpleXML to parse xml response
libxml_use_internal_errors(true);
$xml = simplexml_load_string($curl_response);
if (!$xml) {
	echo 'Failed loading XML' . "\n";
	echo $curl_response . "\n";
	foreach(libxml_get_errors() as $error) {
		echo "\t" . $error->message;
	}
} else {
		
	$token = $xml->children('http://www.canadapost.ca/ws/merchant/registration');
	if ( $token ) {
		echo 'Token Id: ' . $token->{'token-id'} . "\n";
	} else {
		$messages = $xml->children('http://www.canadapost.ca/ws/messages');		
		foreach ( $messages as $message ) {
			echo 'Error Code: ' . $message->code . "\n";
			echo 'Error Msg: ' . $message->description . "\n\n";
		}
	}
}

?>
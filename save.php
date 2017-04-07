<?php

$url = $_GET['url'];

if (!isset($_GET['url'])) {
	echo 'Please send URL';
	die;
}
$url = urldecode($url);
if (filter_var($url, FILTER_VALIDATE_URL) === false) {
    echo("Please send valid URL");
}

include_once 'includes/config.php';


if(isset($_SESSION) && isset($_SESSION['token']))
{
  	//echo 'loggedin';
  	$ch = curl_init(API_BASE_URL.'save&user='.$_SESSION['user_id'].'&url='.urlencode($url));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$headers = [
	'token: '.$_SESSION['token'].'',
	];

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($response,true);
	//echo $response;

	echo $result['msg'];

}


?>

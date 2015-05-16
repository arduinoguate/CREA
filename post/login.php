<?php


	$user = "13a3b20fa41aa80636cffe064abd3e07";
	$pass = "ac2097a20d8b989d1b4305bfdc170b4d";

	$fields = array(
		'username' => $_POST['username'],
		'password' => $_POST['password'],
		'scopes' => 'site-visitor'
	);

	//url-ify the data for the POST
	$fields_string = "";
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://crea.arduinogt.com/v1/session");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, "$user:$pass");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,1);
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4'))
	{
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	}

	$output = curl_exec($ch);
	curl_close($ch);

	$response = json_decode($output);

	if ($response->code == 200){
		session_start();
		$_SESSION['token'] = $response->access_token;
		$_SESSION['username'] = $_POST['username'];


	}
	echo json_encode($response,JSON_UNESCAPED_SLASHES);

?>

<?php
	if(empty($_POST['name'])  		||
	   empty($_POST['email']) 		||
	   empty($_POST['user']) 		||
	   empty($_POST['pass'])	||
	   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
	   {
		echo "No arguments Provided!";
		return false;
	   }

	$user = "08232f894d27f90b8e44fba2712608d3";
	$pass = "1c3e2b7608e06d3d84f2a293445bb21b";

	$nufields = array(
		'nombre' => $_POST['name'],
		'email' => $_POST['email'],
		'password' => $_POST['pass'],
		'username' => $_POST['user']
	);

	$fields_string = "";

	$fields = array(
		'scopes' => 'administrator'
	);

	//url-ify the data for the POST
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
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

	$output = curl_exec($ch);
	curl_close($ch);

	$response = json_decode($output);

	if ($response->code == 200){
		$access_token = $response->access_token;

		$headers = array('Authorization: Bearer ' . $access_token);

		//url-ify the data for the POST
		foreach($nufields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://crea.arduinogt.com/v1/user");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_USERPWD, "$user:$pass");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, 1);
		curl_setopt($ch,CURLOPT_POST, count($nufields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

		$output = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($output);

		if ($response->http_code == 200){
			// Create the email and send the message
			$to = $_POST['email']; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
			$email_subject = "Registro CREA";
			$email_body = "Has recibido este correo como confirmacion de registro. \n\nTu usuario es: ".$_POST['user'];
			$headers = "From: info@arduinogt.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
			$headers .= "Reply-To: ".$_POST['email'];
			mail($to,$email_subject,$email_body,$headers);
			echo json_encode($response,JSON_UNESCAPED_SLASHES);
			return true;
		}else
			header("HTTP/1.1 422 Unauthorized");
			echo json_encode($response,JSON_UNESCAPED_SLASHES);
			return false;
	}else{
		header("HTTP/1.1 401 Unauthorized");
		echo json_encode($response,JSON_UNESCAPED_SLASHES);
		return false;
	}


?>

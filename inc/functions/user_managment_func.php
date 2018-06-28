<?php

function sendmail_template_1($email, $userid, $key) {
	$to = $email;
	$subject = "Welcome to Camagru - a small Instagram-like site";
	$body='Your Activation Code is '.$key.' Please Click On This <a href="http://localhost:8080/inc/sign_up.php?id='.$userid.'&code='.$key.'">link</a> to activate your account.';
	$body = wordwrap($body,70);

	// Set preferences for Subject field
	$subject_preferences = array(
		"input-charset" => "utf-8",
		"output-charset" => "utf-8",
		"line-length" => 76,
		"line-break-chars" => "\r\n"
	);

	// Set mail header
	$header = "Content-type: text/html; charset=utf-8" . " \r\n";
	$header .= "From: <noreply@camagru.com>" . "\r\n";
	$header .= "MIME-Version: 1.0 \r\n";
	$header .= "Content-Transfer-Encoding: 8bit \r\n";
	$header .= "Date: ".date("r (T)")." \r\n";
	$header .= iconv_mime_encode("Subject", $subject, $subject_preferences);

	// Send mail
	return (mail($to, $subject, $body, $header));
}

function show_errors($action) {
	$error = false;

	if (!empty($action['result'])) {
		$error = "<ul class=\"alert $action[result]\">"."\n";
		if (is_array($action['text'])) {
			//loop out each error
			foreach ($action['text'] as $text) {
				$error .= "<li><p>$text</p></li>"."\n";
			}
		} else {
			//single error
			$error .= "<li><p>$action[text]</p></li>";
		}
		$error .= "</ul>"."\n";
	}
	var_dump($error);
	return $error;
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>

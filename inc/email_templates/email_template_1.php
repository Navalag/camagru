<?php

function email_template_1($email, $userid, $key) {
	echo "<br>test5<br>";
	$to = $email;
	$subject = "Welcome to Camagru - a small Instagram-like site";
	$body='Your Activation Code is '.$key.' Please Click On This <a href="http://localhost:8080/inc/registration.php?id='.$userid.'&code='.$key.'">link</a> to activate your account.';
	$body = wordwrap($body,70);
	echo "to -> $to <br>subject -> $subject <br>body -> $body <br>";

	// Set preferences for Subject field
	$subject_preferences = array(
		"input-charset" => $encoding,
		"output-charset" => $encoding,
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

?>

<?php

function email_template_1($email, $userid, $key) {
	echo "<br>test5<br>";
	$to = $email;
	$subject = "Activation Code For Camagru - a small Instagram-like site";
	$body='Your Activation Code is '.$key.' Please Click On This <a href="http://localhost:8080/inc/registration.php?id='.$userid.'&code='.$key.'">link</a> to activate your account.';
	$body = wordwrap($body,70);
	echo "to -> $to <br>subject -> $subject <br>body -> $body <br>";

	// Set preferences for Subject field
	$encoding = "utf-8";
	$subject_preferences = array(
		"input-charset" => $encoding,
		"output-charset" => $encoding,
		"line-length" => 76,
		"line-break-chars" => "\r\n"
	);

	// Set mail header
	$header = 'MIME-version: 1.0' . "\r\n";
	$header .= 'Content-Type:text/html;charset=UTF-8' . "\r\n";
	$header .= 'From: noreply@camagru.com' . "\r\n";

	// $header = "Content-type: text/html; charset=".$encoding." \r\n";
	// $header .= "From: <a.galavan@icloud.com>" . "\r\n";
	// $header .= "MIME-Version: 1.0 \r\n";
	// $header .= "Content-Transfer-Encoding: 8bit \r\n";
	// $header .= "Date: ".date("r (T)")." \r\n";
	// $header .= iconv_mime_encode("Subject", $subject, $subject_preferences);

	// Send mail
	return (mail($to, $subject, $body, $header));
}


/* 
** let's send the email
*/
// $message = "Your Activation Code is ".$key."";
// $to=$email;
// $subject="Activation Code For Camagru - a small Instagram-like site allowing you to create and share photo-montages.";
// $from = 'a.galavan@icloud.com';
// $body='Your Activation Code is '.$key.' Please Click On This link <a href="registration.php">registration.php?id='.$userid.'&code='.$key.'</a>to activate your account.';
// $body = wordwrap($body,70);
// $headers = "From:".$from;
// mail($to,$subject,$body,$headers);
// //
// $encoding = "utf-8";

// // Set preferences for Subject field
// $subject_preferences = array(
// 	"input-charset" => $encoding,
// 	"output-charset" => $encoding,
// 	"line-length" => 76,
// 	"line-break-chars" => "\r\n"
// );

// // Set mail header
// $header = "Content-type: text/html; charset=".$encoding." \r\n";
// $header .= "From: ".$from_name." <".$from_mail."> \r\n";
// $header .= "MIME-Version: 1.0 \r\n";
// $header .= "Content-Transfer-Encoding: 8bit \r\n";
// $header .= "Date: ".date("r (T)")." \r\n";
// $header .= iconv_mime_encode("Subject", $mail_subject, $subject_preferences);

// // Send mail
// mail($mail_to, $mail_subject, $mail_message, $header);

?>
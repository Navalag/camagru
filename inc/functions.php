<?php

/* =======================================================
   PAGE CONTENT FUNCTIONS
   ======================================================= */

/*
** PAGE CONTENT FUNCTIONS
*/
function get_item_html($item) {
	$output = "<li><img src='" 
			. $item["src"] . "' alt='" 
			. $item["img_id"] . "' />" 
			. "<button>remove</button>"
			. "</li>";
	return $output;
}
function get_user_photo_array() {
	include("config/connect.php");

	try {
		$result = $conn->prepare(
			"SELECT img_id, src, user_id 
			FROM user_img"
		);
		$result->execute();
		$catalog = $result->setFetchMode(PDO::FETCH_ASSOC);
		$catalog = $result->fetchAll();
	} catch (PDOException $e) {
		echo "Unable to retrieved results";
		exit;
	}
	return $catalog;
}

/* =======================================================
   USER MANAGMENT FUNCTIONS
   ======================================================= */

/*
** Email teamplates
*/
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

function sendmail_template_2($email, $new_password) {
	$to = $email;
	$subject = "[Camagru] Please reset your password";
	$body = "
			We heard that you lost your Camagru password. Sorry about that!

			But don't worry! Here is you new password that was randomly generated.

			------------------------
			Email: ".$email."
			Password: ".$new_password."
			------------------------

			Thanks,
			your friends at Camagru.
			";
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

/*
** Generate a random string, using a cryptographically secure 
** pseudorandom number generator (random_int)
*/
function random_str($length) {
    $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    $keyspaceLength = strlen($keyspace) - 1; //put the length -1 in cache
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $keyspaceLength)];
    }
    return $str;
}

/*
** Filter input form data from damage content
*/
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>

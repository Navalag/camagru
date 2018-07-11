<?php

/* =======================================================
   PAGE CONTENT FUNCTIONS
   ======================================================= */

function get_item_html($item) {
	$output = "<li><img src='" 
			. $item["src"] . "' alt='" 
			. $item["img_id"] . "' />" 
			. "<button>remove</button>"
			. "</li>";
	return $output;
}
function get_div_item_html($item) {
	$output = "<div class='collage col'><img src='" 
			. $item["src"] . "' alt='" 
			. $item["img_id"] . "' />" 
			. "</div>";
	return $output;
}

/*
** create block with like icons to display it on html page
*/
function get_likes_div_html($item) {
	if (!isset($_SESSION)) {
		session_start();
	}
	include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");
	
	$output = "";
	/*
	** check user session to prevent likes display when user
	** is not authorized
	*/
	if (isset($_SESSION['Username'])) {
		$output = "<div class='likes-block'>";
		/*
		** check `likes` table if user ulready like this img or not
		*/
		try {
			$sql = $conn->prepare("SELECT * FROM `likes`
			WHERE `user_id` = $_SESSION[userID] 
			AND `img_id` = $item[img_id] LIMIT 1");
			$sql->execute();
		} catch (Exception $e) {
			echo "Unable to retrieved results";
			exit;
		}
		$result = $sql->setFetchMode(PDO::FETCH_ASSOC);
		$result = $sql->fetchAll();
		$conn = null;
		if (!empty($result)) {
			/*
			** user already likes post
			*/
			$output .= "<i class='far fa-heart liked' id='img"
				. $item['img_id'] . "' onclick='"
				. "like_unlike_photo(" . $item['img_id'] . ")'></i>";
		} else {
			/*
			** user has not yet liked post
			*/
			$output .= "<i class='far fa-heart unliked' id='img"
				. $item['img_id'] . "' onclick='"
				. "like_unlike_photo(" . $item['img_id'] . ")'></i>";
		}
		$output .= "<span id='likes_count".$item['img_id']."'>" 
				. $item['likes']." likes</span></div>";
	}
	return $output;
}

/* =======================================================
   PAGINATION FUNCTIONS
   ======================================================= */

function get_single_user_photo_array($limit = null, $offset = 0) {
	include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");

	try {
		$sql = "SELECT * FROM user_img
				WHERE `user_id` = $_SESSION[userID]
				ORDER BY img_id DESC";
		if (is_integer($limit)) {
			$results = $conn->prepare($sql . " LIMIT ? OFFSET ?");
			$results->bindParam(1,$limit,PDO::PARAM_INT);
			$results->bindParam(2,$offset,PDO::PARAM_INT);
		} else {
			$results = $conn->prepare($sql);
		}
		$results->execute();
	} catch (PDOException $e) {
		echo "Unable to retrieved results";
		exit;
	}
	$gallery = $results->fetchAll();
	return $gallery;
}

function full_photo_gallery_array($limit = null, $offset = 0) {
	include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");

	try {
		$sql = "SELECT * FROM user_img
				ORDER BY img_id DESC";
		if (is_integer($limit)) {
			$results = $conn->prepare($sql . " LIMIT ? OFFSET ?");
			$results->bindParam(1,$limit,PDO::PARAM_INT);
			$results->bindParam(2,$offset,PDO::PARAM_INT);
		} else {
			$results = $conn->prepare($sql);
		}
		$results->execute();
	} catch (Exception $e) {
		 echo "Unable to retrieved results";
		 exit;
	}
	
	$gallery = $results->fetchAll();
	return $gallery;
}

/*
** count photo for pagination purpose on gallery page
*/
function count_all_photo() {
	include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");

	try {
		$result = $conn->prepare(
			"SELECT COUNT(img_id) FROM user_img"
		);
		$result->execute();
		$count = $result->fetchColumn(0);
	} catch (PDOException $e) {
		echo "Unable to retrieved results";
		exit;
	}
	return $count;
}

/*
** count photo for pagination purpose on account page
*/
function count_all_photo_for_user() {
	include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");

	try {
		$result = $conn->prepare(
			"SELECT COUNT(img_id) FROM user_img
			 WHERE `user_id` = $_SESSION[userID]"
		);
		$result->execute();
	} catch (PDOException $e) {
		echo "Unable to retrieved results";
		exit;
	}
	$count = $result->fetchColumn(0);
	return $count;
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

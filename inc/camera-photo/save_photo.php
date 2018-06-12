<?php

	if (!isset($_SESSION)) {
		session_start();
	}
	include('../../config/setup.php');

	/*
	** SAVE PHOTO TO SERVER FOLDER
	*/
	$image_folder = "../../img/uploads/";
	$image_coded = $_POST['img'];
	$img = str_replace('data:image/png;base64,', '', $image_coded);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$name = mktime() . ".png";
	$file = $image_folder . $name;
	file_put_contents($file, $data);
	/*
	** ADD PHOTO LINK TO DATABASE
	*/
	// $user = UserExist($_SESSION['loggued_on_user'], $pdo);
	// $owner_id = $user['uid'];
	$path = "http://localhost:8100/img/uploads/" . $name;
	try {
		$sql = "INSERT INTO `user_img` (`src`, `user_id`) 
					VALUES ('$path', 1)";
		$conn->exec($sql);
		echo "New record created successfully<br>";
		}
	catch(PDOException $e)
		{
		echo $sql . "<br>" . $e->getMessage();
		}
	$conn = null;

	 // $pdo->query("INSERT INTO `images` (`owner_id`, `src`) VALUES
	 //     ('$owner_id', '$path')");
	 // echo $path;

// $pre_im = explode(',', $_POST["img"]);
// $im = imagecreatefromstring(base64_decode($pre_im[1]));

// $image_name = $_SERVER['DOCUMENT_ROOT'] . '/img/'.md5(time().rand()).'.png';


// $target_dir = "../../img/uploads/";
// $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
// $uploadOk = 1;
// $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// // Check if image file is a actual image or fake image
// if(isset($_POST["submit"])) {
//     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//     if($check !== false) {
//         echo "File is an image - " . $check["mime"] . ".";
//         $uploadOk = 1;
//     } else {
//         echo "File is not an image.";
//         $uploadOk = 0;
//     }
// }
// // Check if file already exists
// if (file_exists($target_file)) {
//     echo "Sorry, file already exists.";
//     $uploadOk = 0;
// }
// // Check file size
// if ($_FILES["fileToUpload"]["size"] > 500000) {
//     echo "Sorry, your file is too large.";
//     $uploadOk = 0;
// }
// // Allow certain file formats
// if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
//     echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//     $uploadOk = 0;
// }
// // Check if $uploadOk is set to 0 by an error
// if ($uploadOk == 0) {
//     echo "Sorry, your file was not uploaded.";
// // if everything is ok, try to upload file
// } else {
//     if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
//         echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
//     } else {
//         echo "Sorry, there was an error uploading your file.";
//     }
// }

?>
<?php 

if (!isset($_SESSION)) {
	session_start();
}
include("inc/functions.php");

$pageTitle = "Personal account - Camagru";
$section = null;

include 'inc/header.php';
?>

<div class="container cont-wrap clearfix">
	
	<div class="account_primary col">
		<h2>You Look Grate!</h2>
		
		<div class="camera">
			<video id="video" width="640" height="480" autoplay>Video stream not available.</video>
			<button id="startVideo">Turn on Camera</button>
			<button id="snapPhoto">Take Photo</button>
			<form action="inc/camera-photo/upload_photo.php" method="post" enctype="multipart/form-data">
				Select image to upload:
				<input type="file" name="fileToUpload" id="fileToUpload">
				<input type="submit" value="Upload Image" name="submit">
			</form>
		</div>
		<div class="camera-canvas">
			<canvas id="canvas" width="640" height="480"></canvas>
			<button id="effect">Add Frame</button>
			<button id="save">Save Photo</button>
		</div>

	</div><!--/.primary-->
	
	<div class="account_secondary col">

		<h2>Welcome!</h2>
		<div class="output">
			<ul id="photo">
				<?php
				$user_photo = get_user_photo_array();
				foreach ($user_photo as $item) {
					echo get_item_html($item);
				}
				?>
			</ul>
		</div>

	</div><!--/.secondary-->
	
</div>

<script src="js/camera_handler_2.js"></script>
<!-- <script src="js/edit_photo.js"></script> -->

<?php include 'inc/footer.php'; ?>

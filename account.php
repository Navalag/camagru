<?php 

if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['Username'])) {
	header("location:http://localhost:8080");
}

$pageTitle = "Personal account - Camagru";
$section = "account";

include($_SERVER["DOCUMENT_ROOT"]."/inc/functions.php");

/*
** BEGIN OF PAGINATION SCRIPT
*/

$items_per_page = 3;
$total_items = count_all_photo_for_user();
$total_pages = 1;
$offset = 0;

if (isset($_GET["pg"])) {
	$current_page = filter_input(INPUT_GET,"pg",FILTER_SANITIZE_NUMBER_INT);
}
if (empty($current_page)) {
	$current_page = 1;
}
if ($total_items > 0) {
	$total_pages = ceil($total_items / $items_per_page);

	/*
	** redirect too-large page numbers to the last page
	*/
	if ($current_page > $total_pages) {
		header("location:http://localhost:8080/account.php?pg=".$total_pages);
	}
	/*
	** redirect too-small page numbers to the first page
	*/
	if ($current_page < 1) {
		header("location:http://localhost:8080/account.php?pg=1");
	}

	/*
	** determine the offset (number of items to skip) for the current page
	** for example: on page 3 with 8 item per page, the offset would be 16
	*/
	$offset = ($current_page - 1) * $items_per_page;

	$pagination = "<div class=\"pagination clearfix\">";
	$pagination .= "<a href=\"account.php?pg=1\">&laquo;</a>";
	for ($i = 1; $i <= $total_pages; $i++) {
		if ($i == $current_page) {
			$pagination .= "<a class=\"active\" href=\"#\">$i</a>";
		} else {
			$pagination .= "<a href='account.php?pg=$i'>$i</a>";
		}
	}
	$pagination .= "<a href=\"account.php?pg=$i\">&raquo;</a>";
	$pagination .= "</div>";
	/* end of pagination */
}

include($_SERVER["DOCUMENT_ROOT"].'/inc/header.php');
?>

<div class="container cont-wrap clearfix account-container">
	
	<div class="account_primary col">
		<h2 class="account-page--header">You Look Grate!</h2>
		
		<div class="border-wrapper">
			<div class="camera" id='cameraDiv'>
				<video style="display: none;" id="video" width="640" height="480" autoplay>Video stream not available.</video>
				<canvas id="canvas" width="640" height="480"></canvas>

				<input class="button" id="startVideo" type="button" value="Turn on Camera">

				<form class="upload-file--form" id="upload-form" action="inc/camera-photo/upload_photo.php" method="post" enctype="multipart/form-data">
					Select image to upload:
					<input type="file" name="fileToUpload" id="fileToUpload">
					<input class="button" id="uploadImage" type="button" value="Upload Image">
				</form>
			</div>
		</div>

		<div class="buttons-group clearfix">
			<button class="button-account-1" id="sizeDown" type="button" disabled><i class="fas fa-minus"></i></button>
			<button class="button-account-1" id="sizeUp" type="button" disabled><i class="fas fa-plus"></i></button>
			<button class="button-account-1" id="removeLast" type="button" disabled><i class="fas fa-undo-alt"></i></button>
			<button class="button-account-1" id="snapPhoto" type="button" disabled><i class="fas fa-camera"></i></button>
		</div>
		
		<div class="camera-canvas">
			
			<div class="photo-montages">
				<img class="demo" src="img/effects/frame1.png" onclick="addFilterOnPhoto('img/effects/frame1.png')">
				<img class="demo" src="img/effects/frame2.png" onclick="addFilterOnPhoto('img/effects/frame2.png')">
				<img class="demo" src="img/effects/frame3.png" onclick="addFilterOnPhoto('img/effects/frame3.png')">
				<img class="demo" src="img/effects/frame4.png" onclick="addFilterOnPhoto('img/effects/frame4.png')">
				<img class="demo" src="img/effects/frame5.png" onclick="addFilterOnPhoto('img/effects/frame5.png')">
				<img class="demo" src="img/effects/frame6.png" onclick="addFilterOnPhoto('img/effects/frame6.png')">
				<img class="demo" src="img/effects/frame7.png" onclick="addFilterOnPhoto('img/effects/frame7.png')">
				<img class="demo" src="img/effects/frame8.png" onclick="addFilterOnPhoto('img/effects/frame8.png')">
				<img class="demo" src="img/effects/frame9.png" onclick="addFilterOnPhoto('img/effects/frame9.png')">
				<img class="demo" src="img/effects/frame10.png" onclick="addFilterOnPhoto('img/effects/frame10.png')">
				<img class="demo" src="img/effects/frame11.png" onclick="addFilterOnPhoto('img/effects/frame11.png')">
				<img class="demo" src="img/effects/frame12.png" onclick="addFilterOnPhoto('img/effects/frame12.png')">
				<img class="demo" src="img/effects/frame13.png" onclick="addFilterOnPhoto('img/effects/frame13.png')">
				<img class="demo" src="img/effects/frame14.png" onclick="addFilterOnPhoto('img/effects/frame14.png')">
				<img class="demo" src="img/effects/frame15.png" onclick="addFilterOnPhoto('img/effects/frame15.png')">
				<img class="demo" src="img/effects/frame16.png" onclick="addFilterOnPhoto('img/effects/frame16.png')">
				<img class="demo" src="img/effects/frame18.png" onclick="addFilterOnPhoto('img/effects/frame18.png')">
				<img class="demo" src="img/effects/frame19.png" onclick="addFilterOnPhoto('img/effects/frame19.png')">
				<img class="demo" src="img/effects/frame20.png" onclick="addFilterOnPhoto('img/effects/frame20.png')">
				<img class="demo" src="img/effects/frame21.png" onclick="addFilterOnPhoto('img/effects/frame21.png')">
				<img class="demo" src="img/effects/frame22.png" onclick="addFilterOnPhoto('img/effects/frame22.png')">
				<img class="demo" src="img/effects/frame23.png" onclick="addFilterOnPhoto('img/effects/frame23.png')">
				<img class="demo" src="img/effects/frame24.png" onclick="addFilterOnPhoto('img/effects/frame24.png')">
				<img class="demo" src="img/effects/frame25.png" onclick="addFilterOnPhoto('img/effects/frame25.png')">
				<img class="demo" src="img/effects/frame26.png" onclick="addFilterOnPhoto('img/effects/frame26.png')">
			</div>

			<input class="button" id="save" type="button" value="Save" disabled>

		</div>

	</div><!--/.primary-->
	
	<div class="account_secondary col">

		<h2 class="account-page--header">Enjoy Your Photo!</h2>
		<div class="output">
			<ul id="photo">
				<?php
				$user_photo = get_single_user_photo_array($items_per_page,$offset);
				foreach ($user_photo as $item) {
					echo get_item_html($item, $current_page);
				}
				?>
			</ul>
			<?php 
			if (isset($pagination)) {
				echo $pagination;
			}
			?>
		</div>

	</div><!--/.secondary-->

</div>

<script src="js/camera.js"></script>

<?php include($_SERVER["DOCUMENT_ROOT"].'/inc/footer.php'); ?>

<?php 

if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['Username'])) {
	header("location:http://localhost:8080");
}

$pageTitle = "Personal account - Camagru";
$section = null;

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

	$pagination = "<div class=\"pagination\">";
	$pagination .= "Pages: ";  
	for ($i = 1; $i <= $total_pages; $i++) {
		if ($i == $current_page) {
			$pagination .= " <span>$i</span>";
		} else {
			$pagination .= " <a href='/account.php?pg=$i'>$i</a>";
		}
	}
	$pagination .= "</div>";
	/* end of pagination */
}

include($_SERVER["DOCUMENT_ROOT"].'/inc/header.php');
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
				$user_photo = get_single_user_photo_array($items_per_page,$offset);
				foreach ($user_photo as $item) {
					echo get_item_html($item);
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

<script src="js/camera_handler_2.js"></script>

<?php include($_SERVER["DOCUMENT_ROOT"].'/inc/footer.php'); ?>

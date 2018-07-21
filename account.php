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

<div class="container cont-wrap clearfix account-container">
	
	<div class="account_primary col">
		<h2 class="account-page--header">You Look Grate!</h2>
		
		<div class="camera">
			<video style="display: none;" id="video" width="640" height="480" autoplay>Video stream not available.</video>
			<canvas id="canvas" width="640" height="480"></canvas>

			<input class="button" id="startVideo" type="button" value="Turn on Camera">

			<form class="upload-file--form" action="inc/camera-photo/upload_photo.php" method="post" enctype="multipart/form-data">
				Select image to upload:
				<input type="file" name="fileToUpload" id="fileToUpload">
				<input type="submit" value="Upload Image" name="submit">
			</form>
		</div>
			<input class="button" id="snapPhoto" type="button" value="Take Photo">
		
		<h2 style="text-align:center">Slideshow Gallery</h2>
		<div class="camera-canvas">
			
			<div class="clearfix">
			    <div class="column">
			      <img class="demo cursor" src="img/img_woods.jpg" style="width:100%" onclick="addFilterOnPhoto('img/img_woods.jpg')" alt="The Woods">
			    </div>
			    <div class="column">
			      <img class="demo cursor" src="img/img_5terre.jpg" style="width:100%" onclick="addFilterOnPhoto('img/img_5terre.jpg')" alt="Cinque Terre">
			    </div>
			    <div class="column">
			      <img class="demo cursor" src="img/img_mountains.jpg" style="width:100%" onclick="addFilterOnPhoto('img/img_mountains.jpg')" alt="Mountains and fjords">
			    </div>
			    <div class="column">
			      <img class="demo cursor" src="img/img_lights.jpg" style="width:100%" onclick="addFilterOnPhoto('img/img_lights.jpg')" alt="Northern Lights">
			    </div>
			    <div class="column">
			      <img class="demo cursor" src="img/img_nature.jpg" style="width:100%" onclick="addFilterOnPhoto('img/img_nature.jpg')" alt="Nature and sunrise">
			    </div>    
			    <div class="column">
			      <img class="demo cursor" src="img/img_snow.jpg" style="width:100%" onclick="addFilterOnPhoto('img/img_snow.jpg')" alt="Snowy Mountains">
			    </div>
			</div>

			<input class="button" id="effect" type="button" value="Add Frame">
			<input class="button" id="save" type="button" value="Save Photo">

		</div>

	</div><!--/.primary-->
	
	<div class="account_secondary col">

		<h2 class="account-page--header">Enjoy Your Photo!</h2>
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

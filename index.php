<?php 

if (!isset($_SESSION)) {
	session_start();
}
include($_SERVER["DOCUMENT_ROOT"]."/config/setup.php");
include($_SERVER["DOCUMENT_ROOT"]."/inc/functions.php");

$pageTitle = "Camagru - small Instagram-like site allowing you to create and share photo-montages";
$section = "landing_page";

/*
** BEGIN OF PAGINATION SCRIPT
*/

$items_per_page = 5;
$total_items = count_all_photo();
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
		header("location:http://localhost:8080?pg=".$total_pages);
	}
	/*
	** redirect too-small page numbers to the first page
	*/
	if ($current_page < 1) {
		header("location:http://localhost:8080?pg=1");
	}

	/*
	** determine the offset (number of items to skip)
	** for the current page
	** for example: on page 3 with 8 item per page,
	** the offset would be 16
	*/
	$offset = ($current_page - 1) * $items_per_page;

	$pagination = "<div class=\"pagination\">";
	$pagination .= "Pages: ";
	for ($i = 1; $i <= $total_pages; $i++) {
		if ($i == $current_page) {
			$pagination .= " <span>$i</span>";
		} else {
			$pagination .= " <a href='index.php?pg=$i'>$i</a>";
		}
	}
	$pagination .= "</div>";
	/* end of pagination */
}

include($_SERVER["DOCUMENT_ROOT"].'/inc/header.php');
?>

<?php if (!isset($_SESSION['Username'])) { ?>

<div class="container clearfix">
	<div class="primary col">

		<h1 class="headline">Welcome! <i class="emoji-header em em-sunny"></i></h1>
		<h2 class="tagline">Camagru is a small Instagram-like <i class="em em-camera"></i> site allowing you to create and share photo-montages.</h2>
		<h2 class="tagline">Simply make photo, add funny effects and leave your footprint <i class="em em-feet"></i> in history.</h2>
		<h2>Enjoy!! <i class="emoji-header em em-yellow_heart"></i> <i class="emoji-header em em-star-struck"></i></h2>

	</div>

	<div class="secondary col clearfix">

		<form class="form-container form-main-page" method="post" action="/inc/sign_in.php">

			<h1>Quick Sign In</h1>

			<label for="name">Username:</label>
			<input type="text" id="name" name="username">

			<label for="password">Password:</label>
			<input type="password" id="password" name="password">

			<input class="button" type="submit" value="Sign In">

		</form>

	</div><!-- /.secondary .col -->
</div><!--/.container-->

<?php } ?>

<div class="container clearfix">

	<!-- display posts, likes and comments gotten from the database  -->
	<ul class="img-container">
		<?php
		$catalog = full_photo_gallery_array($items_per_page,$offset);
		foreach ($catalog as $item) {
		?>
		<li class='collage col'> <?php 
			/*
			** display all photo from database
			*/
			echo get_div_item_html($item); 
			/*
			** if user is registered - display likes and comments
			*/
			echo get_likes_div_html($item);
			echo get_comments_block_html($item); ?>
		</li>
		<?php 
		}
		if (isset($pagination)) {
			echo $pagination;
		}
		?>
	</ul>
	
</div>

<script src="js/comments_likes.js"></script>

<?php include($_SERVER["DOCUMENT_ROOT"].'/inc/footer.php'); ?>

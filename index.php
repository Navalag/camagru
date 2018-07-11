<?php 

if (!isset($_SESSION)) {
	session_start();
}
include($_SERVER["DOCUMENT_ROOT"]."/config/setup.php");
include($_SERVER["DOCUMENT_ROOT"]."/inc/functions.php");

$pageTitle = "Camagru - small Instagram-like site allowing you to create and share photo-montages";
$section = "landing_page";
$items_per_page = 8;

if (isset($_GET["pg"])) {
	$current_page = filter_input(INPUT_GET,"pg",FILTER_SANITIZE_NUMBER_INT);
}
if (empty($current_page)) {
	$current_page = 1;
}

$total_items = count_all_photo();
$total_pages = 1;
$offset = 0;
if ($total_items > 0) {
	$total_pages = ceil($total_items / $items_per_page);

	// redirect too-large page numbers to the last page
	if ($current_page > $total_pages) {
		header("location:http://localhost:8080?pg=".$total_pages);
	}
	// redirect too-small page numbers to the first page
	if ($current_page < 1) {
		header("location:http://localhost:8080?pg=1");
	}

	//determine the offset (number of items to skip) for the current page
	//for example: on page 3 with 8 item per page, the offset would be 16
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
}

include($_SERVER["DOCUMENT_ROOT"].'/inc/header.php');
?>

<div class="banner">
	<i class="logo fas fa-camera-retro"></i>
	<!-- <img class="logo" src="img/camera-retro.svg" alt="City"> -->
	<h1 class="headline">Camagru</h1>
	<span class="tagline">A small Instagram-like site allowing you to create and share photo-montages.</span>
</div><!--/.banner-->

<div class="container clearfix">
	<div class="secondary col">
		<h2>Welcome!</h2>
		<p>Cupcake ipsum dolor sit.</p>
		<p>Cupcake ipsum dolor sit. Amet chocolate cake gummies jelly beans candy bonbon brownie candy. Gingerbread powder muffin. Icing cotton candy. Croissant icing pie ice cream brownie I love cheesecake cookie. Pastry chocolate pastry jelly croissant.</p>
		<p>Cake sesame snaps sweet tart candy canes tiramisu I love oat cake chocolate bar. Jelly beans pastry brownie sugar plum pastry bear claw tiramisu tootsie roll. Tootsie roll wafer I love chocolate donuts.</p>
	</div><!--/.secondary-->
	
	<div class="primary col">
		<h2>Photo Example</h2>
		<img class="feat-img" src="img/treats.svg" alt="Drinks and eats">
		<p>Croissant macaroon pie brownie. Cookie marshmallow liquorice gingerbread caramels toffee I love chocolate. Wafer lollipop dessert. Bonbon jelly beans pudding dessert sugar plum. Marzipan toffee drag&#233;e chocolate bar candy toffee pudding I love. Gummi bears pie gingerbread lollipop.</p>
	</div><!--/.primary-->
	
	<div class="tertiary col">
		<h2>Some Important Facts</h2>
		<p><strong>Plane: </strong>Tiramisu caramels gummies chupa chups lollipop muffin. Jujubes chocolate caramels cheesecake brownie lollipop drag&#233;e cheesecake.</p>
		<p><strong>Train: </strong>Pie apple pie pudding I love wafer toffee liquorice sesame snaps lemon drops. Lollipop gummi bears dessert muffin I love fruitcake toffee pie.</p>
		<p><strong>Car: </strong>Jelly cotton candy bonbon jelly-o jelly-o I love. I love sugar plum chocolate cake pie I love pastry liquorice.</p>
	</div><!--/.tertiary-->	
</div>

<div class="container clearfix">

	<table>
		<tr>
			<td></td>
			<td>Name:</td>
		</tr>
		<tr>
			<td></td>
			<td><input type="text" id="name_entered"/></td>
		</tr>
		<tr>
			<td></td>
			<td>Comment:</td>
		</tr>
		<tr>
			<td></td>
			<td><textarea cols="35" rows="6" id="comment_entered"></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Comment" onclick="submitComment()" /></td>
		</tr>
	</table>

	<br><br>
	<div id="showcomments"></div>

	<!-- display posts, likes and comments gotten from the database  -->
	<?php
	$catalog = full_photo_gallery_array($items_per_page,$offset);
	foreach ($catalog as $item) {
		/*
		** display all photo from database
		*/
		echo get_div_item_html($item); 
		/*
		** if user is registered - display likes and comments
		*/
		echo get_likes_div_html($item);
	}
	if (isset($pagination)) {
		echo $pagination;
	}
	?>
	
</div>

<script src="js/comments_likes.js"></script>

<?php include($_SERVER["DOCUMENT_ROOT"].'/inc/footer.php'); ?>

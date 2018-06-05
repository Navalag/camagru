<?php 

session_start();
include("inc/functions.php");

$pageTitle = "Personal account - Camagru";
$section = null;

include 'inc/header.php';
?>

<div class="container cont-wrap clearfix">
	
	<div class="account_primary col">
		<h2>You Look Grate!</h2>
		<video id="video" width="640" height="480" autoplay></video>
		<button id="snap">Snap Photo</button>
		<canvas id="canvas" width="640" height="480"></canvas>
	</div><!--/.primary-->
	

	<div class="account_secondary col">
		<h2>Welcome!</h2>
		<p>Cupcake ipsum dolor sit.</p>
		<p>Cupcake ipsum dolor sit. Amet chocolate cake gummies jelly beans candy bonbon brownie candy. Gingerbread powder muffin. Icing cotton candy. Croissant icing pie ice cream brownie I love cheesecake cookie. Pastry chocolate pastry jelly croissant.</p>
		<p>Cake sesame snaps sweet tart candy canes tiramisu I love oat cake chocolate bar. Jelly beans pastry brownie sugar plum pastry bear claw tiramisu tootsie roll. Tootsie roll wafer I love chocolate donuts.</p>
		<p id="pngHolder"></p>
	</div><!--/.secondary-->
	
</div>

<script src="js/photo.js"></script>

<?php include 'inc/footer.php'; ?>

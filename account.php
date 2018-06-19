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
			<!-- <img id="photo" alt="The screen capture will appear in this box."> -->
			<ul id="photo"></ul>
		</div>

	</div><!--/.secondary-->
	
</div>

<script src="js/camera_handler_2.js"></script>
<script src="js/edit_photo.js"></script>

<?php include 'inc/footer.php'; ?>



<!-- <body>
	<div class="wrapper">
		<header>
			<h1>RSVP</h1>
			<p>A Treehouse App</p>
			<form id="registrar">
				<input type="text" name="name" placeholder="Invite Someone">
				<button type="submit" name="submit" value="submit">Submit</button>
			</form>
		</header>
		
		<div class="main">	
			<h2>Invitees</h2>
			<ul id="invitedList"></ul>	
		</div>
	</div>
	<script type="text/javascript" src="app.js"></script>
</body>
</html> -->

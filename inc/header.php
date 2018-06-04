<?php 
session_start();

?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $pageTitle; ?></title>
	<link href='https://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	
	<header class="main-header <?php if ($section != "landing_page") { echo "dark-header"; }?>">
		<div class="container clearfix">

			<?php if ($section == "landing_page") { ?>
				<h1 class='name'><a href='/'>Camagru</a></h1>

				<ul class="main-nav">
					<li><a href="inc/sign_in.php">Sign In</a></li>
					<li><a href="inc/sign_up.php">Sign Up</a></li>
				</ul>
			<?php } else { ?>
				<h1 class='name'><a href='/'><i class='fas fa-camera-retro'></i> camagru</a></h1>
				<ul class="main-nav">
					<li><a href="#">Gallery</a></li>
					<li><a href="#">My Profile</a></li>
					<li><a href="#">Settings</a></li>
					<li><a href="#">Sign out</a></li>
				</ul>
			<?php } ?>

		</div>
	</header><!--/.main-header-->

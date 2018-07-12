<?php 
if (!isset($_SESSION)) {
	session_start();
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $pageTitle; ?></title>
	<link href='https://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
	<link rel="stylesheet" href="/css/normalize.css">
	<link rel="stylesheet" href="/css/new_style.css">
	<!-- <link rel="stylesheet" href="/css/style.css"> -->
</head>
<body>
	
	<header class="main-header">
		<div class="container clearfix">
				
			<h1 class='name'><a href='/'><i class='fas fa-camera-retro'></i> camagru</a></h1>

		<?php if (!isset($_SESSION['Username'])) { ?>

			<ul class="main-nav">
				<li><a href="/inc/sign_in.php">Sign In</a></li>
				<li><a href="/inc/sign_up.php">Sign Up</a></li>
			</ul>

		<?php } else { ?>
				
			<ul class="main-nav">
				<li><a href="/">Gallery</a></li>
				<li><a href="/account.php">My Profile</a></li>
				<li><a href="/settings.php">Settings</a></li>
				<li><a href="/inc/sign_out.php">Sign out</a></li>
			</ul>

		<?php } ?>

		</div>
	</header><!--/.main-header-->

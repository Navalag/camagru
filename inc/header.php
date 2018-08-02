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
	<link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
	<link rel="stylesheet" href="/css/normalize.css">
	<link rel="stylesheet" href="/css/style.css">
</head>
<body>

	<header class="main-header">
		<div class="container <?php if ($section == "account") { echo "header-wrap "; } ?>clearfix">
				
			<h1 class='name'><a class='logo' href='/'><i class='fas fa-camera-retro'></i> camagru</a></h1>

		<?php if (!isset($_SESSION['Username'])) { ?>

			<ul class="main-nav">
				<li><a class="nav-link <?php if ($section == "sign_in") { echo " on"; } ?>" href="/inc/sign_in.php">Sign In</a></li>
				<li><a class="nav-link <?php if ($section == "sign_up") { echo " on"; } ?>" href="/inc/sign_up.php">Sign Up</a></li>
			</ul>

		<?php } else { ?>
				
			<ul class="main-nav">
				<li><a class="nav-link <?php if ($section == "landing_page") { echo " on"; } ?>" href="/">Gallery</a></li>
				<li><a class="nav-link <?php if ($section == "account") { echo " on"; } ?>" href="/account.php">My Profile</a></li>
				<li><a class="nav-link <?php if ($section == "settings") { echo " on"; } ?>" href="/settings.php">Settings</a></li>
				<li><a class="nav-link" href="/inc/sign_out.php">Sign out</a></li>
			</ul>

		<?php } ?>

		</div>
	</header><!--/.main-header-->

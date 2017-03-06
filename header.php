<?php if (!isset($_SESSION["login"]))
{
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/header.css">
	</head>
	<div class="header">
		<h1><a class="title" href="index.php">Camagru</a></h1>
		<p class="topbar"><br /><br /><br /><a href="gallery.php">Gallery</a> | <a href="register.php">Register</a> | <a href="resetpw.php">Reset Password</a></p>
		<div class="login">
			<form action="login.php" method="post">
				Login : <br />
				<input type="text" name="login"> <br />
				Password : <br />
				<input type="password" name="pwd"><br />
				<input class="button" type="submit">
			</form>
		</div>
	</div>
</html>

<?php 

}

else
{

?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/header.css">
	 </head>
	<div class="header">
		<h1><a class="title" href="index.php">Camagru</a></h1>
		<p class="topbar"><br /><br /><br /><a href="create.php">Create</a> | <a href="gallery.php">Gallery</a> | <a href="mygallery.php">My Gallery</a></p>
		<div class="login">
			<?php print $_SESSION["login"]; ?><br /><br />
			<a href="accountsettings.php">Account Settings</a><br />
			<a href="disconnect.php">Log out</a><br />
		</div>
	</div>
</html>

<?php
}
?>
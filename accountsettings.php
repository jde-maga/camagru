<?php
	include 'config/database.php';
	if (!isset($_SESSION["login"]))
		header('Location: index.php'); 
?>

<?php
	if (isset($_POST["delacc"]))
	{
		$sql = $dbh->prepare("	DELETE FROM users 		WHERE login=?;
							 	DELETE FROM gallery		WHERE login=?;
							 	DELETE FROM comments	WHERE login=?");
		$sql->execute(array($_SESSION["login"], $_SESSION["login"], $_SESSION["login"]));
		unset($_SESSION["login"]);
		header('Location: index.php');
	}
?>

<?php
	if ($_POST)
	{
		if (isset($_POST["newpwd"]))
		{
			if (strlen($_POST["pwd"]) < 8)
				$error = "Password must be between 8 and 32 characters.";
			else
			{
				$pwd = hash("sha256", $_POST["pwd"]);
				$sql = $dbh->prepare("UPDATE users SET pwd=? WHERE login=?");
				$sql->execute(array($pwd, $_SESSION["login"]));
				$success = 1;
			}
		}
		if (isset($_POST["newmail"]))
		{
			if (!filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL))
				$error = "Invalid email.";
			else if ($dbh->query("select mail from users where mail=\"".$_POST["mail"]."\";")->fetch())
				$error = "Mail already taken.";
			else
			{
				$sql = $dbh->prepare("UPDATE users SET mail=? WHERE login=?");
				$sql->execute(array($_POST["mail"], $_SESSION["login"]));
				$success = 1;
			}
		}
	}
?>

<?php 
	include 'header.php';
	include 'footer.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Account Settings - Camagru</title>
	</head>
	<body>
	<div class="body">
		<form method="post">
			<?php if ($error) print $error; if ($success) print "Success !";?><br /><br />
			New password :  
			<input type="password" name="pwd">
			<input type="submit" name="newpwd" value="Change Password"><br /><br />
			New email :  
			<input type="text" name="mail">
			<input type="submit" name="newmail" value="Change mail"><br /><br /><br />
			Delete account :
			<input type="submit" name="delacc" value="Delete Account">
			</form><br />
	</div>
	</body>
</html>

<!-- OK/Sanitized -->
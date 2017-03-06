<?php
	include 'config/database.php';
	
	if (isset($_SESSION["login"]))
		header('Location: index.php'); 
?>

<?php
	$error = NULL;
	$success = NULL;

	if ($_POST)
	{
		if (isset($_SESSION["login"]))
			header('Location: index.php');
		if (!$_POST["login"] || !$_POST["pwd"])
			$error = "Fill all the fields !<br />";
		else if (ctype_alpha($_POST["login"]) == FALSE)
			$error = "Username can only be letters.<br />";
		else if (strlen($_POST["login"]) > 16)
			$error = "Username must be 16 characters max.<br />";
		else if (strlen($_POST["pwd"]) < 8)
			$error = "Password must be between 8 and 32 characters.";
		else if (!filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL))
			$error = "Invalid email.";
		else if ($dbh->query("select login from users where login=\"".$_POST["login"]."\";")->fetch())
			$error = "Username already taken.";
		else if ($dbh->query("select mail from users where mail=\"".$_POST["mail"]."\";")->fetch())
			$error = "Mail already taken.";
		else
		{
			$pwd = hash("sha256", $_POST["pwd"]);
			$sql = $dbh->prepare("INSERT INTO users (login, pwd, mail) VALUES (?, ?, ?)");
			$sql->execute(array($_POST["login"], $pwd, $_POST["mail"]));
			mail($_POST["mail"], "Confirm your registration to Camagru", "Follow the link to complete your registration :\nlocalhost:8080/camagru/confirm.php?login=".$_POST["login"]."&key=".$pwd);
			$success = 1;
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
		<title>Register - Camagru</title>
	</head>
	<body>
		<div class="body">
		<?php 
			if ($success)
				print "Successfully registered to Camagru. Check your mails for further information.";
			else if (isset($_SESSION["login"]))
				print "You are already registered and logged in to Camagru !";
			else
			{
		?>
			<form method="post">
				<?php if ($error) print $error; ?>
				<h1>Register to Camagru :</h1><br />
				Login : <br />
				<input type="text" name="login" value="<?php if ($_POST) print $_POST["login"];?>"><br />
				Password : <br />
				<input type="password" name="pwd"><br />
				Email : <br />
				<input type ="text" name="mail" value = "<?php if ($_POST) print $_POST["mail"]?>"><br /><br />
				<input type="submit">
			</form>
		<?php } ?>
		</div>
	</body>
</html>

<!-- Sanitized/OK -->
<?php
	include 'config/database.php';
	if (isset($_SESSION["login"]))
		header('Location: index.php'); 
?>

<?php
	$error = NULL;
	if ($_POST)
	{
		$sql = $dbh->prepare("SELECT * FROM users WHERE login=?");
		$sql->execute(array($_POST["login"]));
		$user = $sql->fetch(PDO::FETCH_ASSOC);
		if (!$user)
			$error = "Username not found.";
		else if ($user["pwd"] != hash("sha256", $_POST["pwd"]))
			$error = "Invalid Password.";
		else if (!$user["active"])
			$error = "Account not activated.";
		else
			$_SESSION['login'] = $_POST["login"];
	}
?>

<?php
	include 'header.php';
	include 'footer.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Login - Camagru</title>
	</head>
	<body>
		<div class="body">
			<?php 
				if ($error)
					print $error;
				else if (!isset($_SESSION["login"]))
					print "Log in using the fields above.";
				else
					print "Successfully logged in.";
			?>
		</div>
	</body>
</html>

<!-- OK/Sanitized -->
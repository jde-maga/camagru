<?php
	include 'config/database.php';
	
	if (isset($_SESSION["login"]))
		header('Location: index.php'); 
?>

<?php
	if ($_POST)
	{
		$sql = $dbh->prepare("SELECT mail FROM users WHERE mail=?");
		$sql->execute(array($_POST["mail"]));
		$mail = $sql->fetch(PDO::FETCH_COLUMN);
		if ($mail)
			mail($mail, "Camagru Password Reset", "To reset password, enter the link below :\nlocalhost:8080/camagru/resetpw.php?key=".hash("sha256", $mail));
		else
			$error = "Mail not found !<br /><br />";
	}

?>

<?php
	include 'header.php';
	include 'footer.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Reset PW - Camagru</title>
	</head>
	<body>
	<div class ="body">
		<?php 
			if (isset($_SESSION["login"]))
				print "You're already connected.";
			else if (isset($_GET["key"]))
			{
				$sql = $dbh->prepare("SELECT mail FROM users");
				$sql->execute();
				foreach($sql->fetchAll(PDO::FETCH_COLUMN) as $mail)
				{
					if (hash("sha256", $mail) == $_GET["key"])
					{
						if (isset($_POST["pwd"]))
						{
							if (strlen($_POST["pwd"]) < 8)
								$errorpw = "Password must be between 8 and 32 characters.";
							else
							{
								$successpw = 1;
								$sql = $dbh->prepare("UPDATE users SET pwd=? WHERE mail=?");
								$sql->execute(array(hash("sha256", $_POST["pwd"]), $mail));
								print "Success. Password Changed.";
							}
						}
					if ($successpw != 1)
					{ ?>
			<form method="post">
				<?php if ($errorpw) print $errorpw."<br /><br />"; ?>
				Entrez un nouveau mot de passe :
				<input type="password" name="pwd">
				<input type="submit">
			</form>
				<?php }
					}
				}
			}
			else if ($success)
				print "Success. Follow instructions on your mail to reset your password.";
			else {
		?>
			<form method="post">
				<?php if ($error) print $error; ?>
				Entrez votre mail :
				<input type="text" name="mail">
				<input type="submit">
			</form>
		<?php } ?>
	</div>
	</body>
</html>

<!-- Sanitized/OK -->
<?php
	include 'config/database.php';
	if (isset($_SESSION["login"]))
		header('Location: index.php'); 
?>

<?
	include 'header.php';
	include 'footer.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Confirm - Camagru</title>
	</head>
	<body>
	<div class="body">
		<?php
			if ($_SESSION["login"])
				print "Already connected !";
			else if (!$_GET)
				print "Error.";
			else if (!isset($_GET["login"]))
				print "Error.";
			else if (!isset($_GET["key"]))
				print "Error.";
			else
			{
				$sql = $dbh->prepare("SELECT * FROM users WHERE login=?");
				$sql->execute(array($_GET["login"]));
				$confirm = $sql->fetch(PDO::FETCH_ASSOC);

				if ($confirm && $confirm["pwd"] == $_GET["key"] && !$confirm["active"])
				{
					$sql = $dbh->prepare("UPDATE users SET active=TRUE WHERE login=?");
					$sql->execute(array($confirm["login"]));
					print "Success. Account Activated. Login anytime now.";
				}
				else
					print "Error.";
			}
		?>
	</body>
</html>

<!-- Sanitized/OK -->
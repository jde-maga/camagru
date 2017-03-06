<?php
	include 'config/database.php';
	
	if ($_POST)
	{
		if (!isset($_SESSION["login"]))
			header('Location: index.php'); 

		$sql = $dbh->prepare("SELECT image from gallery WHERE image=?");
		$sql->execute(array($_POST["img"]));
		if (!$sql->rowCount())
			$imgerror = "Invalid Image selected !";
		else if ($_POST["Like"] == "Like")
		{
			$sql = $dbh->prepare("SELECT imglike FROM comments WHERE login=? AND imglike=1 AND image=?");
			$sql->execute(array($_SESSION["login"], $_POST["img"]));
			if ($sql->rowCount())
				{
					$sql = $dbh->prepare("DELETE FROM comments WHERE login=? AND imglike=1 AND image=?");
					$sql->execute(array($_SESSION["login"], $_POST["img"]));
					$sql = $dbh->prepare("UPDATE gallery SET imglike=imglike-1 WHERE image=?");
					$sql->execute(array($_POST["img"]));
				}
			else
			{
				$sql = $dbh->prepare("INSERT INTO comments (login, imglike, image) VALUES (?, TRUE, ?)");
				$sql->execute(array($_SESSION["login"], $_POST["img"]));
				$sql = $dbh->prepare("UPDATE gallery SET imglike=imglike+1 WHERE image=?");
				$sql->execute(array($_POST["img"]));
			}
		}
		else if ($_POST["comment"])
		{
			$sql = $dbh->prepare("INSERT INTO comments (login, comment, image) VALUES (?, ?, ?)");
			$sql->execute(array($_SESSION["login"], $_POST["comment"], $_POST["img"]));

			$sql = $dbh->prepare("SELECT login FROM gallery WHERE image=?");
			$sql->execute(array($_POST["img"]));
			$login = $sql->fetch(PDO::FETCH_COLUMN);
			
			$sql = $dbh->prepare("SELECT mail FROM users WHERE login=?");
			$sql->execute(array($login));
			$mail = $sql->fetch(PDO::FETCH_COLUMN);

			mail($mail, "New comment in Camagru", "Hi, user ".$_SESSION["login"]." sent a comment on your photo :\n\n".$_POST["comment"]);
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
		<title>Display - Camagru</title>
		<link rel="stylesheet" type="text/css" href="css/display.css">
	</head>
	<body>
		<div class="body">
		<div class="picture">
			<?php
			if ($imgerror)
				print $imgerror;
			else if (!isset($_GET["img"]))
				print "No image selected.";
			else
			{
				$sql = $dbh->prepare("SELECT image FROM gallery WHERE image=?");
				$sql->execute(array($_GET["img"]));
				if (!$sql->rowCount())
					print "Invalid image selected.";
				else
				{
					print "<img src=\"gallery/".$_GET["img"].".png\">";
					$sql = $dbh->prepare("SELECT login FROM gallery WHERE image=?");
					$sql->execute(array($_GET["img"]));
					$login = $sql->fetch(PDO::FETCH_COLUMN);
					print "<p><br />From user ".$login."</p>";
					$success = 1;
				}
			}
			?>
		</div>
		<?php if ($success) { ?>
		<div class="comments">
			<?php	if ($_SESSION["login"]) {	?>
			<form method="post">
				Comment here :<br/>
				<input type="text" name="comment">
				<input type="hidden" name="img" value="<?php print $_GET["img"];?>">
				<input type="submit">
				<input type="submit" value="Like" name="Like">
			</form><br/>
			<?php } 
				else
					print "You must be connected to submit comments.<br /><br />"; 
			?>
			Comments :<br /><br />
			<?php 
				$sql = $dbh->prepare("SELECT * FROM comments WHERE image=? ORDER BY cmtdate DESC");
				$sql->execute(array($_GET["img"]));
				foreach ($sql->fetchAll(PDO::FETCH_ASSOC) as $row)
				{
					if ($row["imglike"])
						print "<p>On ".$row["cmtdate"]." :<br />User ".$row["login"]." liked the picture.</p>";
					else
						print "<p>User ".$row["login"]." commented on ".$row["cmtdate"]." :<br />".htmlspecialchars($row["comment"])."</p>";
					print "<br />";
				}
			}
			?>
			</div>
		</div>
	</body>
</html>

<!-- Sanitized/OK -->





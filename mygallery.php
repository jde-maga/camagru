<?php
	include 'config/database.php';
	if (!isset($_SESSION["login"]))
		header('Location: index.php'); 
?>

<?php
	if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0)
		$offset = $_GET["page"] - 1;
	else
		$offset = 0;

	if ($_POST["delpic"])
	{
		$sql = $dbh->prepare("SELECT image FROM gallery WHERE image=? AND login=?");
		$sql->execute(array($_POST["picid"], $_SESSION["login"]));
		if ($sql->fetch(PDO::FETCH_COLUMN))
		{
			$sql = $dbh->prepare("DELETE FROM gallery WHERE image=?");
			$sql->execute(array($_POST["picid"]));
			$sql = $dbh->prepare("DELETE FROM comments WHERE image=?");
			$sql->execute(array($_POST["picid"]));
		}
	}
	else if ($_POST["rename"])
	{
		$sql = $dbh->prepare("SELECT image FROM gallery WHERE image=? AND login=?");
		$sql->execute(array($_POST["picid"], $_SESSION["login"]));
		if ($sql->fetch(PDO::FETCH_COLUMN))
		{
			$sql = $dbh->prepare("UPDATE gallery SET name=? WHERE image=?");
			$sql->execute(array($_POST["rename"], $_POST["picid"]));
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
		<title>Gallery - Camagru</title>
		<link rel="stylesheet" type="text/css" href="css/gallery.css">
	</head>
	<body>
		<div class="body">
			<div class="gallery">
				<?php 
					if (!isset($_SESSION["login"]))
						print "You must be connected to access your gallery.";
					else
					{
					$sql = $dbh->prepare("SELECT image FROM gallery WHERE login=? ORDER BY id DESC LIMIT 3 OFFSET ".$offset*3);
					$sql->execute(array($_SESSION["login"]));
					$gallery = $sql->fetchAll(PDO::FETCH_COLUMN);
					$sql = $dbh->prepare("SELECT * FROM gallery WHERE image=?");
					foreach ($gallery as $image)
					{
						$sql->execute(array($image));
						$imginfo= $sql->fetchAll(PDO::FETCH_ASSOC)[0];
						print "	<div class=\"mypics\">
									<p>".htmlspecialchars($imginfo["name"])." | ".$imginfo["imglike"]." Likes</p>
									<a href=display.php?img=".$image."><img src=\"gallery/".$image.".png\" width=320 height=240></a><br />
									<button type=\"button\" value=\"".$image."\" onclick=\"delpic(this.value);\">Delete</button>
									<button type=\"button\" value=\"".$image."\" onclick=\"namepic(this.value);\">Rename</button>
								</div>";
					}
				?>
			</div>
			<div class="pages">
				<?php
					if ($offset > 0)
						print "<a href=\"mygallery.php?page=".$offset."\">Page précédente</a> - ";
					print "Page ".($offset + 1);
					if (count($gallery) == 3)
						print " - <a href=\"mygallery.php?page=".($offset + 2)."\">Page suivante</a>";
				?>
			</div>
		</div>
		<form id="editpicform" method="post">
			<input type="hidden" name="picid">
			<input type="hidden" name="delpic">
			<input type="hidden" name="rename">
		</form>

	<script>
		function delpic(pic)
		{
			document.getElementsByName("picid")[0].value= pic;
			document.getElementsByName("delpic")[0].value = "OK";
			document.getElementById("editpicform").submit();
		}
		function namepic(pic)
		{
			var name = prompt("Enter the picture's name :");
			if (name)
			{
				if (!name.match(/^([a-z\(\)]+)$/i))
					window.alert("Name must contain only letters");
				else if (name.length > 16)
					window.alert("Name must be less than 16 char.");
				else
				{
					document.getElementsByName("picid")[0].value= pic;
					document.getElementsByName("rename")[0].value = name;
					document.getElementById("editpicform").submit();
				}
			}
		}
	</script>
	<?php } ?>
	</body>
</html>

<!-- Sanitized/OK -->
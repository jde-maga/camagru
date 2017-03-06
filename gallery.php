<?php
	include 'config/database.php';

	if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0)
		$offset = $_GET["page"] - 1;
	else
		$offset = 0;
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
			<div class="sorting">
				Sort by : <a href="gallery.php?sort=date">Date of Creation</a> | <a href="gallery.php?sort=vote">Likes</a>
			</div>
			<br /><br />
			<div class="gallery">
				<?php 
					if (isset($_GET["sort"]) && $_GET["sort"] == "vote")
						$sql = $dbh->prepare("SELECT image FROM gallery ORDER BY imglike DESC LIMIT 3 OFFSET ".$offset*3);
					else
						$sql = $dbh->prepare("SELECT image FROM gallery ORDER BY id DESC LIMIT 3 OFFSET ".$offset*3);
					$sql->execute();
					$gallery = $sql->fetchAll(PDO::FETCH_COLUMN);
					$sql = $dbh->prepare("SELECT * FROM gallery WHERE image=?");
					foreach ($gallery as $image)
					{
						$sql->execute(array($image));
						$imginfo= $sql->fetchAll(PDO::FETCH_ASSOC)[0];
						print "<div class=\"imgwrap\">
								<p>".htmlspecialchars($imginfo["name"])." by ".$imginfo["login"]." | ".$imginfo["imglike"]." Likes</p>
								<a href=display.php?img=".$image."><img src=\"gallery/".$image.".png\" width=320 height=240></a>
								</div>";
					}
				?>
			</div>
			<div class="pages">
				<?php
					if ($offset > 0)
						print "<a href=\"gallery.php?page=".$offset."\">Page précédente</a> - ";
					print "Page ".($offset + 1);
					if (count($gallery) == 3)
						print " - <a href=\"gallery.php?page=".($offset + 2)."\">Page suivante</a>";
				?>
			</div>
		</div>
	</body>
</html>

<!-- Sanitized/OK -->
<?php
	include 'config/database.php';
	include 'header.php';
	include 'footer.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/index.css">
		<title>Camagru</title>
	</head>
	<body>
		<div class="body">
			<?php  	
				if (!isset($_SESSION["login"]))
					print "Welcome to Camagru. Start by logging in, by registering, or browe the gallery.";
				else
					print "Welcome to Camagru. Start creating your picture or browse the gallery.";
				?>
		<div class="allcomments">
			<br /><br />Latest Comments:<br /><br />
			<?php 
				$sql = $dbh->prepare("SELECT * FROM comments ORDER BY id DESC LIMIT 10");
				$sql->execute();
				foreach ($sql->fetchAll(PDO::FETCH_ASSOC) as $comment)
				{
					$name = $dbh->prepare("SELECT name FROM gallery WHERE image=?");
					$name->execute(array($comment["image"]));
					$name = $name->fetch(PDO::FETCH_COLUMN);
					if ($comment["imglike"])
						print $comment["cmtdate"]."<br/>User ".$comment["login"]." liked picture "."<a href=\"display.php?img=".$comment["image"]."\">".htmlspecialchars($name)."</a>";
					else
						print $comment["cmtdate"]."<br />User ".$comment["login"]." commented picture "."<a href=\"display.php?img=".$comment["image"]."\">".htmlspecialchars($name)."</a> :<br/>".htmlspecialchars($comment["comment"]);
					print "<br /><br />";
				}
			?>
		</div>
	</body>
</html>

<!-- Sanitized/OK -->
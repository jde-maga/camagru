<?php
	include 'config/database.php';
	
	if (isset($_SESSION["login"]))
		unset($_SESSION["login"]);
?>


<?php
	include 'header.php';
	include 'footer.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Disconnect - Camagru</title>
	</head>
	<body>
		<div class="body">
			Successfully Disconnected.
		</div>
	</body>
</html>

<!-- OK/Sanitized -->
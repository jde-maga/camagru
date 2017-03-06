<?php
	include 'config/database.php';
	if (!isset($_SESSION["login"]))
		header('Location: index.php'); 
?>

<?php 

    function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
    { 
        $cut = imagecreatetruecolor($src_w, $src_h); 
        imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h); 
        imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h); 
        imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct); 
    } 

	function sideDisplay($dbh)
	{
		$sql = $dbh->prepare("SELECT image FROM gallery WHERE login=? ORDER BY id DESC LIMIT 6");
		$sql->execute(array($_SESSION["login"]));
		foreach ($sql->fetchAll(PDO::FETCH_COLUMN) as $image)
			printf("<a href=\"display.php?img=".$image."\"><img src=\"gallery/".$image.".png\" width=100 height=100 align=\"center\" class=\"img\"></a>");
	}
?>


<?php 
	if ($_POST["uploaded"])
	{
		if ($_FILES["file"]["size"] == 0)
			$uperror = "No files selected !";
   		else if((!$check = getimagesize($_FILES["file"]["tmp_name"])) || $check["mime"] != "image/png") 
   		 	$uperror = "Incorrect file type !";
   		else if ($_FILES["file"]["size"] > 530000)
   			$uperror = "File is too big !";
   		else
   		{
			$date = time();
			move_uploaded_file($_FILES["file"]["tmp_name"], "temp/temp.png");

			$dest = imagecreatefrompng("temp/temp.png");
			$src = imagecreatefrompng($_POST["frame"]);
			imagecopymerge_alpha($dest, $src, 0, 0, 0, 0, 640, 480, 100);
			imagepng($dest, "gallery/".$date.".png");
			$sql = $dbh->prepare("INSERT INTO gallery(image, login, name) VALUES (?, ?, ?)");
			$sql->execute(array($date, $_SESSION["login"], $date));
   		}
	}
	else if ($_POST)
	{
		$date = time();
		$imghandle = fopen("temp/temp.png", "w");
		fwrite($imghandle, base64_decode($_POST["imgdata"]));
		fclose($imghandle);

		$dest = imagecreatefrompng("temp/temp.png");
		$src = imagecreatefrompng($_POST["framesrc"]);
		imagecopymerge_alpha($dest, $src, 0, 0, 0, 0, 640, 480, 100);
		imagepng($dest, "gallery/".$date.".png");
		$sql = $dbh->prepare("INSERT INTO gallery(image, login, name) VALUES (?, ?, ?)");
		$sql->execute(array($date, $_SESSION["login"], $date));
	}
?>

<?php 
	include 'header.php';
	include 'footer.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Create - Camagru</title>
		<link rel="stylesheet" type="text/css" href="css/create.css">
	</head>
	<body>
	<div class="body">
		<div id="lside">
			<img src="frame/1.png" width="100" height="100" align="center" onclick="lay(this);">
			<img src="frame/2.png" width="100" height="100" align="center" onclick="lay(this);">
			<img src="frame/3.png" width="100" height="100" align="center" onclick="lay(this);">
			<img src="frame/4.png" width="100" height="100" align="center" onclick="lay(this);">
			<img src="frame/5.png" width="100" height="100" align="center" onclick="lay(this);">
			<img src="frame/6.png" width="100" height="100" align="center" onclick="lay(this);">
	</div>
	<div class="snapper">
		<video id="video" width="640" height="480" autoplay></video>
		<button id="snap">Snap Photo</button>
		Or select a PNG image to upload (< 500Ko):
		<form id="uploadimg" method="post" enctype="multipart/form-data">
    		<input type="file" name="file" id="file"><br />
    		<input type="hidden" id="frame" name="frame">
    		<input type="hidden" name="uploaded" value="1">
		</form>
	 	<button type="submit" value="Upload Image" name="submit" id="submit">Upload file</button>
		<canvas id="canvas" width="640" height="480"></canvas>
		<?php if ($uperror) print $uperror; ?>
	</div>
	<div id="rside"><?php sideDisplay($dbh); ?></div>
	</div>
	<form id="sendimg" method="post">
		<input type="hidden" id="imgdata" name="imgdata">
		<input type="hidden" id="framesrc" name="framesrc">
	</form>
	</body>
	<script src="create.js"></script>
</html>

<!-- OK/Sanitized -->
<?php
	session_start();
	$DB_DBN="mysql:host=localhost;dbname=camagru_db";
	$DB_USER="root";
	$DB_PASSWORD="";

	$dbh = new PDO($DB_DBN, $DB_USER, $DB_PASSWORD);
?>
<?php

	$DB_DBN="mysql:host=localhost";
	$DB_USER="root";
	$DB_PASSWORD="";

	$dbh = new PDO($DB_DBN, $DB_USER, $DB_PASSWORD);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$dbh->exec("CREATE DATABASE IF NOT EXISTS camagru_db;");
	$dbh->exec("USE camagru_db;");
	$dbh->exec("CREATE TABLE IF NOT EXISTS users
					(
						id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
						login VARCHAR(16) NOT NULL,
						pwd VARCHAR(64) NOT NULL,
						mail VARCHAR(128) NOT NULL,
						active BOOLEAN NOT NULL default FALSE
					);");
	$dbh->exec("CREATE TABLE IF NOT EXISTS gallery
					(
						id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
						image LONGTEXT NOT NULL,
						login VARCHAR(16) NOT NULL,
						imgdate TIMESTAMP NOT NULL,
						name VARCHAR(16) NOT NULL,
						imglike int NOT NULL default 0
					);");
	$dbh->exec("CREATE TABLE IF NOT EXISTS comments
					(
						id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
						login VARCHAR(16) NOT NULL,
						comment LONGTEXT default NULL,
						imglike BOOLEAN NOT NULL default FALSE,
						image LONGTEXT NOT NULL,
						cmtdate TIMESTAMP NOT NULL
						);");
	print "Database is set ! <a href=../index.php>return to menu</a>";
?>
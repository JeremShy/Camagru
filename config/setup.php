<?php
include ("database.php");
$DB_DSN = explode(";", $DB_DSN)[0];
try {
	$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo 'Error : ';
	echo 'Connexion echouee : ' . $e->getMessage();
	exit();
}
$sql = file_get_contents("camagru.sql");
$pdo->exec($sql);

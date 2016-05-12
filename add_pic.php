<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$data = file_get_contents("cadre.png");
$str = base64_encode($data);
$img = "data:image/png;base64,".$str;
include ("config/database.php");
try {
	$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo 'Error : ';
	echo 'Connexion echouee : ' . $e->getMessage();
	exit();
}
$name = "cadre moche";
$req = "INSERT INTO pictures(name, pic) VALUES (:name, :pic);";
$sth = $pdo->prepare($req);
$sth->execute(array(
		'name' => $name,
		'pic' => $img
));
?>

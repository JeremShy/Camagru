<?php
session_start();
if (!isset($_SESSION["logged_on"]) || $_SESSION["logged_on"] == false)
	exit();
if (!isset($_POST) || !isset($_POST["img_id"]) || $_POST["img_id"] == "")
	exit();
include ("config/database.php");
try {
	$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo 'Error : ';
	echo 'Connexion echouee : ' . $e->getMessage();
	exit();
}
$req = "SELECT ID FROM likes WHERE id_liker = :liker AND id_pic = :pic;";
$sth = $pdo->prepare($req);
$sth->execute(array(
	"liker" => $_SESSION["ID"],
	"pic" => $_POST["img_id"]
));
$tab = $sth->fetch();
$id = $tab["ID"];
$req = "DELETE FROM likes WHERE id_liker = :liker AND id_pic = :pic;";
$sth = $pdo->prepare($req);
$sth->execute(array(
	"liker" => $_SESSION["ID"],
	"pic" => $_POST["img_id"]
));
echo $tab["ID"]." ".$_SESSION["name"];
?>

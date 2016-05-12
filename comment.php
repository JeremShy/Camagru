<?php
session_start();
if (!isset($_SESSION["logged_on"]) || $_SESSION["logged_on"] == false)
{
	header("Location: index.php");
	exit();
}
if (!isset($_POST) || !isset($_POST["comment"]) || $_POST["comment"] == "") {
	header("Location: index.php");
	exit();
}
include ("config/database.php");
try {
	$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo 'Error : ';
	echo 'Connexion echouee : ' . $e->getMessage();
	exit();
}
$req = "INSERT INTO comments(ID_owner, comment, ID_pic) VALUES (:ID, :comment, :pic);";
$sth = $pdo->prepare($req);
$sth->execute(array(
	"ID" => $_SESSION["ID"],
	"comment" => $_POST["comment"],
	"pic" => $_POST["ID"]
));
$req = "SELECT users.mail AS mail from user_pictures JOIN users ON user_pictures.owner_ID = users.ID WHERE user_pictures.ID = :pic";
$sth = $pdo->prepare($req);
$sth->execute(array(
	"pic" => $_POST["ID"]
));
$tab = $sth->fetch();
mail($tab["mail"], "Nouveau commentaire", "Une de vos photos a reçu un nouveau commentaire. Pour aller le voir, cliquez sur ce lien : http://localhost:8080/camagru/image.php?id=".$_POST["ID"]);
header("Location: image.php?id=".$_POST["ID"]);
?>

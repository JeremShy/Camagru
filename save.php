<?php
	session_start();
	header("Content-Type: text/plain");
	if (!isset($_POST) || !isset($_POST["img"]) || $_POST["img"] == "")
		exit();
	$img = str_replace(' ','+',substr($_POST["img"], 22));
	include ("config/database.php");
	try {
		$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		echo 'Error : ';
		echo 'Connexion echouee : ' . $e->getMessage();
		exit();
	}
	$req = "INSERT INTO user_pictures(pic, owner_ID) VALUES (:pic, :ID);";
	$sth = $pdo->prepare($req);
	$sth->execute(array(
		"pic" => "data:image/png;base64,".$img,
		"ID" => $_SESSION["ID"]
	));
?>

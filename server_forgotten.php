<?php
	session_start();
	if (isset($_SESSION["logged_on"]) && $_SESSION["logged_on"] == true)
		header("Location: index.php");
	if (!isset($_POST) || !isset($_POST["name"]) || $_POST["name"] == "")
		header("Location: user_forgotten.php?val=unkown_user");
	include ("config/database.php");
	try {
		$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		echo 'Error : ';
		echo 'Connexion echouee : ' . $e->getMessage();
		exit();
	}
	$_POST["name"] = htmlspecialchars($_POST["name"]);
	$req = "SELECT * FROM users WHERE name = :name";
	$sth = $pdo->prepare($req);
	$sth->execute(array(
	    'name' => $_POST["name"]
	));
	$tab = $sth->fetch();
	if (count($tab) == 0 || !$tab || empty($tab))
		header("Location: user_forgotten.php?val=unkown_user");
	$token = hash("sha1", rand(0, 1000));
	$req = "UPDATE users SET reset = 1, reset_token = :token WHERE name = :name";
	$sth = $pdo->prepare($req);
	$sth->execute(array(
		'token' => $token,
		'name' => $_POST["name"]
	));
	mail($tab["mail"], "Reinitialisation de votre mot de passe Camagru.", "Bonjour,\nBonjour, afin de reinitiliaser votre mot de passe veuillez cliquer sur le lien suivant : http://localhost:8080/camagru/user_reinit.php?user=".$_POST["name"]."&token=$token");
	header("Location: user_forgotten.php?val=success")
?>

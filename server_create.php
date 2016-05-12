<?php
	session_start();
	if (isset($_SESSION["logged_on"]) && $_SESSION["logged_on"] == true)
		header("Location: index.php");
	if (!isset($_POST) || !isset($_POST["name"]) || !isset($_POST["password"]) || !isset($_POST["mail"]) || !isset($_POST["confirm"])
			|| $_POST["name"] == "" || $_POST["password"] == "" || $_POST["mail"] == "" || $_POST["confirm"] == "") {
		header("Location: user_create.php?val=empty");
		exit();
	}
	if (!filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL)) {
		header("Location: user_create.php?val=invalid_email");
		exit();
	}
	if ($_POST["password"] != $_POST["confirm"]) {
		header("Location: user_create.php?val=pass_not_matching");
		exit();
	}
	if (strlen($_POST["password"]) < 6) {
		header("Location: user_create.php?val=pass_not_secured");
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
	$_POST["name"] = htmlspecialchars($_POST["name"]);
	$_POST["mail"] = htmlspecialchars($_POST["mail"]);
	$token = hash("sha1", rand(0, 1000));
	$req = "SELECT * FROM users WHERE name = :name OR mail = :mail";
	$sth = $pdo->prepare($req);
	$sth->execute(array(
	    'name' => $_POST["name"],
			'mail' => $_POST["mail"]
	));
	$data = $sth->fetch();
	if (!empty($data)) {
		if ($data['name'] === $_POST['name'])
			header('Location: user_create.php?val=name_exists');
		else
			header('Location: user_create.php?val=mail_exists');
		exit();
	}
	$req = "INSERT INTO users(name, mail, password, valid, validation_token) VALUES (:name, :mail, :password, 0, :token);";
	$sth = $pdo->prepare($req);
	$sth->execute(array(
			'name' => $_POST["name"],
			'mail' => $_POST["mail"],
			'password' => hash("whirlpool", $_POST["password"]),
			'token' => $token
	));
	mail($_POST["mail"], "Jeton de validation", "Bonjour,\nNous vous remercions pour votre inscription sur notre site Camagru. Afin de finaliser votre inscription, veuillez cliquer sur le lien suivant : http://localhost:8080/camagru/validate.php?user=".$_POST["name"]."&token=$token");
	header('Location: user_log.php?val=email_token')
?>

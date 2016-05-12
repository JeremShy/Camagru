<?php	session_start();
	if (!isset($_GET) || !isset($_GET["user"]) || !isset($_GET["token"]) || $_GET["token"] == "" || $_GET["user"] == "" )
	{
		header("location: index.php");
		exit();
	}
?>
<html>
<head>
	<title>Camagru</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="header.css">
	<link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
	<?php
		include("header.php");
		include("config/database.php");
		try {
			$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo 'Error : ';
			echo 'Connexion echouee : ' . $e->getMessage();
			exit();
		}
		$sql = "SELECT validation_token AS token, valid FROM users WHERE name=:name";
		$req = $pdo->prepare($sql);
		$req->execute(array("name" => $_GET["user"]));
		$data = $req->fetch();
		if (empty($data))
			echo "<p class=\"error\">Nom d'utilisateur incorrect.</p>";
		else if ($data['token'] == $_GET["token"] && $data['valid'] != 1)
		{
			$sql = "UPDATE users SET valid=1 WHERE name=:name";
			$req = $pdo->prepare($sql);
			$req->execute(array("name" => $_GET["user"]));
			echo "<p class=\"info\">Votre compte a été validé, vous pouvez dès à présent vous connecter.</p>";
		}
		else if ($data['valid'] == 1)
			echo "<p class=\"error\">Votre compte a déjà été validé.";
		else
			echo "<p class=\"error\">Le jeton de validation est incorrect.</p>";
		include ("footer.php");
	?>
</body>
</html>

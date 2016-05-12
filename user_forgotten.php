<?php
	session_start();
	if (isset($_SESSION["logged_on"]) && $_SESSION["logged_on"] == true)
		header("Location: index.php");
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
	if (isset($_GET) && isset($_GET["val"])) {
		switch ($_GET["val"]) {
			case "unkown_user" :
				echo '<p class="error">Ce nom d\'utilisateur n\'a pas de correspondance dans notre base de donnée.</p>';
				break;
			case "success" :
				echo '<p class="info">Un email vous a été envoyé avec un lien permettant de reinitialiser votre mot de passe.</p>';
				break;
			}
		}
	?>
	<div id="formulaire">
	<p>
		Veuillez entrer votre identifiant afin de pouvoir réinitialiser votre mot de passe.
	</p>
	<br />
	<form method="POST" action="server_forgotten.php">
		Identifiant 	: <input type="text" name="name"/>
		<br />
		<input type="submit" value="Reinitialiser mon mot de passe"/>
	</form>
	</div>
	<?php
		include ("footer.php");
	?>
</body>
</html>

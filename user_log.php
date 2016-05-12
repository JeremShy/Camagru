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
				case "email_token" :
					echo '<p class="info">Veuillez cliquer sur le lien present dans votre email de validation.</p>';
					break;
				case "credentials" :
					echo '<p class="error">Mot de passe ou nom d\'utilisateur incorrect.</p>';
					break;
				case "not_valid" :
				echo '<p class="error">Veuillez verifier votre adresse e-mail en cliquant sur le lien present dans le mail reçu.</p>';
				break;
				case "empty" :
					echo '<p class="error">Veuillez remplir tous les champs.</p>';
					break;
				case "modified" :
					echo '<p class="info">Vore mot de passe à correctement été modifié, vous pouvez vous connecter avec votre nouveau mot de passe.</p>';
					break;
				case "poliment" :
					echo '<p class="error">Vous devez être connecté pour acceder à cette page du site.</p>';
					break;
			}
		}
		?>
	<form method="POST" action="server_log.php">
		Identifiant 	: <input type="text" name="name"/>
		<br />
		Mot de passe 	: <input type="password" name="password"/>
		<br />
		<input type="submit" value="Me connecter"/>
		<br />
		<a href="user_forgotten.php">Mot de passe oublié ?</a>
		<br />
		<a href="user_create.php">Creer un compte</a>
	</form>
	<?php
		include ("footer.php");
	?>
</body>
</html>

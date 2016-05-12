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
	<?php include("header.php");
	if (isset($_GET) && isset($_GET["val"])) {
		switch ($_GET["val"]) {
			case "empty" :
			echo '<p class="error">Veuillez remplir tous les champs.</p>';
			//		echo '<script>alert("Veuillez remplir tous les champs.")</script>"';
			break;
			case "invalid_email" :
			echo '<p class="error">Veuillez rentrer une adresse email valide.</p>';
			//		echo '<script>alert("Veuillez rentrer une adresse email valide.")</script>';
			break;
			case "pass_not_matching" :
			echo '<p class="error">Le mot de passe et sa confirmation ne correspondent pas.</p>';
			//		echo '<script>alert("Le mot de passe et sa confirmation ne correspondent pas.")</script>';
			break;
			case "name_exists" :
				echo '<p class="error">Nom d\'utilisateur deja pris.</p>';
				break;
			case "mail_exists" :
				echo '<p class="error">Mail deja pris.';
				break;
			case "pass_not_secured":
				echo '<p class="error">Le mot de passe doit faire au minimum 6 caracteres.</p>';
				break;
		}
	}
	?>
	<form method="POST" action="server_create.php">
		Identifiant 									: <input type="text" name="name"/>
		<br />
		Mail													: <input type="text" name="mail"/>
		<br />
		Mot de passe 									: <input type="password" name="password"/>
		<br />
		Confirmation du mot de passe 	: <input type="password" name="confirm" />
		<br />
		<input type="submit" value="M'inscrire"/>
	</form>
	<?php
		include ("footer.php");
	?>
</body>
</html>

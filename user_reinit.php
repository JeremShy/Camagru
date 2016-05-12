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
	switch ($_GET["val"]) {
		case "wrong_confirm":
			echo '<p class="error">Le mot de passe et sa confirmation ne correspondent pas.</p>';
			break;
		case "wrong_token":
			echo '<p class="error">Il y a eu une erreur lors du traitement de votre requête.</p>';
			break;
		case "pass_not_secured":
			echo '<p class="error">Le mot de passe doit contenir au moins 6 caracteres.</p>';
	}
	?>
	<div id="formulaire">
	<p>
		Veuillez entrer votre nouveau mot de passe.
	</p>
	<br />
	<form method="POST" action="server_reinit.php">
		<input type="hidden" name="token" value=<?php echo '"'.$_GET["token"].'"'; ?> />
		<input type="hidden" name="user" value=<?php echo '"'.$_GET["user"].'"'; ?> />
		Nouveau de mot passe 	: <input type="password" name="pass"/>
		<br />
		Confirmation	: <input type="password" name="confirm"/>
		<br />
		<input type="submit" value="Reinitialiser mon mot de passe"/>
	</form>
	</div>
	<?php
		include ("footer.php");
	?>
</body>
</html>

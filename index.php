<?php
session_start();
if (!isset($_SESSION["logged_on"]) || $_SESSION["logged_on"] == false) {
	header("Location: user_log.php?val=poliment");
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Camagru</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="header.css">
	<link rel="stylesheet" type="text/css" href="main.css">
	<script type="text/javascript" src="script.js"></script>
</head>
<body>
	<?php
	include("header.php");
	?>
	<aside><?php
			include ("config/database.php");
			try {
				$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				echo 'Error : ';
				echo 'Connexion echouee : ' . $e->getMessage();
				exit();
			}
			$req = "SELECT * FROM user_pictures WHERE owner_ID = :id";
			$sth = $pdo->prepare($req);
			$sth->execute(array(
				"id" => $_SESSION["ID"]
			));
			$i = 0;
			while ($tab = $sth->fetch()) {
				$i = 1;
				echo "<a href=\"image.php?id=".$tab["ID"]."\"><img src=\"".$tab["pic"]."\"></a>";
			}
			if ($i == 0)
				echo "Vous n'avez uploadé aucune photo pour l'instant."
		?></aside><article>
	<?
	if (isset($_GET) && isset($_GET["val"])) {
		switch ($_GET["val"]) {
			case "connection_successful" :
				echo '<p class="info">Vous avez correctement été connecté.</p>';
				break;
			}
		}
	?>
	<div id="container">
		<div id="choice">
			<form id="choice_form">
				<?php
				include ("config/database.php");
				try {
					$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				} catch (PDOException $e) {
					echo 'Error : ';
					echo 'Connexion echouee : ' . $e->getMessage();
					exit();
				}
				$req = "SELECT * FROM pictures";
				$sth = $pdo->prepare($req);
				$sth->execute();
				while ($tab = $sth->fetch()) {
					echo "<label for=".$tab["ID"]."><img class=\"choice_elem\" src=\"".$tab["pic"]."\" /></label>";
					echo '<input type="radio" name= "select" value="'.$tab["ID"].'" id="'.$tab["ID"].'" onclick="choice_made(event)">';
				}
				?>
		</form>
		</div>
		Choisir une photo sur laquelle appliquer un filtre :
		<input type="file" id="perso" accept="image/*" onchange="perso(event)" disabled>
		<div id="cam">
			<video id="video"></video>
			<button id="startbutton" disabled>Prendre une photo</button>
			<input type="hidden" id="hidden" value="none"/>
			<canvas id="canvas"></canvas>
		</div>
		<div id="result_div">
			<img src="" id="result" alt="result">
			<button id="savebutton" onclick="save_pic()">Sauvegarder cette image</button>
		</div>
	</div>
	<script src="cam.js" type="text/javascript"></script>
	<br />
</article>
	<?php
		include ("footer.php");
	?>
</body>
</html>

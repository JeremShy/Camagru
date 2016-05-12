<?php session_start(); ?>
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
	<article><?php
		include("header.php");
		if (!isset($_GET["page"]) || $_GET["page"] < 1)
			$_GET["page"] = 1;
		include ("config/database.php");
		try {
			$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo 'Error : ';
			echo 'Connexion echouee : ' . $e->getMessage();
			exit();
		}
		$req = "SELECT * FROM user_pictures ORDER BY date LIMIT :lun, 5";
		$sth = $pdo->prepare($req);
		$lun = intval(($_GET["page"]) - 1) * 5;
		$sth->bindParam("lun", $lun, PDO::PARAM_INT);
		$sth->execute();
		while ($tab = $sth->fetch()) {
			echo "<a href=\"image.php?id=".$tab["ID"]."\"><img class = \"small_img\" src=\"".$tab["pic"]."\" id=\"".$tab["ID"]."\"/></a>";
			if (isset($_SESSION) && $_SESSION["logged_on"] == true)
			{
				$req = "SELECT * FROM likes WHERE id_liker = :liker AND id_pic = :pic";
				$lsth = $pdo->prepare($req);
				$lsth->execute(array(
					"pic" => $tab["ID"],
					"liker" => $_SESSION["ID"]
				));
				$l_tab = $lsth->fetch();
				if (count($l_tab) == 0 || empty($l_tab))
					echo "<button id=button_".$tab["ID"]." onclick=like(event)>Aimer</button>";
				else
					echo "<button id=button_".$tab["ID"]." onclick=unlike(event)>Ne plus aimer</button>";
				if ($_SESSION["ID"] == $tab["owner_ID"]) {
					echo "<button id=delete_".$tab["ID"]." onclick=delete_pic(event)>Supprimer</button>";
				}
				echo '<ul id="likes_'. $tab["ID"] .'">';
				$req = "SELECT users.name AS name, likes.ID AS ID FROM likes JOIN users ON likes.id_liker = users.id WHERE likes.id_pic = :id_pic";
				$lsth = $pdo->prepare($req);
				$lsth->execute(array(
					"id_pic" => $tab["ID"]
				));
				while($like_tab = $lsth->fetch()) {
					echo "<li id=\"".$like_tab["ID"]."\">".$like_tab["name"]."</li>";
				}
				echo "</ul>";
			}
			else {
				echo "<br />";
			}
		}
		if ($_GET["page"] > 1)
			echo '<a href="galerie.php?page='.strval($_GET["page"] - 1).'">Page précédente</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$req = "SELECT * FROM user_pictures ORDER BY date LIMIT :lun, 5;";
		$sth = $pdo->prepare($req);
		$lun = intval($_GET["page"]) * 5;
		$sth->bindParam("lun", $lun, PDO::PARAM_INT);
		$sth->execute();
		$tab = $sth->fetch();
		if (count($tab) != 0 && !empty($tab))
			echo '<a href="galerie.php?page='.strval($_GET["page"] + 1).'">Page suivante</a>';
		echo "</article>";
			include ("footer.php");
	?>
</body>
</html>

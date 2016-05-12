<?php
session_start();
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
		echo "<article>";
		include ("config/database.php");
		try {
			$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo 'Error : ';
			echo 'Connexion echouee : ' . $e->getMessage();
			exit();
		}
		$req = "SELECT * FROM user_pictures JOIN users ON user_pictures.owner_ID = users.ID WHERE user_pictures.ID = :id";
		$sth = $pdo->prepare($req);
		$sth->execute(array(
			"id" => $_GET["id"]
		));
		$tab = $sth->fetch();
		if (count($tab) == 0 || empty($tab))
		{
			echo '<p class="error">Cette image n\'existe pas.</p>';
			exit();
		}
		echo "Photo prise par : ".$tab["name"]."<br /><br />";
		echo "<img src=\"".$tab["pic"]."\" id=\"".$tab["ID"]."\"/>";
		echo "<br />";
	?>
	<br />
	<?php
	if (isset($_SESSION["logged_on"]) && $_SESSION["logged_on"] == true)
	{
		echo '<form method="POST" action="comment.php" id="choice_form">
		<label>Ajouter un commentaire : </label><textarea form="choice_form" rows=15 cols=60 name="comment"></textarea>
		<br />
		<input type="hidden" name="ID" value="'.$_GET["id"].'"/>
		<input type="submit" />
	</form>';
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
	$req = "SELECT * FROM comments JOIN users on users.ID = comments.ID_owner WHERE ID_pic = :ID";
	$sth = $pdo->prepare($req);
	$sth->execute(array(
		"ID" => $_GET["id"]
	));
	while($tab = $sth->fetch()) {
		echo "<p>Commentaire de ".$tab["name"]." : <br />".$tab["comment"]."<br /> <br />";
	}
	echo "</article>";
	include ("footer.php");
	?>
</body>
</html>

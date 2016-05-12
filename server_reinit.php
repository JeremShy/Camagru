<?php
if (isset($_SESSION["logged_on"]) && $_SESSION["logged_on"] == true) {
	header("Location: index.php");
	exit();
}
if (!isset($_POST) || !isset($_POST["user"]) || !isset($_POST["token"]) || !isset($_POST["pass"]) || !isset($_POST["confirm"])
	|| $_POST["user"] == "" || $_POST["token"] == "" || $_POST["pass"] == "" || $_POST["confirm"] == "") {
	header("Location: user_reinit.php?val=wrong_token");
	exit();
}
if ($_POST["pass"] != $_POST["confirm"]) {
	header("Location: user_reinit.php?val=wrong_confirm&user=".$_POST["user"]."&token=".$_POST["token"]);
	exit();
}
if (strlen($_POST["pass"]) < 6) {
	header("Location: user_reinit.php?val=pass_not_secured&user=".$_POST["user"]."&token=".$_POST["token"]);
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
$_POST["user"] = htmlspecialchars($_POST["user"]);
$_POST["token"] = htmlspecialchars($_POST["token"]);
$_POST["pass"] = htmlspecialchars($_POST["pass"]);
$_POST["confirm"] = htmlspecialchars($_POST["confirm"]);

$req = "SELECT * FROM users WHERE name = :user AND reset_token = :token AND reset = 1";
$sth = $pdo->prepare($req);
$sth->execute(array(
	"user" => $_POST["user"],
	"token" =>$_POST["token"]
));
$tab = $sth->fetch();
if (count($tab) == 0 || !$tab || empty($tab)) {
	header("Location: user_reinit.php?val=wrong_token");
	exit();
}
$req = "UPDATE users SET reset = 0, reset_token = \"\", password = :password WHERE name = :name";
$sth = $pdo->prepare($req);
$sth->execute(array(
	"password" => hash("whirlpool", $_POST["pass"]),
	"name" => $_POST["user"]
));
header("Location: user_log.php?val=modified");
?>

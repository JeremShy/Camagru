<?php
session_start();
if (isset($_SESSION["logged_on"]) && $_SESSION["logged_on"] == true)
  header("Location: index.php");
if (!isset($_POST) || !isset($_POST["name"]) || !isset($_POST["password"]) || $_POST["name"] == "" || $_POST["password"] == "") {
  header("Location: user_log.php?val=empty");
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
$req = "SELECT * FROM users WHERE name=:name AND password=:password;";
$sth = $pdo->prepare($req);
$sth->execute(array(
    'name' => $_POST["name"],
    'password' => hash("whirlpool", $_POST["password"])
));
$data = $sth->fetch();
if ($data && !empty($data)) {
  if ($data['valid'] == 0) {
    header('Location: user_log.php?val=not_valid');
    exit();
  }
  $_SESSION["logged_on"] = true;
  $_SESSION["name"] = $data['name'];
  $_SESSION["mail"] = $data['mail'];
	$_SESSION["ID"] = $data['ID'];
}
else {
  header('Location: user_log.php?val=credentials');
  exit();
}
  header('Location: index.php?val=connection_successful');
?>

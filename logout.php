<?php

session_start();
$_SESSION["logged_on"] = false;
session_destroy();
header("Location: index.php");
?>

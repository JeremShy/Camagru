<?php
	header("Content-Type: text/plain");
	if (!isset($_POST) || !isset($_POST["filter"]) || !isset($_POST["img"]) || $_POST["filter"] == "" || $_POST["img"] == "")
		exit();
	include ("config/database.php");
	try {
		$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		echo 'Error : ';
		echo 'Connexion echouee : ' . $e->getMessage();
		exit();
	}
	$req = "SELECT * FROM pictures WHERE ID = :id";
	$sth = $pdo->prepare($req);
	$sth->execute(array(
			'id' => htmlspecialchars($_POST["filter"])
	));
	$tab = $sth->fetch();
	$filter_str = base64_decode(substr($tab["pic"], 22));
	$img_str = base64_decode(str_replace(' ','+',substr($_POST["img"], 22)));
	$filter_gd = imagecreatefromstring($filter_str);
	$img_gd = imagecreatefromstring($img_str);
	$size_pic = getimagesizefromstring($img_str);
	$new_filter = imagecreatetruecolor($size_pic[0], $size_pic[1]);
	imagealphablending( $new_filter, false );
	imagesavealpha( $new_filter, true );
	$size = getimagesizefromstring($filter_str);
	imagecopyresampled($new_filter, $filter_gd, 0, 0, 0, 0, $size_pic[0], $size_pic[1], $size[0], $size[1]);
	imagecopy($img_gd, $new_filter, 0, 0, 0, 0, $size_pic[0], $size_pic[1]);
	ob_start();
	imagepng($img_gd);
	$rez = ob_get_contents();
	ob_end_clean();
	echo "data:image/png;base64," . base64_encode($rez)
?>

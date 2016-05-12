<div id="header">
	<a href=<?php
	if (isset($_SESSION["logged_on"]) && $_SESSION["logged_on"] == true)
			echo "\"index.php\"";
	else
			echo "\"user_log.php\"";
		?>><h1>Camagru</h1></a>
	<div id="links">
		<?php
			if (isset($_SESSION["logged_on"]) && $_SESSION["logged_on"] == true) {
				echo '<p>Vous êtes connecté en tant que '.$_SESSION["name"].'</p>';
				echo '<a href="logout.php">Me deconnecter</a>';
				}
				echo '<a class="padded" href="galerie.php">Galerie</a>';
				?>
	</div>
</div>

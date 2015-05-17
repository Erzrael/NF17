<html>
	<head>
		<title> Création d'un nouveau compte </title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	<body>
		<h1>Bienvenue</h1>
		<?php
		/* Connexion à la base de données */
		include "fonctions/fonction_type.php";
		include "classes/connexion.php";
		$vConn = new Connexion();

		/* Récupération des variables passées par le fomulaire */
		$vLogin=$_POST['login'];
		$vMdP=$_POST['password'];
		$vType=$_POST['type'];

		/* Le bout de code suivant peut se mettre dans une fonction */
		$vType = isType($vType);

		/* Inscription */
		if ($vConn->ExecuteSQL("INSERT INTO $vType VALUES ('$vLogin', '$vMdP')") != 0);
			echo "<p>Inscription de $vLogin au magasin validée</p>";

		$vConn->close();
		?>

	</body>
</html>
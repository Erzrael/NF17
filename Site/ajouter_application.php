<html>
	<head>
		<title> Création d'une nouvelle application</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	<body>
		<?php
		/* Connexion à la base de données */
		include "fonctions_verification_connexion.php";
		include "connexion.php";
		$vConn = new Connexion();

		/* Récupération des variables passées par le fomulaire */
		$vEditeur=$_POST['editeur'];
		$vTitre=$_POST['titre'];
		$vAdmin=$_POST['admin'];
		$vTarifAbo=$_POST['tarifAbo'];
		$vTarifAS=$_POST['tarifAS'];

		/*Vérifie l'existance de l'éditeur*/
		if (isEditeur($vEditeur, $vConn)){
			/*Vérifie l'existance de l'administrateur*/
			if(isAdmin($vAdmin, $vConn)){
				/*Insère les variables dans contenu et récupère la nouvelle ligne dans $result*/
				$result = $vConn->SelectSQL("INSERT INTO contenu(titre,tarifabo,tarifachatsimple,editeur,nomadministrateur) VALUES ('$vTitre', $vTarifAbo,$vTarifAS,'$vEditeur','$vAdmin') RETURNING identifiant");
				$id = $result[0]['identifiant'];
				/*Insère l'id du contenu dans application*/	
				if ($vConn->ExecuteSQL("INSERT INTO application (contenu) VALUES ($id)") != 0)
					echo "<p>Ajout application</p>";
			}
			else echo "Cet administrateur n'existe pas";
		}
		else echo "Cet éditeur n'existe pas.";

		$vConn->close();
		?>

	</body>
</html>

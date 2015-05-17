<html>
	<head>
		<title>Connexion</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	<body>
		<h1>Bienvenue</h1>
		<?php
		/* Connexion à la base de données */
		include "fonctions/fonctions_verification_connexion.php";
		include "classes/connexion.php";

		$vConn = new Connexion();

		/* Récupération des variables passées par le fomulaire */
		$vLogin=$_POST['login'];
		$vMdP=$_POST['password'];

		/* Connexion */
		if(ExistedUser($vLogin,$vConn))
			if(GoodPwd($vLogin, $vMdP, $vConn))
				if(isClient($vLogin,$vConn))
					echo "Bonjour Monsieur le Client";
				elseif(isEditeur($vLogin,$vConn)) 
					echo "Bonjour Monsieur l'Editeur";
				elseif(isAdministrateur($vLogin,$vConn))
					echo "Bonjour Monsieur l'Administrateur";
				else
					echo "Il semblerait que vous ne soyez pas un type d'utilisateur enregistré";
			else
				echo "Mauvais mot de passe";
		else
			echo "Mauvais login";

		$vConn->close();
		?>
	</body>
</html>
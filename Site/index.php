<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Accueil temporaire site NF17</title>
		<?php include('inc/theme.php'); ?>
	</head>
	<body role="document">

		<?php include('inc/menu.php'); ?>

	    <div class="container theme-showcase main" role="main">

	     	<div class="jumbotron">
				<h1>Page d'accueil test</h1>
				<p>On peut mettre des liens vers toutes les pages ici, en attendant de faire une vraie page d'accueil et tout...</p>
			</div>


			<div class="page-header">
				<h1>Test de PHP + connection à Postgres</h1>
			</div>
			<p>
				Extension Postgres 

				<?php
				/*echo extension_loaded('pgsql') ? 
				'<span class="label label-primary">trouvée</span> <br>':'<span class="label label-warning">pas trouvée</span><br>';*/
				
				include_once("classes/connexion.php");

				$DB = new Connexion();

				$Resulats = $DB->SelectSQL("SELECT prenom, nom FROM tEtudiant WHERE login='lraingev'");
				foreach ($Resulats as $Valeur)
				{
					echo $Valeur['prenom'];
					echo $Valeur['nom'];
				}

				$DB->close();
				?>

			</p>

		</div>

	<?php include("inc/scripts.php"); ?>
	</body>
</html>
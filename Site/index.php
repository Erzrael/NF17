<?php
	/* Démarrage de la session */
	session_start();
?>
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
			</p> <a href="comptes/inscription/creation.html"> Vous êtes nouveau ? </a> </p>
			</p> <a href="comptes/connexion/connexion.html"> Vous avez déjà un compte ? </a> </p>
			</p> <?php 
					if(isset($_SESSION['user']) && isset($_SESSION['type'])){
						echo '<a href="comptes/connexion/deconnexion.php"> Déconnexion </a>';
					} ?>
			</p>
			</div>


			<div class="page-header">
				<h1><?php 
					if(isset($_SESSION['user']) && isset($_SESSION['type'])){
						echo "Bienvenue cher ".$_SESSION['type']." ".$_SESSION['user'];
					} else {
						echo "Bienvenue utilisateur anonyme";
					}
				?></h1>
			</div>
			<p>
				Extension Postgres  <br/>

				<?php				
				include_once("classes/connexion.php");

				$DB = new Connexion();

				$Resulats = $DB->SelectSQL("SELECT * FROM Comptes");
				foreach ($Resulats as $Valeur)
				{
					echo $Valeur['nomutilisateur'].'	';
					echo $Valeur['motdepasse'].'<br/>';
				}

				$DB->close();
				?>

			</p>

		</div>

	<?php include("inc/scripts.php"); ?>
	</body>
</html>
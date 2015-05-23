<html>
	<head>
	<title>Ajout application</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	<body> Veuillez remplir le formulaire d'ajout d'une ressource<br><br>
		<form Method="POST" Action="ajouter_ressource.php">
		 	Titre de la ressource   :  <input type="text" name="titre"><br/>
		 	Tarif abonné : <input type="text" name="tarifAbo"><br/>
			Tarif achat simple: <input type="text" name="tarifAS"><br/>
			Editeur de l'application: <input type="text" name="editeur"><br/>
			Administrateur: <input type="text" name="admin"><br/>
			Application:<select name="appli">
			<?php
			include "connexion.php";
			$i=0;
			$vConn = new Connexion();
			/*Récupération de l'id et du nom des applications existantes*/
			$result=$vConn->SelectSQL('SELECT identifiant, titre FROM contenu, application WHERE identifiant=contenu');
			/*Permet de choisir une des applications existantes*/
			while ($result[$i]){
			echo '<option value="'.$result[$i]["identifiant"].'">'.$result[$i]["identifiant"].' '.$result[$i]["titre"].'</option>';
			$i++;
			}
			$vConn->close();
			?>
			</select><br/>
			<input type="submit">
		</form> 
	</body>
</html>

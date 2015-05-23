<?php
	/* Démarrage de la session */
	session_start();

	include('../../fonctions/fonctions_verification_connexion.php');
	include('../../classes/connexion.php');

	$vConn = new Connexion();

	$vLogin=$_POST['login'];
	$vMdP=$_POST['password'];

	if((!empty($vLogin)) && (!empty($vMdP))){
		if(!ExistedUser($vLogin, $vConn)){
			if(!ExistedPwd($vMdP, $vConn)){
				try{
					$vConn->ExecuteSQL("INSERT INTO Client VALUES ('$vLogin', '$vMdP')");
					$_SESSION['user'] = $vLogin;
					$_SESSION['type'] = 'Client';
					echo 'Félicitation, votre compte a bien été crée. Veuillez retourner à la page d\'accueil : <a href="../../index.php"> Page d\'accueil </a>';
				} catch (Exception $e) {
					echo 'Exception reçue : ', $e->getMessage(), '\n';
				}			
			} else {
				echo 'Le password est déjà pris. Veuillez retourner à la page de création de compte : <a href="creation.html"> Création de comptes </a>';
			}
		} else{
			echo 'Le login est déjà pris. Veuillez retourner à la page de création de compte : <a href="creation.html"> Création de comptes </a>';
		}
	} else{
		echo 'Le login ou password est vide. Veuillez retourner à la page de création de compte : <a href="creation.html"> Création de comptes </a>';
	}

?>
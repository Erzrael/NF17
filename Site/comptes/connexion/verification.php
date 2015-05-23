<?php
	/* Démarrage de la session */
	session_start();

	include('../../fonctions/fonctions_verification_connexion.php');
	include('../../classes/connexion.php');

	$vConn = new Connexion();

	$vLogin=$_POST['login'];
	$vMdP=$_POST['password'];

	if((!empty($vLogin)) && (!empty($vMdP))){
		if(ExistedUser($vLogin, $vConn)){
			if(GoodPwd($vLogin, $vMdP, $vConn)){
				if(isAdmin($vLogin, $vConn)){
					$_SESSION['type'] = 'Administrateur';
				} else if(isClient($vLogin, $vConn)){
					$_SESSION['type'] = 'Client';
				} else if(isEditeur($vLogin, $vConn)){
					$_SESSION['type'] = 'Editeur';
				} else {
					echo 'Cet utilisateur n\'appartient à aucun groupe. Veuillez retourner à la page d\'accueil : <a href="../../index.php"> Page d\'accueil </a>';
					exit();
				}
				$_SESSION['user'] = $vLogin;

				echo 'Félicitation, vous êtes bien connecté. Veuillez retourner à la page d\'accueil : <a href="../../index.php"> Page d\'accueil </a>';		
			} else {
				echo 'Ce n\'est pas le bon password. Veuillez retourner à la page de connexion : <a href="connexion.html"> Connexion </a>';
			}
		} else{
			echo 'Cet utilisateur n\'existe pas. Veuillez retourner à la page de connexion : <a href="connexion.html"> Connexion </a>';
		}
	} else{
		echo 'Le login ou password est vide. Veuillez retourner à la page de connexion : <a href="connexion.html"> Connexion </a>';
	}
?>
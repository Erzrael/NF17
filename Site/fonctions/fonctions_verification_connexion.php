<?php
/* Cette fonction vérifie que l'utlisateur existe */
function ExistedUser($vLogin, $vConn){
	$Resulats = $vConn->SelectSQL("SELECT nomUtilisateur FROM Comptes WHERE nomUtilisateur = '$vLogin'");
	if(!$Resulats)
		return 0;
	else
		return 1;
}

/* Cette fonction vérifie que le MdP associé au Login est valide */
function GoodPwd($vLogin, $vMdP, $vConn){
	$Resulats = $vConn->SelectSQL("SELECT nomUtilisateur FROM Comptes WHERE nomUtilisateur = '$vLogin' AND motDePasse = '$vMdP'");
	if(!$Resulats)
		return 0;
	else
		return 1;
}

/* Les trois fonctions suivantes vérifient que l'utilisateur est l'un des trois types possibles */
function isClient($vLogin, $vConn){
	$Resulats = $vConn->SelectSQL("SELECT nomUtilisateur FROM Client WHERE nomUtilisateur = '$vLogin'");
	if(!$Resulats)
		return 0;
	else
		return 1;
}

function isAdmin($vLogin, $vConn){
	$Resulats = $vConn->SelectSQL("SELECT nomUtilisateur FROM Administrateur WHERE nomUtilisateur = '$vLogin'");
	if(!$Resulats)
		return 0;
	else
		return 1;
}

function isEditeur($vLogin, $vConn){
	$Resulats = $vConn->SelectSQL("SELECT nomUtilisateur FROM Editeur WHERE nomUtilisateur = '$vLogin'");
	if(!$Resulats)
		return 0;
	else
		return 1;
}
?>
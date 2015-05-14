<?php
class Connexion {

	private $_Host;
	private $_Port;
	private $_Dbname;
	private $_User;
	private $_Password;
	private $_Lien;

	public function __construct($User = 'lraingev', $Password = 'root', $Host = 'localhost', $Port = 5432, $Dbname = 'test'){ 
	/*
	Potentiellement rajouter des arguements par défaut comme la base, le host et le port quand on les connaîtra.
	Constructeur de la classe. On lui passe les identifiants de connexions et il se connecte. 
	Pour être propre, il faudrait des setteurs et des getteurs, mais j'ai la flemme d'en faire.
	*/
		$this->_Host = $Host;
		$this->_Port = $Port;
		$this->_Dbname = $Dbname;
		$this->_User = $User;
		$this->_Password = $Password;
		$this->_Lien = pg_connect("host=$this->_Host port=$this->_Port dbname=$this->_Dbname user=$this->_User password=$this->_Password") 
			or die('Échec de la connexion : ' . pg_last_error());
	}

	public function close(){
	/* 
	Fermeture de la connexion à la base de données. 
	*/
		pg_close($this->_Lien);
	}
	
	public function SelectSQL($Requete){
	/*
	/!\ Execute la requête passée en argument que s'il s'agit d'un SELECT. /!\
	*/
		$i = 0;
		$Ressource = pg_query($this->_Lien, $Requete);
		$TabResultat = array();
		if (!$Ressource) 
			echo 'Erreur dans la requête SQL';
		else
		{
			while ($Ligne = pg_fetch_array($Ressource))
			{
				foreach ($Ligne as $clef => $valeur) $TabResultat[$i][$clef] = $valeur;
				$i++;
			}
			pg_free_result($Ressource);
			return $TabResultat;
		}
    }

	public function ExecuteSQL($Requete){
    /*
    /!\ Execute la requête passée en argument que s'il s'agit d'un UPDATE, DELETE, INSERT. /!\
    */
		$Ressource = pg_query($this->_Lien, $Requete);
		if (!$Ressource) 
			echo 'Erreur dans la requête SQL';
		else
		{
			$NbAffectee = pg_affected_rows($Ressource);
			return $NbAffectee;
		}
    }
}
?>

<?php
/* Exemple d'utilisation de la classe dans un autre fichier :

// J'inclus la classe
require 'connexion.php';

// Puis, seulement après, je me sers de ma classe. 
// Dans notre application, on passera en parametre les identifiants de connexions.
$objet = new Connexion('tuxa.sme.utc', '5432', 'dbnf17XXX', 'nf17pXXX', 'xxxXXXxxx' );

// Appel des différentes fonctions
$Resulats = $Objet->SelectSQL('SELECT Champ1,Champ2 FROM table');
foreach ($Resulats as $Valeur)
{
	echo $Valeur['Champ1'];
	echo $Valeur['Champ2'];
}

$Objet->close();

*/
?>

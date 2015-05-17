/* A LIRE :
	- Le type SERIAL permet l'auto incrémentation de la clé primaire à l'aide d'une séquence créée automatiquement.
	- Le type timestamp met le format des jours sous la forme "YYYY-MM-DD-H-M-S".
	- Des clés artificielles ont été ajoutées lorsque la clé primaire était de type VARCHAR à l'exception de la table "Type"
		et des tables d'utilisateurs.
	- Lorsque je n'ai pas remplacé le INTEGER par un SERIAL pour les clés primaires, j'ai supposé que l'utilisateur devait
		rentrer les chiffres.
	- J'aimerai revoir la table "Type" et celle s'en servant en donnant plutôt des integers et ayant une fonction qui en 
		fonction de l'integer donné écrit le type correspondant. 
*/

/* Création des tables */

/* Cette table permet d'avoir tous les identifiant et mdp dans une seule table. 
A l'aide de trigger, on s'assure qu'elle contienne tous les id et mdp */

CREATE TABLE Comptes (
	nomUtilisateur VARCHAR(30) PRIMARY KEY,
	motDePasse VARCHAR(30)
);

CREATE TABLE Administrateur (
	nomUtilisateur VARCHAR(30) PRIMARY KEY,
	motDePasse VARCHAR(30)
);

CREATE TABLE Client (
	nomUtilisateur VARCHAR(30) PRIMARY KEY,
	motDePasse VARCHAR(30)
);

CREATE TABLE Editeur(
	nomUtilisateur VARCHAR(30) PRIMARY KEY,
	motDePasse VARCHAR(30),
	contact VARCHAR(30),
	url VARCHAR(30),
	nomA VARCHAR(30) REFERENCES Administrateur(nomUtilisateur)
);

CREATE TABLE OS(
	identifiant SERIAL PRIMARY KEY,
	constructeur VARCHAR(30),
	version INTEGER
);

CREATE TABLE Modele(
	identifiant SERIAL PRIMARY KEY,
	constructeur VARCHAR(30),
	designationCom VARCHAR(30),
	OS INTEGER REFERENCES OS(identifiant)
);
 
CREATE TABLE Terminal(
	numSerie INTEGER PRIMARY KEY,
	nomClient VARCHAR(30) REFERENCES Client(nomUtilisateur),
	modeleConstructeur INTEGER REFERENCES Modele(identifiant)
);

CREATE TABLE CliTerm (
	nomClient VARCHAR(30) REFERENCES Client(nomUtilisateur),
	numserie1 INTEGER REFERENCES Terminal(numSerie),
	numserie2 INTEGER REFERENCES Terminal(numSerie),
	numserie3 INTEGER REFERENCES Terminal(numSerie),
	numserie4 INTEGER REFERENCES Terminal(numSerie),
	numserie5 INTEGER REFERENCES Terminal(numSerie),
	PRIMARY KEY (nomClient)
);

CREATE TABLE Contenu (
	identifiant SERIAL PRIMARY KEY,
	titre VARCHAR(30),
	tarifAbo DECIMAL(5,2),
	tarifAchatSimple DECIMAL(5,2),
	editeur VARCHAR(30) REFERENCES Editeur(nomUtilisateur),
	nomAdministrateur VARCHAR(30) REFERENCES Administrateur(nomUtilisateur)
);

CREATE TABLE Application (
	contenu INTEGER REFERENCES Contenu(identifiant),
	PRIMARY KEY(contenu)
);

CREATE TABLE Ressource(
	contenu INTEGER REFERENCES Contenu(identifiant),
	application INTEGER REFERENCES Application(contenu),
	PRIMARY KEY (contenu, application)
);	

CREATE TABLE Avis (
	nomClient VARCHAR(30) REFERENCES Client(nomUtilisateur),
	application INTEGER REFERENCES Application(contenu),
	note INTEGER,
	commentaire VARCHAR(300)
);

CREATE TABLE TypeC (
	Type VARCHAR(20) PRIMARY KEY
);

CREATE TABLE ClientType(
	nomClient VARCHAR(30) REFERENCES Client(nomUtilisateur),
	Type VARCHAR(20) REFERENCES TypeC(Type),
	PRIMARY KEY(nomClient, Type)
);

CREATE TABLE DestinationClientContenu(
	TypeClient VARCHAR(30) REFERENCES TypeC(Type),
	contenu INTEGER REFERENCES Contenu(identifiant),
	PRIMARY KEY(TypeClient, contenu)
);

CREATE TABLE DisponibiliteOSContenu(
	contenu INTEGER REFERENCES Contenu(identifiant),
	os INTEGER REFERENCES OS(identifiant),
	PRIMARY KEY (os, contenu)
);

CREATE TABLE CarteBancaire(
	numCB INTEGER PRIMARY KEY,
	nomClient VARCHAR(30) REFERENCES Client(nomUtilisateur)
);

CREATE TABLE CartePrepayee(
	numCP INTEGER PRIMARY KEY,
	montantDepart DECIMAL (5,2),
	montantCourant DECIMAL(5,2),
	dateDeValidite  Date,
	nomPossesseur VARCHAR(30) REFERENCES Client(nomUtilisateur),
	nomGerant VARCHAR(30) REFERENCES Administrateur(nomUtilisateur)
);

CREATE TABLE AchatSimple (
	numTransaction SERIAL PRIMARY KEY,
	dateTransaction timestamp,
	contenu INTEGER REFERENCES Contenu(identifiant),
	numCB INTEGER REFERENCES CarteBancaire(numCB),
	numCP INTEGER REFERENCES CartePrepayee(numCP)
);

CREATE TABLE Abonnement (
	numTransactionAb INTEGER PRIMARY KEY,
	dateAbonnement timestamp,
	dureeUtilisation time,
	periode INTEGER CHECK (periode IN (1, 3, 12)),
	auto boolean,
	contenu INTEGER REFERENCES Contenu(identifiant),
	numCB INTEGER REFERENCES CarteBancaire(numCB),
	numCP INTEGER REFERENCES CartePrepayee(numCP)
);

/* Création des vues */

CREATE VIEW vRessource(identifiant, titre, tarifAbo, tarifAchatSimple, editeur, nomAdministrateur)
	AS
	SELECT Contenu.identifiant, Contenu.titre, Contenu.tarifAbo, Contenu.tarifAchatSimple, Contenu.editeur, Contenu.nomAdministrateur 
	FROM Contenu, Ressource
	WHERE (Contenu.identifiant=Ressource.application);

CREATE VIEW vApplication (identifiant, titre, tarifAbo, tarifAchatSimple, editeur, nomAdministrateur)
	AS
	SELECT Contenu.identifiant, Contenu.titre, Contenu.tarifAbo, Contenu.tarifAchatSimple, Contenu.editeur, Contenu.nomAdministrateur 
	FROM Contenu, Application
	WHERE(Contenu.identifiant=Application.contenu);

/* Création des triggers */

CREATE OR REPLACE FUNCTION MAdministrateur() RETURNS TRIGGER AS $MAdministrateur$
	BEGIN
	    IF (TG_OP = 'DELETE') THEN
	        DELETE FROM Comptes WHERE nomUtilisateur = OLD.nomUtilisateur;
	    ELSIF (TG_OP = 'UPDATE') THEN
	        UPDATE Comptes SET nomUtilisateur = NEW.nomUtilisateur, motDePasse = NEW.motDePasse WHERE nomUtilisateur = OLD.nomUtilisateur;
	    ELSIF (TG_OP = 'INSERT') THEN
	        INSERT INTO Comptes VALUES (NEW.nomUtilisateur, NEW.motDePasse);
	    END IF;
	    RETURN NULL; -- le résultat est ignoré car il s'agit d'un trigger AFTER
	END;
$MAdministrateur$ language plpgsql;

CREATE TRIGGER Maj_Administrateur
	AFTER INSERT OR UPDATE OF nomUtilisateur, motDePasse OR DELETE ON Administrateur
	FOR EACH ROW EXECUTE PROCEDURE MAdministrateur();

CREATE OR REPLACE FUNCTION MClient() RETURNS TRIGGER AS $MClient$
	BEGIN
	    IF (TG_OP = 'DELETE') THEN
	        DELETE FROM Comptes WHERE nomUtilisateur = OLD.nomUtilisateur;
	    ELSIF (TG_OP = 'UPDATE') THEN
	        UPDATE Comptes SET nomUtilisateur = NEW.nomUtilisateur, motDePasse = NEW.motDePasse WHERE nomUtilisateur = OLD.nomUtilisateur;
	    ELSIF (TG_OP = 'INSERT') THEN
	        INSERT INTO Comptes VALUES (NEW.nomUtilisateur, NEW.motDePasse);
	    END IF;
	    RETURN NULL; -- le résultat est ignoré car il s'agit d'un trigger AFTER
	END;
$MClient$ language plpgsql;

CREATE TRIGGER Maj_Client
	AFTER INSERT OR UPDATE OF nomUtilisateur, motDePasse OR DELETE ON Client
	FOR EACH ROW EXECUTE PROCEDURE MClient();

CREATE OR REPLACE FUNCTION MEditeur() RETURNS TRIGGER AS $MEditeur$
	BEGIN
	    IF (TG_OP = 'DELETE') THEN
	        DELETE FROM Comptes WHERE nomUtilisateur = OLD.nomUtilisateur;
	    ELSIF (TG_OP = 'UPDATE') THEN
	        UPDATE Comptes SET nomUtilisateur = NEW.nomUtilisateur, motDePasse = NEW.motDePasse WHERE nomUtilisateur = OLD.nomUtilisateur;
	    ELSIF (TG_OP = 'INSERT') THEN
	        INSERT INTO Comptes VALUES (NEW.nomUtilisateur, NEW.motDePasse);
	    END IF;
	    RETURN NULL; -- le résultat est ignoré car il s'agit d'un trigger AFTER
	END;
$MEditeur$ language plpgsql;

CREATE TRIGGER Maj_Editeur
	AFTER INSERT OR UPDATE OF nomUtilisateur, motDePasse OR DELETE ON Editeur
	FOR EACH ROW EXECUTE PROCEDURE MEditeur();
/* Supprime le schéma public (relation, fonctions, trigger, etc...) */
drop schema public cascade;

/* Recréer le schéma public */
create schema public;
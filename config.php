<?php

// Connexion au serveur de base(s) de donnée(s)
$host = '***HOST***';
$user = '***USER***';
$password = '***PASS***';

// Listez s'il y a des bases à exclure des exports
$bdds_a_exclure = array('information_schema', 'mysql', 'performance_schema');

// Indiquez ici le chemin complet sur le serveur où vous souhaitez déposer vos exports.
// => Laissez le champs vide pour garder le dossier d'export par défaut 'exports_bdds'
$chemin_perso = '';

// Temps en jours de conservation des dossiers d'exports
$jours = '2';

// Chemin de la commande mysqldump sur le serveur
$path_mysqldump = '/usr/bin/';

// Compression gzip des exports ? (oui=1 / non=0)
$comp = 1

?>
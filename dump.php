<?php 

require('config.php');
require('inc.php');

// Le chemin du dossier d'export a-t-il été personalisé ?
if($chemin_perso != '')
{
	$chemin = $chemin_perso;
}
else
{
	$chemin = dossier_courant().'/exports_bdds/';
}

// Si le dossier de destination n'existe pas, je le créé
if(!is_dir($chemin))
{
	mkdir($chemin, 0755);
}

// Récupération de la liste des bases de données
mysql_connect($host, $user, $password);
$result = mysql_query('SHOW DATABASES');

// Définition de la date et de l'heure actuelle
$dateenr = date('Ymd');
$heureenr = date('H:i');
// Déduction de la date à supprimer
$supjours = date('Ymd', strtotime("-".$jours." day"));

// Création du dossier de la date actuelle (si n'existe pas encore)
if(!is_dir($chemin.$dateenr))
{
	mkdir($chemin.$dateenr, 0755);
}
// Création dans le dossier date actuelle d'un dossier de l'heure actuelle
mkdir($chemin.$dateenr.'/'.$heureenr, 0755);

// Suppression du dossier daté de plus que la période configurée (s'il existe)
if(!is_dir($chemin.$dateenr))
{
	suppr_rep($chemin.$supjours);
}

// Création des exports par bases de donnée et enregistrement dans 'chemin/date_jour/heure'
while ($row = mysql_fetch_assoc($result)) {

	$database = $row['Database'];

	if(!in_array($database, $bdds_a_exclure))
	{
		$path = $chemin.$dateenr.'/'.$heureenr.'/';
		$dump = $path.$database."_".$dateenr.'-'.$heureenr.'.sql';

		system($path_mysqldump.'mysqldump --host='.$host.' --user='.$user.' --password='.$password.' --opt '.$database.' > '.$dump);
		if($comp == 1)
		{
			system("gzip ".$dump);
		}
	}

}

?>
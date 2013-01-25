<?php

$date = new DateTime();
include('inc.php');
include('config.php');

$chemin = !empty($config['chemin_perso']) ? $config['chemin_perso'] : dirname(__FILE__) . '/exports_bdds/';
$cheminLog = !empty($config['chemin_perso_logs']) ? $config['chemin_perso_logs'] : $chemin . 'logs/';
$fichierLog = $cheminLog . $config['logFile'];
createDir($cheminLog, 0755, $fichierLog);

log_command('##########################################', $fichierLog);
log_command("####### Nouvelle session d'exports #######", $fichierLog);
log_command('##########################################', $fichierLog);

if (mysql_connect($config['host'], $config['user'], $config['password']))
{
	if ($result = mysql_query('SHOW DATABASES'))
	{
		$path = $chemin . $date->format('Ymd/Hi/');
		createDir($path, 0755, $fichierLog);
		while ($row = mysql_fetch_assoc($result))
		{
			$database = str_replace(' ', '_', trim($row['Database']));
			if(!in_array($database, $config['bdds_a_exclure']))
			{
				$nomExport = $database . "_" . $date->format('Ymd') . '-' . $date->format('Hi') . '.sql';
				$dump = $path . $nomExport;
				$command = $config['path_mysqldump'].'mysqldump --host='.$config['host'].' --user='.$config['user'].' --password='.$config['password'].' --opt '.$database.' > '.$dump;
				// log_command('Export ' . $database . ' -> ' .$command, $fichierLog);
				log_command('Export de la base : ' . $database . ' -> ' . $dump, $fichierLog);
				if (($command_return = system($command)) != '')
				{
					log_command('/!\ Command Output : ' . $command_return, $fichierLog);
				}
				if($config['comp'])
				{
					$command = 'gzip ' . $dump;
					log_command('Compression gZip de cet export : ' . $nomExport . '.gz', $fichierLog);
					if (($command_return = system($command)) != '')
					{
						log_command('/!\ Command Output : ' . $command_return, $fichierLog);
					}
				}
			}
			else
			{
				log_command('### Exclusion de la base : ' . $database, $fichierLog);
			}
		}
		$date->modify('- ' . $config['jours'] . ' DAYS');
		log_command('### Suppression du dossier des exports de plus de ' . $config['jours'] . ' jours -> /' . $date->format('Ymd') . '/ :', $fichierLog);
		if(is_dir($chemin . $date->format('Ymd/')))
		{
			if($config['supp_command'])
			{
				// [ToDo] Commande à vérifier
				$command = 'rm ' . $chemin . $date->format('Ymd/') . ' -rf';
				log_command('### => Suppression du dossier (Commande system(rm /path/ -rf)) /' . $date->format('Ymd/'), $fichierLog);
				if (($command_return = system($command)) != '')
				{
					log_command('/!\ Command Output : ' . $command_return, $fichierLog);
				}
			}
			else
			{
				suppr_rep($chemin . $date->format('Ymd/'), $fichierLog);
			}
		}
		else
		{
			log_command('### => Dossier ' . $chemin . $date->format('Ymd/') . ' : Inexistant !', $fichierLog);
		}
		$date->modify('- ' . (int)($config['logs'] - $config['jours']) . ' DAYS');
		log_command('### Suppression du fichier de logs de plus de ' . $config['logs'] . ' jours -> log_' . $date->format('Ymd') . '.txt :', $fichierLog);
		if(file_exists($chemin . 'logs/log_' . $date->format('Ymd') . '.txt'))
		{
			log_command('### => Suppression du fichier log_' . $date->format('Ymd') . '.txt : ' . ((unlink($chemin . 'logs/log_' . $date->format('Ymd') . '.txt')) ? 'OK' : 'Echec !'), $fichierLog);
		}
		else
		{
			log_command('### => Fichier ' . $chemin . 'logs/log_' . $date->format('Ymd') . '.txt : Inexistant !', $fichierLog);
		}
		
	}
	else
	{
		log_command('### Pas de bases de données à exporter.', $fichierLog);
	}
}
else
{
	log_command('### Connection SQL échouée !', $fichierLog);
}

?>
<?php

function log_command($command = '', $file)
{
	if (!empty($file))
	{
		$log_infos = pathinfo($file);
		createDir($log_infos['dirname']);
		$date = new DateTime();
		file_put_contents($file, $date->format('Y-m-d H:i:s') . ' : ' . $command . PHP_EOL, FILE_APPEND);
	}
}

function suppr_rep($rep, $fichierLog = '')
{
	if (is_dir($rep))
	{
		$rep_courant = opendir($rep);
		while($titre = readdir($rep_courant))
		{
			if(is_dir("$rep/$titre") and ($titre != "." and $titre != ".."))
			{
				suppr_rep("${rep}/${titre}", $fichierLog);
			}
			elseif($titre != "." and $titre != "..")
			{
				unlink("${rep}/${titre}");
			}
		}
		closedir($rep_courant);
		log_command('### => Suppression du dossier (script PHP) ' . $rep . ' : ' . ((rmdir($rep)) ? 'OK' : 'Echec !'), $fichierLog);
	}
}

function createDir($path, $chmod = 0755, $fichierLog = '')
{
	if (!is_dir($path))
	{
		$dirs = explode('/', str_replace(dirname(__FILE__) . '/', '', $path));
		foreach ($dirs as $key => $dir)
		{
			$dir = '';
			for ($i = 0; $i <= $key; $i++) {
				$dir .= $dirs[$i] . '/';
			}
			if (!is_dir($dir))
			{
				log_command('### Création du dossier ' . $dir . ' : ' . ((mkdir($dir, $chmod)) ? 'OK' : 'Echec !'), $fichierLog);
			}
		}
	}
}

?>
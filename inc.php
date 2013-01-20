<?php

// Fonction qui retourne le chemin relatif au dossier dans lequel se trouve le script
function dossier_courant()
{
	// Chemin complet vers le script
	$dossier = $_SERVER['DOCUMENT_ROOT'];
	// Nom du script
	$script = $_SERVER['PHP_SELF'];
	// Chemin - Nom = Dossier courant
	return $dossier.mb_substr($script,0,-mb_strlen(strrchr($script,"/")));
}

// Fonction permettant de supprimer un répertoire après l'avoir vidé des éléments (et dossiers) qu'il contenait etc (en boucle)
function suppr_rep($rep)
{
	if (is_dir($rep))
	{
		$rep_courant = opendir($rep);
		// Tant qu'il y a des éléments dans le dossier courant
		while($titre = readdir($rep_courant))
			{
			// L'élément en cours est un dossier ou un fichier ? (hors '.' et '..')
			if(is_dir("$rep/$titre") and ($titre != "." and $titre != ".."))
			{
				// C'est un dossier -> on réaplique la fonction sur celui-ci
				suppr_rep("${rep}/${titre}");
			}
			elseif($titre != "." and $titre != "..")
			{
				// C'est un fichier -> on le supprime
				unlink("${rep}/${titre}");
			}
		}
		closedir($rep_courant);
		// On supprime le dossier maintenant vide
		rmdir($rep);
	}
}

?>
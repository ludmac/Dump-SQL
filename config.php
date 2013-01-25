<?php

$config = array(
	'host' => '***HOST***',
	'user' => '***USER***',
	'password' => '***PASS***',
	'bdds_a_exclure' => array('information_schema', 'mysql', 'performance_schema'),
	'chemin_perso' => '',
	'jours' => 2,
	'logs' => 2,
	'path_mysqldump' => '/usr/bin/',
	'supp_command' => false,
	'comp' => true,
	'chemin_perso_logs' => '',
	'logFile' => 'log_' . $date->format('Ymd') . '.txt'
);

?>
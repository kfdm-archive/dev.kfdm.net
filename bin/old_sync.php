<?php
require_once('_cmd.php');

$dbold = Database::instance('test',array(
	'connection'=>array(
		'type'     => 'mysql',
		'user'     => 'kfdm',
		'pass'     => 'p@ssw0rd',
		'host'     => 'localhost',
		'port'     => FALSE,
		'socket'   => FALSE,
		'database' => 'kfdm_test'
	)));
$dbnew = Database::instance();

require_once('_users.php');
require_once('_galleries.php');
require_once('_images.php');
require_once('_quotes.php');
<?php 
require 'environment.php';

global $config;
global $db;

$config = array();

if(ENVIRONMENT == 'development') {
	define("BASE_URL", "http://localhost/passwordGeneration/");
	$config['dbname'] = 'calendario';
	$config['host'] = 'localhost';
	$config['dbuser'] = 'root';
	$config['dbpass'] = '';

} else {
	define('BASE_URL', 'http://geradordesenharandom.com/');
	$config['dbname'] ='u757591790_marinatur';
	$config['host'] = 'localhost';
	$config['dbuser'] = 'u757591790_rfcjoujou';
	$config['dbpass'] = '260101';
}

$db = new PDO("mysql:dbname=".$config['dbname'].";host=".$config['host'], $config['dbuser'], $config['dbpass']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



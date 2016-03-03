<?php

error_reporting(E_ALL);
// setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
date_default_timezone_set('America/Sao_Paulo');

define('APPLICATION_BRAND', 'solutta');
define('APPLICATION_MODULE', 'fiscal');

define('APPLICATION_SERVER', isset($_SERVER['APPLICATION_SERVER'])?$_SERVER['APPLICATION_SERVER']: 'palomino');

$barbera = 'barbera.co7flhhjo7hj.sa-east-1.rds.amazonaws.com';
$palomino = 'palomino.co7flhhjo7hj.sa-east-1.rds.amazonaws.com';

$urlServidor = APPLICATION_SERVER == 'palomino' ? $palomino : $barbera;

/*
 * Configurações dos bancos de dados por modulo
 */
$conexoes = array(


		'colecao' => array(
				'host' => $palomino,
				'username' => 'colecao',
				'password' => 'cole3817',
				'dbname' => 'palomino_colecao_dev',
				'charset' => 'utf8',
				'options' => array(
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
				)
		),
		'fiscal' => array(
				'host' => $palomino,
				'username' => 'colecao',
				'password' => 'cole3817',
				'dbname' => 'palomino_fiscal_dev',
				'charset' => 'utf8',
				'options' => array(
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
				)
		)


);

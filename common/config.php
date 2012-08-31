<?php
require_once('./class/Config.php');
require_once('./class/Globals.php');

Globals::$self = new Globals(array('debug' => false));

$config = array(
	// db
	'server'		=> '127.0.0.1',
	'username'		=> 'root',
	'password'		=> '',
	'database'		=> 'pinxiu',
	'charset'		=> 'utf8',
	'tablePrefix'	=> '',
	
	'viewEnabled'	=> true,
	'layoutEnabled'	=> true,
);
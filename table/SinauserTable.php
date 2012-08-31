<?php
Globals::requireClass('Table');

class SinauserTable extends Table
{
	public static $defaultConfig = array(
		'table' => 'app_sina_user'
	);
}

Config::extend('SinauserTable', 'Table');
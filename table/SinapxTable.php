<?php
Globals::requireClass('Table');

class SinapxTable extends Table
{
	public static $defaultConfig = array(
		'table' => 'app_sina_px'
	);
}

Config::extend('SinapxTable', 'Table');
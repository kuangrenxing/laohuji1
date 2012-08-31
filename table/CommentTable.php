<?php
Globals::requireClass('Table');

class CommentTable extends Table
{
	public static $defaultConfig = array(
		'table' => 'tb_pxcomment'
	);
}

Config::extend('CommentTable', 'Table');
<?php
Globals::requireClass('Controller');

class AppController extends Controller
{
	public static $defaultConfig = array(
		'viewEnabled'	=> true,
		'layoutEnabled'	=> true,
		'title'			=> null
	);
	
	public function __construct($config = null)
	{
		parent::__construct($config);
	}
	
	public function indexAction()
	{
		$this->config['viewEnabled'] 	= true;
		$this->config['layoutEnabled'] 	= false;
		header('P3P:CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR');

		$this->redirect(APP_qzone_URL);
	}
	
	protected function out()
	{
		parent::out();
	}
}

Config::extend('AppController', 'Controller');
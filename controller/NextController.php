<?php
Globals::requireClass('Controller');
Globals::requireTable('Sinapx');
Globals::requireTable('Sinauser');

class NextController extends Controller
{
	protected $sinapx;
	protected $sinauser;
	
	public static $defaultConfig = array(
		'viewEnabled'	=> true,
		'layoutEnabled'	=> true,
		'title'			=> null
	);
	
	public function __construct($config = null)
	{
		parent::__construct($config);
		$this->sinapx 	= new SinapxTable($config);
		$this->sinauser = new SinauserTable($config);
	}
	
	public function indexAction()
	{
		$this->config['layoutEnabled'] = false;
		
		header('P3P:CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR');
		session_start();
		

		$app 	= $this->getIntParam("appid");
		$pxInfo = $this->sinapx->getRow($app);
		
		$this->view->data = $pxInfo;


	}
	
	protected function out()
	{
		parent::out();
	}
}

Config::extend('NextController', 'Controller');

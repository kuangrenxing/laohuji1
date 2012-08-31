<?php
Globals::requireClass('Controller');
Globals::requireTable('Sinapx');
Globals::requireTable('Sinauser');

class AjaxController extends Controller
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
		$this->sinapx	= new SinapxTable($config);
		$this->sinauser	= new SinauserTable($config);
	}
	
	public function indexAction()
	{
		$this->config['viewEnabled'] 	= false;
		$this->config['layoutEnabled'] 	= false;
		
		$star	= $this->getIntParam('star');
		if (!$star)
			$star = 1;
			
		$data	= $this->sinapx->listAll(array('star' => $star));
		
		header('Content-type: text/xml; charset=utf-8');
		echo <<<EOF
<?xml version="1.0" encoding="utf-8" ?>
<content>	
EOF;
foreach ($data as $key => $value)
{
echo '<app id="'.$value['id'].'"></app>';
echo '<px id="'.$value['pxid'].'" img="'.APP_DOMAIN.$value['pximg'].'"></px>';
echo '<itema id="'.$value['itemid1'].'" img="'.APP_DOMAIN.$value['itempic1'].'"></itema>';
echo '<itemb id="'.$value['itemid2'].'" img="'.APP_DOMAIN.$value['itempic2'].'"></itemb>';
echo '<itemc id="'.$value['itemid3'].'" img="'.APP_DOMAIN.$value['itempic3'].'"></itemc>';
echo '<itemd id="'.$value['itemid4'].'" img="'.APP_DOMAIN.$value['itempic4'].'"></itemd>';
echo '<yyu name="'.$value['yyu'].'"></yyu>';
echo "<pyu name='".$value['pyu']."'></pyu>";
}
echo "</content>";
die;
	}
	
	protected function out()
	{
		parent::out();
	}
}

Config::extend('AjaxController', 'Controller');
<?php
Globals::requireClass('Controller');
Globals::requireTable('Sinapx');
Globals::requireTable('Sinauser');
Globals::requireTable('Comment');
Globals::requireTable('Pxstat');

Globals::requireClass('OpenApiV3');

class LastController extends Controller
{
	protected $sinapx;
	protected $sinauser;
	protected $comment;
	protected $pxstat;
	
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
		$this->comment	= new CommentTable($config);
		$this->pxstat	= new PxstatTable($config);
	}
	
	public function indexAction()
	{
		$this->config['layoutEnabled'] = false;
		
		$app 	 = $this->getIntParam('appid');
		$pyu 	 = $this->getParam('pyu');
		$appInfo = $this->sinapx->getRow($app);
		$follow  = $this->getParam('followtl');
		$totl 	 = $this->getParam('totuolar');
		
		
		header("content-type:text/html;charset=utf-8");
		header('P3P:CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR');
				
		session_start();
		
		$this->view->data = $appInfo;
		
		
		
// 		if ($totl && $totl == 1)
		if (true)
		{
			
			$post=array();
			
			$post['px_id'] = $appInfo['pxid'];
			$post['username'] = $_SESSION['userinfo']['nickname'];
			if(isset($_SESSION['uid']))
			{
				$post['uid'] = $_SESSION['uid'];
				
			}
			else 
			{
				$this->redirectToController("index");
				$post['uid'] = 0;
			}
			$post['head_pic'] = $_SESSION['head_pic'];
			$post['comment'] = $pyu;
			$post['time_created'] = time();
			
				//发拖拉微博
			$commID = $this->comment->add($post , true);
				
			if ($commID){
				$this->pxstat->update(array('comment = comment + 1') , array('px_id' => $appInfo['pxid']));
			}
		}

		$this->view->userinfo=$_SESSION['userinfo'];
		
	}
	
	
	protected function out()
	{
		parent::out();
	}
}

Config::extend('LastController', 'Controller');
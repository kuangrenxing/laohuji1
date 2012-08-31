<?php
Globals::requireClass('Controller');

class FollowController extends Controller
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

		//新浪微博Oauth
		header('P3P:CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR');
		session_start();
		
		if (!isset($_SESSION['oauth2']) && empty($_SESSION['oauth2']["user_id"])){
			$this->redirect(SINA_APP_CALLBACK);
		}
		
		Globals::requireClass('WeiboOauth');
		$weibo = new SaeTClient( WB_AKEY , WB_SKEY ,$_SESSION['oauth2']['oauth_token'] ,'' );
		$rr = $weibo->follow(WB_UID);
		
//		$this->redirect(SINA_APPS_SITE);
	}
	
	protected function out()
	{
		parent::out();
	}
}

Config::extend('FollowController', 'Controller');
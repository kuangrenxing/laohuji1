<?php
Globals::requireClass('Controller');
Globals::requireClass('OpenApiV3');

Globals::requireTable('Sinapx');
Globals::requireTable('User');
Globals::requireTable('Connect');





class IndexController extends Controller
{
	protected $sinapx;
	protected $user;
	protected $connect;
	
	public static $defaultConfig = array(
		'viewEnabled'	=> true,
		'layoutEnabled'	=> true,
		'title'			=> null
	);
	
	public function __construct($config = null)
	{
		parent::__construct($config);
		$this->sinapx = new SinapxTable($config);
		$this->user				= new UserTable($config);
		$this->connect			= new ConnectTable($config);
	}
	
	public function indexAction()
	{
		header("content-type:text/html;charset=utf-8");
		
		$this->config['layoutEnabled'] = false;
		//$signed	 = $this->getParam("signed_request");
		
		//处理微博Oauth
		header('P3P:CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR');
		

		session_start();
		
		if($_SESSION['openid']=='' || $_SESSION['openkey']=='')
		{
			$_SESSION['openid']=$_GET['openid'];
			$_SESSION['openkey']=$_GET['openkey'];
		}	
		//print_r($_SESSION);	
		
		// 应用基本信息
		$appid = APP_ID;
		$appkey = APP_KEY;
		
		// OpenAPI的服务器IP
		// 最新的API服务器地址请参考wiki文档: http://wiki.open.qq.com/wiki/API3.0%E6%96%87%E6%A1%A3
		$server_name = 'openapi.tencentyun.com';		
		
		
		// 用户的OpenID/OpenKey
		$openid = $_SESSION['openid'];
		$openkey = $_SESSION['openkey'];
		
		
		// 所要访问的平台, pf的其他取值参考wiki文档: http://wiki.open.qq.com/wiki/API3.0%E6%96%87%E6%A1%A3
		$pf = 'qzone';
		
		
		$sdk = new OpenApiV3($appid, $appkey);
		$sdk->setServerName($server_name);
		
		$ret = $this->get_user_info(&$sdk, $openid, $openkey, $pf);
		
		$_SESSION['userinfo']=$ret;
		$_SESSION['userinfo']['id']=$_SESSION['openid'];
		
		//print_r($_SESSION);
		
		 if($ret['ret']>0 )
		{
			print_r($ret);echo " 调用OpenAPI时发生错误，需要开发者进行相应的处理";exit;
		}
		else if(-20<=$ret['ret'] && $ret['ret']<=-1)
		{
			print_r($ret);echo $ret['ret']."接口调用不能通过接口代理机校验，需要开发者进行相应的处理。";exit;
		}
		else if($ret['ret']<-50)
		{
			print_r($ret);echo $ret['ret']."系统内部错误，请通过企业QQ联系OpenAPI支持人员，调查问题原因并获得解决方案";exit;
		}
		else
		{ 
			$star = array(
					'1' =>'双鱼座',
					'2' =>'水瓶座',
					'3' =>'魔羯座',
					'4' =>'射手座',
					'5' =>'天蝎座',
					'6' =>'天秤座',
					'7' =>'处女座',
					'8' =>'狮子座',
					'9' =>'巨蟹座',
					'10'=>'双子座',
					'11'=>'金牛座',
					'12'=>'白羊座'
			);
			
			$this->view->star = $star;
			
			$data = $this->sinapx->listRand(NULL , 11);
			
			$change = $show = array();
			foreach ($data as $k=>$row)
			{
				if ($k)
					$change[] = $row;
				else
					$show = $row;
			}
			$this->view->show = $show;
			$this->view->data = $change;
			$this->view->auth = serialize($_SESSION);
			
			$uid=$this->regUser($_SESSION['userinfo']);
			$_SESSION['uid'] = $uid;

		}
		

		
		

	}
	
	/**
	 * 获取好友资料
	 *
	 * @param object $sdk OpenApiV3 Object
	 * @param string $openid openid
	 * @param string $openkey openkey
	 * @param string $pf 平台
	 * @return array 好友资料数组
	 */
	public function get_user_info($sdk, $openid, $openkey, $pf)
	{
		$params = array(
				'openid' => $openid,
				'openkey' => $openkey,
				'pf' => $pf,
		);
	
		$script_name = '/v3/user/get_info';
	
		return $sdk->api($script_name, $params);
	}
	
	
	protected function regUser($wbUinfo)
	{
		header('P3P:CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR');
	
		if (!isset($wbUinfo['id']) || !$wbUinfo['id'])
			return false;
		
		$newUser['password']  	= generatePassword(8);
		$newUser['username']  	= $wbUinfo['nickname'];
		$newUser['email']  		= $email = "qz_".strtolower($wbUinfo["id"])."@qq.com";
		$newUser['head_pic'] 	= $wbUinfo['figureurl'];
		$newUser['reg_ip'] 		= $newUser['log_ip'] =Globals::getClientIp(false);
		
		$newUser['connfrom'] 	= WB_APP_CONN_WEIFUSHI;
		$newUser['sex'] = $wbUinfo['gender'] == "男" ? 1 : 2;
		$newUser['time_created']= $newUser['log_time'] = time();
		$newUser['head_pic']	.=".jpg";
		$newUser['province'] 	= $wbUinfo['province'];
		$newUser['city'] 	= $wbUinfo['city'];
		
		if (strpos($newUser['head_pic'] , "/50/") !== false){
			$newUser['head_pic'] = str_replace("/50/" , "/180/" , $newUser['head_pic']);
		}
		
		
		$fieldsUser="id,head_pic,stgle";
		//查询用户
		$userInfo = $this->user->getRowWithFields($fieldsUser,array('email' => $newUser['email']));
		
		//用户不存在 记录connect数据
		if (!$userInfo)
		{
			$imgPath = "../../img/user/".date("Ym")."/".date('d')."/".date("g")."/";
			if (!is_dir($imgPath))
			{
				makeDeepDir($imgPath);
			}
			$imgUrl = $imgPath.date("YmdHis").rand(10,99).strrchr($newUser['head_pic'], '.');
			$imgUrl_128 = substr($imgUrl , 0 , strrpos($imgUrl , '.'))."_128.".substr(strrchr($imgUrl, "."), 1);
			$imgUrl_80 = substr($imgUrl , 0 , strrpos($imgUrl , '.'))."_80.".substr(strrchr($imgUrl, "."), 1);
			$imgUrl_36 = substr($imgUrl , 0 , strrpos($imgUrl , '.'))."_36.".substr(strrchr($imgUrl, "."), 1);
			$headImg = GetImage($newUser['head_pic'] , $imgUrl , 1);
			
			
			$headImg_128 = GetImage($newUser['head_pic'] , $imgUrl_128 , 1);
			$headImg_80 = GetImage($newUser['head_pic'] , $imgUrl_80 , 1);
			$headImg_36 = GetImage($newUser['head_pic'] , $imgUrl_36 , 1);
			$headImgUrl = substr($headImg , 4);			
			
			$newUser['head_pic'] = str_replace('../.', '', $imgUrl);
			$_SESSION['head_pic'] = $newUser['head_pic'];
			
			$uid = $userID  = $this->user->add($newUser , true);
			
			
			$conn['type'] 	= CONNECT_TYPE_QQ;
			$conn['uid'] 	= $uid;
			$conn['connuid'] 	= 0;
			$conn['connuname'] 	= $wbUinfo['nickname'];
			$conn['isbind'] = 1;
			$conn['issync'] = 1;
			$conn['token'] 	= $wbUinfo['id'];
			$conn['token_secret'] = '';
			$conn['createtime'] = $conn['updatetime'] = time();
			$stgle = 0;		
			
			$this->connect->add($conn , false);
			return $uid;
		}
		else
		{//用户存在 更新connect数据		
			
			$uid = $userInfo['id'];
			$stgle = $userInfo['stgle'];
			$headImgUrl = $userInfo['head_pic'];
	
			$_SESSION['head_pic'] = $userInfo['head_pic'];
			
			$conn['type'] 	= CONNECT_TYPE_QQ;
			$conn['uid'] 	= $uid;
			$conn['connuid'] 	= 0;
			$conn['connuname'] 	= $wbUinfo['nickname'];;
			$conn['isbind'] = 1;
			$conn['issync'] = 1;
		//	$conn['head_pic'] = $headImgUrl;
			$conn['token'] 	= $wbUinfo['id'];
			$conn['token_secret'] = '';
			$conn['updatetime'] = time();
			
			$this->user->update(array('username' => $newUser['username'] , 'sex'=> $newUser['sex'], 'head_pic' => $headImgUrl) , $uid);
			$this->connect->update(array('connuid' => $conn['connuid'] , 'connuname' => $conn['connuname'] , 'token' => $conn['token'] , 'token_secret' => $conn['token_secret'] , 'isbind' => 0 , 'issync' => 0 , 'updatetime' => $conn['updatetime']) , array('type' => $conn['type'] , 'uid' => $conn['uid']));
			return $uid;
		}
	
	}
	
	protected function out()
	{
		parent::out();
	}
}

Config::extend('IndexController', 'Controller');

<?php
include_once $ENGINE_PATH."View/BasicView.php";
include_once $ENGINE_PATH."Utility/RequestManager.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $ENGINE_PATH."Security/Authentication.php";
include_once $ENGINE_PATH."Security/Permission.php";
include_once $ENGINE_PATH."Admin/UserManager.php";
include_once $ENGINE_PATH."Admin/PluginManager.php";
include_once $ENGINE_PATH."Admin/activityTracker.php";
class Admin extends SQLData{
	var $auth ;
	var $perm;
	var $user;
	var $strHTML;
	var $View;
	var $DEBUG=false;
	var $plugin;
	var $Request;
	var $Session;
	var $roler;
	var $specified_role;
	function __construct(){
		$this->Request = new RequestManager();
		$this->Session = new SessionManager("GM_ADMIN");
		$this->auth = new Authentication($this);
		$this->perm = new Permission($this);
		$this->user = new UserManager();		
		$this->plugin = new PluginManager($this);
		$this->View = new BasicView();
		$this->log = new activityTracker($this);
		if($this->auth->isLogin()){
			$this->roler 			= 	$this->Session->getVariable("roler");
			$this->specified_role 	= 	$this->Session->getVariable("specified_role");
		}
	}
	function show(){
		if(!$this->auth->isLogin()){
			$this->strHTML = $this->showLoginPage();
		}else{
			
			$this->strHTML = $this->showAdminPage();
		}
		if($this->DEBUG){
			print $this->getMessage();
			print $this->perm->getMessage();
			print $this->user->getMessage();
		}
	}
	function loadPlugin($request,$reqID){
		global $APP_PATH,$ENGINE_PATH;
		
		$rs = $this->plugin->getPluginByRequestID($reqID);
		
		if(file_exists($APP_PATH.$rs['plugin_path'].$rs['className'].".php")){
			include_once $APP_PATH.$rs['plugin_path'].$rs['className'].".php";
		
			$className =  $rs['className'];
			$instance = new $className();
			return $instance;
		}else{
			echo "your admin path in-correct!!";
			return false;
		}
	}
	function showDashboard(){
		include_once "DashboardManager.php";
		$dashboard = new DashboardManager(null);
		$output = $dashboard->load();
		$statistikArticle = $dashboard->getArticleStatistik();
		$this->View->assign("jml_unpublish",$statistikArticle['jml_unpublish']);
		$this->View->assign("jml_publish",$statistikArticle['jml_publish']);
		$this->View->assign("jml_inactive",$statistikArticle['jml_inactive']);
		$this->View->assign("DASHBOARD_CONTENT",$output);
		$this->View->assign("user",array("username"=>$this->Session->getVariable("username")));
		$this->View->assign("content",$this->View->toString("common/admin/dashboard.html"));
	}
	function showAdminPage(){
        $this->View->assign("user",array("username"=>$this->Session->getVariable("username")));
		return $this->View->toString("common/admin/admin.html");
	}
	function toString(){
		return $this->strHTML;
	}
	function showLoginPage(){
		if(@mysql_escape_string($_GET['f'])==1){
			$this->View->assign("msg","Access Denied !");
		}
		return $this->View->toString("common/admin/login.html");
	}
	function execute($obj,$reqID){
		if($this->perm->isAllowed($reqID)){
			if($obj->autoconnect){
				$obj->open();
				$this->View->assign("content",$obj->admin());
				$obj->close();
			}else{
				$this->View->assign("content",$obj->admin());
			}
		}else{
			$this->View->assign("content","Access Denied !");
		}
		if($this->DEBUG){
			print $obj->getMessage();
		}
	}
	function attach($obj,$reqID,$arr,$adminMode=true){
		if($adminMode){
			if($this->perm->isAllowed($reqID)){
				$obj->open();
				
				for($i=0;$i<sizeof($arr);$i++){
					$this->View->assign("addon_".$arr[$i],$obj->addon($arr[$i]));	
				}
				$obj->close();
				
			}
		}else{
			for($i=0;$i<sizeof($arr);$i++){
				$this->View->assign("addon_".$arr[$i],$obj->addon($arr[$i]));	
			}
		}
		
		
		if($this->DEBUG){
			print $obj->getMessage();
		}
		
	
	}
	//helper methods
	function _get($name){
		return $this->Request->getParam($name);
	}
	function _post($name){
		return $this->Request->getPost($name);
	}
	function _request($name){
		return $this->Request->getRequest($name);
	}
	/**
	 * lazy alias of _post
	 */
	function _p($name){
		return $this->_post($name);
	}
	/**
	 * lazy alias of _get
	 */
	function _g($name){
		return $this->_get($name);
	}
}
?>
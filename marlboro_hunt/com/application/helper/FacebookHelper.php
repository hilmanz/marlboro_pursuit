<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/facebook/facebook.php";
class FacebookHelper {
	var $fb;
	var $user_id;
	var $me;
	var $_access_token;
	var $logger;
	function __construct($apps=null){
		global $logger;
		$this->apps = $apps;
		$this->logger = $logger;
		$this->init();
		
	}
	
	function init(){
		global $FB,$CONFIG,$thisMobile;
	
			$this->fb = new Facebook(array(
			  'appId'  => $FB['appID'],
			  'secret' => $FB['appSecret'],
			));
			$this->logger->log('[FACEBOOK] : access API ');
			
			if(isset($thisMobile)) $loginReqUri = "{$CONFIG['MOBILE_SITE']}?{$this->apps->Request->encrypt_params(array('page'=>'login'))}";
			else $loginReqUri = "{$CONFIG['BASE_DOMAIN']}?{$this->apps->Request->encrypt_params(array('page'=>'login'))}";
			
			// pr($this->fb->getUser());
			try{
				if($this->fb->getUser()){
					try{
						
						$this->logger->log('[FACEBOOK] [LOGIN ] : Success login, got logout url ');
						$this->fb->setExtendedAccessToken();
						$data['ac'] = $this->fb->getAccessToken();
						$data['user'] =$this->fb->getUser();
						$data['userProfile']['socimg']= "https://graph.facebook.com/{$this->fb->getUser()}/picture?type=square&return_ssl_resources=1";
						$data['userProfile']= $this->fb->api('/me');
						if(isset($thisMobile))$params['next'] = "{$CONFIG['BASE_DOMAIN']}logout.php";
						else $params['next'] = "{$CONFIG['MOBILE_SITE']}logout.php";
						
						if($this->apps->isUserOnline()){
							$data['urlConnect'] =$this->fb->getLogoutUrl($params);
						}else{
							$params = array('scope' => 'email,user_status,user_activities,publish_actions,user_likes,read_friendlists,user_about_me,user_location,publish_stream,user_relationships,read_stream','redirect_uri'=>"{$loginReqUri}");
							$data['urlConnect'] =$this->fb->getLoginUrl($params);
						}
						$this->apps->session->setSession('facebook_session','facebook',$data);
					}catch (Exception $e){
					
						$this->logger->log('[FACEBOOK] [LOGIN ] : failed to login , get url login ');
							
						$params = array('scope' => 'email,user_status,user_activities,publish_actions,user_likes,read_friendlists,user_about_me,user_location,publish_stream,user_relationships,read_stream','redirect_uri'=>"{$loginReqUri}");
						$data['urlConnect'] =$this->fb->getLoginUrl($params);
						$this->apps->session->setSession('facebook_session','facebook',$data);
						
					}		
								
				}else {
				
					$this->logger->log('[FACEBOOK] : get login url ');
					
					$params = array('scope' => 'email,user_status,user_activities,publish_actions,user_likes,read_friendlists,user_about_me,user_location,publish_stream,user_relationships,read_stream','redirect_uri'=>"{$loginReqUri}");
					$data['urlConnect'] =$this->fb->getLoginUrl($params);
					$this->apps->session->setSession('facebook_session','facebook',$data);
				}
			}catch (Exception $e){
			
					$this->logger->log('[FACEBOOK] : get login url , failed authorize ');
					
						$params = array('scope' => 'email,user_status,user_activities,publish_actions,user_likes,read_friendlists,user_about_me,user_location,publish_stream,user_relationships,read_stream','redirect_uri'=>"{$loginReqUri}");
						$data['urlConnect'] =$this->fb->getLoginUrl($params);
						$this->apps->session->setSession('facebook_session','facebook',$data);
			}	
	}
	
	
	
}
?>
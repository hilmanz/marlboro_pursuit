<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/gplus/apiClient.php";
include_once $ENGINE_PATH."Utility/gplus/contrib/apiPlusService.php";
include_once $ENGINE_PATH."Utility/gplus/contrib/apiOauth2Service.php";
class GPlusHelper {
	var $me;
	var $_access_token;
	var $client;
	var $plus;
	var $registered = FALSE;
	var $logger;
	public function __construct($apps=null){
		global $logger;
		$this->apps = $apps;
		$this->logger = $logger;
		
	}
	public function init(){
		
		global $GPLUS,$thisMobile;
		
		$this->client = new apiClient();
		$this->client->setApplicationName('Google+ PHP Starter Application');
		// Visit https://code.google.com/apis/console?api=plus to generate your
		// client id, client secret, and to register your redirect uri.
		$this->client->setClientId($GPLUS['client_id']);
		$this->client->setClientSecret($GPLUS['client_secret']);
		
		if(isset($thisMobile)) $this->client->setRedirectUri($GPLUS['redirect_url_mobile']);
		else $this->client->setRedirectUri($GPLUS['redirect_url']);
		
		$this->client->setDeveloperKey($GPLUS['developer_key']);
		$this->client->setScopes(array("https://www.googleapis.com/auth/plus.me","https://www.googleapis.com/auth/userinfo.profile","https://www.googleapis.com/auth/userinfo.email"));
		$this->plus = new apiPlusService($this->client);
		$this->initSession();
		
		$this->logger->log('[GPLUS] : access API ');
			
	}
	private function initSession(){
		if(isset($this->apps->session->getSession('gplus_session','gplus_permission')->loginPermission)){
			global $CONFIG,$thisMobile;
					//check if the user has been redirected back from google+ login form.
				if($this->apps->session->getSession('gplus_session','gplus_permission')->loginPermission==true) {
					$this->logger->log('[GPLUS][LOGIN] : google must Login');
					if(!$this->apps->_g('state')){
					
						if ($this->apps->_g('code')) {
							try{
								$this->client->authenticate();
								$data['token'] = $this->client->getAccessToken();
								$this->apps->session->setSession('gplus_session','gplus_token',$data);
									$this->logger->log('[GPLUS][LOGIN] : google get Access Token');
							}catch (Exception $e){
									$this->logger->log('[GPLUS][LOGIN]  : google authenticate and access token failed');
								
									if(isset($thisMobile)) sendRedirect($CONFIG['MOBILE_SITE']);
									else sendRedirect($CONFIG['BASE_DOMAIN']);
									exit;										
							}
							$data['loginPermission'] = false;
							$this->apps->session->setSession('gplus_session','gplus_permission',$data);		
							if(!$this->apps->session->get('user')){
								if(isset($thisMobile))  header("location:{$CONFIG['MOBILE_SITE']}?page=login");
								else header("location:{$CONFIG['BASE_DOMAIN']}?page=login");
								exit;
							}else{
								if(isset($thisMobile)) sendRedirect("{$CONFIG['MOBILE_SITE']}?page=login");
								else sendRedirect("{$CONFIG['BASE_DOMAIN']}?page=login");
								exit;
							}							
							// header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
						}else{
							$data['token'] = false;
							$this->apps->session->setSession('gplus_session','gplus_token',$data);
						}
					}
				}else{
					$this->logger->log('[GPLUS][LOGIN] : google get Authorize User ');
					$this->getAuthorizeUser();
				}
			
			}
			
			$data['loginPermission'] = false;
			$this->apps->session->setSession('gplus_session','gplus_permission',$data);
			return false;
			
			
			
		
		//-->
	}
	
	function getAuthorizeUser(){
		global $CONFIG,$thisMobile;
		
		if (@isset($this->apps->session->getSession('gplus_session','gplus_token')->token)){
			
			if (!$this->apps->session->getSession('gplus_session','gplus_token')->token) return false;
				try{
				$this->client->setAccessToken($this->apps->session->getSession('gplus_session','gplus_token')->token);
				$this->logger->log('[GPLUS] : google set Access Token');
				}catch (Exception $e){
				$this->logger->log('[GPLUS] : google set access token failed ');
				if(isset($thisMobile)) sendRedirect($CONFIG['MOBILE_SITE']);
				else sendRedirect($CONFIG['BASE_DOMAIN']);
				exit;					
			}		
			
			//update me
			
			if(@!isset($this->apps->session->getSession('gplus_session','gplus')->userProfile)){
			
				$oauth2 = new apiOauth2Service($this->client);
				
				try{
				$this->me = $oauth2->userinfo->get();
				// pr($this->me);exit;
				// $this->me =  $this->plus->people->get('me');
			
				unset($oauth2);
				
				$this->logger->log('[GPLUS] : get user profile');
				
				$data['userProfile'] = $this->me;
				$data['login'] = true;
				$data['user'] = $this->me['id'];
				
				//gplus data
				try{
					$gplus_profile = @$this->plus->people->get($this->me['id']);
					$data['gplusimage'] = @$gplus_profile['image']['url'];
				}catch(exception $e){}
				
				$this->apps->session->setSession('gplus_session','gplus',$data);
				$this->sync_gplus();
				$data['loginPermission'] = false;
				$this->apps->session->setSession('gplus_session','gplus_permission',$data);
					
				return true;
				}catch (Exception $e){
					$this->logger->log('[GPLUS] : google failed oauth service  ');
					// sendRedirect($CONFIG['BASE_DOMAIN']);
					exit;	
				}
			}

			
			
		}
		$this->logger->log('[GPLUS] : google cannot found current acces token session');
		return false;
	
	}
	
	public function isLogin(){
		$this->init();
		if(@$this->client->getAccessToken()){
			return true;
		}else return false;
	}
	public function getLoginUrl(){
		$this->init();
		$data['urlConnect'] = $this->client->createAuthUrl();
		
		$this->logger->log('[GPLUS] : got login url '.$data['urlConnect']);
			
		$this->apps->session->setSession('gplus_session','gplus',$data);
		return $data['urlConnect'];
	}
	/**
	 * @todo
	 * add functionality to synchronize / integrate the google+ id into axis's database
	 */
	public function sync_gplus(){
		
		$this->registered = false;
	}
	public function is_registered(){
		return $this->registered;
	}
}
?>
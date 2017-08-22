<?php
/**
 * @author duf
 *
 */
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Twitter/tmhOAuth.php";
include_once $ENGINE_PATH."Utility/Twitter/tmhUtilities.php";

class twitterHelper {

	var $tmhOAuth;
	var $oauth;
		
	function __construct($apps=null){
		$this->apps = $apps;
		
	}
	
	function init(){
		global $TWITTER;
		// pr('1');
		// pr($this->apps->session->getSession('twitter_session','twitter'));
			$this->tmhOAuth = new tmhOAuth(array(
							  'consumer_key'    => $TWITTER['CONSUMER_KEY'],
							  'consumer_secret' =>  $TWITTER['CONSUMER_SECRET']
							));
		// pr($this->tmhOAuth);exit;
	}
	
	function authorize(){
		global $CONFIG,$thisMobile;
		if(@strip_tags($this->apps->_g('oauth_verifier'))){
			if(@$this->apps->session->getSession('twitter_session','twitter_permission')->loginPermission){
				$this->init();
				
				$request_code = unserialize(urldecode64(@$this->apps->session->getSession('twitter_session','twitter')->c));
				//$this->tmhOAuth->config['user_token']  = $request_code['oauth_token'];
				//$this->tmhOAuth->config['user_secret'] = $request_code['oauth_token_secret'];
				$this->tmhOAuth->config['user_token']  = strip_tags($_REQUEST['oauth_token']);
				$this->tmhOAuth->config['user_secret'] = $request_code['oauth_token_secret'];
			
				$code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url('oauth/access_token', ''), 
												array(
												'oauth_verifier' => strip_tags($_REQUEST['oauth_verifier'])
												)
				);
			
				if ($code == 200) {
					$access_token = $this->tmhOAuth->extract_params($this->tmhOAuth->response['response']);
					$data['twitter_id'] = $access_token['screen_name'];
					$data['token']= $access_token['oauth_token'];
					$data['secret'] = $access_token['oauth_token_secret'];
					$data['user'] = $access_token['screen_name'];
						$userprofile['name'] =  $access_token['screen_name'];
						$userprofile['gender'] =  $access_token['screen_name']; //ga ketemu
						$userprofile['email'] =  $access_token['screen_name']; //ga ketemu
					$userprofile['socimg']= "https://api.twitter.com/1/users/profile_image/{$access_token['screen_name']}";
					$data['userProfile'] = $userprofile;
					$data['login'] = true;
					$this->apps->session->setSession('twitter_session','twitter',$data);
					$permission['loginPermission'] = false;
					$this->apps->session->setSession('twitter_session','twitter_permission',$permission);
					if(!$this->apps->session->get('user')){
						if(isset($thisMobile))	header("location:{$CONFIG['MOBILE_SITE']}?page=login");
						else header("location:{$CONFIG['BASE_DOMAIN']}?page=login");
						exit;
					}else{
						if(isset($thisMobile))	header("location:{$CONFIG['MOBILE_SITE']}?page=login");
						else header("location:{$CONFIG['BASE_DOMAIN']}?page=login");
						exit;
					}
				}			
			
			
			}
		}
		
		if($this->apps->_g("loginType")=="twitter"){
			$permission['loginPermission'] = false;
			$this->apps->session->setSession('twitter_session','twitter_permission',$permission);
			if(isset($thisMobile))	header("location:{$CONFIG['MOBILE_SITE']}?page=login");
			else header("location:{$CONFIG['BASE_DOMAIN']}?page=login");
			exit;
		}
		
		return false;
	}
	
	function request_login_link(){
		global $TWITTER,$thisMobile,$logger;
		$this->init();
		$logger->info("thisMobile->{$thisMobile}");
		if(isset($thisMobile))$callbackurl = $TWITTER['LOGIN_CALLBACK_MOBILE'];
		else $callbackurl = $TWITTER['LOGIN_CALLBACK'];
   		$callback = isset($_REQUEST['oob']) ? 'oob' : $callbackurl;
   		$params = array(
    		'oauth_callback'=> $callback
   			// 'x_auth_access_type'=>'write'
  		);
		$logger->info(json_encode($params));
		$code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url('oauth/request_token', ''), $params);
		// pr($code);
		$logger->info("code : {$code}");
		
	  	if ($code == 200) {
		  //berhasil dapet access token
	    	$oauth = $this->tmhOAuth->extract_params($this->tmhOAuth->response['response']);
			$data['c'] = urlencode64(serialize($oauth));
	    	
		   	$method = 'authorize';
	    	$force  = '';
	    	$authurl = $this->tmhOAuth->url("oauth/{$method}", '') .  "?oauth_token={$oauth['oauth_token']}{$force}";
	    	$data['urlConnect'] = $authurl;
			$data['login'] = false;
			$this->apps->session->setSession('twitter_session','twitter',$data);
			$logger->info(json_encode($data));
		
	  	} else {
	    	return false;
	  	}
	}

	function remove_tweet(){
	
		if($twitter['token']!=null && $twitter['secret']!=null){
			$this->tmhOAuth->config['user_token']  = $twitter['token'];
			$this->tmhOAuth->config['user_secret'] = $twitter['secret'];
			$id = $this->apps->Request->getParam("id");
			if(strlen($id)>8){
				$code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url("1/statuses/destroy/{$id}"));
				if($code==200){
					//flag deleted
					//$this->flag_deleted_tweet($this->user_id,$twitter['twitter_id'],$id);
					return false; //the tweet has been deleted successfully'
				}else{
					return false ;//tweet not found
				}
			}else{
				return  false; //Cannot remove tweet. u need to specify the tweet id
			}
		}
		return  false; //unauthorized access
	}
	
	function update_tweet($update=null){
		$this->init();
		$request_code = $this->apps->session->getSession('twitter_session','twitter');
		$this->tmhOAuth->config['user_token']  = $request_code->token;
		$this->tmhOAuth->config['user_secret'] = $request_code->secret;

		$params = array('status' => str_replace("\\\\\\", "", $update));
		$updateStatus = $this->tmhOAuth->request('POST', $this->tmhOAuth->url("1.1/statuses/update"), $params);
		if($updateStatus == 200){
			echo "Success";exit;
		}else{
			echo "Updating twitter status is failed, please try again.";exit;
		}
		
	}
	
	function getUserLogin(){
		return @$this->apps->session->getSession('twitter_session','twitter')->login;
	}
	function getUserTimeline($since_id=0,$count=5){
		
		$this->init();
		$request_code = $this->apps->session->getSession('twitter_session','twitter');
		
		$this->tmhOAuth->config['user_token']  = $request_code->token;
		$this->tmhOAuth->config['user_secret'] = $request_code->secret;
		
		
		$params = array('screen_name' => $request_code->twitter_id);
		
		$status = $this->tmhOAuth->request('GET', $this->tmhOAuth->url("1.1/statuses/home_timeline"), $params);
		
		if($status == 200){
			$rs = json_decode($this->tmhOAuth->response['response'],true);
			return $rs;
		}else{
			return array();
		}
	}
	function getHomeTimeline(){
		
		$this->init();
		$request_code = $this->apps->session->getSession('twitter_session','twitter');
		
		$this->tmhOAuth->config['user_token']  = $request_code->token;
		$this->tmhOAuth->config['user_secret'] = $request_code->secret;
		
		// $params = array('screen_name' => $request_code->twitter_id,"since_id"=>$since_id,"count"=>$count);
		
		$status = $this->tmhOAuth->request('GET', $this->tmhOAuth->url("1.1/statuses/home_timeline"));
		
		if($status == 200){
			$rs = json_decode($this->tmhOAuth->response['response'],true);
			// pr($rs);exit;
			return $rs;
		}else{
			return array();
		}
	}
}
?>
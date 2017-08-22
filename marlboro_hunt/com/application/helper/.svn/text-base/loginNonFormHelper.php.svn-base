<?php

class loginNonFormHelper {
	
	var $_mainLayout="";
	
	var $login = false;
		
	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
	
		if(isset($_SESSION['lid'])) $this->lid = intval($_SESSION['lid']);
		else $this->lid = 1;
		if($this->lid=='')$this->lid=1;
		$this->server = $CONFIG['VIEW_ON'];
		if( $this->apps->session->get('user') ){
			
			$this->login = true;
		
		}
	}
	
		
	function checkLogin(){
		
		return $this->login;
	}
	
	//this function without login form with input phone number, 'mr. inong request'
	function loginin($mobile=false){
		// echo 'masuk';
		// exit;
		$ok = false;
				
		if($this->getAccess()){
			$this->apps->log('login','');
			
			return true;
			
		}else{
			
			$this->apps->log('access_denied','');
			
			return false;
		}
	}
	
	function getAccess(){
	
		//check social media logon
		if(!$this->checkSocialMediaSession()) return false;
		// pr($this->session->getSession('facebook_session','facebook'));exit;
					// echo 'masuk';exit;
				 $arrSocialMedia['facebook'] = false;
				 $arrSocialMedia['twitter'] = false;
				 $arrSocialMedia['gplus'] = false;
				 
				
				if(@isset($this->apps->session->getSession('twitter_session','twitter')->user)) {
					$arrSocialMedia['twitter'] = true;
					$qSocial[]= "  twitter_id = '{$this->apps->session->getSession('twitter_session','twitter')->user}' ";
					$profileImage = "https://api.twitter.com/1/users/profile_image/{$this->apps->session->getSession('twitter_session','twitter')->user}";
				}
				if(@isset($this->apps->session->getSession('gplus_session','gplus')->user)) {
					$arrSocialMedia['gplus'] = true;
					$qSocial[]= "  gplus_id = '{$this->apps->session->getSession('gplus_session','gplus')->user}' ";
					if(@$this->apps->session->getSession('gplus_session','gplus')->userProfile->picture){
						$profileImage = "{$this->apps->session->getSession('gplus_session','gplus')->userProfile->picture}";
					}else $profileImage = false;
				}
				if(@isset($this->apps->session->getSession('facebook_session','facebook')->user)) {
					$arrSocialMedia['facebook'] = true;
					$qSocial[]= "  fb_id = '{$this->apps->session->getSession('facebook_session','facebook')->user}' ";
					$profileImage = "https://graph.facebook.com/{$this->apps->session->getSession('facebook_session','facebook')->user}/picture?type=square&return_ssl_resources=1";
				}	
				$this->logger->log('check unique ID');
				
			if(!$qSocial) return false;
			$rs = $this->checkuserexisting($qSocial);			
				
				if(!$rs){				
					$this->logger->log('unique id and phone not found or match');
					$this->apps->socialMediaHelper->addSocialMediaNoneAxis();
					$rs = $this->checkuserexisting($qSocial);	
					if(!$rs) return false;
					$rs['socmed'] = $profileImage;
					$this->apps->session->set('user',urlencode64(json_encode($rs)));					
				}else {
					//update social id
					$rs['socmed'] = $profileImage;
					$this->logger->log('social id found');				
					$this->apps->session->set('user',urlencode64(json_encode($rs)));				
				}
						
			
			$this->login = true;
			return true;
			
	}
	
	function checkuserexisting($qSocial=null){
		if($qSocial==null) return false;
		if(count($qSocial) > 1 ) {
				$strQSocial = implode(' OR ',$qSocial);
				$strQSocial = "( $strQSocial )";
			}else $strQSocial = $qSocial[0];
							
			$sql = "
			SELECT id,name,nickname,email,register_date,sex,birthday,phone_number,fb_id,twitter_id,gplus_id,img
			FROM social_member 
			WHERE 
			n_status=1  
			AND {$strQSocial}
			LIMIT 1";
			$rs = $this->apps->fetch($sql);	
			// pr($sql);exit;
			if(!$rs) return false;
			return $rs;
	}
	
	function add_stat_login($user_id){
	
	
	$sql ="UPDATE social_member SET last_login=now(),login_count=login_count+1 WHERE id=".$user_id." LIMIT 1";
	$rs = $this->apps->query($sql);

	
	}
	
	function getProfile(){
		
		$user = json_decode(urldecode64($this->apps->session->get('user')));
		
		return $user;
	
	}
	
	
	function checkSocialMediaSession(){
	
		if(@isset($this->apps->session->getSession('facebook_session','facebook')->user)) $arrSoMed[] = true;
		else $arrSoMed[] = false;
		
		if(@isset($this->apps->session->getSession('twitter_session','twitter')->user)) $arrSoMed[] = true;
		else $arrSoMed[] = false;
		
		if(@isset($this->apps->session->getSession('gplus_session','gplus')->user)) $arrSoMed[] = true;
		else $arrSoMed[] = false;
		
		// pr($arrSoMed);exit;
		if(in_array(true,$arrSoMed)) return true;
		else return false;
	
	}
	
	
	
}

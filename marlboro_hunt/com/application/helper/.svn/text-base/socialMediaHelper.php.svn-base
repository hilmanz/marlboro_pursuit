<?php

class socialMediaHelper {
	
	function __construct($apps=null){
		global $logger;
		$this->apps = $apps;
		$this->logger = $logger;
				
	}
	
	function checkUserUniqueID(){
		
	
		$phoneNumber = @$this->apps->session->getSession('request_session','data')->phonenumber;
			$sql ="SELECT count(*) as total FROM social_member where phone_number='{$phoneNumber}'  LIMIT 1 ";
			$qData = $this->apps->fetch($sql);
			$this->logger->log($sql);
		if($qData['total'] > 0 ) return true;
		else return false;
	
		
	} 
	
	function checkUserSocialID($type=null){
		if($type==null) return false;
	
		if($type['facebook']==true)	$arrSoMed['facebook'] = $this->syncSocialMedia('facebook');
		if($type['twitter']==true)  $arrSoMed['twitter'] = $this->syncSocialMedia('twitter');
		if($type['gplus']==true) $arrSoMed['gplus'] = $this->syncSocialMedia('gplus');
		if(isset($arrSoMed)){
			if(in_array(true,$arrSoMed)) return true;
		}
		return false;
	}
	
	//add for new user only
	function addSocialMedia(){
		$this->addSocialMediaData();
	}
	function addSocialMediaNoneAxis(){
		$this->addSocialMediaDataNoneAxis();
	}
	//sync to update user social media idonly
	function syncSocialMedia($type=null){
		if($type==null) return false;
		
		if($type=='facebook')  return $this->syncFacebook();
		if($type=='twitter' )  return $this->syncTwitter();
		if($type=='gplus' )  return $this->syncGPlus();
		return false;
	}
	
	function syncFacebook(){
		
		
		if(@isset($this->apps->session->getSession('facebook_session','facebook')->user)) {
			$this->logger->log('HAVE FB USER ID');
			$fb_id = @$this->apps->session->getSession('facebook_session','facebook')->user;
			$sex = @$this->apps->session->getSession('facebook_session','facebook')->userProfile->gender; 	
			$email =  @$this->apps->session->getSession('facebook_session','facebook')->userProfile->email;
			$phonenumber = @$this->apps->session->getSession('request_session','data')->phonenumber;
		
			if(($fb_id!=null)&&($phonenumber!=null)){
				$this->logger->log('MATCH PHONE N FB USER ID');
				$sql ="SELECT count(*) as total FROM social_member where fb_id='{$fb_id}' AND phone_number='{$phonenumber}' LIMIT 1 ";
				$qData = $this->apps->fetch($sql);
				$this->logger->log('check fb and phone '.$sql);
				if($qData['total'] > 0 ) return true;
				
				$sql = " UPDATE social_member SET fb_id='{$fb_id}', email='{$email}' ,sex = '{$sex}' WHERE phone_number='{$phonenumber}'  LIMIT 1";
				$this->logger->log('update fb where phone  '.$sql);
				$uData = $this->apps->query($sql);
				if($uData) return true;
				else return false;
			}else {
			$this->logger->log('NOT MATCH PHONE N FB USER ID');
			return false;
			}
			
			
		}else{
			$this->logger->log('LOST FB USER ID');
		return false;
		}
	
	
	}
	
	function syncTwitter(){
	
		if(@isset($this->apps->session->getSession('twitter_session','twitter')->user)){
			$this->logger->log('HAVE TWITTER USER ID');
			$twitter_id = @$this->apps->session->getSession('twitter_session','twitter')->user;
			$phonenumber = @$this->apps->session->getSession('request_session','data')->phonenumber;
			
			if(($twitter_id!=null)&&($phonenumber!=null)){
			$this->logger->log('TWITTER USER ID PHONENUMBER TRUE');
				$sql ="SELECT count(*) as total FROM social_member where twitter_id='{$twitter_id}' AND phone_number='{$phonenumber}' LIMIT 1 ";
				$qData = $this->apps->fetch($sql);
				$this->logger->log('check tw and phone '.$sql);
				if($qData['total'] > 0 ) return true;
				
				
				$sql = " UPDATE social_member SET twitter_id='{$twitter_id}' WHERE phone_number='{$phonenumber}'  LIMIT 1";
				$this->logger->log('update tw where phone '.$sql);
				$uData = $this->apps->query($sql);
				if($uData) return true;
				else return false;
			}else {
			$this->logger->log('TWITTER USER ID PHONENUMBER FALSE');
			return false;
			}
		}else {
		$this->logger->log('LOST TWITTER USER ID');
		return false;
		}		
	
	}
	
	function syncGPlus(){
	
	
		if(@isset($this->apps->session->getSession('gplus_session','gplus')->user)) {
			$this->logger->log('HAVE GPLUS USER ID');
			$gplus_id = @$this->apps->session->getSession('gplus_session','gplus')->user;
			$email =  @$this->apps->session->getSession('gplus_session','gplus')->userProfile->email;
			$phonenumber = @$this->apps->session->getSession('request_session','data')->phonenumber;
			
			if(($gplus_id!=null)&&($phonenumber!=null)){
				$this->logger->log('MATVH PHONE NGPLUS USER ID');
				$sql ="SELECT count(*) as total FROM social_member where gplus_id='{$gplus_id}' AND phone_number='{$phonenumber}' LIMIT 1 ";
				$qData = $this->apps->fetch($sql);
				$this->logger->log('check gp and phone '.$sql);
				if($qData['total'] > 0 ) return true;
											
				$sql = " UPDATE social_member SET gplus_id='{$gplus_id}',email='{$email}' WHERE phone_number='{$phonenumber}'  LIMIT 1";
				$this->logger->log('update gp where phone '.$sql);
				$uData = $this->apps->query($sql);
				if($uData) return true;
				else return false;
			}else {
			$this->logger->log('NOT MATVH PHONE NGPLUS USER ID');
			return false;
			}
		
		}else {
		$this->logger->log('LOST GPLUS USER ID');
		return false;
		}
	}
	
	
	
	function addSocialMediaData(){
		global $CONFIG;
		$arrSomed[] = false;
		
		if(@$this->apps->session->getSession('facebook_session','facebook')->user) $arrSomed[] = true;
		if(@$this->apps->session->getSession('twitter_session','twitter')->user) $arrSomed[] = true;
		if(@$this->apps->session->getSession('gplus_session','gplus')->user) $arrSomed[] = true;
		if(in_array(true,$arrSomed)){
			$gplus_id = @$this->apps->session->getSession('gplus_session','gplus')->user;
			$twitter_id = @$this->apps->session->getSession('twitter_session','twitter')->user;
			$fbid = @$this->apps->session->getSession('facebook_session','facebook')->user;
			
			//email
			if(@$this->apps->session->getSession('twitter_session','twitter')->userProfile->email) $email =  @$this->apps->session->getSession('twitter_session','twitter')->userProfile->email;
			if(@$this->apps->session->getSession('gplus_session','gplus')->userProfile->email) $email =  @$this->apps->session->getSession('gplus_session','gplus')->userProfile->email;
			if(@$this->apps->session->getSession('facebook_session','facebook')->userProfile->email) $email =  @$this->apps->session->getSession('facebook_session','facebook')->userProfile->email;
			
			//name
			if(@$this->apps->session->getSession('twitter_session','twitter')->userProfile->name) $name =  @$this->apps->session->getSession('twitter_session','twitter')->userProfile->name;
			if(@$this->apps->session->getSession('gplus_session','gplus')->userProfile->name) $name =  @$this->apps->session->getSession('gplus_session','gplus')->userProfile->name;
			if(@$this->apps->session->getSession('facebook_session','facebook')->userProfile->name) $name =  @$this->apps->session->getSession('facebook_session','facebook')->userProfile->name;
			
			//gender
			if(@$this->apps->session->getSession('twitter_session','twitter')->userProfile->gender) $sex = @$this->apps->session->getSession('twitter_session','twitter')->userProfile->gender; 
			if(@$this->apps->session->getSession('gplus_session','gplus')->userProfile->gender) $sex = @$this->apps->session->getSession('gplus_session','gplus')->userProfile->gender; 
			if(@$this->apps->session->getSession('facebook_session','facebook')->userProfile->gender) $sex = @$this->apps->session->getSession('facebook_session','facebook')->userProfile->gender; 
			
			$phonenumber = trim($this->Request->getPost('phonenumber'));
			$password = trim($this->Request->getPost('password'));
			
			$hashPass = sha1($password.$phonenumber.$CONFIG['salt']);
			
			$sql = " 
			INSERT INTO social_member 
			(fb_id,twitter_id,gplus_id,email,name,sex,phone_number,password,register_date,salt,n_status) 
			VALUES ('{$fbid}','{$twitter_id}','{$gplus_id}','{$email}','{$name}','{$sex}','{$phonenumber}','{$hashPass}',NOW(),'{$CONFIG['salt']}',1)";
			$this->logger->log($sql);
			$this->apps->query($sql);
			return true;
	
			}else return false;
			
		
	
	
	}
	
	function addSocialMediaDataNoneAxis(){
		global $CONFIG;
		$arrSomed[] = false;
		
		if(@$this->apps->session->getSession('facebook_session','facebook')->user) $arrSomed[] = true;
		if(@$this->apps->session->getSession('twitter_session','twitter')->user) $arrSomed[] = true;
		if(@$this->apps->session->getSession('gplus_session','gplus')->user) $arrSomed[] = true;
		
		$this->logger->log('arr somed in '.$arrSomed);
		if(in_array(true,$arrSomed)){
			$gplus_id = @$this->apps->session->getSession('gplus_session','gplus')->user;
			$twitter_id = @$this->apps->session->getSession('twitter_session','twitter')->user;
			$fbid = @$this->apps->session->getSession('facebook_session','facebook')->user;
			$email = '';
			$name = '';
			$sex = '';
			//email
			if(@$this->apps->session->getSession('twitter_session','twitter')->userProfile->email) $email =  @$this->apps->session->getSession('twitter_session','twitter')->userProfile->email;
			if(@$this->apps->session->getSession('gplus_session','gplus')->userProfile->email) $email =  @$this->apps->session->getSession('gplus_session','gplus')->userProfile->email;
			if(@$this->apps->session->getSession('facebook_session','facebook')->userProfile->email) $email =  @$this->apps->session->getSession('facebook_session','facebook')->userProfile->email;
			
			//name
			if(@$this->apps->session->getSession('twitter_session','twitter')->userProfile->name) $name =  @$this->apps->session->getSession('twitter_session','twitter')->userProfile->name;
			if(@$this->apps->session->getSession('gplus_session','gplus')->userProfile->name) $name =  @$this->apps->session->getSession('gplus_session','gplus')->userProfile->name;
			if(@$this->apps->session->getSession('facebook_session','facebook')->userProfile->name) $name =  @$this->apps->session->getSession('facebook_session','facebook')->userProfile->name;
			
			//gender
			if(@$this->apps->session->getSession('twitter_session','twitter')->userProfile->gender) $sex = @$this->apps->session->getSession('twitter_session','twitter')->userProfile->gender; 
			if(@$this->apps->session->getSession('gplus_session','gplus')->userProfile->gender) $sex = @$this->apps->session->getSession('gplus_session','gplus')->userProfile->gender; 
			if(@$this->apps->session->getSession('facebook_session','facebook')->userProfile->gender) $sex = @$this->apps->session->getSession('facebook_session','facebook')->userProfile->gender; 
			
			$phonenumber = rand(1,10000);
			$password = "";
			
			$hashPass = sha1($password.$phonenumber.$CONFIG['salt']);
			
			$sql = " 
			INSERT INTO social_member 
			(fb_id,twitter_id,gplus_id,email,name,sex,phone_number,password,register_date,salt,n_status) 
			VALUES ('{$fbid}','{$twitter_id}','{$gplus_id}',\"{$email}\",\"{$name}\",'{$sex}','{$phonenumber}','{$hashPass}',NOW(),'{$CONFIG['salt']}',1)";
			$this->logger->log($sql);
			$this->apps->query($sql);
			return true;
	
			}else return false;
			
		
	
	
	}
	
	
}

<?php

class loginHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		
		$this->config = $CONFIG;
		
		if( $this->apps->session->getSession($this->config['SESSION_NAME'],"dashboard") ){
			
			$this->login = true;
		
		}
	
	}
	
	function checkLogin(){
		
		return $this->login;
	}
	
	
	/* below function used as local login only: on development phase without MOP */
	
	function loginSession($mobile=false){
		$ok = false;
		
		if($this->apps->_p('login')==1){
		
			if($this->goLogin()){
		
				return true;
			}else{				
			
				return false;
			}
		}
		
		
		return false;
	}
	
	
	
	function goLogin(){
		global $logger, $APP_PATH;
			
		$username = trim($this->apps->_p('username'));
		$password = trim($this->apps->_p('password'));
		
	
		
		
		$enc_key = md5(base64_encode($username.md5($password)));
		$password = md5($password);
		$sql = "SELECT userID as id,userID,username,password,enc_key FROM gm_user
							WHERE username='".mysql_escape_string($username)."' 
							AND password='".$password."'";
		$rs = $this->apps->fetch($sql);
		$logger->log($sql);
		
		if($rs['username'] == $username && $rs['password'] == $password && $rs['enc_key'] = $enc_key){
			$this->setdatasessionuser($rs);
			$logger->log('can login');
			$this->login = true;
			return true;
		}else{
			return false;
		}
		
	}
	
	function setdatasessionuser($rs=false){
		if(!$rs) return false;
		
		$id = $rs['id'];
				
		$this->apps->session->setSession($this->config['SESSION_NAME'],"dashboard",$rs);
		
	}
	

	
	function getProfile(){
	
		$user = $this->apps->session->getSession($this->config['SESSION_NAME'],"dashboard");
		
		return $user;
	
	}
	
	
	
}

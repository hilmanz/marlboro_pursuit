<?php
class forgotpassword extends App{
		
	function beforeFilter(){
		global $CONFIG,$logger;
		$this->registerHelper = $this->useHelper('registerHelper');
		$this->loginHelper = $this->useHelper('loginHelper');
		$this->newsHelper = $this->useHelper('newsHelper');
		$this->assign('basedomain',$CONFIG['BASE_DOMAIN']);
	}
	
	function main(){
		global $CONFIG,$logger;
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign('basedomain',$basedomain);
	
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/forgotpassword.html');
	}
	
	function resetpassajax()
	{
		
		global $CONFIG;
		
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$email = $this->_p('email');
		$dontsendmail = false;
		$to= false;
		$from= false;
		$msg= false;
			$sql = "SELECT * FROM social_member WHERE email = '{$email}' AND n_status IN (1,2) LIMIT 1";
			$res = $this->fetch($sql);
		// pr($sql);
			$userdata = false;
			
			if ($res){
				
				// kirim akun login
				$to = $res['email'];
				$from = $CONFIG['EMAIL_FROM_DEFAULT'];
				$subject = "Data login";
				$hashpass = $this->substringshash(md5(date("ymdhis").$res['salt'].$res['id'].$res['name']),10);
				$password = sha1($res['salt'].$hashpass);
				if($res['login_count']==0) $password = $this->substringshash($password,10);
				
					$sql = "UPDATE social_member SET password = '{$password}' WHERE email = '{$to}' LIMIT 1";						
					$qData = $this->query($sql);
				if($qData){
						if($res['login_count']==0) $thepassword = $password;
						else $thepassword = $hashpass;
						
						$dataReset['email'] = $to;
						$dataReset['password'] = $res['password'];
						$token = urlencode64(serialize($dataReset));
						// pr($dataReset);
						// $msg = 'Username = '.$res['username'].'<br>';
						$msg = $basedomain.'forgotpassword/verified_token/'.$token;
					
						$userdata['email'] = $to;
						$userdata['firstname'] = $res['name'];
						$userdata['lastname'] = $res['last_name'];
						$userdata['username'] = $to;
						$userdata['password'] = $thepassword;
						$userdata['trackingcode'] = "";
					}
					
					// } else {
						// $sql = "UPDATE social_member SET password = '{$res['password']}' WHERE email = '{$email}'  LIMIT 1 ";					
						// $qData = $this->query($sql);
						// $dontsendmail = true;
					// }
				}
			if($userdata){
					
				$this->newsHelper->getEmailTemplate('forgotpassword',$userdata,'send');
				print json_encode(array('status'=>TRUE));
				/*
					$send_mail = $this->sendGlobalMail($to,$from,$msg, 2);
					if ($send_mail['result']){
						print json_encode(array('status'=>TRUE));
					}else{
						print json_encode(array('status'=>FALSE));
					}
				*/				
			
			}else print json_encode(array('status'=>FALSE));
		exit;
		
	}
	
	
	function verified_token()
	{
		global $CONFIG;
		$token = trim(strip_tags($this->_g('token')));
		
		$dataToken = @unserialize(urldecode64($token));
		
		
		// pr($dataToken);
		$sql = "SELECT * FROM social_member WHERE email = '{$dataToken['email']}' AND password = '{$dataToken['password']}' LIMIT 1";
		$res = $this->fetch($sql);
		
		if ($res){
			$this->loginHelper->setdatasessionuser($res);
			$this->log('login','welcome');
			$this->assign("msg","login-in.. please wait");
			sendRedirect("{$CONFIG['BASE_DOMAIN']}account/changePassword");
		}else{
			
			// echo 'ada';
			sendRedirect("{$CONFIG['BASE_DOMAIN']}login");
		}
	}
	
		
	function substringshash($hasher=null,$limit=10){
			if($hasher==null) return false;
			$strings = substr($hasher,0,$limit);
			
			return $strings;
	}

}
?>
<?php
class register extends App{
		
	function beforeFilter(){
		$this->registerHelper = $this->useHelper('registerHelper');
		$this->loginHelper = $this->useHelper('loginHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		$this->newsHelper = $this->useHelper('newsHelper');
		
	}
	
	function main(){
		global $CONFIG,$logger;
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign('basedomain',$basedomain);
		$this->log('globalAction','LOGIN');
		

		// $this->View->assign('choose_btn',$this->setWidgets('choose_btn'));
		$this->View->assign('register_bio',$this->setWidgets('register_bio'));
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'register.html');
	}
	
	function provinsi()
	{
		$idProvince = $this->_p('id');
		
		$province = $this->registerHelper->getCity($idProvince);
		print json_encode($province);exit;
	}
	
	
	function giid()
	{
		global $CONFIG,$logger;
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign('basedomain',$basedomain);
		$POST = false;

		
		if (($this->_p('submit')) AND ($this->_p('tokenQuiz') == 1)){
			
			$POST['brand_primary'] = trim($this->_p('brand_primary'));
			$POST['brand_secondary'] = trim($this->_p('brand_secondary'));
			$POST['question_mark'] = trim($this->_p('question_mark'));
			$POST['other_answer'] = trim($this->_p('other_answer'));
			$POST['anotherbrands'] = trim($this->_p('anotherbrands'));
			
			$this->session->setSession($CONFIG['SESSION_NAME'],'REGISTER_BRAND',$POST);
			
			$_POST = false;
			// $survey = $_POST['survey'];
			// if (is_array($survey)){
				// $dataSurvey = implode(',',$survey);
			// }
			
			// $consent = $_POST['consent'];
			// if (is_array($consent)){
				// $dataConsent = implode(',',$consent);
			// }
			
			// $POST = array('survey'=>$dataSurvey, 'consent'=>$dataConsent);
			// $this->session->set($CONFIG['SESSION_NAME']."SURVEY",$POST);
			// sendRedirect("{$CONFIG['BASE_DOMAIN']}register/confirm");
			// die();
			
		}
		
		$data = $this->session->getSession($CONFIG['SESSION_NAME'],'REGISTER');
		$dataBrand = $this->session->getSession($CONFIG['SESSION_NAME'],'REGISTER_BRAND');
		
		if ($data&&$dataBrand){	
			$this->View->assign('popup_giidlist',$this->setWidgets('popup_giidlist'));
			$this->View->assign('popup_upload_photo',$this->setWidgets('popup_upload_photo'));
			$this->View->assign('popup_upload_photo_webcam',$this->setWidgets('popup_upload_photo_webcam'));
			$this->View->assign('register_giid',$this->setWidgets('register_giid'));
		}else{
			
			$this->View->assign('questionaire',$this->setWidgets('questionaire'));
		}
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'register.html');
	}
	
	function upload()
	{
		global $CONFIG,$logger;
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign('basedomain',$basedomain);
		$this->log('globalAction','LOGIN');
		
		$this->View->assign('popup_giidlist',$this->setWidgets('popup_giidlist'));
		$this->View->assign('popup_upload_photo',$this->setWidgets('popup_upload_photo'));
		$this->View->assign('popup_upload_photo_webcam',$this->setWidgets('popup_upload_photo_webcam'));
		
		$this->View->assign('register_giid',$this->setWidgets('register_giid'));
		$arrData = false;
		
		if (($this->_p('upload'))){
			
			
			$email_token = strip_tags(trim($this->_p('token')));
			$sourceFile = $_FILES;
		
			$file['name'] = $sourceFile['image']['name'];
			$file['type'] = $sourceFile['image']['type'];
			$file['tmp_name'] = $sourceFile['image']['tmp_name'];
			$file['error'] = intval($sourceFile['image']['error']);
			$file['size'] = $sourceFile['image']['size'];
			
			
			
			$path = $CONFIG['LOCAL_PUBLIC_ASSET'].'user/gid/';
			
			if ($email_token){
				// upload berdasarkan email token
					if(!$file['error']==0){
						$uploadImage = false;
						// jalaninn fungsi kirim email sama code nya buat masuk url upload giid lagi
					}else $uploadImage = $this->uploadHelper->uploadThisImage($file, $path);
					
					$dataGiid['emailtoken'] = $email_token;
					$dataGiid['filename'] = $uploadImage['arrImage']['filename'];
					$dataGiid['filename_ori'] = $uploadImage['arrImage']['filename_ori'];
					$dataGiid['result'] = $uploadImage['result'];
					$this->session->setSession($CONFIG['SESSION_NAME'],'GIID_IMAGE',$dataGiid);
					
					$arrData = $dataGiid;
			}else{
				
				// upload normal, user langsung uload giid
				
				if(!$file['error']==0){
					$uploadImage = false;
					// jalaninn fungsi kirim email sama code nya buat masuk url upload giid lagi
				}else $uploadImage = $this->uploadHelper->uploadThisImage($file, $path);
				
				//$dataGiid['emailtoken'] = false;
				$dataGiid['filename'] = $uploadImage['arrImage']['filename'];
				$dataGiid['filename_ori'] = $uploadImage['arrImage']['filename_ori'];
				$dataGiid['result'] = $uploadImage['result'];
				
				$this->session->setSession($CONFIG['SESSION_NAME'],'GIID_IMAGE',$dataGiid);
				
				$arrData = $dataGiid;
			}
			
			// sendRedirect("{$CONFIG['BASE_DOMAIN']}register/upload");
			
			// $this->View->assign('register_giid',$this->setWidgets('register_giid'));
			if($arrData){
				if($arrData['result']) $result = true;
				else  $result = false;
			}else $result = false;
			
			print json_encode(array('result'=>$result,'dataGiid'=>$arrData));
			exit;
		}
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'register.html');	
	}
	
	function saveDataRegister()
	{
		global $CONFIG,$logger, $LOCALE;
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign('basedomain',$basedomain);
		$this->log('globalAction','LOGIN');
		
		$register = $this->session->getSession($CONFIG['SESSION_NAME'],'REGISTER');
		
		if (isset($register)){
			
			$resultRegister = $this->registerHelper->doRegister();
			if($resultRegister){
				$resultQuiz = $this->registerHelper->saveQuestion($resultRegister['id']);
				$setGiid = $this->registerHelper->register_setGid(array('id'=>$resultRegister['id']));
				$getIdUser = $this->registerHelper->register_not_validate(array('id'=>$resultRegister['id']));
				
				$to = $getIdUser['email'];
				$from = $CONFIG['EMAIL_FROM_DEFAULT'];
				// $subject = "Data login";
				
				$this->session->setSession($CONFIG['SESSION_NAME'],'REGID',array('regid'=>$resultRegister['id']));
				
				$sessGIID = $this->session->getSession($CONFIG['SESSION_NAME'],'GIID_IMAGE');
				if (array_key_exists('email_token',$sessGIID)){
					// kirim pesan dalam bentuk url
					$msg = " Your link upload is {$basedomain}register/trackingcode Token : {$getIdUser['email_token']} ";
					
					
					
					//$send_mail = $this->newsHelper->sendGlobalMail($to,$from,$msg);
					// $send_mail = $this->newsHelper->sendGlobalMail($to,$from,$msg);
					
					
				}
			
				
				if ($setGiid){
					return TRUE;
				}else{
					return FALSE;
				}
			
			}else return false;
		}
		
		return FALSE;
		//return $this->View->toString(TEMPLATE_DOMAIN_WEB .'register.html');	
	}
	
	
	function questionaire()
	{
		global $CONFIG;
		
		
		
		if ($this->_post('register') == 1){
		
			$POST['nickname'] =  trim($this->_p('nickname'));
			$POST['firstname'] =  trim($this->_p('firstname'));
			$POST['middlename'] =  trim($this->_p('middlename'));
			$POST['lastname'] =  trim($this->_p('lastname'));
			$POST['birthday'] =  trim($this->_p('birthday'));
			
			$exp = explode('/',$POST['birthday']);
			$POST['birthday'] = $exp[2].'-'.$exp[0].'-'.$exp[1];
			
			$POST['email'] =  trim($this->_p('email'));
			$POST['pre_mobile'] =  trim($this->_p('pre_mobile'));
			$POST['last_mobile'] =  trim($this->_p('last_mobile'));
			// $POST['pre_line'] =  trim($this->_p('pre_line'));
			// $POST['last_line'] =  trim($this->_p('last_line'));
			$POST['sex'] = trim($this->_p('sex'));
			$POST['house'] = trim($this->_p('house'));
			$POST['barangay'] = trim($this->_p('barangay'));
			$POST['province'] = trim($this->_p('province'));
			$POST['city'] = trim($this->_p('city'));
			$POST['zipcode'] = trim($this->_p('zipcode'));
			// $POST['refered_by'] = trim($this->_p('refered_by'));
			
			if($POST['firstname']==''||$POST['firstname']==null){
				return false;
			}
			if($POST['email']!= $POST['email']) {
				return false;
			}
	
			$this->session->setSession($CONFIG['SESSION_NAME'],'REGISTER',$POST);
			
			
		}
		
		// get session
		$sessi = $this->session->getSession($CONFIG['SESSION_NAME'],'REGISTER');
		
		
		$this->View->assign('questionaire',$this->setWidgets('questionaire'));
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'register.html');
	}
	
	function confirm(){
		global $CONFIG;
		
		$this->View->assign('popup_giidlist',$this->setWidgets('popup_giidlist'));
		/*
		$giidSessi = $this->session->getSession($CONFIG['SESSION_NAME'],'GIID_IMAGE');
		
		if (empty($giidSessi)){
			echo "<script>alert('Please choose option before to upload GIID');</script>";
			sendRedirect("{$CONFIG['BASE_DOMAIN']}register/giid");
			exit;
		}
		*/
		$email_token = $this->_p('email_token');
		// pr($email_token);
		// exit;
		if ($this->_p('token')){
			
			$POST['giid_type'] = trim(strip_tags($this->_p('giid_type')));
			$POST['giid_number'] = trim(strip_tags($this->_p('giid_number')));
			
			$this->session->setSession($CONFIG['SESSION_NAME'],'GIID_NUMBER',$POST);
			
			if ($email_token){
				$save = $this->registerHelper->register_setGid(array('email_token'=>$email_token));
			}else{
				$save = $this->saveDataRegister();
				
			}
			/*
			if ($save){
				echo "<script>alert('Email terkirim');</script>";
				//session_destroy();
			}else{
				echo "<script>alert('Email gagal terkirim');</script>";
				
			}*/
		
			
				
		}
		// else{
			// echo "<script>alert('Data belum valid');</script>";
		// }
		
		
		$this->View->assign('register_confirm',$this->setWidgets('register_confirm'));
			
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'register.html');	
	
	}
	
	function ajax()
	{
		global $CONFIG,$LOCALE;
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign("registerconfirm",$LOCALE[1]['thankyou']);	
		$this->assign('basedomain',$basedomain);
		
	
		if ($this->_p('later')){
			
				
			$POST['giid_type'] = trim(strip_tags($this->_p('giid_type')));
			$POST['giid_number'] = trim(strip_tags($this->_p('giid_number')));
			
			$this->session->setSession($CONFIG['SESSION_NAME'],'GIID_NUMBER',$POST);
			
			// upload later to generate email token
			$saveData = $this->registerHelper->doRegister();
			if($saveData) {
				$resultQuiz = $this->registerHelper->saveQuestion($saveData['id']);
				$getData = $this->registerHelper->register_not_validate(array('id'=>$saveData['id']));
				$userData = $this->session->getSession($CONFIG['SESSION_NAME'],'REGISTER');
				
				
				$to = $getData['email'];
				$from = $CONFIG['EMAIL_FROM_DEFAULT'];
				
				$urltrackingcode = "{$CONFIG['BASE_DOMAIN']}register/trackingcode";
				$trackingcode = "{$getData['email_token']}";
				
				$userdata['email'] = $to;
				$userdata['firstname'] =$userData->firstname;
				$userdata['lastname'] = $userData->lastname;
				$userdata['username'] = $to;
				$userdata['password'] = "" ;
				$userdata['trackingcode'] = $trackingcode;
				$userdata['url'] = $urltrackingcode;
			
				// $send_mail = $this->newsHelper->sendGlobalMail($to,$from,$msg);
				$this->newsHelper->getEmailTemplate('trackingcode',$userdata,'send');
				// exit;
				$dataGiid['id'] = $saveData['id'];
				$dataGiid['emailtoken'] = false;
				$dataGiid['filename'] = false;
				$this->session->setSession($CONFIG['SESSION_NAME'],'GIID_IMAGE',$dataGiid);
				
				print json_encode(array('status'=>true,'msg'=>$LOCALE[1]['successwait']));
				//session_destroy();
				/*
				if ($send_mail['result']){
					
					print json_encode(array('status'=>true,'msg'=>$LOCALE[1]['successwait']));
					// sendRedirect("{$CONFIG['BASE_DOMAIN']}register/confirm");
					// return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
					// die();
			
			
				}else{
					print json_encode(array('status'=>false,'msg'=>$LOCALE[1]['mailfailed']));
				}
				*/
			}else{
				print json_encode(array('status'=>false,'msg'=>$LOCALE[1]['sorryfailedtoregister']));
			}
			
		}
		
		if ($this->_p('remove')){
			
			//if (isset($this->_p('tokenid'))){
			$token = $this->_p('token');
				$removeGiid = $this->registerHelper->removeGiid(array('token'=>$token));
				
				if ($removeGiid['email_token']){
					$userData = $this->session->getSession($CONFIG['SESSION_NAME'],'REGISTER');
					$to = $userData->email;
					$from = $CONFIG['EMAIL_FROM_DEFAULT'];
					$msg = 'Your link upload is '.$basedomain.'register/trackingcode<br>Token :'.$removeGiid['email_token'];
					//$send_mail = $this->newsHelper->sendGlobalMail($to,$from,$msg);
					
					print json_encode(array('status'=>true));
				}else{
					print json_encode(array('status'=>false));
				}
				
			//}
			
		}
		
		if ($this->_p('inline_remove')){
			
			$data = "";
			$this->session->setSession($CONFIG['SESSION_NAME'],'GIID_IMAGE',$data);
			print json_encode(array('status'=>true));
		}
		
		if ($this->_p('email')){
			
			$validasiEmail = $this->registerHelper->validasiEmail();
			
			if ($validasiEmail){
			
				print json_encode(array('status'=>$validasiEmail));
			}else{
				print json_encode(array('status'=>$validasiEmail));
			}
			
			
		}
		
		if ($this->_p('birthday')){
			
			$validasibirthday = $this->registerHelper->validasiBirthday();
			
			if ($validasibirthday){
			
				print json_encode(array('status'=>TRUE));
			}else{
				print json_encode(array('status'=>false));
			}
			
			
		}
		/*
		if ($this->_p('checkGiid')){
			
			$validasiGiid = $this->registerHelper->checkGiid();
			
			if ($validasiGiid){
			
				print json_encode(array('status'=>TRUE));
			}else{
				print json_encode(array('status'=>false));
			}
			
			
		}
		*/
		exit;
	}
	
	
	function cameragiid(){
		global $CONFIG;
		$data =  $this->uploadHelper->putcontent();
		if($data['result']){
			$this->session->setSession($CONFIG['SESSION_NAME'],'GIID_IMAGE',$data);
		}
		
		print json_encode($data);exit;
	}
	
	function complete()
	{
		global $CONFIG,$LOCALE;
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$isnotlater = $this->session->getSession($CONFIG['SESSION_NAME'],'GIID_IMAGE');
		$msgregister = $LOCALE[1]['thankyouregister'];
		if($isnotlater){
			if($isnotlater->filename==false) $msgregister = $LOCALE[1]['thankyouregisternotgiid'];
		}
		$this->assign("registerconfirm",$msgregister);	
		$this->assign('basedomain',$basedomain);
		if (($this->_p('submit')) AND ($this->_p('tokenConfirm') == 1)){
			
			// Save data session to database
			
			session_destroy();
				
			sendRedirect("{$CONFIG['BASE_DOMAIN']}login",180);
			return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
			die();
		}
	}
	
	function trackingcode()
	{
		session_destroy();
		global $CONFIG;
		$email_token = strip_tags(trim($this->_p('code')));
		if (($this->_p('submit') == true) and ($email_token !='')){
			
			$checkCode = $this->registerHelper->checkTrackingCode();
			if ($checkCode){
				sendRedirect("{$CONFIG['BASE_DOMAIN']}register/upload/token/$email_token");
				die();
			}else{
				sendRedirect("{$CONFIG['BASE_DOMAIN']}register/trackingcode/");
				die();
			}
			
		}
		$this->View->assign('trackingcode',$this->setWidgets('trackingcode'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'register.html');	
	}
	
	function testcameragiid(){
			print $this->out(TEMPLATE_DOMAIN_WEB .'widgets/webcam.html');
			exit;
	}
}
?>
<?php

class registerHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		
		$this->config = $CONFIG;
		
			
	}
	
	function registerPhase($mobile=false){
		$ok = false;
		global $CONFIG;
		$mobilePath = '';
		if($mobile) $mobilePath = '/mobile';
			
		if($this->apps->_p('register')==1){

			$this->logger->log('can register'); 
			if($this->doRegister()){
				
				$this->log('register','');
				$this->apps->assign("msg","register-in.. please wait");
				$this->apps->assign("link","{$CONFIG['BASE_DOMAIN']}gid");
				sendRedirect("{$CONFIG['BASE_DOMAIN']}gid");
				return $this->apps->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
				die();
			}
			
				$this->logger->log('failed to register');
			if(!$ok){ 
				
				$this->log('access_denied','');
				$this->apps->assign("msg","failed to register..");
				$this->apps->assign("link","{$CONFIG['BASE_DOMAIN']}login");
				sendRedirect("{$CONFIG['BASE_DOMAIN']}login");
				return $this->apps->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
				die();
			}
		}
		$this->logger->log('can not register');
		return false;
	}
	
	function doRegister(){
		global $CONFIG;
		$this->logger->log('do register');

		// $dataSession = $this->session->get($CONFIG['SESSION_NAME']);
		$dataSession = $this->apps->session->getSession($CONFIG['SESSION_NAME'],'REGISTER');
		$dataGiidNumber = $this->apps->session->getSession($CONFIG['SESSION_NAME'],'GIID_NUMBER');
		
		if(!$dataSession) {
			echo 'data ga ada';
			exit;
		}
		// echo "masuk asdasdws";exit;
		$nickname =  $dataSession->nickname;
		$firstname =  $dataSession->firstname;
		$middlename =  $dataSession->middlename;
		$lastname =  $dataSession->lastname;
		$email =  $dataSession->email;
		// $month =  $dataSession->month;
		// $day =  $dataSession->day;
		// $year =  $dataSession->year;
		$birthday =  $dataSession->birthday;
		$pre_mobile =  $dataSession->pre_mobile;
		$last_mobile =  $dataSession->last_mobile;
		// $pre_line =  $dataSession->pre_line;
		// $last_line =  $dataSession->last_line;
		$sex =  $dataSession->sex;
		$house =  $dataSession->house;
		$barangay =  $dataSession->barangay;
		$province =  $dataSession->province;
		$city =  intval($dataSession->city);
		$zipcode =  $dataSession->zipcode;
		// $refered_by =  $dataSession->refered_by;
		
		$giid_type =  $dataGiidNumber->giid_type;
		$giid_number =  $dataGiidNumber->giid_number;
		
		// generate email token
		
		
		$email_token = $this->substringshash(sha1('email_token_validasi'.date('Y-m-d H:i:s')),10);
		
		
		if($firstname==''||$firstname==null){
			$this->logger->log('name is null');
			return false;
		}
		if($email!=$email) {
			$this->logger->log('pass and re pass not match');
			return false;
		}
		
		$reg_date = date('Y-m-d H:i:s');
		// $birthday = $year.'-'.$month.'-'.$day;
		$phone_number = $pre_mobile.'-'.$last_mobile;
		// $landline_number = $pre_line.'-'.$last_line;
		
		
		$hashPass = $this->substringshash(sha1($CONFIG['salt'].date('Y-m-d H:i:s')),10);
		
		if ($this->apps->_p('later')){
			$sql = "
				INSERT INTO social_member (name, nickname, email, register_date, img, username, city, zipcode, sex, 
							birthday, middle_name, last_name, StreetName, barangay, phone_number, 
							giid_type, giid_number, n_status, login_count, verified, email_token, salt, password)
				VALUES ( \"{$firstname}\", \"{$nickname}\", \"{$email}\", '{$reg_date}', NULL, \"{$email}\",
						{$city}, '{$zipcode}', '{$sex}', '{$birthday}', '{$middlename}', '{$lastname}', '{$house}', '{$barangay}',
						'{$phone_number}', '{$giid_type}', '{$giid_number}', 0, 0, 0, '{$email_token}',
						'{$CONFIG['salt']}', '{$hashPass}')";
		}else{
			$sql = "
				INSERT INTO social_member (name, nickname, email, register_date, img, username, city, zipcode, sex, 
							birthday, middle_name, last_name, StreetName, barangay, phone_number, 
							n_status, login_count, verified, salt, password)
				VALUES ( \"{$firstname}\", \"{$nickname}\", \"{$email}\", '{$reg_date}', NULL, \"{$email}\",
						{$city}, '{$zipcode}', '{$sex}', '{$birthday}', '{$middlename}', '{$lastname}', '{$house}', '{$barangay}',
						'{$phone_number}', 0, 0, 0, 
						'{$CONFIG['salt']}', '{$hashPass}')";
		}

		$rs = $this->apps->query($sql);
		$id = mysql_insert_id();
		$this->logger->log($sql);
		$id = $this->apps->getLastInsertId();
		
		// pr($rs);
		if($id) return array('id'=>$id);
		else return false;
	
	}
	
	function getQuestion($type)
	{
		$sql = "SELECT * FROM question WHERE question_type = {$type} AND n_status = 1";
		$res = $this->apps->fetch($sql, 1);
		if ($res) return $res;
		return FALSE;
	}
	
	function getProvince()
	{
		$sql = "SELECT * FROM marlborohunt_province_reference WHERE id > 0 ORDER BY province ASC";
		$result = $this->apps->fetch($sql, 1);
		
		if ($result) return $result;
		
		return FALSE;
	}
	
	function getCity($idProvince)
	{
		$sql = "SELECT id, city FROM marlborohunt_city_reference WHERE provinceid = {$idProvince} GROUP BY city ASC";
		// pr($sql);
		$result = $this->apps->fetch($sql, 1);
		
		if ($result) return $result;
		
		return FALSE;
	}
	
	function getGiid()
	{
		$data =  $this->apps->session->getSession($CONFIG['SESSION_NAME'],'REGISTER_LAST_ID');
		$sql = "SELECT * FROM social_member WHERE id = {$data->id} LIMIT 1";
		$result = $this->apps->fetch($sql, 1);
		if ($result) return $result;
		
		return FALSE;
	}
	
	
	function register_setBio($data)
	{
		$salt = '12345';
		foreach ($data as $key => $value){
			$$key = $value;
		}
		//if ($password === $comfirm_password) $fix_password = sha1($salt.$password.$salt);
		$fix_password = sha1($salt.'user'.$salt);
		$reg_date = date('Y-m-d H:i:s');
		$birthday = $year.'-'.$month.'-'.$day;
		$phone_number = $pre_mobile.'-'.$last_mobile;
		// $landline_number = $pre_line.'-'.$last_line;
		
		$sql = "INSERT INTO social_member (id, name, nickname, email, register_date, username, city, zipcode,
				sex, birthday, middle_name, last_name, StreetName, barangay, phone_number, n_status, login_count, verified, 
				salt, password) VALUES (NULL, '$firstname', '$nickname', '$email', '$reg_date', '$email',
				'$city', '$zipcode', '$sex', '$birthday', '$middlename', '$lastname', '$house', '$barangay', '$phone_number',
				0, 0, 0, '$salt', '$fix_password')";
		
		$result = $this->apps->query($sql);
		if ($result){
			$_SESSION['register_id'] = mysql_insert_id();
			return TRUE;
		}
		return FALSE;
	}
	
	function register_setGid($data)
	{
		global $CONFIG;
		
		$sessData = $this->apps->session->getSession($CONFIG['SESSION_NAME'],'GIID_IMAGE');
		$sessGIIDNumber = $this->apps->session->getSession($CONFIG['SESSION_NAME'],'GIID_NUMBER');
		// pr($sessData);
		if ($sessData){
			
			$arrquery[] = " img = '{$sessData->filename}' ";
			$arrquery[] = " small_img = 'small_{$sessData->filename}' ";
			$arrquery[] = " verified = 1 ";
			
			if (array_key_exists('email_token',$data)){
			
				if ($sessGIIDNumber->giid_type !=''){
					$arrquery[] = " giid_type = {$sessGIIDNumber->giid_type}, giid_number = {$sessGIIDNumber->giid_number} ";
					
				}
				if($arrquery){
					$strQuery = implode(',',$arrquery);
				}else $strQuery = "";
				
				$sql = "UPDATE social_member SET {$strQuery} WHERE email_token = '{$data['email_token']}'";
				
			}
			if (array_key_exists('id',$data)){
				$arrquery[] = "  giid_type = {$sessGIIDNumber->giid_type}, giid_number = '{$sessGIIDNumber->giid_number}' ";
				if($arrquery){
					$strQuery = implode(',',$arrquery);
				}else $strQuery = "";
				
				
				$sql = "UPDATE social_member SET {$strQuery} WHERE id = {$data['id']}";
			
			}
		}else{
			
			
		}
		// pr($sql); exit;
		$res = $this->apps->query($sql);
		
		if ($res) return TRUE;
		return FALSE;
	}
	
	function register_not_validate($data=false)
	{
		// pr($data);
		if(!$data) return false;
		global $CONFIG;
		
		$sessData = $this->apps->session->getSession($CONFIG['SESSION_NAME'],'GIID_IMAGE');
		
		if ($data['id'] !=''){
			$sql = "SELECT email, email_token, username, password FROM social_member WHERE id = {$data['id']} LIMIT 1";
		}
		
		if($sessData){
			if (array_key_exists('email_token',$sessData)){
				$sql = "SELECT email, email_token, username, password FROM social_member WHERE email_token = '{$sessData->email_token}' LIMIT 1";
			}
		}
		$res = $this->apps->fetch($sql);
		
		if ($res) return $res;
		return false;
	}
	
	function saveQuestion($id)
	{
		global $CONFIG;
		
		$dataQuiz = @$this->apps->session->getSession($CONFIG['SESSION_NAME'],'REGISTER_BRAND');
		//$dataSurvey = $this->session->get($CONFIG['SESSION_NAME']."SURVEY");
		
		($dataQuiz->anotherbrands !='') ? $anotherbrands = $dataQuiz->anotherbrands : $anotherbrands = '';
		$sql = "INSERT INTO social_brand_preferences (userid, brand_primary, brand_secondary, question_mark, other_answer, 
				n_status) VALUES ('{$id}', '{$dataQuiz->brand_primary}', '{$dataQuiz->brand_secondary}', 
				'{$dataQuiz->question_mark}', '{$anotherbrands}', 1)";
		// pr($sql);
		$res = $this->apps->query($sql);
		
		if ($res) return TRUE;
		
		return FALSE;
	}
	
	function updateQuiz($data)
	{
		global $CONFIG;
		//pr($data);
		$dataQuiz = @$this->apps->session->getSession($CONFIG['SESSION_NAME'],"WEB");
		//$dataSurvey = $this->session->get($CONFIG['SESSION_NAME']."SURVEY");
		
		$sql = "UPDATE social_brand_preferences SET survey = '{$data['survey']}', consent = '{$data['consent']}' WHERE id = {$dataQuiz->id}";
		//pr($sql);//exit;
		$res = $this->apps->query($sql);
		
		if ($res) return TRUE;
		
		return FALSE;
	}
	
	function removeGiid($data)
	{
		// $id = $this->apps->_p('token');
		
		global $CONFIG;
		$path = $CONFIG['LOCAL_PUBLIC_ASSET'].'user/gid/';
		$idReg = @$this->apps->session->getSession($CONFIG['SESSION_NAME'],"REGID");
		$dataImage = @$this->apps->session->getSession($CONFIG['SESSION_NAME'],"GIID_IMAGE");
		// pr($data);
		$email_token = $this->substringshash(sha1('email_token_validasi'.date('Y-m-d H:i:s')),10);
		
		if ($idReg){
			if (is_int($idReg->regid)){
				$sql = "UPDATE social_member SET verified = 0, email_token = '{$email_token}' WHERE id = {$idReg->regid}";
				
			}
		}else{
			if ($data['token'] !=''){
				$sql = "UPDATE social_member SET verified = 0, email_token = '{$email_token}' WHERE email_token = '{$data['token']}'";
			}
		}
		
		
		// else{
			// $sql = "UPDATE social_member SET verified = 0 WHERE email_token  = '{$id}'";
		// }
		
		// pr($sql);
		
		$result = $this->apps->query($sql);
		if ($result){
		
			@unlink($path.'square'.$dataImage->filename);
			@unlink($path.'prev_'.$dataImage->filename);
			@unlink($path.'small_'.$dataImage->filename);
			@unlink($path.'square_big_'.$dataImage->filename);
			@unlink($path.'tiny_'.$dataImage->filename);
			@unlink($path.'big_'.$dataImage->filename);
			@unlink($path.$dataImage->filename);
			
			$this->apps->session->setSession($CONFIG['SESSION_NAME'],'GIID_IMAGE',FALSE);
			return array('email_token'=>$email_token);
		}
		return FALSE;
	}
	
	function checkTrackingCode()
	{
		$trackingCode = trim(strip_tags($this->apps->_p('code')));
		$sql = "SELECT id FROM social_member WHERE email_token = '{$trackingCode}' LIMIT 1";
		$res = $this->apps->fetch($sql);
		if ($res) return TRUE;
		return FALSE;
	}
	
	function getGiidType()
	{
		$sql = "SELECT id, giid_type FROM giid_type WHERE n_status = 1";
		$res = $this->apps->fetch($sql, 1);
		if ($res) return $res;
		return FALSE;
	}
	
	function validasiEmail()
	{
		$data = 0;
		$email = $this->apps->_p('email');
		
		if(filter_var($email, FILTER_VALIDATE_EMAIL)===false) return 2 ;
		
		
		$sql = "SELECT count(*) AS jumlah FROM social_member WHERE email = '{$email}' LIMIT 1";
		$res = $this->apps->fetch($sql);
		
		if ($res['jumlah'] > 0) $data = 1;
		
		 
		return $data;
	}
	
	function validasiBirthday()
	{
		
		$datePost = $this->apps->_p('birthday');
		$dateExplode = explode('/',$datePost);
		$dateLast = $dateExplode[2].'-'.$dateExplode[0].'-'.$dateExplode[1];
		
		// $sql = "SELECT DATEDIFF('$dateNow', '$dateLast') AS jumlah";
		$date1 = new DateTime(date('Y-m-d', strtotime($dateLast)));
		$date2 = new DateTime(date('Y-m-d'));
		$interval = $date1->diff($date2);
		if ($interval->y >= 18) return TRUE;
		return FALSE;
		// echo "difference " . $interval->y . " years, " . $interval->m." months, ".$interval->d." days ";
		//pr($res);
	}
	
	function getGiidNumber($data)
	{
		$sql = "SELECT giid_number FROM social_member WHERE email_token = '{$data['email_token']}'";
		$res = $this->apps->fetch($sql);
		// pr($sql);
		if ($res) return $res;
		return FALSE;
	}
	
	function getMobilePrefix()
	{
		$sql = "SELECT * FROM mobile_prefix";
		$res = $this->apps->fetch($sql,1);
		if ($res) return $res;
		return FALSE;
	}
	
	
	function substringshash($hasher=null,$limit=10){
			if($hasher==null) return false;
			$strings = substr($hasher,0,$limit);
			
			return $strings;
	}
}

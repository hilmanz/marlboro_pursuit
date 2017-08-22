<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class userManagement extends Admin{
	var $category;
	var $type;
	function __construct(){	
		parent::__construct();	
		
		$this->type = "3, 6, 17";
		$this->contentType = "1";
		$this->folder =  'userManagement';
		$this->dbclass = 'marlborohunt';
		$this->fromwho = 0; // 0 is admin/backend
		$this->total_per_page = 20;
		
	}
	
	function admin(){
		
		global $CONFIG;
	
		//get admin role
		foreach($this->roler as $key => $val){
		$this->View->assign($key,$val);
		}
		//get specified admin role if true
		if($this->specified_role){
			foreach($this->specified_role as $val){
				$type[] = $val['type'];
				$category[] = $val['category'];
			}
			if($type) $this->type = implode(',',$type);
			else return false;
			if($category) $this->category = implode(',',$category);
			else return false;
		}
		//helper
		$this->typelist = $this->getTypeList();
		// $this->contributor = $this->getContributor();
		// $this->View->assign('contributor',$this->contributor);
		$this->View->assign('typelist',$this->typelist);
		$this->View->assign('folder',$this->folder);
		
		$this->View->assign('baseurl',$CONFIG['BASE_DOMAIN_PATH']);
		$act = $this->_g('act');
		if($act){
			return $this->$act();
		} else {
			return $this->home();
		}
		
	}
	
	function home(){
		
		//filter box
	
			$approvallist = array(0,5);
		$filter = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$article_type = $this->_g("article_type") == NULL ? '' : $this->_g("article_type");
		$n_status = $this->_g("n_status") == NULL ? '-1' : $this->_g("n_status");
		$usertype = $this->_g("usertype") == NULL ? '-1' : $this->_g("usertype");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$filter .= $startdate=='' ? "" : " AND DATE(con.register_date) >= '{$startdate}' ";
		$filter .= $enddate=='' ? "" : " AND DATE(con.register_date) <= '{$enddate}' ";		
		$filter .= $search=='' ? "" : " AND (con.name LIKE '%{$search}%' OR con.nickname LIKE '%{$search}%' OR con.email LIKE '%{$search}%' OR con.phone_number LIKE '%{$search}%') ";
		$this->View->assign('search',$search);
		$this->View->assign('article_type',$article_type);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);
		$this->View->assign('n_status',$n_status);
		$this->View->assign('usertype',$usertype);
		
		$artType = explode(',',$this->type);
		if ($article_type!='') {
			if(in_array($article_type,$artType)){ $filter .= $article_type=='' ? "" : " AND con.articleType='{$article_type}'";}
			else $filter .= " AND con.articleType IN ({$article_type}) ";
		}
		if ($n_status!=''&&$n_status!=-1) {
			$verified = false;
			if(in_array($n_status,$approvallist)){
						if($n_status==0) $verified = 0;
						if($n_status==5) $verified = 1;
			}
			if($verified)  $filter .=  "   AND ( con.n_status='{$n_status}' OR con.verified='{$verified}' )  ";
			else $filter .=  " AND con.n_status='{$n_status}' ";
		
		}
		if($usertype!=''&&$usertype!=-1){
			$filter .=  " AND con.usertype='{$usertype}' ";
		}
	
		$start = intval($this->_g('st'));
		
		/* Hitung banyak record data */
		$sql ="
			SELECT count(*) total
			FROM social_member con
			WHERE 1 {$filter}";
		$totalList = $this->fetch($sql);	
		// pr($sql);
		if($totalList){
		$total = intval($totalList['total']);
		}else $total = 0;
		
		$sql = "SELECT count(*) total, n_status
				FROM social_member GROUP BY n_status";
		$count = $this->fetch($sql,1);
		// pr($count);
		
		$statusArr[0] = 'Pending GIID upload'; 
		$statusArr[5] = 'Pending CSR Approval '; 
		$statusArr[2] = 'CSR Approval'; 
		$statusArr[3] = 'Rejected - Not Upload GIID '; 
		$statusArr[4] = 'Rejected - Data Not Valid'; 
		$statusArr[1] = 'Verified';
		$statusArr[6] = 'Deactivated';
		$statusArr[7] = 'Blocked ';
		$data = false;	
		$jumlah = false;
		
		foreach($count as $key => $val){
			
			$count[$key]['statusname'] = $statusArr[$val['n_status']];
			$jumlah += $val['total'];
			
		}
			
			// pr($count);
		
		/* list article */
		$sql = "
			SELECT * FROM social_member con
			WHERE 1 {$filter} 
			ORDER BY register_date DESC
			LIMIT {$start},{$this->total_per_page}
			
		";
		
		$list = $this->fetch($sql,1);
		// pr($sql);
		if($list){
				
			$n=$start+1;
		
			foreach($list as $key => $val){
					if(in_array($list[$key]['n_status'],$approvallist)){
						if($list[$key]['verified']==0) $list[$key]['n_status'] = 0;
						if($list[$key]['verified']==1) $list[$key]['n_status'] = 5;
					}
					$list[$key]['no'] = $n++;
					$arrContentId[] = $val['id'];
			}
			
		
			if($arrContentId){
				$strContentId =implode(',',$arrContentId);
				$sql =" SELECT * FROM {$this->dbclass}_news_content_banner WHERE parentid IN ({$strContentId}) ";
				// pr($sql);
				$bannerData = $this->fetch($sql,1);
				if($bannerData){
					foreach($bannerData as $val){
						$parentidinbanner[$val['parentid']] = true;				
					}
				}else $parentidinbanner = false;
			}else $parentidinbanner = false;
			
			//add misc join like comment and other field in here
			foreach($list as $key => $val){
				
				//status banner has been add or not
				if($parentidinbanner){
						if(array_key_exists($val['id'],$parentidinbanner)) $list[$key]['is_banner'] = true;
						else  $list[$key]['is_banner'] = false;
				}
				
				//other status in here
			}
		}
		
			
		
		$this->View->assign('list',$list);
		$this->View->assign('jumlah',$jumlah);
		$this->Paging = new Paginate();
	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&article_type={$article_type}&startdate={$startdate}&enddate={$enddate}&n_status={$n_status}&usertype={$usertype}"));	
		$this->View->assign("count",$count);
	// pr("application/admin/{$this->folder}/{$this->folder}_list.html");
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
	
	
	function viewProfile(){
		global $CONFIG;
		
				
		$SMP = $this->getSMP();

		$this->View->assign('provinces',$SMP);
		
		$id 		= $this->_g('id');
		$authorid				= intval($this->_p("authorid"));
		if($authorid==0)  $authorid		= $this->Session->getVariable("uid");
	
		if(! $this->_p('simpan')){
		
					
// pr($getCitRef);
			$sql = "SELECT sm.*,cr.city as cityName, cr.id as cityid , pr.province as provincename, pr.id as provinceid, gt.giid_type giidType
			FROM social_member sm
			LEFT JOIN {$this->dbclass}_city_reference cr ON cr.id=sm.city			
			LEFT JOIN {$this->dbclass}_province_reference pr ON pr.id=cr.provinceid
			LEFT JOIN giid_type gt ON sm.giid_type = gt.id		
			WHERE sm.id={$id}
			";
			$list = $this->fetch($sql);
			// pr($list);exit;
			if($list){
			
				foreach($list as $key => $val)
					{							
						// $list[$key]['no'] = $key+1;	
						$this->View->assign($key,$val);
					}					
					
			}
			
		
		}else{
			$id 				= $id;
			$city				= $this->_p('city');
			// $provinceName		= $this->_p('provinceName');
			$zipcode			= $this->_p('zipcode');
			$barangay			= $this->_p('barangay');
			$sex 				= $this->_p('sex');
			$description 		= $this->_p('description');
			$middle_name 		= $this->_p('middle_name');
			$last_name 			= $this->_p('last_name');
			$StreetName 		= $this->_p('StreetName');
			$phone_number 		= $this->_p('phone_number');
			$refered_by 		= $this->_p('refered_by');
			// $content 	  		= $this->fixTinyEditor( $content );
			$url 				= $this->_p('url');
			$sourceurl 			= $this->_p('sourceurl');
			if($this->roler['approver']) $status = $this->_p('n_status');
			else $status 	 = 0;
			
			/* if($this->category) {
				$arrCategory 	= explode(',',$this->category);
				if(!in_array($categoryid,$arrCategory)) {
					return $this->View->showMessage('you are not authorize for this category id', "index.php?s={$this->folder}");
				}
			}
			
			if($title=='' ){
				$this->View->assign('msg',"Please complete the form!");
				return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_profile.html");
			}
			 
			if($tags){
				$tags = serialize(explode(',',$tags));
			}*/
			
			$sql = "UPDATE social_member SET 	sex=\"{$sex}\",
												zipcode=\"{$zipcode}\",
												city=\"{$city}\",
												barangay=\"{$barangay}\",
												description=\"{$description}\",
												StreetName=\"{$StreetName}\",
												phone_number='{$phone_number}',
												refered_by=\"{$refered_by}\"
														
												WHERE id={$id} LIMIT 1";
	
			$last_id = $id;
		
			// pr($sql);exit;
			if(!$this->query($sql)){
				$this->View->assign("msg","edit process failure");
				return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_profile.html");
			}else{
				//create Image function
				// $this->createImage($last_id);				
				
				return $this->View->showMessage('Success', "index.php?s={$this->folder}");
			}
		}
		
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_profile.html");
	}
	
	function getCity(){
		$provinceid = intval($this->_p('id'));
		$sql = "SELECT * FROM {$this->dbclass}_city_reference WHERE provinceid={$provinceid} GROUP BY city";
		// pr($sql);
		$cityRef = $this->fetch($sql,1);
		print json_encode($cityRef);exit;
	}
	
	
	function getProvince()
	{
		$sql = "SELECT * FROM marlborohunt_province_reference WHERE id > 0 ORDER BY province ASC";
		$result = $this->fetch($sql, 1);
		
		if ($result) return $result;
		
		return FALSE;
	}
	
	function getSMP(){
	
		$sql = "SELECT * FROM {$this->dbclass}_province_reference WHERE id > 0 ORDER BY province ASC";
		$result = $this->fetch($sql, 1);
		
		if ($result) return $result;
		return FALSE;
	}
	
	function getTypeList(){
		$sql = "SELECT * FROM {$this->dbclass}_news_content_type WHERE id IN ({$this->type}) AND  content =  {$this->contentType} ";
		$type = $this->fetch($sql,1);
		// pr($type);exit;
		return $type;
	}
	
	function getPageList(){
		$sql = "SELECT * FROM {$this->dbclass}_news_content_page WHERE n_status=1 ";
		$page = $this->fetch($sql,1);
		// pr($sql);
		return $page;
	}
		
	function fixTinyEditor($content){
		global $CONFIG;
		$content = str_replace("\\r\\n","",$content);
		$content = htmlspecialchars(stripslashes($content), ENT_QUOTES);
		$content = str_replace("../index.php", "index.php", $content);

		//$content = htmlspecialchars( stripslashes($content) );
		$content = str_replace("&lt;", "<", $content);
		$content = str_replace("&gt;", ">", $content);
		$content = str_replace("&quot;", "'", $content);
		$content = str_replace("&amp;", "&", $content);
		return $content;
	}
	
	
	
	function getEmailTemplate($mailtemplate='welcomeweb',$userdata=false,$sendType='send'){
		global $CONFIG;
		/* user data is array field */
		if($userdata==false) return false;
		
		$host = "api2.silverpop.com";
		$adminuser = "inong@marlboro.ph";
		$adminpass = "Kana9i8u";
		$servlet = "http://api2.silverpop.com/servlet/XMLAPI";

		$list_id = false;
		$mailid = false;
		$email = false;
		$firstname = false;
		$username = false;
		$password = false;
		$lastname = false;
		$trackingcode = false;
		$MAIL = false;
		/* sample 
			
			$arrData['email'] = "rizal@kana.co.id";
			$arrData['firstname'] = "rizal aja";
			$arrData['username'] = "rizal@kana.co.id";
			$arrData['password'] = "9234g2934h239h40203240239480298wrjoiwtowehtoerhtiuerhteukrtj";
			$arrData['lastname'] = "9234g2934h239h40203240239480298wrjoiwtowehtoerhtiuerhteukrtj";
			$arrData['trackingcode'] = "9234g2934h239h40203240239480298wrjoiwtowehtoerhtiuerhteukrtj";
		
		*/
		foreach($userdata as $key => $val){
			$arrData[$key] = $val;
			$$key = $val;
		}
	
		
		include "../../config/mail.inc.php";
		if($MAIL){
			$arrData['mailid'] =  $MAIL[$mailtemplate]['mailid'];
			$arrData['templatedataxml'] = $MAIL[$mailtemplate]['template'];
			
			if($sendType=='send') {
				$this->addRecipeForSilverPop($arrData,$adminuser,$adminpass, $host);
				sleep(1);
				$this->sendMailViaSilverPop($arrData,$adminuser,$adminpass, $host);
			}else $this->addRecipeForSilverPop($arrData,$adminuser,$adminpass, $host); 
		}
	}

	function addRecipeForSilverPop($arrData,$adminname,$adminpass,  $host, $servlet="XMLAPI", $port=80, $time_out=20){
	
	$servlet = $servlet;
	
	foreach($arrData as $key => $val){
		$$key = $val;
	}
	$sock = fsockopen ($host, $port, $errno, $errstr, $time_out); // open socket on port 80 w/ timeout of 20
	$data = "xml=<?xml version=\"1.0\" encoding=\"UTF-8\" ?><Envelope><Body>";
	$data .= "<Login>";
	$data .= "<USERNAME>".$adminname."</USERNAME>";
	$data .= "<PASSWORD>".$adminpass."</PASSWORD>";
	$data .= "</Login>";
	$data .= "<AddRecipient>";
	$data .= $templatedataxml;
	$data .= "</AddRecipient>";
	$data .= "</Body></Envelope>";
	if (!$sock)
	{
	print("Could not connect to host:". $errno . $errstr);
	return (false);
	}
	$size = strlen ($data);
	fputs ($sock, "POST /servlet/" . $servlet . " HTTP/1.1\n");
	fputs ($sock, "Host: " . $host . "\n");
	fputs ($sock, "Content-type: application/x-www-form-urlencoded\n");
	fputs ($sock, "Content-length: " . $size . "\n");
	fputs ($sock, "Connection: close\n\n");
	fputs ($sock, $data);
	$buffer = "";
	while (!feof ($sock)) {
	$buffer .= fgets ($sock);
	}
	// pr($data);
	fclose ($sock);
	return ($buffer);


	}


	function sendMailViaSilverPop($arrData,$adminname,$adminpass, $host, $servlet="XMLAPI", $port=80, $time_out=20){



	$servlet = $servlet;
	
	foreach($arrData as $key => $val){
		$$key = $val;
	}

	$sock = fsockopen ($host, $port, $errno, $errstr, $time_out); // open socket on port 80 w/ timeout of 20
	$data = "xml=<?xml version=\"1.0\" encoding=\"UTF-8\" ?><Envelope><Body>";
	$data .= "<Login>";
	$data .= "<USERNAME>".$adminname."</USERNAME>";
	$data .= "<PASSWORD>".$adminpass."</PASSWORD>";
	$data .= "</Login>";
	$data .= "<SendMailing>
	<MailingId>".$mailid."</MailingId>
	<RecipientEmail>".$email."</RecipientEmail>";	
	$data .= "</SendMailing></Body></Envelope>";
	if (!$sock)
	{
	print("Could not connect to host:". $errno . $errstr);
	return (false);
	}
	$size = strlen ($data);
	fputs ($sock, "POST /servlet/" . $servlet . " HTTP/1.1\n");
	fputs ($sock, "Host: " . $host . "\n");
	fputs ($sock, "Content-type: application/x-www-form-urlencoded\n");
	fputs ($sock, "Content-length: " . $size . "\n");
	fputs ($sock, "Connection: close\n\n");
	fputs ($sock, $data);
	$buffer = "";
	while (!feof ($sock)) {
	$buffer .= fgets ($sock);
	}
	// pr($data);
	fclose ($sock);
	return ($buffer);




	}

	function sendGlobalMail($to,$from,$msg, $flag){
		GLOBAL $ENGINE_PATH, $CONFIG, $LOCALE;
		require_once $ENGINE_PATH."Utility/PHPMailer/class.phpmailer.php";
		
		// $to = "bummi@kana.co.id";
		
		$mail = new PHPMailer();
				
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
												   // 1 = errors and messages
												   // 2 = messages only		
		$mail->Host       = $CONFIG['EMAIL_SMTP_HOST'];  // sets the SMTP server
		$mail->SMTPAuth   = false;                  // enable SMTP authentication
		// $mail->Port       = 26;                    // set the SMTP port for the GMAIL server
		$mail->Username   = $CONFIG['EMAIL_SMTP_USER']; // SMTP account username
		$mail->Password   = $CONFIG['EMAIL_SMTP_PASSWORD'];        // SMTP account password
		
		$mail->SetFrom($CONFIG['EMAIL_FROM_DEFAULT'], 'No Reply Account');
		// $mail->From =$CONFIG['EMAIL_FROM_DEFAULT'];	

		if ($flag = 1){
			$mail->Subject    = "[ NOTIFICATION ] Account User Verification ";
		}
		if ($flag = 2){
			$mail->Subject    = "[ NOTIFICATION ] {$LOCALE[1]['sendmailresetpass']}";
		}

		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

		$mail->MsgHTML($msg);

		$address = $to;
		$mail->AddAddress($address);

		//$mail->AddAttachment("images/phpmailer.gif");      // attachment
		
		$result = $mail->Send();
	
		if($result) return array('message'=>'success send mail','result'=>true,'res'=>$result);
		else return array('message'=>'error mail setting','result'=>false,'res'=>$mail->ErrorInfo);
	}
	
	function ajax()
	{
		//echo 'masuk';
		global $CONFIG;
		
		$n_status = $this->_p('status');
		$user_id = $this->_p('user_id');
		$id = $this->_p('id');
		$sql = "UPDATE social_member SET n_status = {$n_status} WHERE id = {$id}";
		// pr($sql);
		$qData = $this->query($sql);
		if ($qData){
			//echo 'ada';
			$sql = "SELECT * FROM social_member WHERE id = {$id} LIMIT 1";
			$res = $this->fetch($sql);
			if ($res['n_status']==1){
				// kirim akun login
				$to = $res['email'];
				$from = $CONFIG['EMAIL_FROM_DEFAULT'];
				$subject = "Data login";
				$msg = 'Username = '.$res['username'].'<br>Password = '.$res['password'];
				
				$userdata['email'] = $to;
				$userdata['firstname'] = $res['name'];
				$userdata['lastname'] = $res['last_name'];
				$userdata['username'] = $to;
				$userdata['password'] = $res['password'];
				$userdata['trackingcode'] = "";
				
				$this->getEmailTemplate('welcomeweb',$userdata,'send');
				/*
				$send_mail = $this->sendGlobalMail($to,$from,$msg, 1);
				if ($send_mail['result']){
					print json_encode(array('status'=>TRUE));
					exit;
				}else{
					print json_encode(array('status'=>FALSE));
					exit;
				}
				*/
			}
			
			if($res['n_status']==10){
			
				// kirim link upload foto GIID
				$to = $res['email'];
				$from = $CONFIG['EMAIL_FROM_DEFAULT'];
				$subject = "Data login";
				$msg = "Your link upload is {$CONFIG['BASE_DOMAIN']}register/trackingcode, and Please Add this Code = {$res['email_token']}";
				$userdata['email'] = $to;
				$userdata['firstname'] = $res['name'];
				$userdata['lastname'] = $res['last_name'];
				$userdata['username'] = $to;
				$userdata['password'] = "";
				$userdata['trackingcode'] = $msg;
				
				$this->getEmailTemplate('trackingcode',$userdata,'send');
			}
			
			
			/*
			$send_mail = $this->sendGlobalMail($to,$from,$msg, 1);
			if ($send_mail['result']){
				print json_encode(array('status'=>TRUE));
			}else{
				print json_encode(array('status'=>FALSE));
			}
			*/
			
			
			print json_encode(array('status'=>true));
		}else{
			print json_encode(array('status'=>FALSE));
		}
		
		exit;
	}
	
	function resetpassajax()
	{
		global $CONFIG;
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$id = $this->_p('user_id');
		$dontsendmail = false;
	
			//echo 'ada';
			$sql = "SELECT * FROM social_member WHERE id = {$id} LIMIT 1";
			$res = $this->fetch($sql);
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
						// $msg = $basedomain.'forgotpassword/verified_token/'.$token;
					
						$userdata['email'] = $to;
						$userdata['firstname'] = $res['name'];
						$userdata['lastname'] = $res['last_name'];
						$userdata['username'] = $to;
						$userdata['password'] = $thepassword;
						// $userdata['url'] = $msg;
						$userdata['trackingcode'] = "";
					
					
					} 
			}
			
			if($userdata){
					
				$this->getEmailTemplate('forgotpassword',$userdata,'send');
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
	
	
	function substringshash($hasher=null,$limit=10){
			if($hasher==null) return false;
			$strings = substr($hasher,0,$limit);
			
			return $strings;
	}

}
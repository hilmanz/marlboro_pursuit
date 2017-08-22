<?php

class newsHelper {
	
	
	function __construct($apps){
	global $logger,$CONFIG;
	$this->logger = $logger;
	$this->apps = $apps;
	if($this->apps->isUserOnline())  {
			if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
	}
	else $this->uid = 0;
	$this->dbshema = "athreesix";
	}
	
	function inboxcount(){
		// pr('masuk'); exit;
		$data = $this->getInboxUser(0,100,true);
		if($data){
			if(array_key_exists('total',$data)) $total = $data['total'];
			else $total = false;
		}else $total = false;
		return $total;
	}
	
	function getInboxUser($start=0,$limit=10,$all=false,$mail=false){
		global $LOCALE;
		
		/*
			$LOCALE[1]['add friends']['inbox'] = " sekarang follow kamu";
			$LOCALE[1]['add comments']['inbox'] = " telah memberikan komentar di postinganmu";
			$LOCALE[1]['add favorite']['inbox'] = " telah menambahkan postinganmu sebagai favoritnya";
			$LOCALE[1]['add new thread']['inbox'] = " telah memberikan komentar di thread kamu";
		*/
		
		$activityIDarr = false;
		$theactivity = false;
		$qData = false;
		$groupdatedata = false;
		$inboxActivity = "'add comments','add favorite','add new thread'";
		$socialActivity = "'add friends'";
		$dateNewsLetter[0] = "";
		$dateNewsLetter[1] = "DAY(date_time)";
		$dateNewsLetter[7] = " WEEK(date_time)";
		$dateNewsLetter[30] = " MONTH(date_time) ";
		$data['result'] = false;
		$data['total'] = false;
		
		$theactivity = $this->apps->activityHelper->getactivitytype($inboxActivity);		
		if($theactivity) {
			/* get article of user */
			$inboxActivityID = implode(',',$theactivity['id']);
		}else $inboxActivityID = false;
		
		/* get activity social id */	
		$socialactivitydata = $this->apps->activityHelper->getactivitytype($socialActivity);
		if($socialactivitydata) {
			$socialActivityID = implode(',',$socialactivitydata['id']);
		}else $socialActivityID = false;
		
		$activityIDarr = array($inboxActivityID,$socialActivityID);
		
		if($activityIDarr) {
				
				$activityId = implode(',',$activityIDarr);
		
				/* get article of user */
				$sql = "SELECT id FROM {$this->dbshema}_news_content WHERE authorid = {$this->uid} AND n_status = 1 AND fromwho = 1 LIMIT 100 ";
				$qData = $this->apps->fetch($sql,1);
				// pr($qData);
				if($qData){
					foreach($qData as $val){
						$articleData[] = $val['id'];
					}
					if($articleData){
						$thearticles = implode(',',$articleData);
					}else  $thearticles = false ;
				}else $thearticles = false ;
				
				/* if not user notif, to prevent un-categories */
				if($thearticles){
					if($socialActivityID) $appendConditionalQ = " AND IF (action_id IN ({$socialActivityID}),action_value={$this->uid},action_value IN ({$thearticles}) ) ";
					else $appendConditionalQ = " AND action_value IN ({$thearticles}) ";
				}else $appendConditionalQ = " AND action_value={$this->uid} ";
					
								
				//mail system
				if($mail){
					/* group per date picking */
					$sql = "SELECT * FROM my_news_letter WHERE userid={$this->uid} AND n_status = 1 LIMIT 1";
					$groupdatedata = $this->apps->fetch($sql); 
					// pr($sql);
				}
				if($groupdatedata){
					if(array_key_exists($groupdatedata['datenewsletter'],$dateNewsLetter)) $qGroupNewsDate = " AND  DATE_SUB(date_time,INTERVAL {$groupdatedata['datenewsletter']} DAY ) ";
					else $qGroupNewsDate = "";
				}else  $qGroupNewsDate = "";
				
				if($all){
					$sql = " 
					SELECT COUNT(*) total
					FROM tbl_activity_log 
					WHERE 
						action_id IN ({$activityId}) 
					{$appendConditionalQ}
					AND n_status not in (1,2)
					{$qGroupNewsDate}
					";
					$qData = $this->apps->fetch($sql);
					return $qData;
				}
				
					/* query all notif for this use */
				$sql = " 
					SELECT COUNT(*) total
					FROM tbl_activity_log 
					WHERE 
						action_id IN ({$activityId}) 
					{$appendConditionalQ}
					AND n_status not in (2)
					{$qGroupNewsDate}
					";
	
				$qData = $this->apps->fetch($sql);
				if(!$qData) return false;
				$total = intval($qData['total']);
				
				/* query all notif for this use */
				$sql = " 
					SELECT 
						* , 
						IF ( action_id IN ({$socialActivityID}) , 'social' , 'content' ) typeofnotification, 
						DATE(date_time) as datedays, 
						MONTH(date_time) as monthdays, 
						WEEK(date_time) as weekdays
					FROM tbl_activity_log 
					WHERE 
						action_id IN ({$activityId}) 
					{$appendConditionalQ}
					AND n_status not in (1)
					{$qGroupNewsDate}
					ORDER BY date_time DESC
					LIMIT {$start},{$limit}";
		
				$qData = $this->apps->fetch($sql,1);
				if(!$qData) return false;
				
				$socialData = false;
				$contentData = false;
				
				foreach($qData as $key => $val){
					$actionid[$val['action_id']] = $val['action_id'];
					/* parse between id article and user */
					if($val['typeofnotification'] == 'social' )$socialData[] = $val['action_value'];
					else $contentData[] = $val['action_value'];
					
					$subjectData[] =  $val['user_id'];
				}
				if($socialData)$arrUserid= $this->apps->activityHelper->getsocialData($socialData);
				else $arrUserid = false;
				if($contentData) $arrContent = $this->apps->activityHelper->getContentData($contentData);
				else $arrContent = false;
				if($subjectData) $arrSubject = $this->apps->activityHelper->getsocialData($subjectData);
				else $arrSubject = false;
				
				if($actionid){
					$actions = implode(',',$actionid);
					$actionnamedata =  $this->apps->activityHelper->getactivitytype($actions,true);			
				}else $actionnamedata = false;
				// pr($actionnamedata);
				if($actionnamedata){
					$arrNoteId = false;
					foreach($qData as $key => $val){
						if(array_key_exists($val['action_id'],$actionnamedata['name'])) $qData[$key]['activityName'] = $LOCALE[1][$actionnamedata['name'][$val['action_id']]]['inbox'];
						else  $qData[$key]['activityName'] = false;
						if($val['typeofnotification'] == 'social' ){
							if($arrUserid){	
								if(array_key_exists($val['action_value'],$arrUserid))$qData[$key]['userdetail'] = $arrUserid[$val['action_value']];	
								else $qData[$key]['userdetail'] = false;
							}else $qData[$key]['userdetail'] = false;
						}else{
							if($arrContent ){
								if(array_key_exists($val['action_value'],$arrContent)) $qData[$key]['contentdetail'] = $arrContent[$val['action_value']];	
								else $qData[$key]['contentdetail'] = false;
							}else $qData[$key]['contentdetail'] = false;
						}
						if($arrSubject){	
								if(array_key_exists($val['user_id'],$arrSubject))$qData[$key]['userdetail'] = $arrSubject[$val['user_id']];	
								else $qData[$key]['userdetail'] = false;
						}else $qData[$key]['userdetail'] = false;
						
						$arrNoteId[$val['id']] = $val['id'];
					}
					
					/* if($arrNoteId){
						$strNoteId = implode(",",$arrNoteId);
						$sql = "UPDATE tbl_activity_log SET n_status = 2 WHERE id IN ({$strNoteId}) ;";
						
						$this->apps->query($sql);
					} */
					
				}
					// print_r($qData);
				$data['result'] = $qData;
				$data['total'] = intval($total);
				return $data;
		}
		return false;
		
		
	}
	
	function setMessageForMail($start=0,$limit=10,$all=false,$mail=false,$uid=false){
		global $LOCALE;
		
		if($uid) $this->uid = intval($uid['userid']);
		else return false;
		if($this->uid==0) return false;
		
		
		$activityIDarr = false;
		$theactivity = false;
		$qData = false;
		$groupdatedata = false;
		$inboxActivity = "'add comments','add favorite','add new thread'";
		$socialActivity = "'add friends'";
		$dateNewsLetter[0] = "";
		$dateNewsLetter[1] = "DAY(date_time)";
		$dateNewsLetter[7] = " WEEK(date_time)";
		$dateNewsLetter[30] = " MONTH(date_time) ";
		$data['result'] = false;
		$data['total'] = false;
		
		$theactivity = $this->apps->activityHelper->getactivitytype($inboxActivity);		
		if($theactivity) {
			/* get article of user */
			$inboxActivityID = implode(',',$theactivity['id']);
		}else $inboxActivityID = false;
		
		/* get activity social id */	
		$socialactivitydata = $this->apps->activityHelper->getactivitytype($socialActivity);
		if($socialactivitydata) {
			$socialActivityID = implode(',',$socialactivitydata['id']);
		}else $socialActivityID = false;
		
		$activityIDarr = array($inboxActivityID,$socialActivityID);
		
		if($activityIDarr) {
			
				
				$activityId = implode(',',$activityIDarr);
		
				/* get article of user */
				$sql = "SELECT id FROM {$this->dbshema}_news_content WHERE authorid = {$this->uid} AND n_status = 1 AND fromwho = 1 LIMIT 100 ";
				$qData = $this->apps->fetch($sql,1);
				// pr($qData);
				if($qData){
					foreach($qData as $val){
						$articleData[] = $val['id'];
					}
					if($articleData){
						$thearticles = implode(',',$articleData);
					}else  $thearticles = false ;
				}else $thearticles = false ;
				
				/* if not user notif, to prevent un-categories */
				if($thearticles){
					if($socialActivityID) $appendConditionalQ = " AND IF (action_id IN ({$socialActivityID}),action_value={$this->uid},action_value IN ({$thearticles}) ) ";
					else $appendConditionalQ = " AND action_value IN ({$thearticles}) ";
				}else $appendConditionalQ = " AND action_value={$this->uid} ";
					
								
				//mail system
				if($mail){
					/* group per date picking */
					$sql = "SELECT * FROM my_news_letter WHERE userid={$this->uid} AND n_status = 1 LIMIT 1";
					$groupdatedata = $this->apps->fetch($sql); 
					// pr($sql);
				}
				if($groupdatedata){
					if(array_key_exists($groupdatedata['datenewsletter'],$dateNewsLetter)) $qGroupNewsDate = " AND  DATE_SUB(date_time,INTERVAL {$groupdatedata['datenewsletter']} DAY ) ";
					else $qGroupNewsDate = "";
				}else  $qGroupNewsDate = "";
				
				if($all){
					$sql = " 
					SELECT COUNT(*) total
					FROM tbl_activity_log 
					WHERE 
						action_id IN ({$activityId}) 
					{$appendConditionalQ}
					AND n_status not in (1,2)
					{$qGroupNewsDate}
					";
					$qData = $this->apps->fetch($sql);
					return $qData;
				}
				
					/* query all notif for this use */
				$sql = " 
					SELECT COUNT(*) total
					FROM tbl_activity_log 
					WHERE 
						action_id IN ({$activityId}) 
					{$appendConditionalQ}
					AND n_status not in (1,2)
					{$qGroupNewsDate}
					";
	
				$qData = $this->apps->fetch($sql);
				if(!$qData) return false;
				$total = intval($qData['total']);
				
				/* query all notif for this use */
				$sql = " 
					SELECT 
						* , 
						IF ( action_id IN ({$socialActivityID}) , 'social' , 'content' ) typeofnotification, 
						DATE(date_time) as datedays, 
						MONTH(date_time) as monthdays, 
						WEEK(date_time) as weekdays
					FROM tbl_activity_log 
					WHERE 
						action_id IN ({$activityId}) 
					{$appendConditionalQ}
					AND n_status not in (1,2)
					{$qGroupNewsDate}
					ORDER BY date_time DESC
					LIMIT {$start},{$limit}";
				
				$qData = $this->apps->fetch($sql,1);
				if(!$qData) return false;
				
				$socialData = false;
				$contentData = false;
				
				foreach($qData as $key => $val){
					$actionid[$val['action_id']] = $val['action_id'];
					/* parse between id article and user */
					if($val['typeofnotification'] == 'social' )$socialData[] = $val['action_value'];
					else $contentData[] = $val['action_value'];
					
					$subjectData[] =  $val['user_id'];
				}
				if($socialData)$arrUserid= $this->apps->activityHelper->getsocialData($socialData);
				else $arrUserid = false;
				if($contentData) $arrContent = $this->apps->activityHelper->getContentData($contentData);
				else $arrContent = false;
				if($subjectData) $arrSubject = $this->apps->activityHelper->getsocialData($subjectData);
				else $arrSubject = false;
				
				if($actionid){
					$actions = implode(',',$actionid);
					$actionnamedata =  $this->apps->activityHelper->getactivitytype($actions,true);			
				}else $actionnamedata = false;
				// pr($actionnamedata);
				if($actionnamedata){
					foreach($qData as $key => $val){
						if(array_key_exists($val['action_id'],$actionnamedata['name'])) $qData[$key]['activityName'] = $LOCALE[1][$actionnamedata['name'][$val['action_id']]]['inbox'];
						else  $qData[$key]['activityName'] = false;
						if($val['typeofnotification'] == 'social' ){
							if($arrUserid){	
								if(array_key_exists($val['action_value'],$arrUserid))$qData[$key]['userdetail'] = $arrUserid[$val['action_value']];	
								else $qData[$key]['userdetail'] = false;
							}else $qData[$key]['userdetail'] = false;
						}else{
							if($arrContent ){
								if(array_key_exists($val['action_value'],$arrContent)) $qData[$key]['contentdetail'] = $arrContent[$val['action_value']];	
								else $qData[$key]['contentdetail'] = false;
							}else $qData[$key]['contentdetail'] = false;
						}
						if($arrSubject){	
								if(array_key_exists($val['user_id'],$arrSubject))$qData[$key]['userdetail'] = $arrSubject[$val['user_id']];	
								else $qData[$key]['userdetail'] = false;
						}else $qData[$key]['userdetail'] = false;
						
					}
				}
				if($qData){
					$email = false;
					$arrNoteId = false;
					foreach($qData as $val){
						$email[] = "<b>{$val['userdetail']['name']}{$val['userdetail']['last_name']}</b> {$val['activityName']} - {$val['date_time']}";
						$arrNoteId[$val['id']] = $val['id'];
					}
					// pr($email);exit;
					if($email){
						$dosendemail = false;
						$strEmailBody = implode("<br>",$email);
						
						$dosendemail['sendmail'] = $this->sendGlobalMail($uid['email'],"athreesix-mail-blaster",$strEmailBody);
					
						if($dosendemail['sendmail']['result']){
							if($arrNoteId){
								$strNoteId = implode(",",$arrNoteId);
								$sql = "UPDATE tbl_activity_log SET n_status = 2 WHERE id IN ({$strNoteId}) ;";
								
								$dosendemail['updateinbox'] = $this->apps->query($sql);
								
								if($groupdatedata)	$dateadd = intval($groupdatedata['datenewsletter']);
								else $dateadd = 1;
								
								$sql = "UPDATE my_news_letter SET last_send_date = DATE_ADD(DATE(NOW()),INTERVAL {$dateadd} DAY ) WHERE userid = {$uid['userid']} LIMIT 1 ";
							
								$this->apps->query($sql);
								 
							}else $dosendemail['updateinbox'] =false;
						}
						
						return $dosendemail;
					}else return false;
				
				}else return false;
		}
		return false;
		
		
	}
	
	function getNewsLetterScheadule(){
	
		$sql = "SELECT userid,email,datenewsletter, letter.n_status  FROM my_news_letter letter 
		LEFT JOIN social_member member ON member.id=letter.userid
		WHERE letter.n_status = 1 
		AND ( last_send_date <= DATE(NOW()) OR last_send_date = '0000-00-00' )
		";
		$uidData = $this->apps->fetch($sql,1); 

		if(!$uidData) return false;
		else return $uidData;
	}
	
	function trashInbox($id=null){
		if($id == null) return false;
		$id = intval($this->apps->_p('noteid'));
		$sql = "UPDATE tbl_activity_log SET n_status = 1 WHERE id IN ({$id}) ;";

		$qData = $this->apps->query($sql);
		if($qData) return true;
		else return false;
	}
	
	function inboxread($id=null){
		if($id == null) return false;
		$id = intval($this->apps->_p('noteid'));
		$sql = "UPDATE tbl_activity_log SET n_status = 2 WHERE id IN ({$id}) ;";
		// pr($sql);
		$qData = $this->apps->query($sql);
		if($qData) return true;
		else return false;
	
	}
	
	function saveinboxtime(){
	
	$datenewsletter = intval($this->apps->_p('datenewsletter'));
	$n_status = intval($this->apps->_p('n_status'));
	
	$sql = "
		INSERT INTO my_news_letter ( userid,datenewsletter,n_status ) VALUES ({$this->uid},{$datenewsletter},{$n_status}) 
		ON DUPLICATE KEY UPDATE datenewsletter={$datenewsletter},n_status ={$n_status}
	";
	// pr($sql);
	$this->apps->query($sql);
	
	if($this->apps->getLastInsertId()) return true;
	return false;
	
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
	
		
		include "../config/mail.inc.php";
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

	
	function sendGlobalMail($to,$from,$msg){
		GLOBAL $ENGINE_PATH, $CONFIG, $LOCALE;
		require_once $ENGINE_PATH."Utility/PHPMailer/class.phpmailer.php";
		
		// $to = "bummi@kana.co.id";
		if ($from !='') $from = $from;
		else $from = $CONFIG['EMAIL_FROM_DEFAULT'];
		
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
		
		$mail->SetFrom($from, 'No Reply Account');
		// $mail->From =$CONFIG['EMAIL_FROM_DEFAULT'];	

		$mail->Subject    = "[ NOTIFICATION ] Marlboro Pursuit Message";

		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

		$mail->MsgHTML($msg);

		$address = $to;
		$mail->AddAddress($address);

		//$mail->AddAttachment("images/phpmailer.gif");      // attachment
		
		$result = $mail->Send();
	
		if($result) return array('message'=>'success send mail','result'=>true,'res'=>$result);
		else return array('message'=>'error mail setting','result'=>false,'res'=>$mail->ErrorInfo);
	}
	
	/* The Hunt Helper */
	function task()
	{
		$sql = "SELECT * FROM marlborohunt_news_content WHERE articleType = 3 AND n_status = 1 LIMIT 10"; // 3 = task
		$res = $this->apps->fetch($sql, 1);
		if ($res) return $res;
		return FALSE;
	}
	
	function messages()
	{
		$sql = "SELECT * FROM marlborohunt_news_content WHERE articleType = 3 AND n_status = 1 LIMIT 10"; // 4 = message
		$res = $this->apps->fetch($sql, 1);
		if ($res) return $res;
		return FALSE;
	}
	
	function merchandiseRedeem()
	{
		$sql = "SELECT * FROM my_task WHERE n_status = 1"; // 4 = message
		$res = $this->apps->fetch($sql, 1);
		if ($res) return $res;
		return FALSE;
	}
	
	function accomplishedTask()
	{
		$sqlTask = "SELECT id FROM my_task WHERE n_status = 1";
		$res = $this->apps->fetch($sqlTask, 1);
		
		foreach ($res as $taskid){
			
			$sql = "SELECT * FROM marlborohunt_news_content WHERE articleType = {$taskid['id']}";
			$result[$taskid['id']] = $this->apps->fetch($sql, 1);
		}
		
		if (is_array($result)) return $result;
		return FALSE;
	}
	
	function sendInviteFriends()
	{
		// $to = trim(strip_tags($this->apps->_p('to')));
		$to = "aspieyanrieza@gmail.com";
		$from = "ovan89@gmai.com";
		$msg = "<h1>Developed by cacad</h1>";
		
		$sendInvite = sendGlobalMail($to, $from, $msg);
		if ($sendInvite['result']){
			return TRUE;
		}
		
		return FALSE;
		
	}
	
	
}
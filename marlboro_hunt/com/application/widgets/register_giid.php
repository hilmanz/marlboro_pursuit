<?php
class register_giid{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		global $CONFIG;
		
		$sessGiid = $this->apps->session->getSession($CONFIG['SESSION_NAME'],'GIID_IMAGE');
		
		if (@$sessGiid->filename){
		
			(@$sessGiid->filename !='') ? $fileNameGiid = @$sessGiid->filename : $fileNameGiid = "";
			
		}else{
			$fileNameGiid = "";
		}
		$this->apps->assign('sess_giid',$fileNameGiid);
		
		
		$email_token = strip_tags($this->apps->_g('token'));
		//pr($email_token);
		
		$giidType = $this->apps->registerHelper->getGiidType();
		// $giidType = array(array('giid_type'=>1));
		// pr($giidType);
		// $index = 0;
		// foreach ($giidType as $data){
			// $id = $data['id'];
			// $type = strtoupper($data['giid_type']);
			// $data[$index] = array('id'=>$id, 'giid_type'=>$type);
			// $index++;
		// }
		
		// pr($data);
		
		$getGiidNumber = $this->apps->registerHelper->getGiidNumber(array('email_token'=>$email_token));
		// pr($getGiidNumber);
		if ($getGiidNumber['giid_number'] !=""){
			$this->apps->assign('gidNumber',TRUE);
		}else{
			$this->apps->assign('gidNumber',FALSE);
		}
		
		$this->apps->assign('giid_type',$giidType);
		$this->apps->assign('email_token',$email_token);
		
		
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/register-giid.html"); 
		
	}
}
?>
<?php
class register_confirm{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		global $CONFIG;
		
		$fileName = $this->apps->session->getSession($CONFIG['SESSION_NAME'],'GIID_IMAGE');
		// pr($fileName);
		if (isset($fileName)) {
			(@$fileName->filename !='') ? $fileNameGiid = @$fileName->filename : $fileNameGiid = "";
			$data = array('id'=>@$fileName->id,'filename'=>$fileNameGiid, 'emailtoken'=>@$fileName->emailtoken);
			$this->apps->assign('dataFile',$data);
		}
		
		$getSurvey = $this->apps->registerHelper->getQuestion(2);
		$getConsent = $this->apps->registerHelper->getQuestion(3);
		$index = array('A','B','C','D','E','F');
		$this->apps->assign('survey',$getSurvey);
		$this->apps->assign('consent',$getConsent);
		$this->apps->assign('index',$index);
		
		
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/register-confirm.html"); 
		
	}
}
?>
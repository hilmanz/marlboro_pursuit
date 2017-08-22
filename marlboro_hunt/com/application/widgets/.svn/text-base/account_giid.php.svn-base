<?php
class account_giid{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		global $CONFIG;
		
		$getUserGiid = $this->apps->userHelper->getGiidUser();
		// pr($getUserGiid);
		$giidType = $this->apps->registerHelper->getGiidType();
		
		$this->apps->assign('giid_type',$giidType);
		$this->apps->assign('userGiid',$getUserGiid);
		
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/account-giid.html"); 
		
	}
}
?>
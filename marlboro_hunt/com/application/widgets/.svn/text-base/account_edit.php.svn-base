<?php
class account_edit{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		
		$getUserProfile = $this->apps->userHelper->getUserProfile();
		$province = $this->apps->contentHelper->getProvince();
		$mprefix = $this->apps->contentHelper->getMobilePrefix();
		$getCityName = $this->apps->contentHelper->getCity(array('idCity'=>$getUserProfile['city']));
		$this->apps->assign('account',$getUserProfile);
		
		// pr($getUserProfile);
		// pr($getCityName);
		$mobileNumber = explode('-',$getUserProfile['phone_number']);
		$landlineNumber = explode('-',$getUserProfile['landline_number']);
		
		//$this->apps->assign('month',$monthArr);
		$this->apps->assign('province',$province);
		$this->apps->assign('prov_value',$getCityName);
		$this->apps->assign('mobile_value',$mobileNumber);
		$this->apps->assign('landline_value',$landlineNumber);
		$this->apps->assign('mprefix',$mprefix);
		
		// pr($province);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/account-edit.html"); 
		
	}
}
?>
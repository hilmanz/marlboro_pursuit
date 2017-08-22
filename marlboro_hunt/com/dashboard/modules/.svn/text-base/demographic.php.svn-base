<?php
class demographic extends App{
	
	
	function beforeFilter(){
	
		$this->demographyHelper = $this->useHelper("demographyHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
		
		$this->assign('locale', $LOCALE[1]);
	
	}
	function main(){
	
		$ageData = $this->demographyHelper->ageData();
		$genderData = $this->demographyHelper->genderData();
		$province = $this->demographyHelper->provinceData();
		$cityData = $this->demographyHelper->cityData();
		$brandPreference = $this->demographyHelper->brandPreference();
		
		$this->assign("ageData",json_encode($ageData));
		$this->assign("genderData",json_encode($genderData));
		$this->assign("province",json_encode($province));
		$this->assign("cityData",json_encode($cityData));
		$this->assign("brandPreference",json_encode($brandPreference));
		// pr($brandPreference);
		
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/demographic.html');
		
	}
		
}
?>
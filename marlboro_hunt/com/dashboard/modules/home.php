<?php
class home extends App{
	
	
	function beforeFilter(){
	
		$this->dataHelper = $this->useHelper("dataHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
				
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
	
	}
	
	function main(){
		
		$alluser = $this->dataHelper->allUserRegistration();
		$loginData = $this->dataHelper->loginUser(0);
		$activeUser = $this->dataHelper->loginUser(1);
		$superUser = $this->dataHelper->superUser(30);
		$veryActive = $this->dataHelper->veryActive(7);
		// pr($activeUser);
		$userUnverified = $this->dataHelper->userUnverified();
		if ($this->_p('hidden_date')){
			$searchByDate = $this->dataHelper->searchByDate();
		
		}
		
		$this->assign("alluser",$alluser);
		$this->assign("loginData",$loginData);
		$this->assign("activeUser",$activeUser);
		$this->assign("superUser",$superUser);
		$this->assign("userUnverified",$userUnverified);
		$this->assign("veryActive",$veryActive);
		
		/* $this->assign('startdate',$startdate);
		$this->assign('enddate',$enddate); */
		
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/user.html');
		
	}
		
}
?>
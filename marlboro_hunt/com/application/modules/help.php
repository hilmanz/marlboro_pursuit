<?php
class help extends App{
	
	
	function beforeFilter(){

		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
	}
	
	function main(){
		
	   $this->log('globalAction','help pages');
	   return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/help-pages.html');
		
	}
	
	
}
?>
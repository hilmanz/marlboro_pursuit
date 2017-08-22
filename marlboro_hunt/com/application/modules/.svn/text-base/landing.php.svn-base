<?php
class landing extends App{
		
	function beforeFilter(){
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('basemopurl', $CONFIG['BASE_MOP_URL']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);		
		$this->assign('locale', $LOCALE[1]);
		$this->contentHelper = $this->useHelper('contentHelper');
		
	}
	
	function main(){
		
		$page='home';
		$data = $this->contentHelper->getBanner($page,"slider_header");
		$this->assign('banner',$data);		
		$this->log('surf','landing');
		print $this->View->toString(TEMPLATE_DOMAIN_WEB .'/landing.html');
		exit;
		
	}
	
}
?>
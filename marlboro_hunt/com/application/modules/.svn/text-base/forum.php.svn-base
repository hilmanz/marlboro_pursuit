<?php
class forum extends App{
	
	
	function beforeFilter(){
		$this->contentHelper = $this->useHelper('contentHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
	}
	
	function main(){
		
		// $this->View->assign('newest_post',$this->setWidgets('newest_post'));
		$this->log('globalAction','forum pages');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/forum-pages.html');
		
	}
	
	
}
?>
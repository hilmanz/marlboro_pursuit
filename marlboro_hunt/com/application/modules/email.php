<?php
class email extends App{
	
	
	function beforeFilter(){
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->userHelper = $this->useHelper('userHelper');
		$this->searchHelper = $this->useHelper('searchHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
		$this->assign('pages', strip_tags($this->_g('page')));
		
	}
	
	function main(){
		//pr($_POST);
		
		$this->View->assign('send_mail',$this->setWidgets('send_mail'));
		
		//$this->log('surf','event pages');
		//exit;
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/email-form.html');
		
	}
	
}
?>
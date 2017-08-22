<?php
class mypages extends App{
	
	
	function beforeFilter(){
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->userHelper = $this->useHelper('userHelper');
		$this->pageHelper = $this->useHelper('pageHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
	}
	
	function main(){
		
		// $this->View->assign('newest_post',$this->setWidgets('newest_post'));
		$this->log('globalAction','my page');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/my-page.html');
		
	}
	
	function band(){
		
		// $this->View->assign('newest_post',$this->setWidgets('newest_post'));
		$this->log('globalAction','my page band');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/my-page-band.html');
		
	}
	
	function visualart(){
		
		// $this->View->assign('newest_post',$this->setWidgets('newest_post'));
		$this->log('globalAction','my page visualart');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/my-page-visual-art.html');
		
	}
	function photography(){
		
		// $this->View->assign('newest_post',$this->setWidgets('newest_post'));
		$this->log('globalAction','my page photography');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/my-page-photography.html');
		
	}
	function fashion(){
		
		// $this->View->assign('newest_post',$this->setWidgets('newest_post'));
		$this->log('globalAction','my page fashion');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/my-page-fashion.html');
		
	}
	
	function dj(){
		
		// $this->View->assign('newest_post',$this->setWidgets('newest_post'));
		$this->log('globalAction','my page fashion');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/my-page-dj.html');
		
	}
}
?>
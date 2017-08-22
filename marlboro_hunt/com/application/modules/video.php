<?php
class video extends App{
	function beforeFilter(){
	
		$this->contentHelper = $this->useHelper('contentHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
		$this->assign('locale', $LOCALE[1]);
		
	}
	
	function main()
	{
	
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/video-pages.html');
	}
	
	
}
?>
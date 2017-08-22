<?php
class testviews extends App{
	
	
	
	function beforeFilter(){
		/* ini init si helper yang mw lu pake */
		// $this->articleHelper = $this->useHelper('articleHelper'); /* ini nama file helper nya */
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('locale',$LOCALE[$this->lid]);
		
	}
	function main(){
		global $LOCALE;
		/* ini untuk nge-set widget yang mw lu pake di module nya*/
		$this->View->assign('articleWidget',$this->setWidgets('articleWidget'));
		// $this->View->assign('bannerWidget',$this->setWidgets('bannerWidget'));
	
		/* test itu folder ya */
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/test/testviews.html');/* ini nama file tempaltes nya */
	}
	
	/* pertama */
	/* bikin widget buat article , liat di articleWidget.php */ 
	
}
?>
<?php
class tnc extends App{
	
	
	function beforeFilter(){
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->searchHelper = $this->useHelper('searchHelper');
		$this->loginHelper = $this->useHelper('loginHelper');
	
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
		$this->assign('pages', strip_tags($this->_g('page')));
	}
	
	function main(){
		
		global $CONFIG;
		$this->View->assign('term_and_condition',$this->setWidgets('term_and_condition'));
		
		if ($this->_p('agree') == 1){
			
			$res = $this->loginHelper->term_and_condition();
			
			if ($res){
				
				//exit;
				sendRedirect("{$CONFIG['BASE_DOMAIN']}questionnaire/survey");
				die();
			}
		}
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/widgets/term-and-condition.html');
		
	}
	
	
}
?>
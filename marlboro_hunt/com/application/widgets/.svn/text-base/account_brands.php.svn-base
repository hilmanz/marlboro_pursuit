<?php
class account_brands{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$pages = $this->apps->_g('act');
	
		$getUserProfile = $this->apps->userHelper->getUserProfile();
		$getBrandsByUser = $this->apps->userHelper->getBrandsByUser();
		// pr($getBrandsByUser);
		$brands = $this->apps->loginHelper->getBrands();
		$question = $this->apps->registerHelper->getQuestion(1);
		$this->apps->assign('brands', $brands);
		$this->apps->assign('question', $question);
		$this->apps->assign('userBrands', $getBrandsByUser);
		$this->apps->assign('brandsIndex', ($getBrandsByUser['other_answer'] - 1));
		
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/account-brands.html"); 
	
		
		
	}
}
?>
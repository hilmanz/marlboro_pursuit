<?php
class search_event{
	
	function __construct($apps=null){		
			$this->apps = $apps;	
			global $LOCALE,$CONFIG;
			$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
			$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
			$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		$content = $this->apps->contentHelper->getEventArticleType();
		// pr($content);
		// $cityid = intval($this->apps->_p('cityid'));
		// $dateposted = intval($this->apps->_p('dateposted'));
		// pr($city);
		$this->apps->View->assign('city',false);
		$this->apps->View->assign('cityid',false);
		$this->apps->View->assign('dateposted',false);
	
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/search-event.html");
	
	}
	



}


?>
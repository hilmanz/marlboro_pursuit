<?php
class search extends App{

	
	function beforeFilter(){
	
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->searchHelper = $this->useHelper('searchHelper');
		$this->userHelper = $this->useHelper('userHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
		$this->assign('pages', strip_tags($this->_g('page')));
		$this->assign('locale', $LOCALE[1]);
		
	}
	
	function main(){
		
		$keywords =  strip_tags($this->_p('q'));
		
		$this->View->assign('side_banner',$this->setWidgets('side_banner'));
		$this->assign('keywords',$keywords);
		$result = $this->searchHelper->search(null,10,null,$keywords);
		$this->assign('total', intval($result['total']));
		$this->assign('start', intval($this->_request('start')));
		if($result){
			$this->assign('article', $result['result']);		
		}
			$this->log('surf','search');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/search.html'); 
	}
	
	function friends(){
		
		$data = $this->userHelper->getSearchFriends();
		print json_encode($data);
		exit;
	
	}
	
	function redirecting(){
		global $CONFIG;
		$link  = strip_tags($this->_g('link'));
		$keywords = strip_tags($this->_g('keywords'));
		$contentid = intval($this->_g('contentid'));
		$fromwhere = intval($this->_g('fromwhere'));

		if(strpos($link,"|")) {
			$link = preg_replace("/\|/","/",$link);
			if(strpos($link,"_")) $link = preg_replace("/_/",".",$link); 
		}

		$result = $this->searchHelper->addKeywords($keywords,$contentid,$fromwhere,$link);
		if($result) sendRedirect($link);
		else sendRedirect($CONFIG['BASE_DOMAIN']."search/".$keywords);
		exit;
	}
	
	function ajax(){
		$keywords =  strip_tags($this->_p('q'));		
		$result = $this->searchHelper->search(null,10,null,$keywords);	
		print json_encode($result);exit;		
		
	}
	
}
?>
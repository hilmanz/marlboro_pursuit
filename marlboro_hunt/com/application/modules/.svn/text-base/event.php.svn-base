<?php
class event extends App{
	
	
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
		
		$this->View->assign('search_event',$this->setWidgets('search_event'));
		// $this->View->assign('event_review',$this->setWidgets('event_review'));
		// $this->View->assign('side_banner',$this->setWidgets('side_banner'));
		// $this->View->assign('shorter_filter',$this->setWidgets('shorter_filter'));
		// $this->View->assign('article_calendar_list',$this->setWidgets('article_calendar_list'));
		
		$this->log('surf','event pages');
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/event-pages.html');
		
	}
	
		
	function detail(){
		
		$this->setWidgets('popup_article_detail');
		exit;
	
	}
	
	function ajax(){
		$needs = $this->_request("needs");
		$contentid = intval($this->_request("contentid"));
		$start = intval($this->_request("start"));
		if($needs=="content")  $data =  $this->contentHelper->getArticleContent($start);		
		if($needs=="comment") $data =  $this->contentHelper->getComment($contentid,false,$start);
		
		print json_encode($data);exit;
		
	}
	
	function attending(){
		$data =  $this->userHelper->attending();
		print json_encode($data);exit;
	}
	
}
?>
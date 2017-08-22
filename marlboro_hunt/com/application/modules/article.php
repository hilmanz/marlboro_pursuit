<?php
class article extends App{

	
	function beforeFilter(){
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->contentHelper = $this->useHelper('contentHelper');

		$this->searchHelper = $this->useHelper('searchHelper');
		
	}
	
	function main(){
		
		return false;

	}
	
	function comment(){
		$data = $this->contentHelper->addComment();
		if($data)$this->log("add comments", intval($this->_p('cid')));
		print json_encode($data);exit;
	}
	
	function favorite(){
		$data = $this->contentHelper->addFavorite();
		if($data)$this->log("add favorite", intval($this->_p('cid')));
		print json_encode($data);exit;
	}

}
?>
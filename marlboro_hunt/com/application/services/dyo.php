<?php
class dyo  extends ServiceAPI{
			

	function beforeFilter(){
		
		$this->contentHelper = $this->useHelper('contentHelper');
		
		$this->uploadHelper = $this->useHelper('uploadHelper');
		$this->DYOHelper = $this->useHelper('DYOHelper');
	
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));		
		
	}
	
	function getUser(){		
		// $this->Request->setParam('uid',1);
		$user = $this->userHelper->getUserProfile(); 
		$data['profile'] = $user;	
		return $data;
	}
	
	function saveImage(){
		
		$data = $this->DYOHelper->saveImage(); 	
		print json_encode($data);exit;	
	}
	
	function getGallery(){
		GLOBAL $CONFIG;
		$data['dyo'] = $this->DYOHelper->getGallery(false);
		$data['BASEPATH'] = $CONFIG['BASE_DOMAIN']."public_assets/user/dyo/";
		print json_encode($data);exit;		
	}
	
	function getUserGallery(){
		GLOBAL $CONFIG;
		$data['dyo'] = $this->DYOHelper->getGallery(true); 
		$data['BASEPATH'] =  $CONFIG['BASE_DOMAIN']."public_assets/user/dyo/";
		print json_encode($data);exit;		
	}
}
?>

<?php
class mygallery extends App{	
	
	function beforeFilter(){
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->userHelper  = $this->useHelper('userHelper');
		$this->wallpaperHelper = $this->useHelper('wallpaperHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('pages', strip_tags($this->_g('page')));
		$this->assign('tokenize',gettokenize(5000*60,$this->user->id));
	}
	
	function main(){
		$this->View->assign('profie_box',$this->setWidgets('my_profile_box'));
 		$this->View->assign('my_circle',$this->setWidgets('my_circle'));
		$this->View->assign('side_banner',$this->setWidgets('side_banner'));
		$this->View->assign('wallpaper',$this->setWidgets('my_wallpaper'));
		$this->View->assign('myGallery',$this->setWidgets('my_gallery'));
		$this->log('surf','my profile');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/my-profile.html');
	}
	
	function setting(){
		global $CONFIG;
		$doupdate = intval($this->_p('update'));
		if($doupdate==1) {		
			$update = $this->userHelper->updateUserProfile();
			if($update) {
				$this->log('update profile','');
				sendRedirect( $CONFIG['BASE_DOMAIN']."my");
				return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/login_message.html');	
			}
		}
		$data = $this->userHelper->getUserProfile();
		if($data){
				foreach($data as $key => $val){
					$this->assign($key,$val);
				}
		}else $data = false;
		$this->log('surf','my profile');
		print(json_encode($data));	exit;
	}
	
	function photo(){
		global $CONFIG;
		
		if(strip_tags($this->_request('action'))=='set') {
		
			$data = $this->userHelper->saveImage();
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json');		
			print(json_encode($data));
			exit;
		}
		if(strip_tags($this->_request('action'))=='crop') {
			$data = $this->userHelper->saveCropImage();
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json');		
			print(json_encode($data));
			exit;
		}
	}
	
	function message(){
		$this->log('surf','my message');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/my-message.html');
	}	
	
	function friends(){
		global $CONFIG;
		if(strip_tags($this->_g('do'))=='add') {
			$uid = intval($this->_request('uid'));
			$result = $this->userHelper->addCircleUser();
			if($result) {				
				$this->log('add friends',"{$uid}");
				print(json_encode($result));
			}			
			exit;
		}
		
		if(strip_tags($this->_g('do'))=='undo') {
			$uid = intval($this->_request('uid'));
			$result = $this->userHelper->unAddCircleUser();
			if($result) {
				$this->log('unfriends',"{$uid}");				
				print(json_encode($result));	
			}			
			exit;
		}
		
		$this->log('surf','my friends list');
		$this->View->assign('profie_box',$this->setWidgets('my_profile_box'));
		$this->View->assign('my_circle',$this->setWidgets('my_circle'));
		$this->View->assign('side_banner',$this->setWidgets('side_banner'));
		$data = $this->userHelper->getFriends(true,16);
		
		if($data){
			$this->View->assign('usercircle',$data['result']);
			$this->View->assign('total',$data['total']);
	
		}else $this->View->assign('total',0);
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/my-friends.html');
	}
	
	function circle(){			
		global $CONFIG;
		
		if(strip_tags($this->_g('do'))=='create') {
			$data = $this->userHelper->createCircleUser();
			$name = strip_tags($this->_request('name'));
			if($data) $this->log('create group',"{$name}");
			print(json_encode($data));	
		}
		if(strip_tags($this->_g('do'))=='loss') {
			$data = $this->userHelper->uncreateCircleUser();
			$name = strip_tags($this->_request('name'));
			if($data) $this->log('destroy group',"{$name}");
			print(json_encode($data));	
		}		
		exit;			
	}
	
	function cover(){
		if(strip_tags($this->_request('action'))=='set') {
			$data = $this->contentHelper->setCover();
			print json_encode($data);
		}
		if(strip_tags($this->_request('action'))=='crop') {
			$data = $this->wallpaperHelper->saveCropImage();
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json');		
			print(json_encode($data));
		}		
		exit;		
	}	
	
	function ajax(){
		$needs = $this->_request("needs");
		$contentid = intval($this->_request("contentid"));
		$start = intval($this->_request("start"));
		if($needs=="content")  $data =  $this->contentHelper->getListSongs($start);
		if($needs=="comment") $data =  $this->contentHelper->getComment($contentid,false,$start);
		
		print json_encode($data);exit;		
	}
}
?>
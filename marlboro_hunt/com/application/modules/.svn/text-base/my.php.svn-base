<?php
class my extends App{	
	
	function beforeFilter(){
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->userHelper  = $this->useHelper('userHelper');
		$this->wallpaperHelper = $this->useHelper('wallpaperHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		$this->newsHelper = $this->useHelper('newsHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);		
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));
		$this->assign('tokenize',gettokenize(5000*60,$this->user->id));
	}
	
	function main(){
		$this->userHelper->getRankUser();
		$this->View->assign('profie_box',$this->setWidgets('my_profile_box'));
 		$this->View->assign('my_circle',$this->setWidgets('my_circle'));
		$this->View->assign('side_banner',$this->setWidgets('side_banner'));
		$this->View->assign('wallpaper',$this->setWidgets('my_wallpaper'));
		$this->View->assign('myGallery',$this->setWidgets('my_gallery'));
		$this->View->assign('my_activity',$this->setWidgets('my_activity'));
		$this->View->assign('my_favorite',$this->setWidgets('my_favorite'));
		//$this->View->assign('my_contest_submission',$this->setWidgets('my_contest_submission'));
		$this->View->assign('my_calendar',$this->setWidgets('my_calendar'));
		$this->View->assign('my_playlist',$this->setWidgets('playlist_songs'));
		$this->log('my profile',$this->user->id);
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/my-profile.html');		
	}
	
	function inbox(){
		$this->View->assign('profie_box',$this->setWidgets('my_profile_box'));
 		$this->View->assign('my_circle',$this->setWidgets('my_circle'));
		$this->View->assign('side_banner',$this->setWidgets('side_banner'));
		$this->View->assign('my_inbox',$this->setWidgets('my_inbox'));
	
		$this->log('surf','my inbox');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/my-inbox.html');		
	}
	
	function setting(){
		global $CONFIG;
		$doupdate = intval($this->_p('update'));
		if($doupdate==1) {		
			$update = $this->userHelper->updateUserProfile();
			if($update) {
				$this->log('update profile',$this->user->id);
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
			$data = $this->userHelper->saveImage('photo_profile');
			print(json_encode($data));
			exit;
		}
		if(strip_tags($this->_request('action'))=='crop') {
			$data = $this->userHelper->saveCropImage();
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
		
		if(strip_tags($this->_g('do'))=='degroup') {
			$uid = intval($this->_request('uid'));
			$result = $this->userHelper->deGroupCircleUser();
			if($result) {
				$this->log('degroupfriend',"{$uid}");				
				print(json_encode($result));	
			}			
			exit;
		}
		
		$this->log('surf','my friends list');
		$this->View->assign('profie_box',$this->setWidgets('my_profile_box'));
		$this->View->assign('my_circle',$this->setWidgets('my_circle'));
		$this->View->assign('side_banner',$this->setWidgets('side_banner'));
		$data = $this->userHelper->getFriends(true,16);
		
		$this->View->assign('usercircle',false);
		$this->View->assign('total',0);
		if($data){
			$this->View->assign('usercircle',$data['result']);
			$this->View->assign('total',$data['total']);
	
		}
	
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/my-friends.html');
	}
	
	function gallery(){
		global $CONFIG;
		
		$this->log('surf','my gallery all');
		$this->View->assign('profie_box',$this->setWidgets('my_profile_box'));
		$this->View->assign('my_circle',$this->setWidgets('my_circle'));
		$this->View->assign('side_banner',$this->setWidgets('side_banner'));
		$this->View->assign('wallpaper',$this->setWidgets('my_wallpaper'));
		$data = $this->contentHelper->getMygallery($start=null,$limit=9,$userid=NULL);
		$this->View->assign('my_gallery_all',$data['result']);
		$this->View->assign('start',intval($this->_request('start')));
		$this->View->assign('total',intval($data['total']));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/my-gallery-all.html');
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
		$this->log('add cover',$this->user->id);
		if(strip_tags($this->_request('action'))=='set') {
			$data = $this->contentHelper->setCover();
			print json_encode($data);
			exit;
		}
		if(strip_tags($this->_request('action'))=='upload') {
			$data = $this->userHelper->saveImage('photo_cover');
			print json_encode($data);
			exit;
		}
		if(strip_tags($this->_request('action'))=='crop') {
			$data = $this->userHelper->saveCropCoverImage();
			print(json_encode($data));	
			exit;
		}
		
	}
	
	function ajax(){
		$needs = $this->_request("needs");
		$contentid = intval($this->_request("contentid"));		
		$start = intval($this->_request("start"));
		$noteid = intval($this->_p("noteid"));

		if($needs=="contentgallery") $data =  $this->contentHelper->getMygallery($start,$limit=9);
		if($needs=="content") $data =  $this->contentHelper->getListSongs($start);
		if($needs=="comment") $data =  $this->contentHelper->getComment($contentid,false,$start);
		if($needs=="hapusmygallery") $data =  $this->contentHelper->hapusmygallery();
		if($needs=="inbox-trash") $data = $this->newsHelper->trashInbox($noteid);
		if($needs=="inbox-news-letter") $data = $this->newsHelper->saveinboxtime();	
		if($needs=="inbox-count") $data = $this->newsHelper->inboxcount();	
		if($needs=="inbox-read") $data = $this->newsHelper->inboxread($noteid);	
		if($needs=="inbox-data-json") $data = $this->newsHelper->getInboxUser($start);	
		if($needs=="friends-list") 	$data = $this->userHelper->getFriends(true,16);	
		
		print json_encode($data);exit;
	}
}
?>
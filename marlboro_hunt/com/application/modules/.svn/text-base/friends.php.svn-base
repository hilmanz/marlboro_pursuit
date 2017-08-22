<?php
class friends extends App{	
	
	function beforeFilter(){
		global $LOCALE,$CONFIG;
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->userHelper  = $this->useHelper('userHelper');
		$this->wallpaperHelper = $this->useHelper('wallpaperHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->fid = intval($this->_request('uid'));
		
		$this->assign('locale', $LOCALE[1]);
		
		$this->assign('pages', strip_tags($this->_g('page')));
		$this->assign('friendid',$this->fid);
		// pr($this->userHelper->getUserProfile());
		if($this->user->id==$this->fid || !$this->userHelper->getUserProfile() ) {
			sendRedirect($CONFIG['BASE_DOMAIN'].'my');
			exit;
		}
		
	
	}
	
	function main(){
		$this->View->assign('update_profile',$this->setWidgets('update_profile'));
		$this->View->assign('profie_box',$this->setWidgets('my_profile_box'));
		$this->View->assign('my_circle',$this->setWidgets('my_circle'));
		$this->View->assign('wallpaper',$this->setWidgets('my_wallpaper'));
		$this->View->assign('myGallery',$this->setWidgets('my_gallery'));
		$this->View->assign('upload_image',$this->setWidgets('upload_image'));
		$this->View->assign('isFriends',$this->userHelper->isFriends($this->fid));
		$this->View->assign('my_activity',$this->setWidgets('my_activity'));
		$this->log('surf',"friends_profile_{$this->fid}");
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/friends-profile.html');		
	}
	
	
	function offriends(){
		global $CONFIG;
				
		$this->log('surf','friends of friends list');
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
	
}
?>
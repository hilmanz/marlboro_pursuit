<?php
class upload extends App{
	
	function beforeFilter(){
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
	}
	
	function main(){
		$this->log('surf','upload pages');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/upload-pages.html');		
	}
	
	function image(){
		$this->View->assign('upload_pages_image',$this->setWidgets('upload_pages_image'));
		// $this->log('uploads','upload pages image');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/upload-pages-image.html');		
	}
	
	function music(){
		$this->View->assign('upload_pages_music',$this->setWidgets('upload_pages_music'));
		// $this->log('uploads','upload pages music');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/upload-pages-music.html');		
	}
	
	function video(){
		$this->View->assign('upload_pages_video',$this->setWidgets('upload_pages_video'));
		// $this->log('uploads','upload pages video');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/upload-pages-video.html');	
	}
	
	function cropcenterviewoimage(){
		//sample cropcenterviewoimage
		//$imageFilename=null,$imageUrl=null,$width=0,$height=0
		$data = $this->uploadHelper->autoCropCenterArea('image_banner2.jpg','D:/xampp/htdocs/a360/trunk/athreesix/public_html/public_assets/banner/',300,370);
		pr($data);
		exit;
	}
}
?>
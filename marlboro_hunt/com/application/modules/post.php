<?php
class post extends App{	
	
	function beforeFilter(){
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		$this->loginHelper = $this->useHelper('loginHelper');
		global $LOCALE,$CONFIG;
		
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('user',$this->user);
		$this->assign('tokenize',gettokenize(5000*60,$this->user->id));
		$this->assign('pages', strip_tags($this->_g('page')));
		$this->assign('locale', $LOCALE[1]);		
		$this->dbshema = "athreesix";
	}
	
	function main(){
		global $LOCALE,$CONFIG;
		
	
		$type_content = $this->contentHelper->getTypeContent();
		$this->View->assign('jenis_type',$type_content);
		$this->View->assign('upload_pages_image',$this->setWidgets('upload_pages_image'));

		$this->log('surf','post');
		print $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/post.html');
		exit;
	}
	
	function upload(){
		global $CONFIG,$LOCALE;
		$username = ucwords($this->user->name);
		
		if(strip_tags($this->_p('upload'))=='image') {
			$type = intval($this->_p('type'));
			if ($type==3) {
				$type = intval($this->_p('typemusic'));
			} else {
				$type = intval($this->_p('type'));
			}
			
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."article/";
			if (isset($_FILES['image'])&&$_FILES['image']['name']!=NULL) {
				if (isset($_FILES['image'])&&$_FILES['image']['size'] <= 2000000) {
					$data = $this->uploadHelper->uploadThisImage($_FILES['image'],$path);
					if ($data['arrImage']!=NULL) {
						$result = $this->contentHelper->addUploadImage($data,$type);
						if($result) {
							// $this->log('uploads',$this->getLastInsertId());
							$data = true;
						} else {
							$data = false;
						}
					} else {
						$data = false;
					}
				} else {
					$data = false;
				}
			} else {
				$data = false;
			}
		} elseif (strip_tags($this->_p('upload'))=='music') {
			$type = intval($this->_p('typemusic'));
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."music/mp3/";
			
			if (isset($_FILES['music'])&&$_FILES['music']['name']!=NULL) {
				if (isset($_FILES['music'])&&$_FILES['music']['size'] <= 5242880) {
					$data = $this->uploadHelper->uploadThisMusic($_FILES['music'],$path);
					if ($data['arrMusic']!=NULL) {
						$result = $this->contentHelper->addUploadMusic($data,$type);
						if($result) {
							// $this->log('uploads',$this->getLastInsertId());
							$data = true;
						} else {
							$data = false;
						}
					} else {
						$data = false;
					}
				} else {
					$data = false;
				}
			} else {
				$data = false;
			}
		} elseif (strip_tags($this->_p('upload'))=='video') {
			$type = intval($this->_p('type'));
			if ($type==3) {
				$type = intval($this->_p('typemusic'));
			} else {
				$type = intval($this->_p('type'));
			}
			
			if ($type) {
				if (strip_tags($this->_p('url'))!=NULL) {
					$data = $this->contentHelper->addUploadVideo($type);
					// $this->log('uploads',$this->getLastInsertId());
				} else $data = false;
			} else $data = false;
		}
		
		print json_encode($data);exit;
	}

		
	function getJenis(){
		$valJenis = intval($this->_p('jenis_info'));
		print json_encode($valJenis);exit;
	}
	
	function ajaxpost($data){
		print json_encode($data);exit;
	}
}
?>
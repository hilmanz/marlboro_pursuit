<?php
class gid extends App{
		
	function beforeFilter(){
		$this->registerHelper = $this->useHelper('registerHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
	}
	
	function main(){
	
		global $CONFIG,$logger;
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign('basedomain',$basedomain);
		$this->log('globalAction','LOGIN');
		
		$path = $CONFIG['LOCAL_PUBLIC_ASSET'].'user/gid/';//pr($path);
		$sourceFile = $_FILES;//pr($_FILES);
		pr('masuk');
		exit;
		$file['name'] = $sourceFile['image']['name'];
		$file['type'] = $sourceFile['image']['type'];
		$file['tmp_name'] = $sourceFile['image']['tmp_name'];
		$file['error'] = $sourceFile['image']['error'];
		$file['size'] = $sourceFile['image']['size'];
		
		$uploadImage = $this->uploadHelper->uploadThisImage($file, $path);
		//pr($uploadImage);
		$imageName = $uploadImage['arrImage']['filename'];
		$result = $this->registerHelper->register_setGid($imageName);
		if ($result){
			unset($_SESSION['register_id']);
			echo '<script>window.location.href="'.$basedomain.'"</script>';
		}
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'register.html');
	}
	
	
	
}
?>
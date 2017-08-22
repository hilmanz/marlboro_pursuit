<?php 

class wallpaperHelper {
	
	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
	}

	function getWallpaper (){
		global $CONFIG;
		$typeofpages = 0;
		$myid = 0;
		//friend
		$uid = intval($this->apps->_request('uid'));
		if($uid!=0) $myid = $uid;
		
		
		//page
		$pid = intval($this->apps->_request('pid'));
		if($pid!=0) $myid = $pid;		
		if($myid==0) $myid = intval($this->uid);
		
		$sql ="	SELECT * FROM my_wallpaper WHERE myid={$myid} AND type={$typeofpages} AND n_status=1 ORDER BY datetime DESC LIMIT 1 "; 
		$qData=$this->apps->fetch($sql);
		// pr($sql);
		if(!$qData) return false;
		//cek detail image from folder
		if($typeofpages==0) $coverfolder = "user/cover/";
		else $coverfolder = "pages/cover/";	
		$qData['coverfolder'] = $coverfolder;
		if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}{$coverfolder}original_{$qData['image']}")) $qData['imgoriginal']= "original_{$qData['image']}";
		else $qData['imgoriginal'] = false;
			
		return $qData;
		
	}

	function getPagesWallpaper (){
		global $CONFIG;
		
		$page = $this->apps->_g('page');
		if ($page) {
			$typeofpages = $page=="myband" ? 1 : 4;
		}
		$myid = 0;
		//friend
		$uid = intval($this->apps->_request('uid'));
		if($uid!=0) $myid = $uid;
		
		$pid = intval($this->apps->_request('pid'));
		if($pid) {
			$sql_owner = "SELECT id,ownerid FROM my_pages WHERE id = {$pid} ";
			$rdata = $this->apps->fetch($sql_owner);
			$pid = $rdata['ownerid'];
		}
		if(!$pid) $pid = intval($this->uid);
		if($pid!=0 || $pid!=null) {
			$sql ="	SELECT * FROM my_wallpaper WHERE myid={$pid} AND type={$typeofpages} AND n_status=1 ORDER BY datetime DESC LIMIT 1 "; 
			$qData=$this->apps->fetch($sql);		
			if(!$qData) return false;
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}pages/cover/{$qData['image']}")) $qData['image'];
			else $qData['image']="";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}pages/cover/original_{$qData['image']}")) $qData['imgoriginal']= "original_{$qData['image']}";
			else $qData['imgoriginal'] = false;
			return $qData;
		}
		return false;
	}
	
	function saveCropImage(){
				global $CONFIG;
				$typeofimage = strip_tags($this->apps->_p("typeofimage"));
				$files['source_file'] =  strip_tags($this->apps->_p("imageFilename"));
				$files['url'] = "{$CONFIG['LOCAL_PUBLIC_ASSET']}{$typeofimage}/";
				$arrFilename = explode('.',$files['source_file']);
				if($files==null) return false;
				$targ_w = $this->apps->_p('w');
				$targ_h =$this->apps->_p('h');
				$jpeg_quality = 90;
				
				if($files['source_file']=='') return false;
				
				$src = 	$files['url'].$files['source_file'];
				copy($src,$files['url'].time()."_".$files['source_file']);
				try{
					$img_r = false;
					if(strtolower($arrFilename[1])=='jpg' || strtolower($arrFilename[1])=='jpeg' ) $img_r = imagecreatefromjpeg($src);
					if(strtolower($arrFilename[1])=='png' ) $img_r = imagecreatefrompng($src);
					if(strtolower($arrFilename[1])=='gif' ) $img_r = imagecreatefromgif($src);
					if(!$img_r) return false;
					$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

					imagecopyresampled($dst_r,$img_r,0,0,$this->apps->_p('x'),$this->apps->_p('y'),	$targ_w,$targ_h,$this->apps->_p('w'),$this->apps->_p('h'));

					// header('Content-type: image/jpeg');
					$dest_img =$files['source_file'];
					
					if(strtolower($arrFilename[1])=='jpg' || strtolower($arrFilename[1])=='jpeg' ) imagejpeg($dst_r,$files['url'].$dest_img,$jpeg_quality);
					if(strtolower($arrFilename[1])=='png' ) imagepng($dst_r,$files['url'].$dest_img);
					if(strtolower($arrFilename[1])=='gif' ) imagegif($dst_r,$files['url'].$dest_img);
					
				}catch (Exception $e){
					return false;
				}
				
				if(file_exists("{$files['url']}{$dest_img}")) return array('url'=>$files['url'] , 'img' => $dest_img);
				else return false;
	}
}
?>
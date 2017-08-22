<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class UserManager extends SQLData{
	var $userID;
	function UserManager(){
		parent::SQLData();
		$this->force_connect(true);
		
	}
	function add($data){
		global $CONFIG,$ENGINE_PATH;
	
		$enc_key = md5(base64_encode($data['username'].md5($data['password'])));
		$password = md5($data['password']);
	
		$sql ="INSERT INTO gm_user(name,email,position,level,username,password,enc_key)
					  VALUES('".$data['name']."','".$data['email']."','".$data['position']."','".$data['level']."','".$data['username']."','".$password."','".$enc_key."')";
		
		
		$rs = $this->query($sql);
		$last_id = $this->getLastInsertId();
		
		if ($data['emblem']['name']!=NULL) {
			include_once $ENGINE_PATH.'Utility/phpthumb/ThumbLib.inc.php';
			list($file_name,$ext) = explode('.',$data['emblem']['name']);
			$img = md5($data['emblem']['name'].rand(1000,9999)).".".$ext;
			try{
				$thumb = PhpThumbFactory::create($data['emblem']['tmp_name']);
			}catch (Exception $e){
				// handle error here however you'd like
			}
			
			if(move_uploaded_file($data['emblem']['tmp_name'],"{$CONFIG['LOCAL_PUBLIC_ASSET']}emblem/".$img)){
				list($width, $height, $type, $attr) = getimagesize("{$CONFIG['LOCAL_PUBLIC_ASSET']}emblem/{$img}");
				$maxSize = 1000;
				if($width>=$maxSize){
					if($width>=$height) {
						$subs = $width - $maxSize;
						$percentageSubs = $subs/$width;
					}
				}
				if($height>=$maxSize) {
					if($height>=$width) {
						$subs = $height - $maxSize;
						$percentageSubs = $subs/$height;
					}
				}
				if(isset($percentageSubs)) {
					$width = $width - ($width * $percentageSubs);
					$height =  $height - ($height * $percentageSubs);
				}
				
				$w_small = $width - ($width * 0.5);
				$h_small = $height - ($height * 0.5);
				$w_tiny = $width - ($width * 0.7);
				$h_tiny = $height - ($height * 0.7);
				
				//resize the image
				$thumb->adaptiveResize($width,$height);
				$big = $thumb->save( "{$CONFIG['LOCAL_PUBLIC_ASSET']}emblem/big_".$img);
				$thumb->adaptiveResize($w_small,$h_small);
				$small = $thumb->save( "{$CONFIG['LOCAL_PUBLIC_ASSET']}emblem/small_".$img );
				$thumb->adaptiveResize($w_tiny,$h_tiny);
				$tiny = $thumb->save( "{$CONFIG['LOCAL_PUBLIC_ASSET']}emblem/tiny_".$img );
			}
			$this->inputImage($last_id,$img);
		}
		if($last_id){
			$this->query("
			INSERT INTO gm_specified_role (level ,	aid ,	type ,	category ,	n_status )
			VALUES ('".$data['level']."',{$last_id},2,'".$data['category']."',1)
			");
		}
		return $rs;
	}
	
	function delete($userID){

		$f = $this->query("DELETE FROM gm_user WHERE userID='".$userID."'");

		return $f;
	}
	
	function update($data){
		global $CONFIG,$ENGINE_PATH;
	
		if(!$data['noupdatepass']){
			$enc_key = md5(base64_encode($data['username'].md5($data['password'])));
			$password = md5($data['password']);
			$qUpdatePass = " password='".$password."',  enc_key='".$enc_key."' , ";
		}
		else  $qUpdatePass ="";
		$f = $this->query("UPDATE gm_user SET username='".$data['username']."', 
							name='".$data['name']."', position='".$data['position']."', 
							{$qUpdatePass} 
							email='".$data['email']."', level='".$data['level']."' 						 
						   WHERE userID='".$data['userID']."'");		
		
		$last_id = $data['userID'];
		if ($data['emblem']['name']!=NULL) {
			include_once $ENGINE_PATH.'Utility/phpthumb/ThumbLib.inc.php';
			list($file_name,$ext) = explode('.',$data['emblem']['name']);
			$img = md5($data['emblem']['name'].rand(1000,9999)).".".$ext;
			try{
				$thumb = PhpThumbFactory::create($data['emblem']['tmp_name']);
			}catch (Exception $e){
				// handle error here however you'd like
			}
			
			if(move_uploaded_file($data['emblem']['tmp_name'],"{$CONFIG['LOCAL_PUBLIC_ASSET']}emblem/".$img)){
				list($width, $height, $type, $attr) = getimagesize("{$CONFIG['LOCAL_PUBLIC_ASSET']}emblem/{$img}");
				$maxSize = 1000;
				if($width>=$maxSize){
					if($width>=$height) {
						$subs = $width - $maxSize;
						$percentageSubs = $subs/$width;
					}
				}
				if($height>=$maxSize) {
					if($height>=$width) {
						$subs = $height - $maxSize;
						$percentageSubs = $subs/$height;
					}
				}
				if(isset($percentageSubs)) {
					$width = $width - ($width * $percentageSubs);
					$height =  $height - ($height * $percentageSubs);
				}
				
				$w_small = $width - ($width * 0.5);
				$h_small = $height - ($height * 0.5);
				$w_tiny = $width - ($width * 0.7);
				$h_tiny = $height - ($height * 0.7);
				
				//resize the image
				$thumb->adaptiveResize($width,$height);
				$big = $thumb->save( "{$CONFIG['LOCAL_PUBLIC_ASSET']}emblem/big_".$img);
				$thumb->adaptiveResize($w_small,$h_small);
				$small = $thumb->save( "{$CONFIG['LOCAL_PUBLIC_ASSET']}emblem/small_".$img );
				$thumb->adaptiveResize($w_tiny,$h_tiny);
				$tiny = $thumb->save( "{$CONFIG['LOCAL_PUBLIC_ASSET']}emblem/tiny_".$img );
			}
			$this->inputImage($last_id,$img);
		}
		
		if($f){
			$this->query("
			INSERT INTO gm_specified_role (level ,	aid ,	type ,	category ,	n_status )
			VALUES ('".$data['level']."',{$data['userID']},2,'".$data['category']."',1)
			");
		
		}
		return $f;
	}	
	
	function inputImage($id,$img){
		$this->query("UPDATE gm_user SET emblem='{$img}' WHERE userID={$id}");
	}
	
	function getAllUsers($AC,$start,$total=30){
		
		if($AC==base64_encode(date("YmdHi"))){
	
			
			$rs = $this->fetch("SELECT * FROM gm_user ORDER BY userID LIMIT ".$start.",".$total,1);
		
			
			return $rs;
		}else{
			
			return false;
		}
	}
	function getUserInfo($userID){
		if(strlen(stripslashes($userID))>0){
			return $this->fetch("SELECT * FROM gm_user WHERE userID='".$userID."' LIMIT 1");
		}
	}
	function check($username,$password){
		$enc_key = md5(base64_encode($username.md5($password)));
		$password = md5($password);
		
		// $this->userID = 1;
		// return true;

		$rs = $this->fetch("SELECT userID,username,password,enc_key FROM gm_user
							WHERE username='".mysql_escape_string($username)."' 
							AND password='".$password."'");
		
		if($rs['username'] == $username && $rs['password'] == $password && $rs['enc_key'] = $enc_key){
			$this->userID = $rs['userID'];

			return true;
		}else{
			print mysql_error();
		}

	}
}
?>
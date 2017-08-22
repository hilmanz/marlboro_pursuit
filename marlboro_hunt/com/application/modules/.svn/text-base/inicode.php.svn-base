<?php
class inicode extends App{
	
	
	function beforeFilter(){
	
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		$this->userHelper = $this->useHelper('userHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
		$this->assign('locale', $LOCALE[1]);
		
	}
	
	function main(){
		
		$rahasia['userid'] = 9999;
		$rahasia['hashcode'] =  sha1(serialize(array('secret' => 'ini gw acak ya' , 'dates' => date('YmdHi'))));
		
		
		$serializerahasia = serialize($rahasia);
		
		$code = urlencode64($serializerahasia);
		pr($code);exit;
	}
	
	function decodeencoder(){
	
		$code = $this->_g('code');
		
		$hasildecode = urldecode64($code);
		
		pr($hasildecode);
		
		$rahasia = unserialize($hasildecode);
		
		$lecekabis = sha1(serialize(array('secret' => 'ini gw acak ya' , 'dates' => date('YmdHi'))));
		
		if($rahasia['hashcode']==$lecekabis) pr('ini  bisa masuk');
		else  pr('ini ga bisa masuk');
		pr($rahasia);exit;
	}
}
?>
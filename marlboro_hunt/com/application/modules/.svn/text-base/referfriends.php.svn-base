<?php
class referfriends extends App{

	
	
	function beforeFilter(){
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
		$this->assign('pages', strip_tags($this->_g('page')));
		
	}
	
	
	function main(){
		global $CONFIG;
		$this->log('referfriends',$this->user->id);
		//id={$_SESSION['mop_sess_id']}&
			// pr($_SESSION);exit;
		$url = "{$CONFIG['BASE_MOP_URL']}Templates/referfriends.aspx?id={$_SESSION['mop_sess_id']}&promoref=ID1300AM01001";
		sendRedirect($url);
		exit;

	}
	

}
?>
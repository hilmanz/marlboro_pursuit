<?php
class account extends App{
	function beforeFilter(){
	
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		$this->userHelper = $this->useHelper('userHelper');
		$this->loginHelper = $this->useHelper('loginHelper');
		$this->registerHelper = $this->useHelper('registerHelper');
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
		$this->assign('locale', $LOCALE[1]);
		
	}
	
	
	function main()
	{
		
		$this->View->assign('account_edit',$this->setWidgets('account_edit'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/account_pages.html');
	}
	
	function changePassword()
	{
		global $CONFIG;
		
		
		if ($this->_p('login')){
			
			$updateUser = $this->loginHelper->updateLoginUser();
			if ($updateUser){
				$data = $this->session->getSession($CONFIG['SESSION_NAME'],"WEB");
				$this->log('login','welcome');
				
				if ($data->login_count == 0){
				// pr($data);exit;
					sendRedirect("{$CONFIG['BASE_DOMAIN']}video");
					
				}else{
					
					sendRedirect("{$CONFIG['BASE_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
				}
				return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
				die();
				
			}
			$this->assign("msg","failed to login..");
		}
		$this->View->assign('login_new_password',$this->setWidgets('login_new_password'));
		// return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/login.html');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/account_pages.html');
	}
	
	function edit()
	{
		global $CONFIG;
		
		
		if ($this->_p('edit_account')){
			
			
			$update = $this->userHelper->updateDataUser();
			if ($update){
				
				
				if ($update ==1){
					sendRedirect("{$CONFIG['BASE_DOMAIN']}account/brands");
					return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
					die();
				}else{
					$this->View->assign('passwordnotmatch', 1);
				}
				
			}
		}
		$this->View->assign('account_edit',$this->setWidgets('account_edit'));
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/account_pages.html');
	}
	
	function getProvinsibyAjax()
	{
		$idProvince = $this->_p('id');
		
		$province = $this->contentHelper->getCity(array('idProvince'=>$idProvince));
		print json_encode($province);exit;
	}
	
	function brands()
	{
		global $CONFIG;
		$this->View->assign('account_brands',$this->setWidgets('account_brands'));
		
		if (($this->_p('submit')) AND ($this->_p('tokenQuiz') == 1)){
			
			$saveBrands = $this->userHelper->saveUserBrands();
			if ($saveBrands){
				sendRedirect("{$CONFIG['BASE_DOMAIN']}account/giid");
				return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
				die();
			}
		}
		
		
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/account_pages.html');
	}
	
	function giid()
	{
		global $CONFIG;
		$this->View->assign('account_giid',$this->setWidgets('account_giid'));
		
		if (($this->_p('submit')) AND ($this->_p('token') == 1)){
			
			sendRedirect("{$CONFIG['BASE_DOMAIN']}account");
			return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
			die();
		}
		
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/account_pages.html');
	}
}
?>
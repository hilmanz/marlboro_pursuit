<?php
class questionnaire extends App{
		
	function beforeFilter(){
		$this->registerHelper = $this->useHelper('registerHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		$this->loginHelper = $this->useHelper('loginHelper');
	}
	
	function main(){
	
		global $CONFIG;
		
		if (($this->_p('agree')) == 1){
			// validate term and condition
			$this->View->assign('login_quiz',$this->setWidgets('login_quiz'));
		}else if ($this->_p('token') == 1){
			// validate quiz from register
			$saveQuiz = $this->loginHelper->saveQuiz();
			if ($saveQuiz){
				
				$this->View->assign('login_new_password',$this->setWidgets('login_new_password'));
			}else{
				$this->View->assign('login_quiz',$this->setWidgets('login_quiz'));
			}
			
			
		}
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/login.html');
	}
	
	function survey()
	{
		global $CONFIG;
		$dataSurvey = false;
		$dataConsent = false;
		$survey = false;
		$consent = false;
		if ($this->_p('token')){
			$survey = @$_POST['survey'];
			if (is_array($survey)){
				$dataSurvey = implode(',',$survey);
			}
			
			$consent = @$_POST['consent'];
			if (is_array($consent)){
				$dataConsent = implode(',',$consent);
			}
			
			$updateQuiz = $this->registerHelper->updateQuiz(array('survey'=>$dataSurvey, 'consent'=>$dataConsent));
			
			if ($updateQuiz){
				sendRedirect("{$CONFIG['BASE_DOMAIN']}account/changePassword");
				die();
			}	
		}
		
		$this->View->assign('register_survey',$this->setWidgets('questionaire'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/survey-form.html');
	}
	
}
?>
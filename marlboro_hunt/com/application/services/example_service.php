<?php
class example_service  extends ServiceAPI{
			

	function beforeFilter(){
		/* 
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		$this->newsHelper = $this->useHelper('newsHelper'); 
		*/
	}
	
	function me(){
		return $_SESSION;
	
	}
	
}
?>

<?php
class task_list{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		
		$task = $this->apps->newsHelper->task();
		$message = $this->apps->newsHelper->messages();
		$accomplish = $this->apps->newsHelper->accomplishedTask();
		$huntPlayer = $this->apps->userHelper->getHuntPlayer();
		
		// pr($huntPlayer);
		// pr($accomplish);
		$this->apps->assign('task', $task);
		$this->apps->assign('message', $message);
		$this->apps->assign('accomplish', $accomplish);
		$this->apps->assign('player', $huntPlayer);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/task-list.html"); 
		
	}
}
?>
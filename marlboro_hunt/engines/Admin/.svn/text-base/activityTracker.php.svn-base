<?php 
class activityTracker{
	function __construct($admin=null){
		$this->admin = $admin;
	}
	
	function sendActivity($action=null,$description=null){
		if($action==null) return false;
		
		$this->admin->open();
		$sql = " INSERT INTO gm_activity_log (	admin_id,	date_ts ,	date_time, 	action ,	description ) 
		VALUES ( {$this->admin->Session->getVariable("uid")},".time().",NOW(),'{$action}','{$description}')
		";
		$rs = $this->admin->query($sql);
		$this->admin->close();
		if($rs) return true;
		else return false;
	
	}
	
}
?>
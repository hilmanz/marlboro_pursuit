<?php 
class PluginManager{
	function __construct($admin=null){
		$this->admin = $admin;
	}
	function getPluginByRequestID($reqID){
		$this->admin->open();
		$rs = $this->admin->fetch("SELECT * FROM gm_plugin 
							 WHERE requestID='".$reqID."' 
							 LIMIT 1");
		$this->admin->close();
		return $rs;
	}
	function getPlugins(){
		$this->admin->open();
		$rs = $this->admin->fetch("SELECT * FROM gm_plugin ORDER BY plugin_name",1);
		$this->admin->close();
		return $rs;
	}
	
}
?>
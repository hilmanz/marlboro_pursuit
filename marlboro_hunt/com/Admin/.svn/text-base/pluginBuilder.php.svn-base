<?php

class pluginBuilder extends Admin{

	function __construct(){
		parent::__construct();
			
	}
	
	
	function admin(){
		$r = $this->Request->getParam('r');
		if(!$r) return $this->buildModule();
		else return $this->$r();
	}
	
	function buildModule(){
		
		$do = $this->Request->getRequest('do');
		if($do) {
			$editList = $this->$do();
			$this->View->assign("editList",$editList);
		}
		$qData = $this->getAllPlugin();
		
		$this->View->assign("list",$qData);	
		return $this->View->toString("common/admin/pluginBuilder/build_module.html");
	}
	
	function getAllPlugin($id=null,$all=true){
		$this->force_connect(true);
		if($id) $wID = " AND id={$id} ";
		else $wID = "";
		$sql =" SELECT * FROM gm_plugin WHERE 1 {$wID} ";
		if($all) return $this->fetch($sql,1);
		else return $this->fetch($sql);
	}
	
	
	function editModule(){
		$this->force_connect(true);
		$id = $this->Request->getParam('id');
		if($id) return $this->getAllPlugin($id,false);
		else return false;			
	
	}
	
	function updateModule(){
		$this->force_connect(true);
		$id =  $this->Request->getPost('id');		 	
		$update['plugin_name'] = $this->Request->getPost('className');
		$update['plugin_path'] = $this->Request->getPost('pluginPath');
		$update['requestID'] = $this->Request->getPost('requestID');
		$update['className'] = $this->Request->getPost('className');
		$update['is_enabled'] = $this->Request->getPost('status');
		
		foreach($update as $key => $val){
			if($val!='') $filterUpdate[]= $key."='".$val."'";
			else continue;
		}
		
		$updateString = implode(',',$filterUpdate);
	
		$sql =" UPDATE gm_plugin SET {$updateString} WHERE id={$id}  ";
		$rs = $this->query($sql,1);
		if($rs) $this->View->assign('msg','success saving module');
		else $this->View->assign('msg','failed to save module');
	
	}
	
	function saveModule(){
		$this->force_connect(true);
	 	
		$insertData[] = "'" . $this->Request->getPost('className') . "'";
		$insertData[] = "'" .  $this->Request->getPost('pluginPath'). "'";
		$insertData[] = "'" .  $this->Request->getPost('requestID'). "'";
		$insertData[] = "'" .  $this->Request->getPost('className'). "'";
		$insertData[] = "'" .  $this->Request->getPost('status'). "'";
	
		$values = implode(',',$insertData);
	
		$sql =" INSERT INTO gm_plugin (plugin_name ,plugin_path,requestID,className,is_enabled) VALUES ({$values})  ";
		$rs = $this->query($sql,1);
		if($rs) $this->View->assign('msg','success saving module');
		else $this->View->assign('msg','failed to save module');
	
	}
	
	function deleteModule(){
		$this->force_connect(true);
		$id = $this->Request->getParam('id');
		$sql =" DELETE FROM gm_plugin WHERE 1 AND id={$id}  ";
		$this->query($sql,1);
		sendRedirect('index.php?s=pluginBuilder');
	}
	
}
?>
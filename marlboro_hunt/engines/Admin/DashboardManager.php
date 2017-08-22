<?php 
class DashboardManager extends SQLData{
	var $View;
	var $Request;
	function DashboardManager($req){
		$this->SQLData();
		$this->View = new BasicView();
		$this->Request = $req;
	}
	function getConfiguration(){
		//$this->open();
		$rs = $this->fetch("SELECT * FROM gm_dashboard LIMIT 20",1);
		///$this->close();
		return $rs;
	}
	function getArticleStatistik(){
		$jml_unpublish = $this->fetch("SELECT count(*) as jml_unpublish FROM axis_news_content WHERE n_status=0");
		$jml_publish = $this->fetch("SELECT count(*) as jml_publish FROM axis_news_content WHERE n_status=1");
		$jml_inactive = $this->fetch("SELECT count(*) as jml_inactive FROM axis_news_content WHERE n_status=2");
		$rs=array();
		$rs['jml_unpublish']=$jml_unpublish['jml_unpublish'];
		$rs['jml_publish']=$jml_publish['jml_publish'];
		$rs['jml_inactive']=$jml_inactive['jml_inactive'];
		return $rs;
	}
	function getPath($uri){
		$str = explode(".",trim($uri));
		$f = "";
		for($i=0;$i<sizeof($str);$i++){
			$f.=$str[$i];
			if($i<sizeof($str)-1){
			$f.="/";
			}
		}
		if(strlen($f)>5){
			$f.=".php";
			$className = $str[sizeof($str)-1];
		}
		$rs['file'] = $f;
		$rs['className'] = $className;
		return $rs;
	}
	function load(){
		global $APP_PATH,$ENGINE_PATH;
		$this->open(0);
		$items = $this->getConfiguration();
		$this->close();
		$plugins = array();
		for($i=0;$i<sizeof($items);$i++){
			$item = $this->getPath($items[$i]['class']);
			if(file_exists("../../".$item['file'])){
				
				include_once "../../".$item['file'];
				$obj = new $item['className']($this->Request);
				$plugins[$i]['name'] = $items[$i]['name'];
				$plugins[$i]['html'] = $obj->Dashboard();
				$plugins[$i]['slot'] = $items[$i]['slot'];
				
			}else{
				
				//print "class not found-->../".$item['file'];
			}
		}
		$this->View->assign("plugins",$plugins);
		return $this->View->toString("common/admin/dashboard_panel.html");
	}
	function addItem($name,$className,$invoker,$slot,$status){
		return $this->query("INSERT INTO gm_dashboard(name,class,invoker,slot,status) 
					 VALUES('".$name."','".$className."','".$invoker."','".$slot."','".$status."')");
	}
	function removeItem($id){
		return $this->query("DELETE FROM gm_dashboard WHERE id='".$id."'");
	}
	function editItem($id,$name,$className,$invoker,$status){
		return $this->query("UPDATE gm_dashboard 
							SET name='".$name."',class='".$className."',
							invoker='".$invoker."',status='".$status."' 
							WHERE id='".$id."'");
	}
}
?>
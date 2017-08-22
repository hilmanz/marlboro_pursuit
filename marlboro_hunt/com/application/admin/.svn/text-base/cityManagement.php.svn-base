<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class cityManagement extends Admin{
	var $category;
	var $type;
	function __construct(){	
		parent::__construct();	
		
		$this->type = "1,3,4,5,6";
		$this->contentType = "0";
		$this->folder =  'cityManagement';
		$this->dbclass = 'marlborohunt';
		$this->fromwho = 0; // 0 is admin/backend
		$this->total_per_page = 20;
		
	}
	
	function admin(){
		
		global $CONFIG;
	
		//get admin role
		foreach($this->roler as $key => $val){
		$this->View->assign($key,$val);
		}
		//get specified admin role if true
		if($this->specified_role){
			foreach($this->specified_role as $val){
				$type[] = $val['type'];
				$category[] = $val['category'];
			}
			if($type) $this->type = implode(',',$type);
			else return false;
			if($category) $this->category = implode(',',$category);
			else return false;
		}
		//helper
		$this->typelist = $this->getTypeList();
		// $this->contributor = $this->getContributor();
		// $this->View->assign('contributor',$this->contributor);
		$this->View->assign('typelist',$this->typelist);
		$this->View->assign('folder',$this->folder);
		
		$this->View->assign('baseurl',$CONFIG['BASE_DOMAIN_PATH']);
		$act = $this->_g('act');
		if($act){
			return $this->$act();
		} else {
			return $this->home();
		}
	}

	function home(){
		$search = $this->Request->getParam('search');		
		$filter = $search!='' ? " WHERE provinceName LIKE '%{$search}%' OR city LIKE '%{$search}%'" : ""; 
		$start = intval($this->Request->getParam('st'));
		$total_per_page = 20;
		$sql = "SELECT * FROM {$this->dbclass}_city_reference {$filter} ORDER BY provinceName LIMIT {$start},{$total_per_page}";
		
		$totalSql = "SELECT count(*) total FROM {$this->dbclass}_city_reference {$filter}";
		$this->open(0);
		$list = $this->fetch($sql,1);
		$tot_city = $this->fetch($totalSql);
		$this->close();
		$total = $tot_city['total'];
		$no=1+$start;		
		foreach($list as $k => $v){
			$v['no'] = $no++;
			$data[] = $v;
		}
		
		$this->View->assign('list',$data);
		$this->View->assign('search',$search);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}"));	
		// pr("application/admin/{$this->folder}/{$this->folder}_list.html");
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
	
		
		
	
	
	function add(){	
		
		$province = $this->getProvince();
		
		if ($this->_p('simpan') == true){
			$city 		= $this->_p('city');
			$provinceid = $this->_p('provinceid');
			
			
			$provinceName = $this->fetch("SELECT province FROM {$this->dbclass}_province_reference WHERE id={$provinceid}");
			
			if($this->_g('act') == 'add'){ 
				if( $city != ''){
					$qry = "INSERT INTO {$this->dbclass}_city_reference (provinceName,provinceid,city) VALUES ('{$provinceName['province']}','{$provinceid}','{$city}')";
					 pr($qry);
					if(!$this->query($qry)){
						$this->View->assign("msg","Add process failure");
						return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_new.html");
					}else{
						return $this->View->showMessage('Success', "index.php?s={$this->folder}");
					}
				}else{
					$this->View->assign('msg','Error, please fill all field!');
				}
			}
		}
		
		$this->View->assign('province',$province);
		
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_new.html");
	}
	
	function getProvince(){
		$province = $this->fetch("SELECT * FROM {$this->dbclass}_province_reference ORDER BY province",1);
		return $province;
	}
	
	function hapus(){
		$id = $this->_g('id');
		if( !$this->query("DELETE FROM {$this->dbclass}_city_reference WHERE id={$id}")){
			return $this->View->showMessage('Failed',"index.php?s={$this->folder}");
		}else{
			return $this->View->showMessage('Success',"index.php?s={$this->folder}");
		}
	}
	
	function edit(){
	
		
		$cityid = $this->_g('id');
		
		$province = $this->getProvince();
		$this->View->assign('province',$province);
		
		if ($this->_p('simpan')){
			$provinceid = $this->_p('provinceid');
			$city 		= $this->_p('city');
			
			$getProvince = $this->fetch("SELECT * FROM {$this->dbclass}_province_reference WHERE id={$provinceid} LIMIT 1" );
			// $cityName = $this->fetch("SELECT * FROM {$this->dbclass}_city_reference WHERE id={$cityid} LIMIT 1" );
		
			//pr($cityName);
			if( $cityid > 0 && $city != ''){
				$qry = "UPDATE {$this->dbclass}_city_reference SET provinceName='{$getProvince['province']}', city='{$city}' WHERE id={$cityid}";
				
				if($this->query($qry)){
					
					return $this->View->showMessage('Success', "index.php?s={$this->folder}");
				}else{				
					$this->View->assign('msg','Error, please fill all field!');
				}
			}else{
				$this->View->assign('msg','Error, please fill all field!');
			}
		}
		
		
		
		$list_city = $this->fetch("SELECT * FROM {$this->dbclass}_city_reference WHERE id={$cityid}");
		
		if( is_array($list_city) ){
			$this->View->assign("id_city",$list_city['id']);
			$this->View->assign("provinceid",$list_city['provinceid']);
			$this->View->assign("city",$list_city['city']);
			return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_edit.html");
		}else{
			return $this->View->showMessage('Invalid id', "index.php?s={$this->folder}");
		}
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_edit.html");
		
	}
	
	function getTypeList(){
		$sql = "SELECT * FROM {$this->dbclass}_news_content_type WHERE id IN ({$this->type}) AND  content =  {$this->contentType} ";
		$type = $this->fetch($sql,1);
		// pr($type);exit;
		return $type;
	}
	function getBannerTypeList(){
		$type = $this->fetch("SELECT * FROM  {$this->dbclass}_news_content_banner_type WHERE n_status=1",1);
		return $type;
	}
	function getPageList(){
		$sql = "SELECT * FROM {$this->dbclass}_news_content_page WHERE n_status=1 ";
		$page = $this->fetch($sql,1);
		// pr($sql);
		return $page;
	}
	
	function getContributor(){
		$articleType = intval($this->_p("articleType"));
		
		$sql = "
			SELECT *
			FROM gm_member 
			WHERE n_status <> 3
			AND articleTypes like '%\"{$articleType}\"%'
			ORDER BY name DESC
			
		";	
		// pr($sql);
		$list = $this->fetch($sql,1);
		print json_encode($list);exit;
	}
	
	function fixTinyEditor($content){
		global $CONFIG;
		$content = str_replace("\\r\\n","",$content);
		$content = htmlspecialchars(stripslashes($content), ENT_QUOTES);
		$content = str_replace("../index.php", "index.php", $content);

		//$content = htmlspecialchars( stripslashes($content) );
		$content = str_replace("&lt;", "<", $content);
		$content = str_replace("&gt;", ">", $content);
		$content = str_replace("&quot;", "'", $content);
		$content = str_replace("&amp;", "&", $content);
		return $content;
	}
	
}
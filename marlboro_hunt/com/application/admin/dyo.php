<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class dyo extends Admin{
	var $category;
	var $type;
	function __construct(){	
		parent::__construct();	
		
		$this->type = "1,3,4,5,6";
		$this->contentType = "0";
		$this->folder =  'dyo';
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
	
		$this->View->assign('folder',$this->folder);
		$this->View->assign('basedomain',$CONFIG['BASE_DOMAIN']);
		$this->View->assign('baseurl',$CONFIG['BASE_DOMAIN_PATH']);
		$act = $this->_g('act');
		if($act){
			return $this->$act();
		} else {
			return $this->home();
		}
	}
	
	function home (){

		//filter box
		$filter = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$article_type = $this->_g("article_type") == NULL ? '' : $this->_g("article_type");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$filter .= $startdate=='' ? "" : "AND con.posted_date >= '{$startdate}' ";
		$filter .= $enddate=='' ? "" : "AND con.posted_date < '{$enddate}' ";		
		$filter .= $search=='' ? "" : "AND (con.name LIKE '%{$search}%' OR con.nickname LIKE '%{$search}%' OR con.email LIKE '%{$search}%') ";
		$this->View->assign('search',$search);
		$this->View->assign('article_type',$article_type);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);
		
		
		
		$artType = explode(',',$this->type);
		if ($article_type!='') {
			if(in_array($article_type,$artType)){ $filter .= $article_type=='' ? "" : "AND con.articleType='{$article_type}'";}
			else $filter .= "AND con.articleType IN ({$article_type}) ";
		}
	
	
		$start = intval($this->_g('st'));
		
		/* Hitung banyak record data */
		$sql ="SELECT count(*) total, a.userid, a.image, DATE_FORMAT(a.datetime,'%Y-%m-%d') datetime, a.n_status, con.name, con.id
				FROM my_dyo AS a
				LEFT JOIN social_member con ON a.userid = con.id
				ORDER BY datetime GROUP BY user_id{$filter}";
		$totalList = $this->fetch($sql);	
		// pr($totalList);
		if($totalList){
		$total = intval($totalList['total']);
		}else $total = 0;
		
		/* list article */
		$sql = "
			SELECT a.userid, a.image, DATE_FORMAT(a.datetime,'%Y-%m-%d') datetime, a.n_status, con.name, con.id
				FROM my_dyo AS a
				LEFT JOIN social_member con ON a.userid = con.id
				GROUP BY a.userid
				ORDER BY datetime {$filter} LIMIT {$start},{$this->total_per_page}
		";
		
		$list = $this->fetch($sql,1);
		// pr($sql);
		if($list){
				
			$n=$start+1;
			foreach($list as $key => $val){
					$list[$key]['no'] = $n++;
					$arrContentId[] = $val['id'];
			}
			
		
			if($arrContentId){
				$strContentId =implode(',',$arrContentId);
				$sql =" SELECT * FROM {$this->dbclass}_news_content_banner WHERE parentid IN ({$strContentId}) ";
				// pr($sql);
				$bannerData = $this->fetch($sql,1);
				if($bannerData){
					foreach($bannerData as $val){
						$parentidinbanner[$val['parentid']] = true;				
					}
				}else $parentidinbanner = false;
			}else $parentidinbanner = false;
			
			//add misc join like comment and other field in here
			foreach($list as $key => $val){
				
				//status banner has been add or not
				if($parentidinbanner){
						if(array_key_exists($val['id'],$parentidinbanner)) $list[$key]['is_banner'] = true;
						else  $list[$key]['is_banner'] = false;
				}
				
				//other status in here
			}
		}
		
			
		
		$this->View->assign('list',$list);

		$this->Paging = new Paginate();
	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&article_type={$article_type}&startdate={$startdate}&enddate={$enddate}"));	
	// pr("application/admin/{$this->folder}/{$this->folder}_list.html");
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
	
	function getMyGallery(){
		
		$userid = intval($this->_p('userid'));
		$sql="SELECT * FROM my_dyo WHERE userid={$userid} ORDER BY datetime DESC LIMIT 3";
		$qData = $this->fetch($sql,1);
		$sqlList = "SELECT COUNT( * ) totalVote, contentid dyoid
					FROM marlborohunt_news_content_favorite
					GROUP BY dyoid";
		$voteData = $this->fetch($sqlList,1);
		// pr($sql);
		if($qData){
			foreach($voteData as $val){
				$voteDatas[$val['dyoid']] = $val['totalVote'];
			}
			foreach($qData as $key => $val){
				if(array_key_exists($val['id'],$voteDatas)) $qData[$key]['vote'] =$voteDatas[$val['id']];
				else $qData[$key]['vote'] = 0;
			}
			if($qData) print json_encode($qData);
			else print json_encode(false);
		}else print json_encode(false);
		// pr($qData);
		exit;
	
	}
	
	
	function ajax() {

		$n_status = $this->_p('n_status');
		$userid = $this->_p('userid');
		$id = $this->_p('id');
		
		$sql = "UPDATE my_dyo SET n_status = {$n_status} WHERE id = {$id}";
		$qData = $this->query($sql);

		if ($qData){
			print json_encode(array('status'=>TRUE));
		}else{
			print json_encode(array('status'=>FALSE));
		}
		
		exit;
	}
	
}
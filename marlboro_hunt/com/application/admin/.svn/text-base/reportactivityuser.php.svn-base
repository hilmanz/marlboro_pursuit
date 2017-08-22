<?php
error_reporting(0);
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";

class reportactivityuser extends Admin{
	function __construct(){
		parent::__construct();		
	}
	
	function admin(){
		global $CONFIG;
		$this->View->assign('baseurl',$CONFIG['BASE_DOMAIN_PATH']);
		$act = $this->Request->getParam('act');
		
		if($act){
			return $this->$act();
		} else {
			return $this->home();
		}
	}
	
	function home(){
		$search = $this->Request->getParam("search") == NULL ? '' : $this->Request->getParam("search");
		$startdate = $this->Request->getParam("startdate") == NULL ? '' : $this->Request->getParam("startdate");
		$enddate = $this->Request->getParam("enddate") == NULL ? '' : $this->Request->getParam("enddate");
		
		$filter  = $search=='' ? "" : "WHERE (act.activityName LIKE '%{$search}%') ";
		
		$time['time'] = '%H:%M:%S';
		$total_per_page = 30;
		$start = intval($this->Request->getParam('st'));
		
		$sql = "
			SELECT log.id,log.action_id,act.activityName,log.action_value,count(log.action_value) as total_value 
			FROM `tbl_activity_log` log
			LEFT JOIN tbl_activity_actions act ON log.action_id = act.id
			{$filter}
			GROUP BY action_id,action_value ORDER BY action_id
			LIMIT {$start},{$total_per_page}
		";
		//print_r($sql);
		$totalSql = "
			SELECT count(a.id) as total
			FROM (
				SELECT log.id,log.action_id,act.activityName,log.action_value,count(log.action_value) as total_value 
				FROM `tbl_activity_log` log
				LEFT JOIN tbl_activity_actions act ON log.action_id = act.id
				{$filter}
				GROUP BY action_id,action_value ORDER BY action_id
			) a
		";
		
		$this->open(0);
		$list = $this->fetch($sql,1);
		$arrUser = $this->fetch($sql_member);
		$content = $this->fetch($totalSql);
		$this->close();
		$total = $content['total'];
		
		$no=1+$start;
		foreach($list as $k => $v){
			$v['no'] = $no++;
			$data[] = $v;
			$v['action_value'] = $v['action_value']=='' ? 0 : $v['action_value'];
			$action_val = stripos($v['action_value'], 'social_');
			if ($action_val !== false) {
				$arrActionValue_social[substr($v['action_value'],7,20)] = substr($v['action_value'],7,20);
			} else {
				$arrActionValue[$v['action_value']] = "'".$v['action_value']."'";
			}			
		}
		
		$strActionValue_social = implode(",",$arrActionValue_social);
		$strActionValue = implode(",",$arrActionValue);
		
		if($strActionValue_social || $strActionValue ){
			$sql_newsContent ="SELECT * FROM axis_news_content WHERE id IN ({$strActionValue})";
			$qData_newsContent = $this->fetch($sql_newsContent,1);
			foreach($qData_newsContent as $key => $val){
				$newsContent[$val['id']][$val['id']] =  $val['title']." ".$val['brief'];
			}
			
			$sql_socialContent ="SELECT * FROM social_news_content WHERE id IN ({$strActionValue_social})";
			$qData_socialContent = $this->fetch($sql_socialContent,1);
			foreach($qData_socialContent as $key => $val){
				$socialContent['social_'.$val['id']]['social_'.$val['id']] =  $val['title']." ".$val['brief'];
			}
		}
		
		foreach($data as $key => $v){
			if(array_key_exists($v['action_value'],$socialContent)) {
				$data[$key]['action_desc'] = $socialContent[$v['action_value']][$v['action_value']];
			} elseif(array_key_exists($v['action_value'],$newsContent)) {
				$data[$key]['action_desc'] = $newsContent[$v['action_value']][$v['action_value']];
			} else {
				$data[$key]['action_desc'] = $v['action_value'];
			}
		}
		
		$this->View->assign('list',$data);
		$this->View->assign('nama',$arrUser['name']);
		$this->View->assign('id_user',$id);
		$this->View->assign('time',$time);
		$this->View->assign('search',$search);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=reportactivityuser&search={$search}&startdate={$startdate}&enddate={$enddate}"));	
		return $this->View->toString("application/admin/report/rpt_usersocial.html");
	}
}
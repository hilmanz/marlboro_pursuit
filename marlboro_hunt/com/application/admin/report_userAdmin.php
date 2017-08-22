<?php
error_reporting(0);
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";

class report_useradmin extends Admin{
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
		
		$filter  = $search=='' ? "" : "WHERE (gm.name LIKE '%{$search}%' OR log.action LIKE '%{$search}%') ";
		if ($search!='') {
			$filter .= $startdate=='' ? "" : "AND log.date_time >= '{$startdate}' ";
		} else {
			$filter .= $startdate=='' ? "" : "WHERE log.date_time >= '{$startdate}' ";
		}
		if ($startdate!='') {
			$filter .= $enddate=='' ? "" : "AND log.date_time < '{$enddate}' ";
		} else {
			$filter .= $enddate=='' ? "" : "WHERE log.date_time < '{$enddate}' ";
		}
		
		$time['time'] = '%H:%M:%S';
		$total_per_page = 30;
		$start = intval($this->Request->getParam('st'));
		
		$sql = "
			SELECT log.*,gm.name
			FROM gm_activity_log log
			LEFT JOIN gm_user gm ON log.admin_id = gm.userID
			{$filter}
			ORDER BY log.date_time DESC
			LIMIT {$start},{$total_per_page}
		";
		
		$totalSql = "SELECT count(*) total 
			FROM gm_activity_log log
			LEFT JOIN gm_user gm ON log.admin_id = gm.userID
			{$filter}";
		
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
		}
		
		$this->View->assign('list',$data);
		$this->View->assign('nama',$arrUser['name']);
		$this->View->assign('id_user',$id);
		$this->View->assign('time',$time);
		$this->View->assign('search',$search);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=report_useradmin&search={$search}&startdate={$startdate}&enddate={$enddate}"));	
		return $this->View->toString("application/admin/report/rpt_useradmin.html");
	}
}
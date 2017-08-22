<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class dashboard extends Admin{
	function __construct(){	
		parent::__construct();	
		
		$this->type = "1,2,3,4,5,6,7,8,9,10";
		$this->contentType = "0";
		$this->folder =  'dashboard';
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
		
		/* Count User */
		$sql = "SELECT count(*) total, n_status
				FROM social_member GROUP BY n_status";
		$count = $this->fetch($sql,1);
		
		$statusArr[0] = 'Pending GIID upload'; 
		$statusArr[1] = 'Pending CSR Approval '; 
		$statusArr[2] = 'CSR Approval'; 
		$statusArr[3] = 'Rejected - Not Upload GIID '; 
		$statusArr[4] = 'Rejected - Data Not Valid'; 
		$statusArr[5] = 'Verified';
		$statusArr[6] = 'Deactivated';
		$statusArr[7] = 'Blocked ';
		$data = false;	
		$jumlah = false;
		
		foreach($count as $key => $val){
			
			$count[$key]['statusname'] = $statusArr[$val['n_status']];
			$jumlah += $val['total'];
			
		}
		
		/* Count DYO */
		$sql = "SELECT COUNT( * ) totalVote, contentid dyoid
					FROM {$this->dbclass}_news_content_favorite
					GROUP BY dyoid";
		$totalVote = $this->fetch($sql,1);
		
		$total = false;
		
		foreach ($totalVote as $key => $v){
			$total += $v['totalVote'];
		}
		// pr($total);
		
		
		$this->View->assign('jumlah',$jumlah);
		$this->View->assign('count',$count);
		$this->View->assign('total',$total);
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
	
}
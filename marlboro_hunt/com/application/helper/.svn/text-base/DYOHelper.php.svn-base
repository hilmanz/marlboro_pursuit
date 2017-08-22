<?php 

class DYOHelper {

	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);

		$this->dbshema = "marlborohunt";	
		
		$this->week = 7;
		$week = intval($this->apps->_request('weeks'));
		if($week!=0) $this->week = $week;
		
		$this->startweekcampaign = "2013-04-25";
		// pr($this->apps->_request('week'));
	}

	
	
	function saveImage($attendartype=0){	
	
		// $this->uid = 1;
		$qData['total'] = 0;
		$sql = "SELECT COUNT(*) total FROM my_dyo  WHERE userid={$this->uid} AND n_status = 1  GROUP BY userid ";
		$qData = $this->apps->fetch($sql);
		// if(!$qData) return false;
		if($qData['total']>3) return false;
		$data =  $this->apps->uploadHelper->putcontent("user/dyo/");
		// $data =  $this->apps->uploadHelper->httprawpostdata("user/dyo/");
		if($data['result']){
			$sql = "INSERT INTO my_dyo (userid,image,datetime,n_status) VALUES ({$this->uid},'{$data['filename']}',NOW(),1)";
			$this->apps->query($sql);
		}
		return $data;
	}
	
	
	function getGallery($user=false,$limit=5){	
		
		// $this->uid = 1;
		if($user==false){
			$sql = "SELECT * FROM my_dyo WHERE n_status=1 ORDER BY datetime DESC LIMIT {$limit} ";
		}else{
			$sql = "SELECT * FROM my_dyo WHERE userid={$this->uid} AND n_status=1 ORDER BY datetime DESC LIMIT {$limit} ";
		}		
		$data = $this->apps->fetch($sql,1);
		if($data) return $data;
		else return false;
	}
	
	function getGaleryDyo($start=null,$limit=5)
	{	
		$filter ="";
		$ORDER ="";
		$sub ="";
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$week = intval($this->apps->_g('weeks'));
		$most = intval($this->apps->_g('most'));
		// pr($_GET);
		
		$sub .= "(SELECT COUNT( * ) totalview,action_value dyoid FROM tbl_activity_log WHERE action_id = 16 GROUP BY action_value ) tlv ON tlv.dyoid = a.id";
		if($week!=0) $filter .= "AND WEEK(a.datetime) = WEEK(DATE_ADD('{$this->startweekcampaign}', INTERVAL {$this->week} DAY )) ";
		
		if($most!=0) {		
			$ORDER .= " ORDER BY datetime DESC ";
		}else {
			
			$ORDER .= " ORDER BY totalview DESC ";
		}
		
		$sql = "
		SELECT count(*) total FROM my_dyo
		";
		$total = $this->apps->fetch($sql);
		 
		if($total['total']==0)return false;
		
		$sql = "SELECT a.*, b.name  ,totalview
				FROM my_dyo AS a 
				LEFT JOIN social_member AS b ON a.userid = b.id 
				LEFT JOIN {$sub}
				WHERE b.name IS NOT NULL AND a.n_status = 1 
				{$filter} {$ORDER}
				LIMIT {$start},{$limit}";		
		
		$res = $this->apps->fetch($sql,1);
		// pr($sql);
		if ($res){
		$arrdyoid = false;
			foreach($res as $key =>  $val){
					$res[$key]['vote'] =  0 ;
					$arrdyoid[$val['id']] = $val['id'];
			}	
			if($arrdyoid){
				$strdyoid = implode(',',$arrdyoid);
				
				$sql = "
					SELECT count(*) vote, contentid dyoid
					FROM {$this->dbshema}_news_content_favorite  
					WHERE contentid IN ({$strdyoid}) 
					GROUP BY dyoid
				";
				$voteData = $this->apps->fetch($sql,1);
				if($voteData){
					$vote =false;
					foreach($voteData as $val){
						$vote[$val['dyoid']] = $val['vote'];
					}
					if($vote){
						foreach($res as $key => $val){
							if(array_key_exists($val['id'],$vote)) $res[$key]['vote'] = $vote[$val['id']];						
						}	
					}			
				}			
			}
			// pr($res);exit;
			$data['result'] = $res;
			$data['total'] = $total['total'];
			return $data;
		}else{
			return FALSE;
		}
		
		
	}
	
	function addFavorite(){
		
		if(!$this->uid) return false;
		$cid = intval($this->apps->_p("cid"));
		if($cid==0) return false;
		$sql = "
		SELECT count(*) total 
		FROM {$this->dbshema}_news_content_favorite
		WHERE userid={$this->uid} AND contentid={$cid} LIMIT 1";
		
		$qData = $this->apps->fetch($sql);
		
		if(!$qData) return false;
		if($qData['total']>0) return false;		
		
		$sql=" 
				INSERT INTO 
				{$this->dbshema}_news_content_favorite 	(userid ,	contentid 	,likes, 	date ,	n_status  ) 
				VALUES ({$this->uid},{$cid},1,NOW(),1)
				";
		$this->apps->query($sql);
		if($this->apps->getLastInsertId()>0) return true;
		return false;
	
	}
	
	function checkBeforeCreateDyo()
	{
		$qData['total'] = 0;
		$sql = "SELECT COUNT(*) total FROM my_dyo WHERE userid={$this->uid} AND n_status = 1  GROUP BY userid  ";
		
		$qData = $this->apps->fetch($sql);
		// if(!$qData) return false;

		if(intval($qData['total'])>3) return false;		
		return true;
	}
	
}

?>


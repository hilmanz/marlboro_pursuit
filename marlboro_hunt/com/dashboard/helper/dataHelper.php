<?php

class dataHelper {

	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);

		$this->dbshema = "marlborohunt";	
		
		$this->startdate = $this->apps->_g('startdate');
		$this->enddate = $this->apps->_g('enddate');	
		if($this->enddate=='') $this->enddate = date('Y-m-d');		
		if($this->startdate=='') $this->startdate = date('Y-m-d' ,  strtotime( '-7 day' ,strtotime($this->enddate)) );
	}
	
	
		
	function allUserRegistration(){
		
		$sql = "SELECT count( * ) num, DATE_FORMAT( register_date, '%Y-%m-%d' ) register_date, sex
				FROM social_member WHERE 
				register_date >= '{$this->startdate}'
				AND register_date <= '{$this->enddate}' 
				GROUP BY sex, register_date
				ORDER BY register_date"; 
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		// pr($sql);
		// pr($sql);
		if(!$qData) return false;
		$data = false;
		$data['count'] = 0;
		$data['jumlah_female'] = false;
		$data['jumlah_male'] = false;
		$data['unknown'] = false;
		$data['jumlah'] = false;
		foreach($qData as $val){
			$data['data'][$val['register_date']] = $val['num'];
			if($val['sex']=="F") $data['jumlah_female']+= $val['num'];
			if($val['sex']=="M") $data['jumlah_male']+= $val['num'];			
			if($val['sex']!="M"&&$val['sex']!="F") $data['unknown']+= $val['num'];			
			
			$data['date'][$val['register_date']] = $val['register_date'];
			
			$data['jumlah']+= $val['num'];		
			
		}
		
		$data['count'] = count($qData);
		return $data;
		
	}
	
	function userUnverified(){
		$sql = "SELECT count( * ) num, sex, DATE_FORMAT( register_date, '%Y-%m-%d' ) register_date
				FROM social_member WHERE n_status = 0 AND register_date >= '{$this->startdate}'
				AND register_date <= '{$this->enddate}' GROUP BY register_date ORDER BY register_date DESC"; 
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		// pr($sql);
		if(!$qData) return false;
		$data = false;
		$data['count'] = 0;
		$data['jumlah_female'] = false;
		$data['jumlah_male'] = false;
		$data['unknown'] = false;
		$data['jumlah'] = false;
		foreach($qData as $val){	
			$data['data'][$val['register_date']] = $val['num'];
			if($val['sex']=="F") $data['jumlah_female']+= $val['num'];
			if($val['sex']=="M") $data['jumlah_male']+= $val['num'];			
			if($val['sex']!="M"&&$val['sex']!="F") $data['unknown']+= $val['num'];
			
			
			$data['date'][$val['register_date']] = $val['register_date'];
			
			$data['jumlah']+= $val['num'];	
		}		
		
		// pr($data);
		return $data;
		
	}
	
	function loginUser($type=7){
		$qTypeUser = "";
		$qActiveUser = "";
		if($type!=0) {
			$startdate =  $this->apps->_g('startdate');
			if($startdate=='') $startdate = " DATE_SUB(NOW() , INTERVAL {$type} DAY )";
			$qTypeUser = " AND log.date_time BETWEEN {$startdate} AND NOW() ";
			$qActiveUser = " HAVING count(*) > 1 ";
		}
		
		$sql = "SELECT count(*) num, DATE_FORMAT(log.date_time,'%Y-%m-%d') datetime , sm.sex
				FROM tbl_activity_log log
				LEFT JOIN social_member sm ON sm.id = log.user_id
				WHERE log.action_id = 1  {$qTypeUser}  GROUP BY sm.sex,datetime {$qActiveUser}
				ORDER BY datetime DESC LIMIT 7"; 
		// pr($sql);
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		// pr($qData);
		if(!$qData) return false;
		$data = false;
		$data['count'] = 0;
		$data['jumlah_female'] = false;
		$data['jumlah_male'] = false;
		$data['unknown'] = false;
		$data['jumlah'] = false;
		foreach($qData as $val){	
			$data['data'][$val['datetime']] = $val['num'];
			if($val['sex']=="F") $data['jumlah_female']+= $val['num'];
			if($val['sex']=="M") $data['jumlah_male']+= $val['num'];			
			if($val['sex']!="M"&&$val['sex']!="F") $data['unknown']+= $val['num'];			
			
			$data['date'][$val['datetime']] = $val['datetime'];
			
			$data['jumlah']+= $val['num'];	
		}		
		
		// pr($data);
		return $data;
		
	}
		
	function superUser ($type=30){
		$qTypeUser = "";
		$qActiveUser = "";
		if($type!=0) {
			$startdate =  $this->apps->_g('startdate');
			if($startdate=='') $startdate = " DATE_SUB(NOW() , INTERVAL {$type} DAY )";
			$qTypeUser = " AND log.date_time BETWEEN {$startdate} AND NOW() ";
			$qActiveUser = " HAVING count(*) > 1 ";
		}
		
		$sql = "SELECT count(*) num, DATE_FORMAT(log.date_time,'%Y-%m-%d') datetime , sm.sex
				FROM tbl_activity_log log
				LEFT JOIN social_member sm ON sm.id = log.user_id
				WHERE log.action_id = 1 {$qTypeUser}
				GROUP BY sm.sex,datetime  {$qActiveUser } ORDER BY datetime DESC LIMIT 7"; 
		// pr($sql);
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		$data = false;
		$data['count'] = 0;
		$data['jumlah_female'] = false;
		$data['jumlah_male'] = false;
		$data['unknown'] = false;
		$data['jumlah'] = false;
		foreach($qData as $val){	
			$data['data'][$val['datetime']] = $val['num'];
			if($val['sex']=="F") $data['jumlah_female']+= $val['num'];
			if($val['sex']=="M") $data['jumlah_male']+= $val['num'];			
			if($val['sex']!="M"&&$val['sex']!="F") $data['unknown']+= $val['num'];			
			
			$data['date'][$val['datetime']] = $val['datetime'];
			
			$data['jumlah']+= $val['num'];		
		}		
		
		return $data;
		
	}
	
	function veryActive ($type=30){
		$qTypeUser = "";
		$qActiveUser = "";
		if($type!=0) {
			$startdate =  $this->apps->_g('startdate');
			if($startdate=='') $startdate = " DATE_SUB(NOW() , INTERVAL {$type} DAY )";
			$qTypeUser = " AND date_time BETWEEN {$startdate} AND NOW() ";
			$qActiveUser = " HAVING count(*) > 1 ";
		}
		
		$sql = "SELECT count(*) num, DATE_FORMAT(log.date_time,'%Y-%m-%d') datetime , sm.sex
				FROM tbl_activity_log log
				LEFT JOIN social_member sm ON sm.id = log.user_id
				WHERE log.action_id = 1 {$qTypeUser}
				GROUP BY sm.sex,datetime  {$qActiveUser } ORDER BY datetime DESC LIMIT 7"; 
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		$data = false;
		$data['count'] = 0;
		$data['jumlah_female'] = false;
		$data['jumlah_male'] = false;
		$data['unknown'] = false;
		$data['jumlah'] = false;
		foreach($qData as $val){	
			$data['data'][$val['datetime']] = $val['num'];
			if($val['sex']=="F") $data['jumlah_female']+= $val['num'];
			if($val['sex']=="M") $data['jumlah_male']+= $val['num'];			
			if($val['sex']!="M"&&$val['sex']!="F") $data['unknown']+= $val['num'];			
			
			$data['date'][$val['datetime']] = $val['datetime'];
			
			$data['jumlah']+= $val['num'];		
		}		
		
		return $data;
		
	}
	
	
	function getChartDataOf($searchData=null){
		
		if($searchData==null) return false;
		
		if(is_array($searchData)) {
			foreach($searchData as $val){
				$nuArr[] = "'{$val}'";
			}
			if($nuArr)	$searchData = implode(',',$nuArr);
			else return false;
		}
		
		$theactivity = "{$searchData}";
		
		/* get activity ID */
		$actionnamedata = $this->getactivitytype($theactivity);

		if($actionnamedata) {
			
			$activityID = implode(',',$actionnamedata['id']);
		}else $activityID = false;
			
		$sql = "SELECT count(*) total, DATE(date_time) dateformatonly  FROM tbl_activity_log WHERE action_id IN ({$activityId}) ORDER BY dateformatonly GROUP BY dateformatonly LIMIT {$start},{$limit}";

		$getChartDataOf[$searchData] = $this->apps->fetch($sql);
		
		//pr($getChartDataOf);
		exit;

	}

	function getactivitytype($dataactivity=null,$id=false){
			if($dataactivity==null)return false;
			if($id) $qAppender = " id IN ({$dataactivity}) ";
			else $qAppender = " activityName IN ({$dataactivity})  ";
			$theactivity = false;
			/* get activity  id */	
			$sql = " SELECT * FROM tbl_activity_actions WHERE {$qAppender} ";

			$qData = $this->apps->open(1);
				
			if($qData) {
				foreach($qData as $val){
					$theactivity['id'][$val['id']]=$val['id'];
					$theactivity['name'][$val['id']]=$val['activityName'];
					
				}
			}
			return $theactivity;
		}

}

?>
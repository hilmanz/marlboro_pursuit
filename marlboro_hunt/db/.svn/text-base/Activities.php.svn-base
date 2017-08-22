<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class Activities extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	function admin(){
		$act = $this->Request->getParam('act');
		
		return $this->main();
	}

	function main(){
		//PVD
		$pvd = array();
		$this->open(0);
		$tgl = $this->fetch("SELECT date_d FROM ".ReportDB.".rp_overall_pageview_daily WHERE date_d >= DATE_SUB(CURRENT_DATE, INTERVAL 6 DAY) AND action_id = 7 GROUP BY date_d ASC",1);
		//var_dump($tgl);
		$title = $this->fetch("SELECT title FROM ".ReportDB.".rp_overall_pageview_daily WHERE date_d >= DATE_SUB(CURRENT_DATE, INTERVAL 6 DAY) AND action_id = 7 GROUP BY title ASC",1);
		for ($i=0;$i<sizeof($title);$i++){
			$pvdItem = array();
			$page = $title[$i]["title"];
			$rs = $this->fetch("SELECT date_d, SUM(pageview_count) AS pageview FROM ".ReportDB.".rp_overall_pageview_daily WHERE date_d >= DATE_SUB(CURRENT_DATE, INTERVAL 6 DAY) AND action_id = 7 AND title='".$page."' GROUP BY date_d ASC",1);
			$k = 0;
			for ($j=0;$j<sizeof($tgl);$j++){
				
				if ($tgl[$j]["date_d"] == $rs[$k]["date_d"]){
					$listData = array(
							'num'=> $rs[$k]["pageview"],
							'datee' => $rs[$k]["date_d"]
					);
					$k++;
				}else{
					$listData = array(
							'num'=> 0,
							'datee' => 0
					);
				}
				
				
				array_push($pvdItem, $listData);
			}
			
			$listTitle = array('page' => $page, 'data' => $pvdItem);
			array_push($pvd, $listTitle);
			$pvdItem = null;
		}
		
		//var_dump($pvd);
		$this->close();
		$tgl = json_encode($tgl);
		$data = json_encode($pvd);
		//var_dump($data);
		$this->View->assign("tgl", $tgl);
		$this->View->assign("pvd", $data);
		
		//Activity Distribution
		$this->open(0);
		$rs = $this->fetch("SELECT date_d, activity_id, activity_name, percentage ,num, total FROM ".ReportDB.".rp_activity_dist_daily WHERE date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)",1);
		//sample
		//$rs = $this->fetch("SELECT date_d, activity_id, activity_name, percentage ,num, total FROM ".ReportDB.".rp_activity_dist_daily WHERE date_d = '2012-03-30'",1);
		$this->close();
		$data = json_encode($rs);
		$this->View->assign("AD", $data);
		
		//Average Time on Activity Distribution
		$this->open(0);
		$rs = $this->fetch("SELECT date_d, activity_id, activity_name, avg_total, time_total FROM ".ReportDB.".rp_activity_dist_daily WHERE date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)",1);
		//sample
		//$rs = $this->fetch("SELECT date_d, activity_id, activity_name, avg_total, time_total FROM ".ReportDB.".rp_activity_dist_daily WHERE date_d = '2012-03-30'",1);
		$this->close();
		$data = json_encode($rs);
		$this->View->assign("ATOAD", $data);
		
		//Racing
		$this->open(0);
		$rs = $this->fetch("SELECT date_d, race_played_num AS race, car_modif_num AS car, mini_game_played_num AS mini FROM ".ReportDB.".rp_overall_daily WHERE date_d >= DATE_SUB(CURRENT_DATE, INTERVAL 2 DAY)",1);
		//sample
		//$rs = $this->fetch("SELECT date_d, race_played_num AS race, car_modif_num AS car, mini_game_played_num AS mini FROM ".ReportDB.".rp_overall_daily WHERE date_d = '2012-03-30' OR date_d = '2012-03-29'",1);
		$this->close();
		
		//Races Played
		$race1 = intval($rs[0]["race"]);
		$race2 = intval($rs[1]["race"]);
		$raceArrow = round((($race2-$race1)/$race1)*100);
		$racePercentage = abs($raceArrow);
		//var_dump($racePercentage);exit();
		$this->View->assign("racing", $rs);
		$this->View->assign("raceArrow", $raceArrow);
		$this->View->assign("racePercent", $racePercentage);
		
		//Car Modification
		$car1 = intval($rs[0]["car"]);
		$car2 = intval($rs[1]["car"]);
		$carArrow = round((($car2-$car1)/$car1)*100);
		$carPercentage = abs($carArrow);
		//var_dump($carPercentage);exit();
		$this->View->assign("carmodif", $rs);
		$this->View->assign("carArrow", $carArrow);
		$this->View->assign("carPercent", $carPercentage);
		
		//Total Mini Game Played
		$mini1 = intval($rs[0]["mini"]);
		$mini2 = intval($rs[1]["mini"]);
		$miniArrow = round((($mini2-$mini1)/$mini1)*100);
		$miniPercentage = abs($miniArrow);
		//var_dump($miniPercentage);exit();
		$this->View->assign("mini", $rs);
		$this->View->assign("miniArrow", $miniArrow);
		$this->View->assign("miniPercent", $miniPercentage);
		// print_r($rs);exit;
		
		//Racing: Time on each level
		$this->open(0);
		//$rs = $this->fetch("SELECT date_d, level, total_time FROM ".ReportDB.".rp_overall_race_level_daily WHERE date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ORDER BY LEVEL ASC",1);
		//sample
		$rs = $this->fetch("SELECT LEVEL, SUM(total_time) total_time, COUNT(user_num), SUM(total_time)/(86400*COUNT(user_num)) total_days FROM (SELECT user_id, LEVEL, MAX(total_time) total_time, 1 AS user_num FROM ".ReportDB.".rp_user_race_level_data GROUP BY user_id, LEVEL ) AS A GROUP BY LEVEL",1);		$this->close();
		$data = json_encode($rs);
		$this->View->assign("TOEL", $data);
		
		//Play by Game and Average Time on Game
		$this->open(0);
		$rs = $this->fetch("SELECT date_d, mini_game_id, mini_game_name, num, total, percentage, avg_time, jam, menit, detik FROM ".ReportDB.".rp_overall_minigame_daily WHERE date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ORDER BY mini_game_name ASC",1);
		$this->close();
		$avg = array();
		// var_dump($avg);exit;
		$data = json_encode($rs);
		$this->View->assign("PBG", $data);
		
		
		//Merchandise Redeem
		$this->open(0);
		$rs = $this->fetch("SELECT date_d, merchandise_id, merchandise_name, redeem_count FROM ".ReportDB.".rp_overall_redeem_daily WHERE date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)",1);
		//sample
		// $rs = $this->fetch("SELECT date_d, merchandise_id, merchandise_name, redeem_count FROM ".ReportDB.".rp_overall_redeem_daily",1);
		$this->close();
		$data = json_encode($rs);
		$this->View->assign("MR", $data);
		
		$this->View->assign("menu","activities");
		return $this->View->toString("RedRushWeb/dashboard/activities.html");
	}
}
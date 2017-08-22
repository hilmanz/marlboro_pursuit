<?php

class webActivityHelper {

	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);

		$this->dbshema = "athreesix";	
		$this->week = 7;
		$week = intval($this->apps->_request('week'));
		if($week!=0) $this->week = $week;
		
		$this->startweekcampaign = "2013-04-25";
		// pr($this->apps->_request('week'));
		
	}
	
	function top10UserActivity(){
		$top10user = false;
		$sql = "
		SELECT *,SUM(num) num FROM usr_activity_subculture 
		WHERE 
		WEEK(dateday) = WEEK(DATE_ADD('{$this->startweekcampaign}', INTERVAL {$this->week} DAY ))  
		GROUP BY action_id,type 
		ORDER BY num DESC ";
		// pr($sql);
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		if($qData){
			
			foreach($qData  as $val){
					$top10user[$val['type']][] = $val;
			}
		}
		// pr($top10user);
		if($top10user)		return $top10user;
		else return false;
		
	}

	/* Top 10 (per user activity) Like */
	function topUserActLike(){
		$sql = "SELECT *,SUM(num) num 
		FROM usr_activity_like WHERE 
		WEEK(dateday) = WEEK(DATE_ADD('{$this->startweekcampaign}', INTERVAL {$this->week} DAY ))    
		GROUP BY user_id
		ORDER BY num DESC LIMIT 10";
		// pr($sql);

		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['user_id']] = $val['user_id'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
		
				foreach($qData as $key => $val){
						if(array_key_exists($val['user_id'],$social)) $qData[$key]['img'] = $social[$val['user_id']]['img'];
				}
				// pr($qData);
			}
			return $qData;
		}
		return false;
	}
	
	function topUserActComment(){
		$sql = "SELECT *,SUM(num) num  FROM usr_activity_comment  
		WHERE WEEK(dateday) = WEEK(DATE_ADD('{$this->startweekcampaign}', INTERVAL {$this->week} DAY ))    
		GROUP BY user_id ORDER BY num  DESC LIMIT 10";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['user_id']] = $val['user_id'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['user_id'],$social)) $qData[$key]['img'] = $social[$val['user_id']]['img'];
				}
			
			}
			return $qData;
		}
		return false;
	}
	
	function topUserActUpload(){
		$sql = "SELECT *,SUM(num) num FROM usr_activity_upload  WHERE 
		WEEK(dateday) = WEEK(DATE_ADD('{$this->startweekcampaign}', INTERVAL {$this->week} DAY ))    
		GROUP BY user_id  ORDER BY num  DESC LIMIT 10";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['user_id']] = $val['user_id'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['user_id'],$social)) $qData[$key]['img'] = $social[$val['user_id']]['img'];
				}
			
			}
			return $qData;
		}
		return false;
	}
	
	function topUserActDownload(){
		$sql = "SELECT *,SUM(num) num FROM usr_activity_download  WHERE 
		WEEK(dateday) = WEEK(DATE_ADD('{$this->startweekcampaign}', INTERVAL {$this->week} DAY ))    
		GROUP BY user_id   ORDER BY num  DESC LIMIT 10";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['user_id']] = $val['user_id'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['user_id'],$social)) $qData[$key]['img'] = $social[$val['user_id']]['img'];
				}
			
			}
			return $qData;
		}
		return false;
	}
	
	
	/* End of User Activities Tab */
	
	/* --------------------------- */

	/* Top Users Tab */
	
	function topViewMostTime(){
		$sql = "SELECT *,SUM(num) num FROM usr_most_view  WHERE 
		WEEK(dateday) = WEEK(DATE_ADD('{$this->startweekcampaign}', INTERVAL {$this->week} DAY ))    
		AND name IS NOT NULL AND name <> ''
		GROUP BY user_id  
		ORDER BY num  DESC LIMIT 10";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['user_id']] = $val['user_id'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['user_id'],$social)) $qData[$key]['img'] = $social[$val['user_id']]['img'];
				}
			
			}
			return $qData;
		}
		return false;
	}
	
	
	
	function topTenSuperUser(){
		$sql = "SELECT  *,SUM(num) num FROM user_monthly  WHERE 
		WEEK(dateday) = WEEK(DATE_ADD('{$this->startweekcampaign}', INTERVAL {$this->week} DAY )) 
		AND name IS NOT NULL AND name <> ''		
		GROUP BY userid
		ORDER BY num  DESC LIMIT 10";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);

		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['userid']] = $val['userid'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['userid'],$social)) $qData[$key]['img'] = $social[$val['userid']]['img'];
				}
			
			}
			return $qData;
		}
		return false;
	}
	
	function topTenUserWeekly(){
		$sql = "SELECT *,user_id userid FROM user_weekly  WHERE dateday >= DATE_SUB(dateday, INTERVAL {$this->week} DAY ) 
				AND name IS NOT NULL AND name <> ''
				ORDER BY num  DESC LIMIT 10";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['user_id']] = $val['user_id'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['user_id'],$social)) $qData[$key]['img'] = $social[$val['user_id']]['img'];
				}
			
			}
			return $qData;
		}
		return false;
		
	}
	
	function topTenBasedNumFriend(){
		$sql = "SELECT * FROM usr_based_on  WHERE dateday >= DATE_SUB(dateday, INTERVAL {$this->week} DAY )
				AND name IS NOT NULL AND name <> ''
				ORDER BY num  DESC LIMIT 10";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['userid']] = $val['userid'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['userid'],$social)) $qData[$key]['img'] = $social[$val['userid']]['img'];
				}
			
			}
			return $qData;
		}
		return false;
		
	}
	
	/* End of Top User Tab */
	
	/* --------------------------- */
	
	/* Top Visited Page */
	
		
	function topTenVisitedPage(){
		$sql = "SELECT COUNT( * ) num, a.action_id, b.id as content_id, c.type, user_id
				FROM athreesix_web_2013.tbl_activity_log as a 
				LEFT JOIN athreesix_web_2013.athreesix_news_content as b on a.action_value = b.id
				LEFT JOIN athreesix_web_2013.athreesix_news_content_type as c on b.articleType = c.id
				WHERE b.id IS NOT NULL AND a.action_id = 2
				GROUP BY a.action_value 
				ORDER BY a.date_time LIMIT 10";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		// pr($qData);
		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['user_id']] = $val['user_id'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['user_id'],$social)) $qData[$key]['img'] = $social[$val['user_id']]['img'];
				}
			
			}
			return $qData;
		}
		// pr($qData);
		return false;
	}
	
	function topThreeVisitMusic(){
		$sql = "SELECT COUNT( * ) num, a.action_id, b.id as content_id, c.type 
				FROM athreesix_web_2013.tbl_activity_log as a 
				LEFT JOIN athreesix_web_2013.athreesix_news_content as b on a.action_value = b.id
				LEFT JOIN athreesix_web_2013.athreesix_news_content_type as c on b.articleType = c.id
				WHERE b.id IS NOT NULL AND a.action_id = 2 AND c.type = 'music'
				GROUP BY a.action_value 
				ORDER BY a.date_time DESC LIMIT 3";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['user_id']] = $val['user_id'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['user_id'],$social)) $qData[$key]['img'] = $social[$val['user_id']]['img'];
				}
			
			}
			return $qData;
		}
		return false;
	}
	
	function topThreeVisitDJ(){
		$sql = "SELECT COUNT( * ) num, a.action_id, b.id as content_id, c.type 
				FROM athreesix_web_2013.tbl_activity_log as a 
				LEFT JOIN athreesix_web_2013.athreesix_news_content as b on a.action_value = b.id
				LEFT JOIN athreesix_web_2013.athreesix_news_content_type as c on b.articleType = c.id
				WHERE b.id IS NOT NULL AND a.action_id = 2 AND c.type = 'dj'
				GROUP BY a.action_value 
				ORDER BY a.date_time LIMIT 3";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['user_id']] = $val['user_id'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['user_id'],$social)) $qData[$key]['img'] = $social[$val['user_id']]['img'];
				}
			
			}
			return $qData;
		}
		return false;
	}
	
	function topThreeVisitVisualart(){
	$sql = "SELECT COUNT( * ) num, a.action_id, b.id as content_id, c.type 
				FROM athreesix_web_2013.tbl_activity_log as a 
				LEFT JOIN athreesix_web_2013.athreesix_news_content as b on a.action_value = b.id
				LEFT JOIN athreesix_web_2013.athreesix_news_content_type as c on b.articleType = c.id
				WHERE b.id IS NOT NULL AND a.action_id = 2 AND c.type = 'visualart'
				GROUP BY a.action_value 
				ORDER BY a.date_time LIMIT 3";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['user_id']] = $val['user_id'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['user_id'],$social)) $qData[$key]['img'] = $social[$val['user_id']]['img'];
				}
			
			}
			return $qData;
		}
		return false;
	}
	
	function topThreeVisitFashion(){
		$sql = "SELECT COUNT( * ) num, a.action_id, b.id as content_id, c.type 
				FROM athreesix_web_2013.tbl_activity_log as a 
				LEFT JOIN athreesix_web_2013.athreesix_news_content as b on a.action_value = b.id
				LEFT JOIN athreesix_web_2013.athreesix_news_content_type as c on b.articleType = c.id
				WHERE b.id IS NOT NULL AND a.action_id = 2 AND c.type = 'fashion'
				GROUP BY a.action_value 
				ORDER BY a.date_time LIMIT 3";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['user_id']] = $val['user_id'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['user_id'],$social)) $qData[$key]['img'] = $social[$val['user_id']]['img'];
				}
			
			}
			return $qData;
		}
		return false;
	}
	
	function topThreeVisitPhotography(){
		$sql = "SELECT COUNT( * ) num, a.action_id, b.id as content_id, c.type 
				FROM athreesix_web_2013.tbl_activity_log as a 
				LEFT JOIN athreesix_web_2013.athreesix_news_content as b on a.action_value = b.id
				LEFT JOIN athreesix_web_2013.athreesix_news_content_type as c on b.articleType = c.id
				WHERE b.id IS NOT NULL AND a.action_id = 2 AND c.type = 'photography'
				GROUP BY a.action_value 
				ORDER BY a.date_time LIMIT 3";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['user_id']] = $val['user_id'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['user_id'],$social)) $qData[$key]['img'] = $social[$val['user_id']]['img'];
				}
			
			}
			return $qData;
		}
		return false;
	}
	
	/* End of Top Visited Page */
	
	/* --------------------------- */
	
	/* Top Content Page */
	function topContent(){
	
		$sql = "SELECT sum(num) num , type, activityName, userid, title
				FROM  most_viewed_artist as a 
				WHERE dateday >= DATE_SUB(dateday, INTERVAL {$this->week} DAY ) 
				GROUP BY title ,action_id
				ORDER BY num  DESC LIMIT 5";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['userid']] = $val['userid'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['userid'],$social)) $qData[$key]['img'] = $social[$val['userid']]['img'];
				}
			
			}
			return $qData;
		}
		return false;
	}
	
	function topFiveLiked() {
		$sql = "SELECT sum(num) num , type, activityName, userid,title
				FROM  most_viewed_artist as a 
				WHERE dateday >= DATE_SUB(dateday, INTERVAL {$this->week} DAY ) 
				AND action_id = 11
				GROUP BY title 
				ORDER BY num  DESC LIMIT 5";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['userid']] = $val['userid'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['userid'],$social)) $qData[$key]['img'] = $social[$val['userid']]['img'];
				}
			
			}
			return $qData;
		}
		return false;
	}
	
	function topFiveCommented() {
		$sql = "SELECT sum(num) num , type, activityName, userid, title
				FROM   most_viewed_artist as a 
				WHERE dateday >= DATE_SUB(dateday, INTERVAL {$this->week} DAY ) 
				AND action_id = 10
				GROUP BY title 
				ORDER BY num  DESC LIMIT 5";
		$this->apps->open(1);
	$qData = $this->apps->fetch($sql,1);
		
		if($qData){
		
			foreach($qData as $val){
				$arruserid[$val['userid']] = $val['userid'];
			}
			
			$struserid = implode(',',$arruserid);
			$social = $this->getsocial($struserid);
			
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['userid'],$social)) $qData[$key]['img'] = $social[$val['userid']]['img'];
				}
			
			}
			return $qData;
		}
		return false;
	}
	
	function basedOnKeyWord(){
		$sql = "SELECT *,SUM(num) num FROM top_content_search 
				WHERE WEEK(dateday) = WEEK(DATE_ADD('{$this->startweekcampaign}', INTERVAL {$this->week} DAY ))  
				GROUP BY action_value ORDER BY num DESC ";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		return $qData;
		
		
	}
	
	function basedOnContent() {
		$sql = "SELECT *,SUM(num) num FROM based_on_content 
				WHERE WEEK(dateday) = WEEK(DATE_ADD('{$this->startweekcampaign}', INTERVAL {$this->week} DAY ))  
				GROUP BY action_value ORDER BY num DESC";
		$this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		return $qData;
		// pr($qData);
	}
	
	/* End of Top Content Page */
	
	/* --------------------------- */	
	
	/* Top Most Viewed Artist */
	
	function mostViewedArtist($type='music') {
		if($type=='music'){
			$sqlMusic = "SELECT sum(num) num, names ,gender ,userid FROM most_viewed_artist  
						 WHERE dateday >= DATE_SUB(dateday, INTERVAL 7 DAY ) AND type='music' 
						 AND names IS NOT NULL AND names <> ''
						 GROUP BY names ORDER BY num DESC LIMIT 10";
			$this->apps->open(1);
			$qData = $this->apps->fetch($sqlMusic,1);
		}
		
		if($type=='dj'){
			$sqlDJ = "SELECT sum(num) num, names ,gender ,userid  FROM most_viewed_artist  
					  WHERE dateday >= DATE_SUB(dateday, INTERVAL 7 DAY ) AND type='dj' 
					  AND names IS NOT NULL AND names <> ''
					  GROUP BY names ORDER BY num DESC LIMIT 10";
			$this->apps->open(1);
			$qData = $this->apps->fetch($sqlDJ,1);
		}
		// pr($sqlDJ);
		
		if($type=='visual'){
			$sqlVisArt = "SELECT sum(num) num, names ,gender ,userid  FROM most_viewed_artist  
						  WHERE dateday >= DATE_SUB(dateday, INTERVAL 7 DAY ) AND type='visualart' 
						  AND names IS NOT NULL AND names <> ''
						  GROUP BY names ORDER BY num DESC LIMIT 10";
			$this->apps->open(1);
			$qData = $this->apps->fetch($sqlVisArt,1);
		}
		
		if($type=='style'){
			$sqlFashion = "SELECT sum(num) num, names ,gender ,userid  FROM most_viewed_artist 
						   WHERE dateday >= DATE_SUB(dateday, INTERVAL 7 DAY ) AND type='style'
						   AND names IS NOT NULL AND names <> ''
						   GROUP BY names ORDER BY num DESC LIMIT 10";
			$this->apps->open(1);
			$qData = $this->apps->fetch($sqlFashion,1);
		}
		
		if($type=='photography'){
			$sqlFashion = "SELECT sum(num) num, names ,gender ,userid  FROM most_viewed_artist 
						   WHERE dateday >= DATE_SUB(dateday, INTERVAL 7 DAY ) AND type='photography'
						   AND names IS NOT NULL AND names <> ''						
						   GROUP BY names ORDER BY num DESC LIMIT 10";
			$this->apps->open(1);
			$qData = $this->apps->fetch($sqlFashion,1);
		}
		if($qData){
			$socialarr = array("M","F");
			$pagesarr = array("UB");
			$arruserid = false;
			$arrpageid = false;
			$social = false;
			$pages = false;
			foreach($qData as $val){
				if(in_array($val['gender'],$socialarr)) $arruserid[$val['userid']] = $val['userid'];
				if(in_array($val['gender'],$pagesarr)) $arrpageid[$val['userid']] = $val['userid'];
			}
			if($arruserid){
				$struserid = implode(',',$arruserid);
				$social = $this->getsocial($struserid);
			}
			if($arrpageid){
				$strpageid = implode(',',$arrpageid);
				$pages = $this->getPages($strpageid);
			}
			if($social){
				foreach($qData as $key => $val){
						if(array_key_exists($val['userid'],$social)) $qData[$key]['img'] = $social[$val['userid']]['img'];
				}
			
			}
			
			if($pages){
				foreach($qData as $key => $val){
						if(array_key_exists($val['userid'],$pages)) $qData[$key]['img'] = $pages[$val['userid']]['img'];
				}
			
			}
			
			
				foreach($qData as $key => $val){
						if($val['gender']=="U") $qData[$key]['img'] = "default.jpg";
				}
		
			
			
			return $qData;
		}
		return $qData;
	}
	/* ------------------------------------ */
	
	function getsocial($struserid=null){
			if($struserid==null) return false;
			
		
			$sql ="SELECT img,id FROM social_member WHERE id IN ( {$struserid} ) ";	
			$this->apps->close();
			$this->apps->open(0);
			$qData = $this->apps->fetch($sql,1);
			$this->apps->close();
			$this->apps->open(1);
		
		if($qData){
			
			$arrData = false;
			
			foreach($qData as $val){
				$arrData[$val['id']] = $val;
			}	
			return $arrData;
		}
		
		return false;
	}
	
	function getPages($struserid=null){
			if($struserid==null) return false;
			
		
			$sql ="SELECT img,id FROM my_pages WHERE id IN ( {$struserid} ) ";	
			$this->apps->close();
			$this->apps->open(0);
			$qData = $this->apps->fetch($sql,1);
			$this->apps->close();
			$this->apps->open(1);
		
		if($qData){
			
			$arrData = false;
			
			foreach($qData as $val){
				$arrData[$val['id']] = $val;
			}	
			return $arrData;
		}
		
		return false;
	}
	
}

?>
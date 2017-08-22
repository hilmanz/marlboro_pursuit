<?php 
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Mobile_Detect.php";
class contentHelper {

	var $uid;
	var $osDetect;

	
	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;

		$this->apps = $apps;
		if($this->apps->isUserOnline())  {
			if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
			// if(is_object($this->apps->page)) $this->pageid = intval($this->apps->page->id);
			
		}
		
		if(isset($_SESSION['lid'])) $this->lid = intval($_SESSION['lid']);
		else $this->lid = 1;
		if($this->lid=='')$this->lid=1;
		$this->server = intval($CONFIG['VIEW_ON']);
		$this->osDetect = new Mobile_Detect;
		
		$this->moderation = $CONFIG['MODERATION'];
		$this->dbshema = "marlborohunt";
		
		$this->week = 7;
		$week = intval($this->apps->_request('weeks'));
		if($week!=0) $this->week = $week;
		
		$this->startweekcampaign = "2013-04-25";
		// pr($this->apps->_request('week'));
	}
	
		

	
	function getAuthorProfile($otherid=null,$typeAuthor='admin'){
		if($otherid==null) return false;
		
		$sql = "SELECT name, id AS authorid, '' as last_name,'' as pagestype,image as img FROM gm_member WHERE id IN ({$otherid}) LIMIT 10 ";
		if($typeAuthor == 'social' ) $sql = "SELECT name, id AS authorid, last_name,'' as pagestype,img  FROM social_member WHERE id IN ({$otherid}) LIMIT 10 ";
		if($typeAuthor == 'page' ) $sql = "SELECT name, id AS authorid , '' as last_name,type as pagestype,img FROM my_pages WHERE id IN ({$otherid}) LIMIT 10 ";
		// pr($sql);
		$data = $this->apps->fetch($sql,1);
		if(!$data) return false;
		
		foreach($data as $val){
			$arrData[$val['authorid']] = $val;
		}
		if(!isset($arrData)) return false;
		return $arrData;
	}
	
	
	function getFavoriteUrl($cid=null,$content=2){
		if($cid==null) return false;
		$sql="
		SELECT count(*) total, contentid,url FROM social_bookmark sb 
		WHERE content={$content}
		AND contentid IN ({$cid}) 
		GROUP BY contentid ";
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		foreach($qData as $val){
			$arrData[$val['contentid']] = $val['total'];
		}	
		if($arrData) return $arrData;
		return false;
	}
	
	
	function saveFavorite(){
	
		$cid = intval($this->apps->_p('cid'));
		$likes =1;
		$uid = intval($this->uid);
		if($cid!=0 && $uid!=0){
			$sql ="
					INSERT INTO {$this->dbshema}_news_content_favorite (userid,contentid,likes,date,n_status) 
					VALUES ({$uid},{$cid},{$likes},NOW(),1)
					";
			$this->apps->query($sql);
			$this->logger->log($sql);
			if($this->apps->getLastInsertId()>0) return true;
			
		}
		return false;
	}
	
	
	
	
	function getFavorite($cid=null){
		if($cid==null) $cid = $this->apps->_p('cid');
		if($cid){
			$cidin = " AND contentid IN ({$cid}) ";
		}
			$sql ="
					SELECT count(*) total,contentid FROM {$this->dbshema}_news_content_favorite WHERE n_status=  1 {$cidin}  GROUP BY contentid
					";
		
				$qData = $this->apps->fetch($sql,1);
				if($qData) {
					$this->logger->log("have favorite");
					foreach($qData as $val){
						$favoriteData[$val['contentid']]=$val['total'];
					}
					
						return $favoriteData;
				}
		return false;
			
			
	}
	
	
	function addComment($cid=null,$comment=null){
	
		if($cid==null) $cid = intval($this->apps->_p('cid'));
		if($comment==null) $comment = $this->apps->_p('comment');
		if(!$this->uid) return false;
		$uid = intval($this->uid);
		if($cid&&$comment){
		if($comment=="") return false;
		global $CONFIG;
			//bot system halt
			$sql = "SELECT id,date,count(id) total FROM {$this->dbshema}_news_content_comment WHERE userid={$uid} ORDER BY id DESC LIMIT 1";
			$lastInsert = $this->apps->fetch($sql);
			$this->logger->log($lastInsert['total']);
			if($lastInsert['total']==0) $divTime = $CONFIG['DELAYTIME']+1;
			else $divTime = strtotime(date("Y-m-d H:i:s")) - strtotime($lastInsert['date']); 
			if($CONFIG['DELAYTIME']==0) $divTime = $CONFIG['DELAYTIME']+1;
			$this->logger->log(date("Y-m-d H:i:s").' .... '.$lastInsert['date']);
			if($divTime<=$CONFIG['DELAYTIME']) return false; 
			
			$sql ="
					INSERT INTO {$this->dbshema}_news_content_comment (userid,contentid,comment,date,n_status) 
					VALUES ({$uid},{$cid},'{$comment}',NOW(),1)
					";
			$this->apps->query($sql);
			$this->logger->log($sql);
			if($this->apps->getLastInsertId()>0) return true;
			
		} else $this->logger->log($cid.'|'.$comment);
		return false;
	
	}
	
	function getComment($cid=null,$all=false,$start=0,$limit=5){
		// return $cid;
		if($cid==null) $cid = $this->apps->_request('id');
		
		if(intval($this->apps->_request('start'))>=0)$start = intval($this->apps->_request('start'));
	
		if($cid){			
			if($all==true) $qAllRecord = "";
			else  $qAllRecord = " LIMIT {$start},{$limit} ";
			if($all==true) $qFieldRecord = " count(*) total , contentid ";
			else  $qFieldRecord = " * ";
			if($all==true) $qGroupRecord = " GROUP BY contentid ";
			else  $qGroupRecord = "  ";
			
			$sql ="	SELECT {$qFieldRecord} FROM {$this->dbshema}_news_content_comment 
					WHERE contentid IN ({$cid}) AND n_status = 1
					{$qGroupRecord}
					ORDER BY date DESC {$qAllRecord}
					";
			//pr($sql);
			$qData = $this->apps->fetch($sql,1);
			
			$this->logger->log($sql);
			if($qData) {
			
				if($all==true) {
					foreach($qData as $val){
						$arrComment[$val['contentid']] = $val['total'];
					}
					return $arrComment;
				}
				
				
				foreach($qData as $val){
					$arrUserid[] = $val['userid'];				
				}
				
				$users = implode(",",$arrUserid);
				
				
				$sql = "SELECT * FROM social_member WHERE id IN ({$users})  AND n_status = 1 ";
				$qDataUser = $this->apps->fetch($sql,1);
				if($qDataUser){
					// $userRate = $this->getUserFavorite($cid,$users);
					$userRate = false;
					
					foreach($qDataUser as $val){
						$userDetail[$val['id']]['name'] = $val['name'];
						$userDetail[$val['id']]['img'] = $val['img'];
						
					}
					foreach($qData as $key => $val){
						$arrComment[$val['contentid']][$key] = $val;
						if(array_key_exists($val['userid'],$userDetail)){
							$arrComment[$val['contentid']][$key]['name'] = $userDetail[$val['userid']]['name'] ;
							$arrComment[$val['contentid']][$key]['img'] = $userDetail[$val['userid']]['img'] ;
							
							if($userRate){
								if(array_key_exists($val['contentid'],$userRate)) {
									if(array_key_exists($val['userid'],$userRate[$val['contentid']]))$arrComment[$val['contentid']][$key]['favorite'] = $userRate[$val['contentid']][$val['userid']] ; 
									else $arrComment[$val['contentid']][$key]['favorite'] = 0;
								}else $arrComment[$val['contentid']][$key]['favorite'] = 0;
							}else  $arrComment[$val['contentid']][$key]['favorite'] = 0;
						}
					}
				
					$qData = null;
					// pr($arrComment);exit;
					return $arrComment;
				}
			}			
		}
		return false;	
	}
	
	function getAttending($cid=null){
		if($cid==null) $cid = $this->apps->_p('cid');
		if($cid){
			$cidin = " AND contestid IN ({$cid}) ";
		}
			$sql ="
					SELECT count(*) total,contestid FROM my_contest WHERE n_status=  1 {$cidin}  GROUP BY contestid
					";
		
				$qData = $this->apps->fetch($sql,1);
				if($qData) {
					$this->logger->log("have attending");
					foreach($qData as $val){
						$attendingData[$val['contestid']]=$val['total'];
					}
					
						return $attendingData;
				}
		return false;
	}
	
	function getBanner($page="home",$type="slider_header",$featured=0,$limit=4){
		global $CONFIG;
		$sql ="SELECT * FROM {$this->dbshema}_news_content_banner_type WHERE type ='{$type}' AND n_status=1 LIMIT 1 "; 
		
		$this->logger->log($sql);
		$bannerType = $this->apps->fetch($sql);	
		if(!$bannerType) return false;
		$sql ="SELECT * FROM {$this->dbshema}_news_content_page WHERE pagename = '{$page}' AND n_status = 1 LIMIT 1";		
		$this->logger->log($sql);
		$bannerPage = $this->apps->fetch($sql);
		if(!$bannerPage) return false;	
	 
		$sql = "SELECT * FROM {$this->dbshema}_news_content_banner WHERE page LIKE '%{$bannerPage['id']}%' AND type IN ({$bannerType['id']}) AND n_status IN ({$this->server}) ";
		$this->logger->log($sql);
		$banner = $this->apps->fetch($sql,1);		
		
		if(!$banner) return false;
		foreach($banner as $val){
			$arrBannerID[] = $val['parentid'];
			$banners[$val['parentid']] = $val;
		}
	
		$bannerId = implode(",",$arrBannerID) ;
		
		$sql = "	
		SELECT anc.id,anc.content,anc.brief,anc.title,anc.image,anc.posted_date ,anc.categoryid,ancc.category,anc.articleType,anc.slider_image,anc.thumbnail_image,anct.content_name,anct.type typeofarticlename,anc.url
		FROM {$this->dbshema}_news_content anc
		LEFT JOIN {$this->dbshema}_news_content_category ancc ON ancc.id = anc.categoryid
		LEFT JOIN {$this->dbshema}_news_content_type anct ON anct.id = anc.articleType
		WHERE anc.id IN ({$bannerId}) AND anc.n_status IN ({$this->server})
		ORDER BY posted_date DESC  LIMIT {$limit}
		";
		
		$this->logger->log($sql);
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		foreach($qData as $key => $val){
			if(array_key_exists($val['id'],$banners)) $qData[$key]['banner'] = $banners[$val['id']];			
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/thumb_{$val['slider_image']}")) $qData[$key]['banner_thumb'] = true;
			else  $qData[$key]['banner_thumb'] = false;
				// pr("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/thumb_{$val['slider_image']}");
			//parseurl for video thumb
				$video_thumbnail = false;
				if($val['articleType']==3&&$val['url']!='')	{				
					//parser url and get param data
						$parseUrl = parse_url($val['url']);
						if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
						else $parseQuery = false;
						if($parseQuery) {
							if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
						} 
						$qData[$key]['video_thumbnail'] = $video_thumbnail;
				}else $qData[$key]['video_thumbnail'] = false;		
			
		}
		
		return $qData;
	}	
	
	function getCity_($province=NULL, $type=NULL, $cityID=NULL){
		if($cityID){
			$filter = 'AND id = '.$cityID;
			$default = "SELECT * FROM {$this->dbshema}_city_reference WHERE  provinceid<>0  AND city <> '(NOT SPECIFIED)' AND id ={$cityID} ORDER BY city";
			$qDefault = $this->apps->fetch($default);
		}else{
			$filter = '';
		}
		
		if ($province) {
			$filterprov = " provinceid = {$province}";
		} else {
			$filterprov = "";
		}
		
		$sql ="SELECT * FROM {$this->dbshema}_city_reference WHERE provinceid <> 0 AND city <> '(NOT SPECIFIED)' {$filterprov} {$filter}  ORDER BY city LIMIT 10";
		$qData = $this->apps->fetch($sql,1);
		$this->logger->log($sql);
		
		if($type=='topup'){
			array_unshift($qData, $qDefault);
		}		
		
		if(!$qData) return false;
		return $qData;
	}
	
	function getTypeContent(){
		$sql_type ="SELECT id,type FROM {$this->dbshema}_news_content_type WHERE id IN ('3','4','5','6') ORDER BY id";
		$qData = $this->apps->fetch($sql_type,1);
		
		if(!$qData) return false;
		return $qData;
	}
	
	function getEventArticleType(){
		$sql_type ="SELECT * FROM {$this->dbshema}_news_content_type WHERE content = 4 ORDER BY id";
		$qData = $this->apps->fetch($sql_type,1);
		
		if(!$qData) return false;
		return $qData;
	}
	
	function getProvince_($type=null,$id=null){
		if($id){
			$filter = 'WHERE id <> '.$id;
			$default = "SELECT * FROM {$this->dbshema}_province_reference WHERE id = ".$id;
			$qDefault = $this->apps->fetch($default);
		}else{
			$filter = '  ';
		}
		// pr($id);
		if($type=='topup'){$filterProvince = 'AND id IN (1,2,3,4,5,6,7,8,9,10,11,12)';}
		else if($type=='coverage'){$filterProvince = 'AND id IN (1,2,3,4,5,6,8,9,10,13,14,16,19,21,24,30)';}
		else if($type=='coveragemap'){$filterProvince = 'WHERE id IN (1,2,3,4,5,6,7,8,9,10,11,12,16,17,25,26,27,28,29,30)';}
		else{$filterProvince = '';}
	
		$sql ="SELECT * FROM {$this->dbshema}_province_reference {$filter} {$filterProvince}";
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		
		if($type=='coverage' || $type=='topup'){
			array_unshift($qData, $qDefault);
		}
		
		
		if(!$qData) return false;
		return $qData;
	}
	
	// add by putra featured article
	function getArticleFeatured($contenttype=0,$topcontent=array(2)) {
		global $CONFIG;
		$topcontent = implode(',',$topcontent);
		$contenttype = intval($contenttype);
		$typeid = strip_tags($this->checkPage($contenttype));
		
		if(!$typeid) return false;
		
		$sql = "SELECT id,title,brief,image,thumbnail_image,slider_image,posted_date,file,url,fromwho,tags,authorid,topcontent,cityid ,articleType,can_save,content
		FROM {$this->dbshema}_news_content WHERE articleType IN ({$typeid}) AND topcontent={$topcontent}  AND n_status=1 ORDER BY posted_date DESC ,id DESC  LIMIT 1";
		
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		//CEK DETAIL IMAGE FROM FOLDER
		//IF IS ARTICLE, IMAGE BANNER DO NOT SHOWN
		foreach($qData as $key => $val){
			$qData[$key]['imagepath'] = false;
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";	
		
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}")) $qData[$key]['banner'] = false;
			else $qData[$key]['banner'] = true;	
			
			//CHECK FILE
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $qData[$key]['hasfile'] = true;
			else $qData[$key]['hasfile'] = false;				
			
			//PARSEURL FOR VIDEO THUMB
			if($val['articleType']==3&&$val['url']!='')	{
				//PARSER URL AND GET PARAM DATA
				$parseUrl = parse_url($val['url']);
				if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
				else $parseQuery = false;
				if($parseQuery) {
					if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
					else $video_thumbnail= false;
				}else $video_thumbnail = false;
				$qData[$key]['video_thumbnail'] = $video_thumbnail;
			}else $qData[$key]['video_thumbnail'] = false;		
		}
		
		if($qData) $qData =	$this->getStatistictArticle($qData);
		else return false;
		return $qData;
	}
	
	function getArticleContent($start=null,$limit=10,$contenttype=0,$topcontent=array(0,3)) {
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$contenttype = intval($contenttype);
		$limit = intval($limit);
		$topcontent = implode(',',$topcontent);
		
		//RUN FILTER ENGINE, KEYWORDSEARCH , CONTENTSEARCH 
		$filter = $this->apps->searchHelper->filterEngine($limit);
		$typeid = strip_tags($this->checkPage($contenttype));		
	
		if(!$typeid) return false;		
		
		$file = "";
		if ($this->apps->_g('page')=='music' || $this->apps->_g('page')=='dj') {
			if(strip_tags($this->apps->_g('act'))=="highlight") $topcontent = 3;
			else $topcontent = 0;
		}
		
		//GET ARTICLE INTERVIEW
		$arrInterview = "";
		$interview = "";
		$sql_interview = "SELECT * FROM {$this->dbshema}_news_content WHERE articleType IN ({$typeid}) AND topcontent IN (3) {$file} AND n_status=1 ORDER BY posted_date DESC LIMIT 2";
		$arrInterview = $this->apps->fetch($sql_interview,1);
		if($arrInterview) {
			if($start==0) {
				$limit = $limit-4;
			} elseif($start==10) {
				$start = 10 - 4;
				$limit = 10;
			} else {
				$start = $start - 4;
				$limit = 10;
			}
			$interData=	$this->getStatistictArticle($arrInterview);
			foreach ($interData as $k => $v) {
				$interview[] = $v;
			}
		} else {
			$interview = false;
		}
		
		if ($typeid==3||$typeid==15) {
			$file = " AND NOT EXISTS (SELECT contentid FROM my_images WHERE contentid = anc.id ORDER BY date DESC LIMIT 50) ";
			//$file = " AND file <> '' ";
		} else{
			$file ="";
		}
		
		//GET TOTAL ARTICLE
		$sql = "SELECT count(*) total FROM {$this->dbshema}_news_content anc  WHERE articleType IN ({$typeid}) AND topcontent IN ({$topcontent}) {$file} {$filter['keywordsearch']} {$filter['categorysearch']} {$filter['citysearch']} {$filter['postedsearch']} {$filter['fromwhosearch']} AND n_status=1 ";
		$total = $this->apps->fetch($sql);
		
		if(intval($total['total'])<=$limit) $start = 0;
		
		$sql = "
			SELECT id,title,brief,image,thumbnail_image,slider_image,posted_date,file,url,fromwho,tags,authorid,topcontent,cityid ,articleType,can_save
			FROM {$this->dbshema}_news_content anc
			{$filter['subqueryfilter']} 
			WHERE articleType IN ({$typeid}) AND topcontent IN ({$topcontent}) {$file} {$filter['keywordsearch']} {$filter['categorysearch']} {$filter['citysearch']} {$filter['postedsearch']} {$filter['fromwhosearch']} AND n_status=1   
			ORDER BY {$filter['suborderfilter']}  posted_date DESC , id DESC
			LIMIT {$start},{$limit}";
		
		$rqData = $this->apps->fetch($sql,1);
		if($rqData) {
			//CEK DETAIL IMAGE FROM FOLDER
			//IF IS ARTICLE, IMAGE BANNER DO NOT SHOWN
			foreach($rqData as $key => $val){
				$rqData[$key]['imagepath'] = false;
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$rqData[$key]['imagepath'] = "event";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$rqData[$key]['imagepath'] = "banner";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$rqData[$key]['imagepath'] = "article";					
				
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}")) 	$rqData[$key]['banner'] = false;
				else $rqData[$key]['banner'] = true;
				
				//CHECK FILE
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $rqData[$key]['hasfile'] = true;
				else $rqData[$key]['hasfile'] = false;	
				
				//CHECK FILE SMALL
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}{$rqData[$key]['imagepath']}/small_{$val['image']}")) $rqData[$key]['image'] = "small_{$val['image']}";
				
				//PARSEURL FOR VIDEO THUMB
				$video_thumbnail = false;
				if($val['url']!='')	{
					//PARSER URL AND GET PARAM DATA
					$parseUrl = parse_url($val['url']);
					if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
					else $parseQuery = false;
					if($parseQuery) {
						if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
					} 
					$rqData[$key]['video_thumbnail'] = $video_thumbnail;
				}else $rqData[$key]['video_thumbnail'] = false;
			}
			
			if($rqData) $qData=	$this->getStatistictArticle($rqData);
			else $qData = false;
		}else $qData = false;
		
		$result['articleinter'] = $interview;
		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
		return $result;
	}
	
		
	function getStatistictArticle($rqData=null){
		
		if($rqData==null) return false;
		global $CONFIG;
		/* path to page */
		$profilepath[1] = "myband";
		$profilepath[4] = "mydj";
		
		$adminArrAuhtor = false;
		$socialArrAuthor = false;
		$pageArrAuhtor = false;
		$adminProfile = false;
		$socialProfile = false;
		$pageProfile = false;
		$qData = false;
		$cidArr = false;
		$cityData = false;
		$arrCityID = false;
		foreach($rqData as $key => $val){
		
			$cidArr[] = $val['id'];
			if(array_key_exists('cityid',$val)) $arrCityID[$val['cityid']] = intval($val['cityid']);
			
			//get profile array
			if($val['fromwho']==0) $adminArrAuhtor[] = $val['authorid'];
			if($val['fromwho']==1) $socialArrAuthor[] = $val['authorid'];
			if($val['fromwho']==2) $pageArrAuhtor[] = $val['authorid'];
			
			$qData[$key] = $val;
			$qData[$key]['ts'] = strtotime($val['posted_date']);
			$qData[$key]['user'] = false;
			// $qData[$key]['comment'] = false;
			$qData[$key]['commentcount'] = 0;
			$qData[$key]['favorite'] = 0;
			$qData[$key]['views'] = 0;
			$qData[$key]['author'] = false;
			$qData[$key]['attending'] = 0;
			$qData[$key]['gallery'] = false;
			$qData[$key]['profilepath'] = false;
			$qData[$key]['cityname'] = false;
		}
		
		if(!$cidArr) return false;
		$cidStr = implode(",",$cidArr);		
		
		if(!$arrCityID) return false;
		$cityArr = implode(",",$arrCityID);		
		
		//get profiler
		if($adminArrAuhtor) {
			$adminStr = implode(",",$adminArrAuhtor);
			$adminProfile = $this->getAuthorProfile($adminStr,'admin');
		}
		if($socialArrAuthor){
			$socialStr = implode(",",$socialArrAuthor);
			$socialProfile = $this->getAuthorProfile($socialStr,'social');
		}
		if($pageArrAuhtor) {
			$pageStr = implode(",",$pageArrAuhtor);
			$pageProfile = $this->getAuthorProfile($pageStr,'page');
		}
		
		if($cityArr){
			$cityData = $this->checkCity($cityArr);
		}
		
		//merge profiler
		foreach($qData as $key => $val){
				//admin profile
				if($adminProfile)	if($val['fromwho']==0)  if(array_key_exists($val['authorid'],$adminProfile)) $qData[$key]['author'] = $adminProfile[$val['authorid']];
				//user profile
				if($socialProfile)	if($val['fromwho']==1)  if(array_key_exists($val['authorid'],$socialProfile)) $qData[$key]['author'] = $socialProfile[$val['authorid']];
				//page profile
				if($pageProfile) if($val['fromwho']==2)  if(array_key_exists($val['authorid'],$pageProfile)) $qData[$key]['author'] = $pageProfile[$val['authorid']];
				//city data
				if($cityData)  if(array_key_exists($val['cityid'],$cityData)) $qData[$key]['cityname'] = $cityData[$val['cityid']];
				
				
				//admin profile
				if($adminProfile)	if($val['fromwho']==0)  $qData[$key]['profilepath'] = false;
				//user profile
				if($socialProfile)	if($val['fromwho']==1) $qData[$key]['profilepath'] = "friends";
				//page profile
				if($pageProfile) {
					if($val['fromwho']==2)  {
						if(array_key_exists($val['authorid'],$pageProfile)) {
							if(array_key_exists($pageProfile[$val['authorid']]['pagestype'],$profilepath)) $qData[$key]['profilepath'] = $profilepath[$pageProfile[$val['authorid']]['pagestype']];
							else $qData[$key]['profilepath'] = false;
							
						if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}pages/{$pageProfile[$val['authorid']]['img']}")){
							$qData[$key]['imagepath'] = "pages";		
							$qData[$key]['image'] = $pageProfile[$val['authorid']]['img'];
						}
						
						}else $qData[$key]['profilepath'] = false;
						
						
					}
				}
				
		}
		
		// favorite or like data
		$favoriteData = $this->getFavorite($cidStr);
		if($favoriteData){
			
			foreach($qData as $key => $val){
				if(array_key_exists($val['id'],$favoriteData)) $qData[$key]['favorite'] = $favoriteData[$val['id']];
				
			
			}
		
		}
		
		//comment di list article 
		$commentsData = $this->getComment($cidStr,true);
		
		if($commentsData){
			foreach($qData as $key => $val){
				if(array_key_exists($val['id'],$commentsData)) {
					// $qData[$key]['comment'] = $commentsData[$val['id']];
					$qData[$key]['commentcount'] = $commentsData[$val['id']];
				}
				
			
			}
			// pr($qData);
		}
		
		// gallery or repository data
		$gallerydata = $this->getContentRepository($cidStr);
		if($gallerydata){
			
			foreach($qData as $key => $val){
				if(array_key_exists($val['id'],$gallerydata)) $qData[$key]['gallery'] = $gallerydata[$val['id']];
				
			
			}
		
		}
		//get views
		$getTotalViewsArticle = $this->getTotalViewsArticle($cidStr);
		if($getTotalViewsArticle){
			
			foreach($qData as $key => $val){
				if(array_key_exists($val['id'],$getTotalViewsArticle)) $qData[$key]['views'] = $getTotalViewsArticle[$val['id']];
				
			
			}
		
		}
		
		//get attending
		$attendingData = $this->getAttending($cidStr);
		if($attendingData){
			
			foreach($qData as $key => $val){
				if(array_key_exists($val['id'],$attendingData)) $qData[$key]['attending'] = $attendingData[$val['id']];
				
			
			}
		
		}	
		
		
		if($qData) {
			return $qData;
		} else {
		return false;
		}
	}
	
	function checkCity($strCity=null){
			if($strCity==null) return false;
			$sql ="SELECT * FROM {$this->dbshema}_city_reference WHERE id IN ({$strCity}) LIMIT 20 ";
			// pr($sql);
			$qData = $this->apps->fetch($sql,1);
			if(!$qData)return false;
			$rqData = false;
			foreach($qData as $val){
				$rqData[$val['id']] = $val['city'];
			}	
			
			return $rqData;
			
	}
	
	function checkPage($contenttype=0){
	
		$articleType = strip_tags($this->apps->_g('page'));
		if($articleType=="event" || $articleType=="myband" || $articleType=="mydj") {
			$contenttype = "3,4";			
			$sql = "SELECT * FROM {$this->dbshema}_news_content_type WHERE content IN ({$contenttype})";
		}else $sql = "SELECT * FROM {$this->dbshema}_news_content_type WHERE type = '{$articleType}' AND content={$contenttype} LIMIT 1";
		$arrType = $this->apps->fetch($sql,1);
		
		if(!$arrType) return false;
		foreach($arrType as $val){
			$arrtypeid[] = $val['id'];
		}
		$typeid = false;
		if($arrtypeid) $typeid = implode(',',$arrtypeid);
		else return false;
		return $typeid;
	}
	
		
	function getDetailArticle($start=null,$limit=1,$contenttype=false) {
		global $CONFIG;
		//pr($this->apps->user);
		if($start==null)$start = intval($this->apps->_request('start'));
		$category = intval($this->apps->_request('cid'));
		$id = intval($this->apps->_request('id'));
		
		$limit = intval($limit);
	
		if($category!=0) $qCategory = " AND categoryid={$category} ";
		else $qCategory = "";
		
		if($id!=0) $qid = " AND acontent.id={$id} ";
		else $qid = ""; 
		
		if($contenttype){
			$contenttype = intval($contenttype);
			$qType = " AND articleType = {$contenttype} ";
		}else $qType = "";
		// $typeid = strip_tags($this->checkPage($contenttype));
		//get total
		$sql = "
		SELECT count(*) total  
		FROM {$this->dbshema}_news_content acontent
		LEFT JOIN {$this->dbshema}_news_content_category acategory ON acontent.categoryid = acategory.id
		WHERE  n_status=1  {$qid}  {$qCategory} {$qType} ";

		$totaldata = $this->apps->fetch($sql);
		if(!$totaldata) return false;
		if($totaldata['total']<=0) return false;
		
		$sql = "
		SELECT acontent.*, acategory.point ,acategory.category,anct.type  
		FROM {$this->dbshema}_news_content acontent
		LEFT JOIN {$this->dbshema}_news_content_type anct ON acontent.articleType = anct.id
		LEFT JOIN {$this->dbshema}_news_content_category acategory ON acontent.categoryid = acategory.id
		WHERE  n_status=1  {$qid}  {$qCategory} {$qType} 
		ORDER BY posted_date DESC LIMIT {$start},{$limit}";

		$rqData = $this->apps->fetch($sql,1);
		if($rqData){
			//cek detail image from folder
				//if is article, image banner do not shown
			foreach($rqData as $key => $val){
				$rqData[$key]['session_userid'] = $this->apps->user->id;
				$rqData[$key]['session_pageid'] = $this->apps->user->pageid;
				$untags = unserialize($val['tags']);
				if(is_array($untags)) $rqData[$key]['un_tags'] = implode(",",$untags);	
				else $rqData[$key]['un_tags'] = false;
				$rqData[$key]['imagepath'] = false;
								
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$rqData[$key]['imagepath'] = "event";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$rqData[$key]['imagepath'] = "banner";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$rqData[$key]['imagepath'] = "article";	
				// pr(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"));
				//check file
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $rqData[$key]['hasfile'] = true;
				else $rqData[$key]['hasfile'] = false;	
				
				//parseurl for video thumb
				$video_thumbnail = false;
				if($val['url']!='')	{
					//parser url and get param data
						$parseUrl = parse_url($val['url']);
						if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
						else $parseQuery = false;
						if($parseQuery) {
							if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
						} 
						$rqData[$key]['video_thumbnail'] = $video_thumbnail;
				}else $rqData[$key]['video_thumbnail'] = false;							
			}
		}
		if($rqData) $qData=	$this->getStatistictArticle($rqData);
		else $qData = false;
		
		if(!$qData) return false;
		if($this->uid && $qData){
		
				$sql ="
				INSERT INTO {$this->dbshema}_news_content_rank (categoryid ,	point, 	userid ,	date) 
				VALUES ({$qData[0]['categoryid']},{$qData[0]['point']},{$this->uid},NOW())
				
				";
				$this->apps->query($sql);
				
				// job buat bot tracking user preference
				// $sql ="
				// INSERT INTO job_content_preference (user_id ,	content_id, 	n_status) 
				// VALUES ({$this->uid},{$qData['id']},0)
				
				// ";
				
				// $this->apps->query($sql);
		
	
		}
		
		
		if(!$qData) return false;
		
		$result['result'] = $qData;
		$result['total'] = $totaldata['total'];		
		 //pr($result);
		return $result;
	}
	
	
	
	
	function getTotalViewsArticle($cid=null){
		if($cid==null) return false;
		
		$sql = "SELECT COUNT(*) total, action_value as cid FROM tbl_activity_log WHERE action_id=2 AND action_value IN ({$cid}) GROUP BY cid";
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		$arrViewArticle = false;
		foreach($qData as $key => $val){
			$arrViewArticle[$val['cid']] = $val['total'];
		}
		if($arrViewArticle){
			return $arrViewArticle;
		}else return false;
		
	}
	
	function getContentRepository($gallerytype=0,$id=null,$limit=10){
		if($id==null) return false;
		
		$gallerytype = intval($gallerytype);
		$limit = intval($limit);
				
		$sql = "SELECT * FROM  {$this->dbshema}_news_content_repo WHERE gallerytype={$gallerytype} AND otherid IN ({$strId}) AND n_status=1 ORDER BY created_date DESC LIMIT {$limit} ";
		
		$rqData = $this->apps->fetch($sql);
		
		if(!$rqData) return false;
		$qData = false;
		foreach($rqData as $key => $val){
			$qData[$val['otherid']] = $val;
		}
		
		if(!$qData) return false;
		
		return $qData;
	
	}
	
	function getListSongs($start=null,$limit=9) {
		global $CONFIG;
		
		$pid = intval($this->apps->_request('pid'));
		if(!$pid) $pid = intval($this->apps->user->pageid);
		if($pid!=0 || $pid!=null) {		
			if($start==null)$start = intval($this->apps->_request('start'));
			$limit = intval($limit);
			$pages = $this->apps->_g('page');
			$userid = $this->uid;
			if ($pages=='my') {
				//GET IDCONTENT PLAYLIST
				$sql_playlist = "SELECT id,otherid FROM my_playlist where myid = {$userid} AND n_status=1 ORDER BY datetime DESC";
				$dataPlaylist = $this->apps->fetch($sql_playlist,1);
				if ($dataPlaylist) {
					foreach($dataPlaylist as $key => $val){
						$idcontent[] = $val['otherid'];
					}
					if(!$idcontent) return false;
					$arrIdContent = implode(",",$idcontent);
				} else return false;
				
				//GET TOTAL PLAYLIST
				$sql_total = "SELECT count(*) total FROM {$this->dbshema}_news_content WHERE id IN ({$arrIdContent}) AND n_status = 1";
				$total = $this->apps->fetch($sql_total);
				
				if(!$total) return false;
				if($start>intval($total['total'])) return false;
				if(intval($total['total'])<=$limit) $start = 0;
				
				//GET DATA PLAYLIST
				$sql = " SELECT * FROM {$this->dbshema}_news_content WHERE id IN ({$arrIdContent}) AND n_status = 1 LIMIT {$start},{$limit}";
			} elseif ($pages=='myband' || $pages=='mydj' ) {
				$type = $pages=='myband' ? 1 : 4;
				
				//GET TOTAL SONGS
				$sql_total = "SELECT count(*) total FROM {$this->dbshema}_news_content WHERE fromwho = 2 AND articleType = 3 AND file <> '' AND authorid = {$pid} AND n_status = 1";
				$total = $this->apps->fetch($sql_total);
				
				if(!$total) return false;
				if($start>intval($total['total'])) return false;
				if(intval($total['total'])<=$limit) $start = 0;
				
				//GET DATA SONGS
				$sql = "SELECT * FROM {$this->dbshema}_news_content WHERE fromwho = 2 AND articleType = 3 AND file <> '' AND authorid = {$pid} AND n_status = 1 ORDER BY posted_date DESC LIMIT {$start},{$limit}";
			} else return false;
			$rqData = $this->apps->fetch($sql,1);
			
			$no=1;
			if($rqData) {
				foreach ($rqData as $k => $v) {
					$v['no'] = $no++;
					if ($v['filesize']) {
						$durasi = $v['filesize']/1000;
						$v['filesize'] = sprintf("%02d:%02d", ($durasi /60), $durasi %60 );
					} else $v['filesize'] = "";
					$rqData[$k] = $v;
					
					if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$v['file']}")) $rqData[$k]['hasfile'] = true;
					else $rqData[$k]['hasfile'] = false;				
				}
			}
			
			if($rqData) $qData=	$this->getStatistictArticle($rqData);
			else $qData = false;
			
			if(!$qData) return false;
			$arrPlaylist['result'] = $qData;
			$arrPlaylist['total'] = $total['total'];
			return $arrPlaylist;
		}
		return false;
	}
	
	function getMygallery($start=null,$limit=3,$userid=NULL) {
		global $CONFIG;
		if($start==null) $start = intval($this->apps->_g('start'));
		
		if(strip_tags($this->apps->_g('page'))=='my') $userid = $this->uid;
		else $userid = intval($this->apps->_g('uid'));
				
		//get total gallery
		$sql_total = "
			SELECT count(*) total FROM `my_images` mm
			WHERE mm.userid = '{$userid}'
			AND mm.n_status = 1
		";
		$total = $this->apps->fetch($sql_total);
		
		if(!$total) return false;
		if($start>intval($total['total'])) return false;
		if(intval($total['total'])<=$limit) $start = 0;
		
		$sql = "
			SELECT mm.*,nc.title,nc.brief,nc.image,nc.file,nc.articleType,ct.content as typecontent,ct.type as typeofarticle,nc.fromwho,nc.authorid,nc.posted_date , nc.url
			FROM `my_images` mm
			LEFT JOIN {$this->dbshema}_news_content nc ON mm.contentid = nc.id
			LEFT JOIN {$this->dbshema}_news_content_type ct ON nc.articleType = ct.id
			WHERE mm.userid = '{$userid}' AND mm.type = 0
			AND mm.n_status = 1 ORDER BY mm.date DESC LIMIT {$start},{$limit}
		";
		
		$rqData = $this->apps->fetch($sql,1);
		if(!$rqData) return false;
		foreach ($rqData as $key => $val) {
			if ($val['typecontent']==0) {
				$val['typecontent'] = "article";
			} elseif($val['typecontent']==2) {
				$val['typecontent'] = "banner";
			} elseif($val['typecontent']==4) {
				$val['typecontent'] = "event";
			} else {
				$val['typecontent'] = "";
			}
			$val['id_image'] = $val['id'];
			$val['id'] = $val['contentid'];
			
			$rqData[$key] = $val;
			
			$rqData[$key]['imagepath'] = false;
								
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$rqData[$key]['imagepath'] = "event";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$rqData[$key]['imagepath'] = "banner";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$rqData[$key]['imagepath'] = "article";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$rqData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$rqData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$rqData[$key]['image'] = "small_{$val['image']}";	
			
			$video_thumbnail = false;
			if($val['url']!='')	{
				//parser url and get param data
				$parseUrl = parse_url($val['url']);
				if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
				else $parseQuery = false;
				if($parseQuery) {
					if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
				} 
				$rqData[$key]['video_thumbnail'] = $video_thumbnail;
			}else $rqData[$key]['video_thumbnail'] = false;		
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $rqData[$key]['hasfile'] = true;
			else $rqData[$key]['hasfile'] = false;			
		}
		//pr($rqData);
		if($rqData) $qData=	$this->getStatistictArticle($rqData);		
		else $qData = false;
		
		if(!$qData) return false;
		$arrGallery['result'] = $qData;
		$arrGallery['total'] = $total['total'];
		return $arrGallery;
	}
	
	function hapusmygallery(){
		$cid = $this->apps->_p('cid');
		$sql = "UPDATE my_images set n_status = 0 WHERE id = {$cid}";
		if ($this->apps->query($sql)) {
			$data = array("status"=>1);
		} else {
			$data = array("status"=>0);
		}
		
		return $data;
	}
	
	function addUploadImage($data,$type=NULL){
		
		$fromwho = intval($this->apps->_p('fromwho'));
		if($fromwho==0) {
			$fromwho = $type==3 ? 2 : 1;
		}
		$title = strip_tags($this->apps->_p('title'));
		$description = strip_tags($this->apps->_p('desc'));
		$tags = strip_tags($this->apps->_p('tags'));
		$brief = strip_tags($this->apps->_p('brief'));
		if($brief=='') $brief = $this->wordcut($description,10);
		$posted_date = strip_tags($this->apps->_p('posted_date'));
		$city_event = intval($this->apps->_p('city_event'));
		$image = $data['arrImage']['filename'];
		if($tags){
			$tags = serialize(explode(',',$tags));
		}
		if ($type==3||$fromwho==2) {
			$authorid = intval($this->apps->user->pageid);
		} else {
			if(!$this->uid) return false;
			$authorid = intval($this->uid);
			if(!$authorid) return false;		
		}
		
		if($posted_date=='') $posted_date = date('Y-m-d H:i:s');		
		
		if(intval($data['result'])==1){
			$sql ="
				INSERT INTO {$this->dbshema}_news_content (cityid,brief,title,content,tags,image,articleType,created_date,posted_date,authorid,fromwho,n_status) 
				VALUES ('{$city_event}','{$brief}','{$title}','{$description}','{$tags}','{$image}',{$type},NOW(),'{$posted_date}','{$authorid}','{$fromwho}',{$this->moderation})
				";
				$this->logger->log($sql);
				if ($this->apps->query($sql)) return true;
				else return false;
				
				/* $result = false;
				if($this->apps->getLastInsertId()>0) {
					$result['cotentid'] = $this->apps->getLastInsertId();
					$contentid = $result['cotentid'];
					if ($type==3 || $type==15) {
						$sql_image = "INSERT INTO my_images (userid,type,contentid,date,n_status) values ('{$uid}',1,'{$contentid}',NOW(),1) ";
						$this->apps->query($sql_image);
					}
					return $result;
				} */
		} else return false;
	}
	
	function addUploadMusic($data,$type=NULL){
		$fromwho = 2;
		$title = strip_tags($this->apps->_p('title'));
		$description = strip_tags($this->apps->_p('desc'))=='Description' ? "" : strip_tags($this->apps->_p('desc'));
		$tags = strip_tags($this->apps->_p('tags'));
		$can_save = intval($this->apps->_p('can_save'));
		$music = $data['arrMusic']['filename'];
		
		if($tags){
			$tags = serialize(explode(',',$tags));
		}
		
		$pageid = intval($this->apps->user->pageid);		
		if(!$pageid) return false;
		if(intval($data['result'])==1){
			$sql ="
				INSERT INTO {$this->dbshema}_news_content (title,content,tags,file,articleType,created_date,posted_date,authorid,fromwho,can_save,n_status) 
				VALUES ('{$title}','{$description}','{$tags}','{$music}',{$type},NOW(),NOW(),'{$pageid}','{$fromwho}','{$can_save}',{$this->moderation})
				";
			$this->apps->query($sql);
			//$this->logger->log($sql);
			if($this->apps->getLastInsertId()>0) return true;			
		} else return false;
	}
	
	function addUploadVideo($type=NULL){
		$fromwho = $type==3 ? 2 : 1;
		$title = strip_tags($this->apps->_p('title'));
		$description = strip_tags($this->apps->_p('desc'));
		$tags = strip_tags($this->apps->_p('tags'));
		$url = strip_tags($this->apps->_p('url'));
		
		if($tags){
			$tags = serialize(explode(',',$tags));
		}
		if ($type==3) {
			$authorid = intval($this->apps->user->pageid);
		} else {
			if(!$this->uid) return false;
			$authorid = intval($this->uid);
			if(!$authorid) return false;		
		}
		$sql ="
			INSERT INTO {$this->dbshema}_news_content (title,content,tags,url,articleType,created_date,posted_date,authorid,fromwho,n_status) 
			VALUES ('{$title}','{$description}','{$tags}',\"{$url}\",{$type},NOW(),NOW(),'{$authorid}','{$fromwho}',{$this->moderation})
			";
		;
		//$this->logger->log($sql);
		if($this->apps->query($sql)) return true;
		else return false;
	}
	
	function getPagesCategory($pageid='photography',$checkpage=true){
		if($checkpage){
			$page = strip_tags($pageid);
			$sql ="SELECT * FROM {$this->dbshema}_news_content_page WHERE pagename='{$page}' LIMIT 1" ;
			$pageData = $this->apps->fetch($sql); 
			if(!$pageData) return false;
			$pageid = intval($pageData['id']);
		}else $pageid = intval($pageid);
		
		$sql ="SELECT * FROM {$this->dbshema}_news_content_category WHERE pageid={$pageid} " ;
		$qData = $this->apps->fetch($sql,1);
		
		return $qData ; 
	}
	
	function populartags($contenttype=0,$limit=5){
			
			$typeid = strip_tags($this->checkPage($contenttype));
			
			$limit = intval($limit);
			
			$sql ="	SELECT COUNT(*) total,content.id,content.tags
					FROM {$this->dbshema}_news_content content 
					LEFT JOIN tbl_activity_log log ON log.action_value = content.id
					WHERE log.action_id=2  AND content.n_status=1 AND content.articleType IN ({$typeid})
					GROUP BY content.id
					ORDER BY total DESC LIMIT {$limit}
					";
			// pr($sql);
			$qData = $this->apps->fetch($sql,1);
			
			if(!$qData) return false;
			$nametags = false;
			foreach($qData as $key => $val){
				if($val['tags']) $nametags[$val['id']] = unserialize($val['tags']);
				
			}
			$qData = null;
			if(!$nametags) return false;
			foreach($nametags as $key => $val){
				foreach($val as $tags){	
					$arrtags[$key] = $tags;	
				}
			}
			
			if($arrtags)	return $arrtags;
			return false;
			
	}
	
	function weeklyPopular ($contenttype=0,$limit=9){
			global $CONFIG;
			$typeid = strip_tags($this->checkPage($contenttype));
			
			$limit = intval($limit);
			//get between this week days
				//get monday of this week
					$mondaydate = date("Y-m-d",strtotime('last monday', strtotime('next sunday')));
				
			$sql ="	
					SELECT COUNT(*) total,content.*
					FROM {$this->dbshema}_news_content content 
					LEFT JOIN tbl_activity_log log ON log.action_value = content.id
					WHERE log.date_time BETWEEN '{$mondaydate}' AND DATE_ADD(NOW(),INTERVAL 1 DAY) AND content.n_status=1 AND articleType IN ({$typeid})
					GROUP BY content.id
					ORDER BY total DESC LIMIT {$limit}
					";
	
			$qData = $this->apps->fetch($sql,1);

			if(!$qData) return false;
			foreach($qData as $key => $val){
			$qData[$key]['imagepath'] = false;
								
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			
			
			//parseurl for video thumb
			$video_thumbnail = false;
			if($val['url']!='')	{
				//parser url and get param data
				$parseUrl = parse_url($val['url']);
				if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
				else $parseQuery = false;
				if($parseQuery) {
					if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
				} 
				$qData[$key]['video_thumbnail'] = $video_thumbnail;
			}else $qData[$key]['video_thumbnail'] = false;		
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $qData[$key]['hasfile'] = true;
			else $qData[$key]['hasfile'] = false;			
		}
		if($qData) $qData=	$this->getStatistictArticle($qData);
		else $qData = false;
		return $qData;
	}
	
	
	
	function getnewestupload(){
		global $CONFIG;
		$sql = "
			SELECT anc.id,anc.title,anc.brief,image,thumbnail_image,slider_image,posted_date,file,url,fromwho,tags,authorid,topcontent,cityid , anct.type,anct.content,anc.articleType,anct.type pagesname
			FROM {$this->dbshema}_news_content  anc
			LEFT JOIN {$this->dbshema}_news_content_type anct ON anc.articleType = anct.id
			WHERE n_status = 1   AND anc.articleType <> 0
			ORDER BY created_date DESC , id DESC
			LIMIT 4";
			$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		//cek detail image from folder
			//if is article, image banner do not shown
		foreach($qData as $key => $val){
			$qData[$key]['imagepath'] = false;
								
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			
			
			//parseurl for video thumb
			$video_thumbnail = false;
			if($val['url']!='')	{
				//parser url and get param data
				$parseUrl = parse_url($val['url']);
				if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
				else $parseQuery = false;
				if($parseQuery) {
					if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
				} 
				$qData[$key]['video_thumbnail'] = $video_thumbnail;
			}else $qData[$key]['video_thumbnail'] = false;		
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $qData[$key]['hasfile'] = true;
			else $qData[$key]['hasfile'] = false;			
		}
		if($qData) $qData=	$this->getStatistictArticle($qData);
		else $qData = false;
		//pr($qData);
		return $qData;
	}
	
	function setCover(){
		global $CONFIG;
		$cid = intval($this->apps->_request('cid'));
		$fromwhere = intval($this->apps->_request('fromwhere'));
		$typeofpage = intval($this->apps->_request('typeofpage')); //used by who
	
		//type of page , define user pages
		if($typeofpage!=0){
			$myid = $this->pageid; // wait for session
		}else $myid = $this->uid;
		$image = false;
		//if from content, get image content
		$sql ="SELECT id,image FROM {$this->dbshema}_news_content WHERE  id={$cid} AND fromwho = {$fromwhere} LIMIT 1;";
		$data = $this->apps->fetch($sql);
	
		if(!$data) return false;
		if($typeofpage==0) $coverfolder = "user/cover/";
		else $coverfolder = "pages/cover/";
		$folder = $CONFIG['LOCAL_PUBLIC_ASSET'];
		$image = $data['image'];	
		copy($folder."article/".$image,$folder.$coverfolder.$image);
		
		//userid 	image 	otherid 	fromwhere 0:news_content;1:my	type 0:user;1-n mypagestype	n_status 
		$sql =" 
		INSERT INTO my_wallpaper ( myid,	image ,	otherid ,	fromwhere ,type, n_status ,datetime )
		VALUES ({$myid},'{$image}',{$cid},{$fromwhere},{$typeofpage},1,NOW())
		ON DUPLICATE KEY UPDATE datetime=NOW()
		";
		//pr($sql);
		$this->apps->query($sql);
		
		if($this->apps->getLastInsertId()>0) {
			$sql =" 
			INSERT INTO my_images ( userid,	contentid ,	date ,	n_status )
			VALUES ({$myid},{$cid},NOW(),1)
			ON DUPLICATE KEY UPDATE date=NOW()
			";
			
			$this->apps->query($sql);
			return true;
		} else return false;
		
	}
	
	function setPlaylist(){
		$cid = intval($this->apps->_request('cid'));
		$fromwhere = intval($this->apps->_request('fromwhere'));
		$typeofpage = intval($this->apps->_request('typeofpage'));
		$authorid = intval($this->apps->_request('authorid'));
		
		//check user have myid relation		
		$sql ="SELECT id, ownerid FROM my_pages WHERE ownerid={$this->uid} AND n_status<> 3 LIMIT 1";
		$data = $this->apps->fetch($sql);
		if($data) {
			if($data['id']==$authorid) {
				return false;
			} else {
				$myid = $this->uid;
			}
		} else {
			$myid = $this->uid;
		}
		
		//get file content
		$file = false;
		$sql ="SELECT id,file FROM {$this->dbshema}_news_content WHERE  id={$cid} AND fromwho = {$fromwhere} LIMIT 1;";
		$data = $this->apps->fetch($sql);
	
		if(!$data) return false;
		$file = $data['file'];
		
		//get type of page
		$type = false;
		$sql ="SELECT id,type FROM my_pages WHERE  id={$authorid} LIMIT 1;";
		$arrtype = $this->apps->fetch($sql);
		if (!$arrtype) return false;
		$type = $arrtype['type'];
		
		//userid  image  otherid  fromwhere 0:news_content;1:my	type 0:user;1-n mypagestype	n_status 
		$sql =" 
			INSERT INTO my_playlist (myid,file,otherid,fromwhere,type,n_status,datetime) VALUES ({$myid},'{$file}',{$cid},{$fromwhere},{$type},1,NOW())
			ON DUPLICATE KEY UPDATE datetime=NOW()
		";
		
		$this->apps->query($sql);
		
		if($this->apps->getLastInsertId()>0) {
			/*
			$sql =" 
			INSERT INTO my_images ( userid,	contentid ,	date ,	n_status )
			VALUES ({$myid},{$cid},NOW(),1)
			ON DUPLICATE KEY UPDATE date=NOW()
			";
			
			$this->apps->query($sql);
			*/
			return true;
		} else return false;
	}
		
	function getMyFavorite($userid=0,$limit=10){
			global $CONFIG;
			
			if($userid==0) return false;
			$start = intval($this->apps->_request('start'));
			$sql ="
					SELECT contentid FROM {$this->dbshema}_news_content_favorite WHERE n_status=  1 AND userid = {$userid} ORDER BY date DESC  LIMIT {$start},{$limit}
					";

				$qData = $this->apps->fetch($sql,1);
				if($qData) {
					$this->logger->log("have favorite");
					foreach($qData as $val){
						$favoriteData[$val['contentid']]=$val['contentid'];
					}
				
					if(!$favoriteData) return false;
					$strcontentid = implode(',',$favoriteData);
					
					//get content
					$sql = "
					SELECT anc.id,anc.title,anc.brief,anc.image,anc.thumbnail_image,anc.slider_image,anc.posted_date,anc.file,anc.url,anc.fromwho,anc.tags,anc.authorid,anc.topcontent,anc.cityid,anct.type pagesname FROM athreesix_news_content anc
					LEFT JOIN {$this->dbshema}_news_content_type anct ON anct.id = anc.articleType					
					WHERE anc.id IN ({$strcontentid}) AND anc.n_status=1 LIMIT {$limit}";
			
					$qData = $this->apps->fetch($sql,1);
					if($qData){
						foreach($qData as $val){
							$arrContent[] = $val;
						}
					}else $arrContent = false;
					
					if($arrContent){
						
						foreach($arrContent as $key => $val){
							$arrContent[$key]['imagepath'] = false;
								
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$arrContent[$key]['imagepath'] = "event";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$arrContent[$key]['imagepath'] = "banner";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$arrContent[$key]['imagepath'] = "article";	
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$arrContent[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$arrContent[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$arrContent[$key]['image'] = "small_{$val['image']}";	
							
							$video_thumbnail = false;
							if($val['url']!='')	{
								//parser url and get param data
								$parseUrl = parse_url($val['url']);
								if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
								else $parseQuery = false;
								if($parseQuery) {
									if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
								} 
								$arrContent[$key]['video_thumbnail'] = $video_thumbnail;
							}else $arrContent[$key]['video_thumbnail'] = false;		
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $arrContent[$key]['hasfile'] = true;
							else $arrContent[$key]['hasfile'] = false;		
						}
						
						$arrContent = $this->apps->contentHelper->getStatistictArticle($arrContent);
						return $arrContent;
					}else return false;
				}
		return false;
			
			
			
			
	}
	
	
	function getContestSubmission($userid=0,$mypagestype=0,$limit=10){
			
			global $CONFIG;
			if($userid==0) return false;
			$start = intval($this->apps->_request('start'));
			$sql ="
					SELECT contestid FROM my_contest WHERE n_status=  1 AND otherid = {$userid}  AND mypagestype={$mypagestype} LIMIT {$start},{$limit}
					";
		// pr($sql);
				$qData = $this->apps->fetch($sql,1);
				if($qData) {
					$this->logger->log("have contest");
					foreach($qData as $val){
						$contestData[$val['contestid']]=$val['contestid'];
					}

					if(!$contestData) return false;
					$strcontentid = implode(',',$contestData);
					
					//get content
					$sql = "
					SELECT anc.id,anc.title,anc.brief,anc.image,anc.thumbnail_image,anc.slider_image,anc.posted_date,anc.file,anc.url,anc.fromwho,anc.tags,anc.authorid,anc.topcontent,anc.cityid,anct.type pagesname FROM athreesix_news_content anc
					LEFT JOIN {$this->dbshema}_news_content_type anct ON anct.id = anc.articleType					
					WHERE anc.id IN ({$strcontentid}) AND anc.n_status=1 LIMIT {$limit}";
			
					$qData = $this->apps->fetch($sql,1);
					if($qData){
						foreach($qData as $key => $val){
							$qData[$key]['imagepath'] = false;
								
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";	
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							
							$video_thumbnail = false;
							if($val['url']!='')	{
								//parser url and get param data
								$parseUrl = parse_url($val['url']);
								if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
								else $parseQuery = false;
								if($parseQuery) {
									if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
								} 
								$qData[$key]['video_thumbnail'] = $video_thumbnail;
							}else $qData[$key]['video_thumbnail'] = false;	
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $qData[$key]['hasfile'] = true;
							else $qData[$key]['hasfile'] = false;		
							
							$arrContent[] = $qData[$key];
							
						}
					}else $arrContent = false;
					
					if($arrContent){
						$arrContent = $this->apps->contentHelper->getStatistictArticle($arrContent);
						return $arrContent;
					}else return false;
				}
		return false;			
	}
	
	
	function getMyCalendar($userid=0,$mypagestype=0,$limit=10){
			
			global $CONFIG;
			if($userid==0) return false;
			$contestData = false;
			$start = intval($this->apps->_request('start'));
			if($mypagestype>=2) $qFromWho = " AND anc.fromwho = 2 ";
			else $qFromWho = "";
					$sql ="
					SELECT contestid FROM my_contest WHERE n_status=  1 AND otherid = {$userid}  AND mypagestype={$mypagestype} LIMIT {$start},{$limit}
					";
			
				$qData = $this->apps->fetch($sql,1);
				// pr($sql);
					if($qData) {
						$this->logger->log("have contest");
						foreach($qData as $val){
							$contestData[$val['contestid']]=$val['contestid'];
						}
					}
					
					if($contestData){
						$strcontentid = implode(',',$contestData);
						$qContentId = " anc.id IN ({$strcontentid}) OR  ";
					}else $qContentId = "";
					//get content
					$sql = "
					SELECT anc.id,anc.title,anc.brief,anc.image,anc.thumbnail_image,anc.slider_image,anc.posted_date,anc.file,anc.url,anc.fromwho,anc.tags,anc.authorid,anc.topcontent,anc.cityid,anct.type pagesname FROM athreesix_news_content anc
					LEFT JOIN {$this->dbshema}_news_content_type anct ON anct.id = anc.articleType					
					WHERE {$qContentId} authorid={$userid}  AND anc.n_status=1 {$qFromWho} ORDER BY anc.posted_date DESC LIMIT {$limit}";
						// pr($sql);
					$qData = $this->apps->fetch($sql,1);
					
					if($qData){
						foreach($qData as $key => $val){
							$qData[$key]['imagepath'] = false;
								
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";	
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							
							$video_thumbnail = false;
							if($val['url']!='')	{
								//parser url and get param data
								$parseUrl = parse_url($val['url']);
								if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
								else $parseQuery = false;
								if($parseQuery) {
									if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
								} 
								$qData[$key]['video_thumbnail'] = $video_thumbnail;
							}else $qData[$key]['video_thumbnail'] = false;	
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $qData[$key]['hasfile'] = true;
							else $qData[$key]['hasfile'] = false;		
							
							$arrContent[] = $qData[$key];
							
						}
					}else $arrContent = false;
					
					if($arrContent){
						$arrContent = $this->apps->contentHelper->getStatistictArticle($arrContent);
						return $arrContent;
					}else return false;
			
		return false;			
	}
	
	
	function setEditContent(){
		global $CONFIG;
		$cid = intval($this->apps->_p('article_id'));
		$title = strip_tags($this->apps->_p('title'));
		$content = strip_tags($this->apps->_p('description'));
		$tags = strip_tags($this->apps->_p('tags'));
		if($tags){
			$tags = serialize(explode(',',$tags));
		}
		
		$sql = "UPDATE {$this->dbshema}_news_content SET title = \"{$title}\",content = \"{$content}\",tags = '{$tags}' WHERE id = '{$cid}' ";
		if ($this->apps->query($sql)) {
			return true;
		} else return false;
		return false;
	}
	
	
	function wordcut($str=null,$num=1){
			if($str==null) return false;
			
			$arrStr = explode(" ",$str);
			$arrNewStr = false;
			foreach($arrStr as $key => $val){
				if($key<=$num){
					$arrNewStr[] = $val;
				}else break;
			}
			if($arrNewStr==false) return false;
			$str = implode(" ",$arrNewStr);
			return $str;
	
	}
	
	function getProvince()
	{
		$sql = "SELECT * FROM marlborohunt_province_reference WHERE id > 0 ORDER BY province ASC";
		$result = $this->apps->fetch($sql, 1);
		
		if ($result) return $result;
		
		return FALSE;
	}
	
	function getCity($data)
	{
		if (array_key_exists('idProvince', $data)){
			$sql = "SELECT id, city FROM {$this->dbshema}_city_reference WHERE provinceid = {$data['idProvince']} GROUP BY city ASC";
			$result = $this->apps->fetch($sql, 1);
		}else if (array_key_exists('idCity', $data)){
			$sql = "SELECT * FROM {$this->dbshema}_city_reference WHERE id = {$data['idCity']} LIMIT 1";
			$result = $this->apps->fetch($sql);
		}
		
		// pr($sql);
		
		
		if ($result) return $result;
		
		return FALSE;
	}
	
	
	
	function getMobilePrefix()
	{
		$sql = "SELECT * FROM mobile_prefix";
		$res = $this->apps->fetch($sql,1);
		if ($res) return $res;
		return FALSE;
	}
	
	
	
}
?>


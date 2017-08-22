<?php 

class bookmarkHelper {

	var $uid;	
	function __construct($apps){
		global $CONFIG;
		$this->apps = $apps;
		if($this->apps->isUserOnline()) {

				if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
			
		}
		
		
		if(isset($_SESSION['lid'])) $this->lid = intval($_SESSION['lid']);
		else $this->lid = 1;
		$this->server = intval($CONFIG['VIEW_ON']);
		
		
	}
	
	
	function getBookmark($start=0,$limit =4){
	
		$sql ="
		SELECT *,sb.contentid parentid FROM social_bookmark sb WHERE userid={$this->uid} AND n_status = {$this->server}
		ORDER BY date DESC LIMIT {$start},{$limit}";
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;		
		$n=0;
		foreach($qData as $val){
			if( $val['content'] == 0 ) $content[] = $val['contentid'];
			if($val['content'] == 1) $social[] =  $val['contentid'];
			if($val['content'] != 1 && $val['content'] != 0 ) $other[] = $val;
			
			$groupArrContent["{$val['content']}_{$val['contentid']}"]=$n;
			$n++;
		}
	
		if(isset($content)) {
			$cindicate = 0;
			$arrContentStr = implode(",",$content);
			
			$sql ="
			SELECT anc.id,ancc.category,anc.brief,anc.title,anc.image,anc.posted_date ,anc.categoryid, anc.online,anct.type as ctype,anc.parentid,anc.url,anc.articleType
			FROM axis_news_content anc
			LEFT JOIN axis_news_content_category ancc ON ancc.id = anc.categoryid
			LEFT JOIN axis_news_content_type anct ON anct.id = anc.articleType
			WHERE 
			anc.n_status = {$this->server}
			AND anc.parentid IN ({$arrContentStr})
			ORDER BY posted_date DESC LIMIT 30";
			
			$cData = $this->apps->fetch($sql,1);
			
			if($cData) {
					foreach($cData as $key => $val){
						$cidArr[] = $val['parentid'];
						$cData[$key]['content'] = $cindicate;
					}
					if(!$cidArr) return false;
					$cidStr = implode(",",$cidArr);
					$ratingData = $this->apps->contentHelper->getRating($cidStr);
					$commentsData = $this->apps->contentHelper->getComment($cidStr);
				
					if($ratingData){
						
						foreach($cData as $key => $val){
							if(array_key_exists($val['parentid'],$ratingData)) $cData[$key]['rating'] = $ratingData[$val['parentid']];
							else $cData[$key]['rating'] = false;
							
						}
					
					}
					if($commentsData){
						
						foreach($cData as $key => $val){
							if(array_key_exists($val['parentid'],$commentsData)) $cData[$key]['comment'] = count($commentsData[$val['parentid']]);
							else $cData[$key]['comment'] = false;
							
						}
					
					}
					
				foreach($cData as $val){
					
					$newArr[$groupArrContent["{$cindicate}_{$val['parentid']}"]] = $val;
				}
			}
		}
		if(isset($social)) {
			$sindicate = 1;
			$arrSocialStr = implode(",",$social); 
			
			$sql ="
			SELECT snc.id,ancc.category,snc.brief,snc.title,snc.image,snc.posted_date ,snc.categoryid,snc.url,snc.id parentid
			FROM social_news_content snc
			LEFT JOIN axis_news_content_category ancc ON ancc.id = snc.categoryid
			WHERE n_status = 1 
			AND snc.id IN ({$arrSocialStr})
			ORDER BY posted_date DESC LIMIT 30";
			
			$sData = $this->apps->fetch($sql,1);
			if($sData){
				foreach($sData as $key => $val){
						$cidArr[] = $val['parentid'];
						$sData[$key]['content'] = $sindicate;
					}
				if(!$cidArr) return false;
				$cidStr = implode(",",$cidArr);
				
				$ratingData = $this->apps->sharePostHelper->getRating($cidStr);
				$commentsData = $this->apps->sharePostHelper->getComment($cidStr);
				
				if($ratingData){
						
						foreach($sData as $key => $val){
							if(array_key_exists($val['parentid'],$ratingData)) $sData[$key]['rating'] = $ratingData[$val['parentid']];
							else $sData[$key]['rating'] = false;
							
						}
					
				}
				if($commentsData){
						
						foreach($sData as $key => $val){
							if(array_key_exists($val['parentid'],$commentsData)) $sData[$key]['comment'] = count($commentsData[$val['parentid']]);
							else $sData[$key]['comment'] = false;
							
						}
					
					}
				foreach($sData as $val){
					$newArr[$groupArrContent["{$sindicate}_{$val['parentid']}"]] = $val;
				}
								
			}
		}
		if(isset($other)){
			foreach($other as $key => $val){
					$other[$key]['content'] = 2;
					$newArr[$groupArrContent["{$val['content']}_{$val['contentid']}"]] = $val;
				}
		}
		if(!$newArr) return false;
		
		sort($newArr);
			
		return $newArr;
	}
	
	
	
	function saveBookmark(){
		
		if(!$this->uid) return false;
		$content = intval($this->apps->Request->getParam('content'));
		if($content==null) $content=0;
		$url = strip_tags($this->apps->_p('url_fav'));
		if($url) {$content = 2;} 
		$contentid = intval($this->apps->Request->getParam('id'));
		if($contentid==0) return false;
		$sql =" INSERT INTO social_bookmark 
		(userid,url,content,contentid,date,n_status) 
		VALUES 
		({$this->uid},'{$url}',{$content},{$contentid},NOW(),1)
		ON DUPLICATE KEY UPDATE n_status=1
		";
		$this->apps->query($sql);
		// pr($sql);exit;
		if($this->apps->getLastInsertId())	return true;
		else return false;
		
		
	}
	
	function deleteBookmark(){
		if(!$this->uid) return false;
		$content = intval($this->apps->Request->getParam('content'));
		if($content==null) $content=0;
		$contentid = intval($this->apps->Request->getParam('id'));
		if($contentid==0) return false;	
		$sql =" UPDATE social_bookmark set n_status=3
		WHERE contentid IN ({$contentid})
		AND userid ={$this->uid}
		AND content={$content} LIMIT 1
		";

		$data = $this->apps->query($sql);
		
		if($data) return true;
		else return false;
	}
	
			
}	

?>


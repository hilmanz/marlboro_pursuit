<?php
class activities extends App{
	
	
	function beforeFilter(){
	
		$this->webActivityHelper = $this->useHelper("webActivityHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
		$this->assign('basedomainpath',$CONFIG['BASE_DOMAIN_PATH']);
		
		$this->assign('locale', $LOCALE[1]);
	
	}
	
	function main(){
		// pr($_GET);
		$top10UserActivity = $this->webActivityHelper->top10UserActivity();
	
		$topUserActLike = $this->webActivityHelper->topUserActLike();
		$topUserActComment = $this->webActivityHelper->topUserActComment();
		$topUserActUpload = $this->webActivityHelper->topUserActUpload();
		$topUserActDownload = $this->webActivityHelper->topUserActDownload();
		
		
		$topViewMostTime = $this->webActivityHelper->topViewMostTime();
		$topTenSuperUser = $this->webActivityHelper->topTenSuperUser();
		$topTenUserWeekly = $this->webActivityHelper->topTenUserWeekly();
		$topTenBasedNumFriend = $this->webActivityHelper->topTenBasedNumFriend();
		
		$topTenVisitedPage = $this->webActivityHelper->topTenVisitedPage();
		$topThreeVisitMusic = $this->webActivityHelper->topThreeVisitMusic();
		$topThreeVisitDJ = $this->webActivityHelper->topThreeVisitDJ();
		$topThreeVisitVisualart = $this->webActivityHelper->topThreeVisitVisualart();
		$topThreeVisitFashion = $this->webActivityHelper->topThreeVisitFashion();
		$topThreeVisitPhotography = $this->webActivityHelper->topThreeVisitPhotography();

		
		$topFiveLiked = $this->webActivityHelper->topFiveLiked();
		$topFiveCommented = $this->webActivityHelper->topFiveCommented();
		$topContent = $this->webActivityHelper->topContent();
		$basedOnKeyWord = $this->webActivityHelper->basedOnKeyWord();
		$basedOnContent = $this->webActivityHelper->basedOnContent();
		

		$mostViewedArtistMusic = $this->webActivityHelper->mostViewedArtist('music');
		$mostViewedArtistDj = $this->webActivityHelper->mostViewedArtist('dj');
		$mostViewedArtistVisual = $this->webActivityHelper->mostViewedArtist('visual');
		$mostViewedArtistStyle = $this->webActivityHelper->mostViewedArtist('style');
		$mostViewedArtistPhoto = $this->webActivityHelper->mostViewedArtist('photography');
				// pr($basedOnContent);

		/* -------------------------------------------------------------------- */
		
		$this->assign("top10UserActivity",$top10UserActivity);
		
		$this->assign("topUserActLike",$topUserActLike);
		$this->assign("topUserActComment",$topUserActComment);
		$this->assign("topUserActUpload",$topUserActUpload);
		$this->assign("topUserActDownload",$topUserActDownload);
		
		$this->assign("topViewMostTime",$topViewMostTime);
		$this->assign("topTenSuperUser",$topTenSuperUser);
		$this->assign("topTenUserWeekly",$topTenUserWeekly);
		$this->assign("topTenBasedNumFriend",$topTenBasedNumFriend);
		
		$this->assign("topTenVisitedPage",$topTenVisitedPage);
		$this->assign("topThreeVisitMusic",$topThreeVisitMusic);
		$this->assign("topThreeVisitDJ",$topThreeVisitDJ);
		$this->assign("topThreeVisitVisualart",$topThreeVisitVisualart);
		$this->assign("topThreeVisitFashion",$topThreeVisitFashion);
		$this->assign("topThreeVisitPhotography",$topThreeVisitPhotography);
		

		
		$this->assign("topFiveLiked",$topFiveLiked);
		$this->assign("topFiveCommented",$topFiveCommented);
		$this->assign("topContent",$topContent);
		$this->assign("basedOnKeyWord",$basedOnKeyWord);
		$this->assign("basedOnContent",$basedOnContent);
		

		$this->assign("mostViewedArtistMusic",$mostViewedArtistMusic);
		$this->assign("mostViewedArtistDj",$mostViewedArtistDj);
		$this->assign("mostViewedArtistVisual",$mostViewedArtistVisual);
		$this->assign("mostViewedArtistStyle",$mostViewedArtistStyle);
		$this->assign("mostViewedArtistPhoto",$mostViewedArtistPhoto);
		
		$week = @($_GET['week'] / 7);
		$this->assign("week",$week);
		
		
		

		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/activities.html');
		
	}
	
}
?>
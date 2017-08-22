<?php
class analytic extends App{
	
	
	function beforeFilter(){
		
		$this->googleAnalyticHelper = $this->useHelper("googleAnalyticHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
		
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
	
		
	}
	function main(){
		
		$gaData = $this->googleAnalyticHelper->gaData();
		// pr($gaData);
		// $gaDataChart = $this->googleAnalyticHelper->gaDataChart();
		$data = $this->googleAnalyticHelper->gaDataChart();
		
		$this->assign("gaData",$gaData);
		// $this->assign("gaDataChart",json_encode($gaDataChart)); 
		$this->assign("dataChart",$data);
		
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/analytic.html');
		
	}
	
}
?>
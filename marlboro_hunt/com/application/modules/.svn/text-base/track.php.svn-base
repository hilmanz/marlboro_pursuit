<?php
class track extends App{
		
	function beforeFilter(){
		$this->mopHelper = $this->useHelper('mopHelper');
	}
	
	function main(){
		$data['page'] = strip_tags(rtrim(ltrim($this->_p('onpage'))));
		$data['act'] =  strip_tags(rtrim(ltrim($this->_p('onact'))));
		// print json_encode($this->mopHelper->sendActivityToMop($data));
		exit;
	}
	
}
?>
<?php 

class articleHelper {

	function __construct($apps){
		$this->apps = $apps;
		$this->page  = strip_tags($this->apps->_p('page'));
	}
	
	function getarticle(){
		$sql =" SELECT * FROM athreesix_news_content WHERE articleType='1' LIMIT 1 ";
		$qData = $this->apps->fetch($sql);
		if($qData) return $qData;
		return false;
	}
	
	function getbanner(){
			$sql =" SELECT * FROM athreesix_news_content WHERE articleType='2' LIMIT 1 ";
		$qData = $this->fetch($sql);
		if($qData) return $qData;
		return false;
	}
	
	
	}
?>


class apps
	
	function fetch($sql){
		return $sqldata
	}
	
	function query
	
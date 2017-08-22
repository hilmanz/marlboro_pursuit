<?php
class news extends App{
	
	
	function beforeFilter(){
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->searchHelper = $this->useHelper('searchHelper');
	
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
		$this->assign('pages', strip_tags($this->_g('page')));
	}
	
	function main(){
		
		//pr($_GET);
		$this->View->assign('start',0);
		$this->View->assign('news_detail',$this->setWidgets('news_detail'));
		$this->View->assign('side_banner',$this->setWidgets('side_banner'));
		$this->View->assign('latest_news',$this->setWidgets('latest_news'));
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/news-pages.html');
		
	}
	
	function detail(){
	
	/*
		$contentid = intval($this->_p('id'));
		if($contentid!=0){
			$this->setWidgets('popup_article_detail');
			exit;	
		}
		$this->View->assign('news_detail',$this->setWidgets('news_detail'));
		$this->View->assign('side_banner',$this->setWidgets('side_banner'));
		$this->View->assign('latest_news',$this->setWidgets('latest_news'));
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/news-pages.html');
		*/
		
		$this->View->assign('news_detail',$this->setWidgets('news_detail'));
		$this->View->assign('side_banner',$this->setWidgets('side_banner'));
		$this->View->assign('latest_news',$this->setWidgets('latest_news'));
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/news-pages.html');
	}
	
	function ajax(){
	
		/*
		$needs = $this->_request("needs");
		$contentid = intval($this->_request("contentid"));
		$start = intval($this->_p("start"));
		if($needs=="latest")  $data =  $this->contentHelper->getArticleContent($start,5);	
		if($needs=="content") {
			$rsdata = $this->contentHelper->getDetailArticle($start,1,1);
			$data['result'] = $rsdata['result'][0];
			$data['total'] = intval($rsdata['total']);
		}
		
		print json_encode($data);exit;
		*/
		$start = intval($this->_p("start"));
		$dataperpage = 10;
		$data =  $this->contentHelper->getNewsbyAjax($start,$dataperpage);
		print json_encode($data);exit;
	}
	
	
	
}
?>
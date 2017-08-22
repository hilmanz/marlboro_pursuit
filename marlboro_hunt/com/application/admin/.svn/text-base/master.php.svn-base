<?php
	
global $ENGINE_PATH;
global $CONFIG;
include_once $ENGINE_PATH."Utility/Paginate.php";
	
class master extends Admin{
	function __construct(){
		parent::__construct();		
		$this->dbclass = 'marlborohunt';
	}
	
	function admin(){
		$act = strip_tags($this->Request->getParam('act'));

		if($act){
			return $this->$act();
		} else {
			return $this->View->toString("application/admin/master/default.html");
		}
	}

	function list_type(){
		$start = intval($this->Request->getParam('st'));
		$total_per_page = 20;
		$sql = "SELECT * FROM {$this->dbclass}_news_content_type ORDER BY id LIMIT {$start},{$total_per_page}";
		$totalSql = "SELECT count(*) total FROM {$this->dbclass}_news_content_type";
		$this->open(0);
		$list = $this->fetch($sql,1);
		$tot_type = $this->fetch($totalSql);
		$this->close();
		$total = $tot_type['total'];
		
		$no=1+$start;		
		foreach($list as $k => $v){
			$v['no'] = $no++;
			$data[] = $v;
		}
		
		$this->View->assign('list',$data);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=master&act=list_type"));	
		return $this->View->toString("application/admin/master/list_type.html");
	}
	
	function add_type_news(){
		$type 	= $this->Request->getParam('type');
		if($this->Request->getParam('cmd') == 'add'){
			if( $type != ''){
				$qry = "INSERT INTO {$this->dbclass}_news_content_type (type) VALUES ('{$type}')";
				if(!$this->query($qry)){
					$this->View->assign("msg","Add process failure");
					return $this->View->toString("application/admin/master/new_type_News.html");
				}else{
					return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_type");
				}
			}else{
				$this->View->assign('msg','Error, please fill all field!');
			}
		}
		return $this->View->toString("application/admin/master/new_type_News.html");
	}
	
	function edit_type_news(){
		$id 	= $this->Request->getParam('id');
		$type 	= $this->Request->getParam('type');
		
		if( $id > 0 && $type != ''){
			$qry = "UPDATE {$this->dbclass}_news_content_type SET type='{$type}' WHERE id={$id}";
			if($this->query($qry)){
				return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_type");
			}else{
				$this->View->assign('msg','Error, please fill all field!');
			}			
		}else{
			$this->View->assign('msg','Error, please fill all field!');
		}
		
		$list_type = $this->fetch("SELECT * FROM {$this->dbclass}_news_content_type WHERE id={$id}");
		if( is_array($list_type) ){
			$this->View->assign("id_type",$list_type['id']);
			$this->View->assign("type_val",$list_type['type']);
			return $this->View->toString("application/admin/master/edit_type_News.html");
		}else{
			return $this->View->showMessage('Invalid id', "index.php?s=master&act=edit_type_news");
		}
		return $this->View->toString("application/admin/master/edit_type_News.html");
	}
	
	function delete_type_news(){
		$id = $this->Request->getParam('id');
		if( !$this->query("DELETE FROM {$this->dbclass}_news_content_type WHERE id={$id}")){
			return $this->View->showMessage('Gagal', "index.php?s=master&act=list_type");
		}else{
			return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_type");
		}
	}
	
	function list_category(){
		$start = intval($this->Request->getParam('st'));
		$total_per_page = 20;
		$sql = "SELECT * FROM {$this->dbclass}_news_content_category ORDER BY id LIMIT {$start},{$total_per_page}";
		$totalSql = "SELECT count(*) total FROM {$this->dbclass}_news_content_category";
		$this->open(0);
		$list = $this->fetch($sql,1);		
		$tot_category = $this->fetch($totalSql);
		$this->close();
		$total = $tot_category['total'];
		
		$no=1+$start;
		foreach($list as $k => $v){
			$v['no'] = $no++;
			$v['naming'] = unserialize($v['naming']);
			$data[] = $v;
		}
		
		$this->View->assign('list',$data);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=master&act=list_category"));	
		return $this->View->toString("application/admin/master/list_category.html");
	}
	
	function add_category_news(){
		$category 	= $this->Request->getParam('category');
		$naming 	=  serialize($_GET['naming']);
		$point 	= $this->Request->getParam('point');
		if($this->Request->getParam('cmd') == 'add'){
			if( $category != ''){
				$qry = "INSERT INTO {$this->dbclass}_news_content_category (category,naming,point) VALUES ('{$category}','{$naming}','{$point}')";
				if(!$this->query($qry)){
					$this->View->assign("msg","Add process failure");
					return $this->View->toString("application/admin/master/new_category_News.html");
				}else{
					return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_category");
				}
			}else{
				$this->View->assign('msg','Error, please fill all field!');
			}
		}
		return $this->View->toString("application/admin/master/new_category_News.html");
	}
	
	function edit_category_news(){
		$id 		= $this->Request->getParam('id');
		$category 	= $this->Request->getParam('category');
		$naming 	=  serialize($_GET['naming']);
		$point 		= $this->Request->getParam('point');
		
		if( $id > 0 && $category != '' && $naming != ''){
			$qry = "UPDATE {$this->dbclass}_news_content_category SET category='{$category}',naming='{$naming}',point='{$point}' WHERE id={$id}";
			if($this->query($qry)){
				return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_category");
			}else{
				$this->View->assign('msg','Error, please fill all field!');
			}
		}else{
			$this->View->assign('msg','Error, please fill all field!');
		}
		
		$list_category = $this->fetch("SELECT * FROM {$this->dbclass}_news_content_category WHERE id={$id}");
		if(is_array($list_category)){
			$this->View->assign("id_cat",$list_category['id']);
			$this->View->assign("cat_val",$list_category['category']);
			$this->View->assign("naming",unserialize($list_category['naming']));
			$this->View->assign("point",$list_category['point']);
			return $this->View->toString("application/admin/master/edit_category_News.html");
		} else {
			return $this->View->showMessage('Invalid id', "index.php?s=master&act=edit_category_news");
		}
		return $this->View->toString("application/admin/master/edit_category_News.html");
	}
	
	function delete_category_news(){
		$id = $this->Request->getParam('id');
		if( !$this->query("DELETE FROM {$this->dbclass}_news_content_category WHERE id={$id}")){
			return $this->View->showMessage('Gagal', "index.php?s=master&act=list_category");
		}else{
			return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_category");
		}
	}
	
	function list_page(){
		$start = intval($this->Request->getParam('st'));
		$total_per_page = 20;
		$sql = "SELECT * FROM {$this->dbclass}_news_content_page ORDER BY id LIMIT {$start},{$total_per_page}";
		$totalSql = "SELECT count(*) total FROM {$this->dbclass}_news_content_page";
		// pr($sql);
		$this->open(0);
		$list = $this->fetch($sql,1);
		$tot_category = $this->fetch($totalSql);
		$this->close();
		$total = $tot_category['total'];
		
		$no=1+$start;		
		foreach($list as $k => $v){
			$v['no'] = $no++;
			$data[] = $v;
		}
		
		$this->View->assign('list',$data);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=master&act=list_page"));	
		return $this->View->toString("application/admin/master/list_page.html");
	}
	
	function add_page_news(){
		$pageName 	= $this->Request->getParam('pagename');
		$pagetitle 	= $this->Request->getParam('pagetitle');
		$n_status 	= $this->Request->getParam('n_status');
		
		if($this->Request->getParam('cmd') == 'add'){
			if( $pageName != ''){
				$qry = "INSERT INTO {$this->dbclass}_news_content_page (pagename,pagetitle,n_status) VALUES ('{$pageName}','{$pagetitle}','{$n_status}')";
				if(!$this->query($qry)){
					$this->View->assign("msg","Add process failure");
					return $this->View->toString("application/admin/master/new_page.html");
				}else{
					return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_page");
				}
			}else{
				$this->View->assign('msg','Error, please fill all field!');
			}
		}
		return $this->View->toString("application/admin/master/new_page.html");
	}
	
	function edit_page_news(){
		$id 		= $this->Request->getParam('id');
		$pagename 	= $this->Request->getParam('pagename');
		$pagetitle 	= $this->Request->getParam('pagetitle');
		$status 	= $this->Request->getParam('n_status');
		
		if( $id > 0 && $pagename != ''){
			$qry = "UPDATE {$this->dbclass}_news_content_page SET pagename='{$pagename}',pagetitle='{$pagetitle}',n_status='{$status}' WHERE id={$id}";
			if($this->query($qry)){
				return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_page");
			}else{
				$this->View->assign('msg','Error, please fill all field!');
			}
		}else{
			$this->View->assign('msg','Error, please fill all field!');
		}
		
		$list_page = $this->fetch("SELECT * FROM {$this->dbclass}_news_content_page WHERE id={$id}");
		if( is_array($list_page) ){
			$this->View->assign("id_page",$list_page['id']);
			$this->View->assign("pagename",$list_page['pagename']);
			$this->View->assign("pagetitle",$list_page['pagetitle']);
			$this->View->assign("status",$list_page['n_status']);
			return $this->View->toString("application/admin/master/edit_page.html");
		}else{
			return $this->View->showMessage('Invalid id', "index.php?s=master&act=edit_page_news");
		}
		return $this->View->toString("application/admin/master/edit_page.html");
	}
	
	function hapus_page_news(){
		$id = $this->Request->getParam('id');
		if( !$this->query("DELETE FROM {$this->dbclass}_news_content_page WHERE id={$id}")){
			return $this->View->showMessage('Gagal', "index.php?s=master&act=list_page");
		}else{
			return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_page");
		}
	}
	
	function list_device(){
		$start = intval($this->Request->getParam('st'));
		$total_per_page = 20;
		$sql = "SELECT * FROM {$this->dbclass}_news_device_type ORDER BY id LIMIT {$start},{$total_per_page}";
		$totalSql = "SELECT count(*) total FROM {$this->dbclass}_news_device_type";
		$this->open(0);
		$list = $this->fetch($sql,1);
		$tot_category = $this->fetch($totalSql);
		$this->close();
		$total = $tot_category['total'];
		
		$no=1+$start;		
		foreach($list as $k => $v){
			$v['no'] = $no++;
			$data[] = $v;
		}
		
		$this->View->assign('list',$data);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=master&act=list_device"));	
		return $this->View->toString("application/admin/master/list_device.html");
	}
	
	function add_device(){
		$type 	= $this->Request->getParam('type');
		
		if($this->Request->getParam('cmd') == 'add'){
			if( $type != ''){
				$qry = "INSERT INTO {$this->dbclass}_news_device_type (type) VALUES ('{$type}')";
				if(!$this->query($qry)){
					$this->View->assign("msg","Add process failure");
					return $this->View->toString("application/admin/master/new_device.html");
				}else{
					return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_device");
				}
			}else{
				$this->View->assign('msg','Error, please fill all field!');
			}
		}
		return $this->View->toString("application/admin/master/new_device.html");
	}
	
	function edit_device(){
		$id 		= $this->Request->getParam('id');
		$id_device 	= $this->Request->getParam('id_device');
		$type 		= $this->Request->getParam('type');

		if ($id!=$id_device) {
			if($id_device=='') {$id;} else {$id;}
		} else {
			$id;
		}
		
		if($type != ''){
			$qry = "UPDATE {$this->dbclass}_news_device_type SET id='{$id_device}',type='{$type}' WHERE id={$id}";
			if($this->query($qry)){
				return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_device");
			}else{
				$this->View->assign('msg','Error, please fill all field!');
			}
		}else{
			$this->View->assign('msg','Error, please fill all field!');
		}
		
		$list_device = $this->fetch("SELECT * FROM {$this->dbclass}_news_device_type WHERE id={$id}");
		if( is_array($list_device) ){
			$this->View->assign("id_device",$list_device['id']);
			$this->View->assign("type",$list_device['type']);
			return $this->View->toString("application/admin/master/edit_device.html");
		}else{
			return $this->View->showMessage('Invalid id', "index.php?s=master&act=edit_device");
		}
		return $this->View->toString("application/admin/master/edit_device.html");
	}
	
	function delete_device(){
		$id = $this->Request->getParam('id');
		if( !$this->query("DELETE FROM {$this->dbclass}_news_device_type WHERE id={$id}")){
			return $this->View->showMessage('Gagal', "index.php?s=master&act=list_device");
		}else{
			return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_device");
		}
	}
	
	function list_province(){
		$search = $this->Request->getParam('search');		
		$filter = $search!='' ? " WHERE province LIKE '%{$search}%' OR province_en LIKE '%{$search}%' " : ""; 
		$start = intval($this->Request->getParam('st'));
		$total_per_page = 20;
		$sql = "SELECT * FROM {$this->dbclass}_province_reference {$filter} ORDER BY province LIMIT {$start},{$total_per_page}";
		$totalSql = "SELECT count(*) total FROM {$this->dbclass}_province_reference {$filter}";
		$this->open(0);
		$list = $this->fetch($sql,1);
		$tot_province = $this->fetch($totalSql);
		$this->close();
		$total = $tot_province['total'];
		$no=1+$start;		
		foreach($list as $k => $v){
			$v['no'] = $no++;
			$data[] = $v;
		}
		
		$this->View->assign('list',$data);
		$this->View->assign('search',$search);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=master&act=list_province&search={$search}"));	
		return $this->View->toString("application/admin/master/list_province.html");
	}
	
	function add_province(){
		global $CONFIG;
		$province 	= strtoupper($this->Request->getPost('province'));
		$province_en = strtoupper($this->Request->getPost('province_en'));
		$n 			= $this->Request->getPost('nn');
		$s 			= $this->Request->getPost('ss');
		$e 			= $this->Request->getPost('ee');
		$w 			= $this->Request->getPost('ww');
		$cover 		= $this->Request->getPost('cover');
		
		if($this->Request->getPost('cmd') == 'add'){
			if( $province != ''){
				$qry = "INSERT INTO {$this->dbclass}_province_reference (province,province_en,n,s,e,w,cover) VALUES ('{$province}','{$province_en}','{$n}','{$s}','{$e}','{$w}','{$cover}')";
				if(!$this->query($qry)){
					$this->View->assign("msg","Add process failure");
					return $this->View->toString("application/admin/master/new_province.html");
				}else{
					$last_id = $this->getLastInsertId();
					$img = md5($province.rand(1000,9999));
					if ($_FILES['image_3g']['name']!=NULL) {
						include_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
						list($file_name,$ext) = explode('.',$_FILES['image_3g']['name']);
						$img_3g = $img."_3g".".".$ext;
						try{
							$thumb = PhpThumbFactory::create( $_FILES['image_3g']['tmp_name']);
						}catch (Exception $e){
							// handle error here however you'd like
						}
						
						if(move_uploaded_file($_FILES['image_3g']['tmp_name'],"{$CONFIG['LOCAL_PUBLIC_ASSET']}coverage/maps/".$img_3g)){
							list($width, $height, $type, $attr) = getimagesize("{$CONFIG['LOCAL_PUBLIC_ASSET']}coverage/maps/{$img_3g}");
							
							//resize the image
							$thumb->adaptiveResize($width,$height);
							$big = $thumb->save( "{$CONFIG['LOCAL_PUBLIC_ASSET']}coverage/maps/".$img_3g);
						}
						$this->inputImage($last_id,$img);
					}
					if ($_FILES['image_edge']['name']!=NULL) {
						include_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
						list($file_name,$ext) = explode('.',$_FILES['image_edge']['name']);
						$img_edge = $img."_edge".".".$ext;
						try{
							$thumb = PhpThumbFactory::create( $_FILES['image_edge']['tmp_name']);
						}catch (Exception $e){
							// handle error here however you'd like
						}
						
						if(move_uploaded_file($_FILES['image_edge']['tmp_name'],"{$CONFIG['LOCAL_PUBLIC_ASSET']}coverage/maps/".$img_edge)){
							list($width, $height, $type, $attr) = getimagesize("{$CONFIG['LOCAL_PUBLIC_ASSET']}coverage/maps/{$img_edge}");
							
							//resize the image
							$thumb->adaptiveResize($width,$height);
							$big = $thumb->save( "{$CONFIG['LOCAL_PUBLIC_ASSET']}coverage/maps/".$img_edge);
						}
						$this->inputImage($last_id,$img);
					}
					return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_province");
				}
			}else{
				$this->View->assign('msg','Error, please fill all field!');
			}
		}
		return $this->View->toString("application/admin/master/new_province.html");
	}
	
	function edit_province(){
		global $CONFIG;
		$id 		= $this->Request->getParam('id');
		$province 	= $this->Request->getPost('province');
		$province_en = $this->Request->getPost('province_en');
		$n 			= $this->Request->getPost('nn');
		$s 			= $this->Request->getPost('ss');
		$e 			= $this->Request->getPost('ee');
		$w 			= $this->Request->getPost('ww');
		$cover 		= $this->Request->getPost('cover');
		
		if( $id > 0 && $province != ''){
			$qry = "UPDATE {$this->dbclass}_province_reference SET province_en='{$province_en}', province='{$province}',n='{$n}',s='{$s}',e='{$e}',w='{$w}',cover='{$cover}' WHERE id={$id}";
			
			if($this->query($qry)){
				$img = md5($province.rand(1000,9999));
				$image = $this->fetch("SELECT image FROM {$this->dbclass}_province_reference WHERE id={$id}");
				if ($_FILES['image_3g']['name']!=NULL) {
					if ($image['image']=='') {
						$img;
					} else {
						$img = $image['image'];
					}
					include_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
					list($file_name,$ext) = explode('.',$_FILES['image_3g']['name']);
					$img_3g = $img."_3g".".".$ext;
					try{
						$thumb = PhpThumbFactory::create( $_FILES['image_3g']['tmp_name']);
					}catch (Exception $e){
						// handle error here however you'd like
					}
					
					if(move_uploaded_file($_FILES['image_3g']['tmp_name'],"{$CONFIG['LOCAL_PUBLIC_ASSET']}coverage/maps/".$img_3g)){
						list($width, $height, $type, $attr) = getimagesize("{$CONFIG['LOCAL_PUBLIC_ASSET']}coverage/maps/{$img_3g}");
						
						//resize the image
						$thumb->adaptiveResize($width,$height);
						$big = $thumb->save( "{$CONFIG['LOCAL_PUBLIC_ASSET']}coverage/maps/".$img_3g);
					}
					$this->inputImage($id,$img);
				}
				if ($_FILES['image_edge']['name']!=NULL) {
					if ($image['image']=='' || $_FILES['image_3g']['name']!=NULL) {
						$img;
					} else {
						$img = $image['image'];
					}
					include_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
					list($file_name,$ext) = explode('.',$_FILES['image_edge']['name']);
					$img_edge = $img."_edge".".".$ext;
					try{
						$thumb = PhpThumbFactory::create( $_FILES['image_edge']['tmp_name']);
					}catch (Exception $e){
						// handle error here however you'd like
					}
					
					if(move_uploaded_file($_FILES['image_edge']['tmp_name'],"{$CONFIG['LOCAL_PUBLIC_ASSET']}coverage/maps/".$img_edge)){
						list($width, $height, $type, $attr) = getimagesize("{$CONFIG['LOCAL_PUBLIC_ASSET']}coverage/maps/{$img_edge}");
						
						//resize the image
						$thumb->adaptiveResize($width,$height);
						$big = $thumb->save( "{$CONFIG['LOCAL_PUBLIC_ASSET']}coverage/maps/".$img_edge);
					}
					$this->inputImage($id,$img);
				}
				return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_province");
			}else{
				$this->View->assign('msg','Error, please fill all field!');
			}
		}else{
			$this->View->assign('msg','Error, please fill all field!');
		}
		
		$list_province = $this->fetch("SELECT * FROM {$this->dbclass}_province_reference WHERE id={$id}");
		if( is_array($list_province) ){
			$this->View->assign("id_province",$list_province['id']);
			$this->View->assign("province",$list_province['province']);
			$this->View->assign("province_en",$list_province['province_en']);
			$this->View->assign("n",$list_province['n']);
			$this->View->assign("s",$list_province['s']);
			$this->View->assign("e",$list_province['e']);
			$this->View->assign("w",$list_province['w']);
			$this->View->assign("cover",$list_province['cover']);
			return $this->View->toString("application/admin/master/edit_province.html");
		}else{
			return $this->View->showMessage('Invalid id', "index.php?s=master&act=edit_province");
		}
		return $this->View->toString("application/admin/master/edit_province.html");
	}
	
	function hapus_province(){
		$id = $this->Request->getParam('id');
		if( !$this->query("DELETE FROM {$this->dbclass}_province_reference WHERE id={$id}")){
			return $this->View->showMessage('Gagal', "index.php?s=master&act=list_province");
		}else{
			return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_province");
		}
	}	
	
	function list_city(){
		$search = $this->Request->getParam('search');		
		$filter = $search!='' ? " WHERE provinceName LIKE '%{$search}%' OR city LIKE '%{$search}%' OR city_en LIKE '%{$search}%' " : ""; 
		$start = intval($this->Request->getParam('st'));
		$total_per_page = 20;
		$sql = "SELECT * FROM {$this->dbclass}_city_reference {$filter} ORDER BY provinceName LIMIT {$start},{$total_per_page}";
		$totalSql = "SELECT count(*) total FROM {$this->dbclass}_city_reference {$filter}";
		$this->open(0);
		$list = $this->fetch($sql,1);
		$tot_city = $this->fetch($totalSql);
		$this->close();
		$total = $tot_city['total'];
		$no=1+$start;		
		foreach($list as $k => $v){
			$v['no'] = $no++;
			$data[] = $v;
		}
		
		$this->View->assign('list',$data);
		$this->View->assign('search',$search);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=master&act=list_city&search={$search}"));	
		return $this->View->toString("application/admin/master/list_city.html");
	}
	
	function add_city(){
		$city 		= strtoupper($this->Request->getParam('city'));
		$city_en 		= strtoupper($this->Request->getParam('city_en'));
		$provinceid = $this->Request->getParam('provinceid');
		
		$province = $this->getProvince();
		$this->View->assign('province',$province);
		$provinceName = $this->fetch("SELECT province FROM {$this->dbclass}_province_reference WHERE id={$provinceid}");
		
		if($this->Request->getParam('cmd') == 'add'){
			if( $city != ''){
				$qry = "INSERT INTO {$this->dbclass}_city_reference (provinceName,provinceid,city,city_en) VALUES ('{$provinceName['province']}','{$provinceid}','{$city}','{$city_en}')";
				if(!$this->query($qry)){
					$this->View->assign("msg","Add process failure");
					return $this->View->toString("application/admin/master/new_city.html");
				}else{
					return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_city");
				}
			}else{
				$this->View->assign('msg','Error, please fill all field!');
			}
		}
		return $this->View->toString("application/admin/master/new_city.html");
	}
	
	function edit_city(){
		$id 		= $this->Request->getParam('id');
		$city 		= strtoupper($this->Request->getParam('city'));
		$city_en 	= strtoupper($this->Request->getParam('city_en'));
		$provinceid = $this->Request->getParam('provinceid');
		
		$province = $this->getProvince();
		$this->View->assign('province',$province);
		$provinceName = $this->fetch("SELECT province FROM {$this->dbclass}_province_reference WHERE id={$provinceid}");
		
		if( $id > 0 && $city != ''){
			$qry = "UPDATE {$this->dbclass}_city_reference SET provinceName='{$provinceName['province']}',provinceid='{$provinceid}',city='{$city}',city_en='{$city_en}' WHERE id={$id}";
			if($this->query($qry)){
				return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_city");
			}else{				
				$this->View->assign('msg','Error, please fill all field!');
			}
		}else{
			$this->View->assign('msg','Error, please fill all field!');
		}
		
		$list_city = $this->fetch("SELECT * FROM {$this->dbclass}_city_reference WHERE id={$id}");
		if( is_array($list_city) ){
			$this->View->assign("id_city",$list_city['id']);
			$this->View->assign("provinceid",$list_city['provinceid']);
			$this->View->assign("city",$list_city['city']);
			$this->View->assign("city_en",$list_city['city_en']);
			return $this->View->toString("application/admin/master/edit_city.html");
		}else{
			return $this->View->showMessage('Invalid id', "index.php?s=master&act=edit_city");
		}
		return $this->View->toString("application/admin/master/edit_city.html");
	}
	
	function hapus_city(){
		$id = $this->Request->getParam('id');
		if( !$this->query("DELETE FROM {$this->dbclass}_city_reference WHERE id={$id}")){
			return $this->View->showMessage('Gagal', "index.php?s=master&act=list_city");
		}else{
			return $this->View->showMessage('Berhasil', "index.php?s=master&act=list_city");
		}
	}
	
	function getProvince(){
		$province = $this->fetch("SELECT * FROM {$this->dbclass}_province_reference ORDER BY province",1);
		return $province;
	}
	
	function inputImage($id,$img,$cover){
		$this->query("UPDATE {$this->dbclass}_province_reference SET image='{$img}' WHERE id={$id};");
	}
}
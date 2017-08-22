<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class inBound extends Admin{
	var $category;
	var $type;
	function __construct(){	
		parent::__construct();	
		
		$this->type = "1,3,4,5,6";
		$this->contentType = "0";
		$this->folder =  'inBound';
		$this->dbclass = 'marlborohunt';
		$this->fromwho = 0; // 0 is admin/backend
		$this->total_per_page = 20;
		
	}
	
	function admin(){
		
		global $CONFIG;
	
		//get admin role
		foreach($this->roler as $key => $val){
		$this->View->assign($key,$val);
		}
		//get specified admin role if true
		if($this->specified_role){
			foreach($this->specified_role as $val){
				$type[] = $val['type'];
				$category[] = $val['category'];
			}
			if($type) $this->type = implode(',',$type);
			else return false;
			if($category) $this->category = implode(',',$category);
			else return false;
		}
		//helper
		$this->typelist = $this->getTypeList();
		// $this->contributor = $this->getContributor();
		// $this->View->assign('contributor',$this->contributor);
		$this->View->assign('typelist',$this->typelist);
		$this->View->assign('folder',$this->folder);
		
		$this->View->assign('baseurl',$CONFIG['BASE_DOMAIN_PATH']);
		$act = $this->_g('act');
		if($act){
			return $this->$act();
		} else {
			return $this->home();
		}
	}

	function home(){
		
		$filter = "";
		$startdate = false;
		$enddate = false;
		$start = intval($this->_g('st'));
		
		/* Hitung banyak record data */
		$sql ="
			SELECT count(*) total
			FROM archlight_inbound
			WHERE n_status<>3
			{$filter}";
		$totalList = $this->fetch($sql);	
		// pr($totalList);
		if($totalList){
		$total = intval($totalList['total']);
		}else $total = 0;
		
		/* list article */
		$sql = "
			SELECT *
			FROM archlight_inbound
			WHERE n_status<>3
			{$filter}
			ORDER BY datetime DESC 
			LIMIT {$start},{$this->total_per_page}
		";
	
		$list = $this->fetch($sql,1);
		
		if($list){				
			$n=$start+1;
			foreach($list as $key => $val){
					$list[$key]['no'] = $n++;				
			}			
		}
	
		
		$this->View->assign('list',$list);

		$this->Paging = new Paginate();
	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&startdate={$startdate}&enddate={$enddate}"));	
	// pr("application/admin/{$this->folder}/{$this->folder}_list.html");
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
	
	
	function create(){	
		
	
	

		$sql = "
		SELECT * FROM social_member  sm		
		WHERE n_status = 1 AND usertype=0 AND verified = 1 ";
		$list = $this->fetch($sql,1);
		if($list){
			$arrUserid = false;
			$arrCity = false;
			$sbranddata = false;
			$scitydata = false;
			$arrgiididtype = false;
			$sgiiddata = false;
			$questionmark = array("000","DB9","LV9","OS9","WT9","ZZ9");
			$brands = "
			SELECT * FROM brands ";
			$branddata = $this->fetch($brands,1);
			
			if($branddata){
				foreach($branddata as $val ){			
					$branddictionary[$val['id']]=$val;
				}
			
			}
			
			foreach($list as $key => $val){
				$arrUserid[$val['id']] = $val['id'];
				$arrCity[$val['city']] = $val['city'];
				$arrgiididtype[$val['giid_type']] = $val['giid_type'];
			}
			
			if($arrUserid){
				$struserid = implode(',',$arrUserid);
				
				$sql = "SELECT * FROM social_brand_preferences WHERE userid IN ({$struserid}) ";
				$brandpref = $this->fetch($sql,1);
				
				if($brandpref){
					foreach($brandpref as $key => $val){
							if(array_key_exists($val['brand_primary'],$branddictionary)) $brandpref[$key]['brand_primary_name'] = $branddictionary[$val['brand_primary']];
							if(array_key_exists($val['brand_secondary'],$branddictionary)) $brandpref[$key]['brand_secondary_name'] = $branddictionary[$val['brand_secondary']];
							if(array_key_exists($val['question_mark'],$questionmark)) $brandpref[$key]['question_mark_name'] = $questionmark[$val['question_mark']];
							$sbranddata[$val['userid']] = $brandpref[$key];
					}
					
					
				}
				
				
			}
			
			if($arrCity){
				$strcityid = implode(',',$arrCity);
				
				$sql = "SELECT * FROM {$this->dbclass}_city_reference WHERE id IN ({$strcityid}) ";
				$citydata = $this->fetch($sql,1);
				
				if($citydata){
					foreach($citydata as $val){
							$scitydata[$val['id']]=$val;					
					}
				}
				
				
			}
			
			if($arrgiididtype){
				$strgiididtype = implode(',',$arrgiididtype);
				
				$sql = "SELECT * FROM giid_type WHERE id IN ({$strgiididtype}) ";
				$giiddata = $this->fetch($sql,1);
				
				if($giiddata){
					foreach($giiddata as $val){
							$sgiiddata[$val['id']]=$val;					
					}
				}
				
				
			}
			
			
			foreach($list as $key => $val){
				/* city list */
				$list[$key]['citydetail'] = false;
				$list[$key]['branddetail'] = false;
				if($scitydata) 	if(array_key_exists($val['city'],$scitydata)) $list[$key]['citydetail'] =  $scitydata[$val['city']];
				if($sbranddata) if(array_key_exists($val['id'],$sbranddata)) $list[$key]['branddetail'] =  $sbranddata[$val['id']];
				if($sgiiddata) if(array_key_exists($val['giid_type'],$sgiiddata)) $list[$key]['giiddetail'] =  $sgiiddata[$val['giid_type']];
				
				
				/* conditioning */
				$list[$key]['phone_number'] =  str_replace("-","",$list[$key]['phone_number']);
				$list[$key]['sex'] = substr($val["sex"],0,1);
			
			}
			
		
		}
		
		
		$data = $this->addToXML($list);
		if($data['result']){
			$datetime = date("Y-m-d H:i:s");
			$sql ="
				INSERT INTO archlight_inbound (filename,record,datetime,n_status)
				VALUES (\"{$data['filename']}\",\"{$data['record']}\",\"{$datetime}\",1)
			";
			$this->query($sql);
			if($this->getLastInsertId()) return $this->View->showMessage('BERHASIL UPLOAD DATA TO SERVER', "index.php?s={$this->folder}");
		}
		
		return $this->View->showMessage('GAGAL UPLOAD DATA TO SERVER', "index.php?s={$this->folder}");
	}
	
	
	function addToXML($list=false){
		
		
		
		if($list==false) return false;
		
		$CampaignNumber = "PH13000421O01";
		$CampaignPhase = "XXXA";
		$Audience = "XXX1";
		$MediaCategory = "ODM";
		$OfferCategory = "400";
		$OfferCode = "400403";
		// pr($list);exit;
		
		$arrRecord = false;
		
		foreach($list as $val){
		
			$datetime = date("Y-m-d");
			if($val["sex"]=="M") $prefix = "MR";
			else $prefix = "MRS";
			
			$dataXML = "<Record>";
			$dataXML .= "<VendorCode>A12</VendorCode>";
			$dataXML .= "<DocumentReferenceNumber></DocumentReferenceNumber>";
			$dataXML .= "<DateOfProcessing>{$datetime}</DateOfProcessing>";
			$dataXML .= "<LocalMarketCode>PH</LocalMarketCode>";
			$dataXML .= "<Prefix>{$prefix}</Prefix>";
			$dataXML .= "<FirstName>{$val['name']}</FirstName>";
			$dataXML .= "<MiddleInitial>{$val['middle_name']}</MiddleInitial>";
			$dataXML .= "<ThirdName>{$val['last_name']}</ThirdName>";
			$dataXML .= "<NickName>{$val['nickname']}</NickName>";
			$dataXML .= "<Gender>{$val['sex']}</Gender>";
			$dataXML .= "<CurrentAddress>";
			$dataXML .= "<HouseNumber>{$val['StreetName']}</HouseNumber>";
			$dataXML .= "<StreetName>{$val['StreetName']}</StreetName>";
			$dataXML .= "<City>{$val['citydetail']['city']}</City>";
			$dataXML .= "<Village>{$val['StreetName']}</Village>";
			$dataXML .= "<District>{$val['barangay']}</District>";
			$dataXML .= "<Block>{$val['barangay']}</Block>";
			$dataXML .= "<State>{$val['citydetail']['provinceName']}</State>";
			$dataXML .= "<PostalCode>{$val['zipcode']}</PostalCode>";
			$dataXML .= "<Country>PH</Country>";
			$dataXML .= "</CurrentAddress>";
			$dataXML .= "<OfficePhone></OfficePhone>";
			$dataXML .= "<OfficePhoneExtension></OfficePhoneExtension>";
			$dataXML .= "<HomePhone></HomePhone>";
			$dataXML .= "<MobilePhone>{$val['phone_number']}</MobilePhone>";
			$dataXML .= "<MobileServiceProvider></MobileServiceProvider>";
			$dataXML .= "<EmailAddress>{$val['email']}</EmailAddress>";
			$dataXML .= "<DateOfBirth>{$val['birthday']}</DateOfBirth>";
			$dataXML .= "<DateOfCapture>{$datetime}</DateOfCapture>";
			$dataXML .= "<SignatureReasonCode></SignatureReasonCode>";
			$dataXML .= "<AgeVerificationType></AgeVerificationType>";
			$dataXML .= "<GovernmentIDType>{$val['giiddetail']['giid_code']}</GovernmentIDType>";
			$dataXML .= "<GovernmentIDNumber>{$val['giid_number']}</GovernmentIDNumber>";
			$dataXML .= "<OptOffDirectMail></OptOffDirectMail>";
			$dataXML .= "<OptOffHomePhone></OptOffHomePhone>";
			$dataXML .= "<OptOffMobilePhone></OptOffMobilePhone>";
			$dataXML .= "<OptOffDigital></OptOffDigital>";
			$dataXML .= "<OptOffAllChannels></OptOffAllChannels>";
			$dataXML .= "<OptOffTelemarketing></OptOffTelemarketing>";
			$dataXML .= "<OptOffOfficePhone></OptOffOfficePhone>";
			$dataXML .= "<DMDeliverabilityCode>3M</DMDeliverabilityCode>";
			$dataXML .= "<HomePhoneDeliverabilityCode>3H</HomePhoneDeliverabilityCode>";
			$dataXML .= "<MobilePhoneDeliverabilityCode>3P</MobilePhoneDeliverabilityCode>";
			$dataXML .= "<DigitalDeviceDeliverabilityCode>3D</DigitalDeviceDeliverabilityCode>";
			$dataXML .= "<OfficePhoneDeliverabilityCode>3O</OfficePhoneDeliverabilityCode>";
			$dataXML .= "<HomePhoneDeliverabilityReason>VDH</HomePhoneDeliverabilityReason>";
			$dataXML .= "<MobilePhoneDeliverabilityReason>VDP</MobilePhoneDeliverabilityReason>";
			$dataXML .= "<OfficePhoneDeliverabilityReason>VDO</OfficePhoneDeliverabilityReason>";
			$dataXML .= "<DigitalDeviceDeliverabilityReason>VDD</DigitalDeviceDeliverabilityReason>";
			$dataXML .= "<DMDeliverabilityReason>VDM</DMDeliverabilityReason>";
			$dataXML .= "<CurrentBrand>{$val['branddetail']['brand_primary_name']['code']}</CurrentBrand>";
			$dataXML .= "<CurrentBrandAffinity>0</CurrentBrandAffinity>";
			$dataXML .= "<CurrentBrandFlavor>{$val['branddetail']['brand_primary_name']['flavor']}</CurrentBrandFlavor>";
			$dataXML .= "<CurrentBrandTarLevel>{$val['branddetail']['brand_primary_name']['tar']}</CurrentBrandTarLevel>";
			$dataXML .= "<BrandBuyKind></BrandBuyKind>";
			$dataXML .= "<BrandPurchaseTimespan></BrandPurchaseTimespan>";
			$dataXML .= "<AlternatePurchaseIndicator>{$val['branddetail']['question_mark_name']}</AlternatePurchaseIndicator>";
			$dataXML .= "<FirstAlternateBrand>{$val['branddetail']['brand_secondary_name']['code']}</FirstAlternateBrand>";
			$dataXML .= "<FirstAlternateBrandAffinity>0</FirstAlternateBrandAffinity>";
			$dataXML .= "<SecondAlternateBrand>{$val['branddetail']['brand_secondary_name']['code']}</SecondAlternateBrand>";
			$dataXML .= "<SecondAlternateBrandAffinity>0</SecondAlternateBrandAffinity>";
			$dataXML .= "<FirstBrandFlavor>{$val['branddetail']['brand_secondary_name']['flavor']}</FirstBrandFlavor>";
			$dataXML .= "<FirstBrandTarLevel>{$val['branddetail']['brand_secondary_name']['tar']}</FirstBrandTarLevel>";
			$dataXML .= "<CampaignNumber>{$CampaignNumber}</CampaignNumber>";
			$dataXML .= "<CampaignPhase>{$CampaignPhase}</CampaignPhase>";
			$dataXML .= "<Audience>{$Audience}</Audience>";
			$dataXML .= "<MediaCategory>{$MediaCategory}</MediaCategory>";
			$dataXML .= "<OfferCategory>{$OfferCategory}</OfferCategory>";
			$dataXML .= "<OfferCode>{$OfferCode}</OfferCode>";
			$dataXML .= "<EventConfirmation></EventConfirmation>";
			$dataXML .= "<CollectionCity></CollectionCity>";
			$dataXML .= "<PromoterSignature></PromoterSignature>";
			$dataXML .= "<PromoterSignatureDate></PromoterSignatureDate>";
			$dataXML .= "<MMSCapability></MMSCapability>";
			$dataXML .= "<ThreeGCapability></ThreeGCapability>";
			$dataXML .= "<CollectionPlace></CollectionPlace>";
			$dataXML .= "<PromoterCode></PromoterCode>";
			$dataXML .= "<WebId>{$val['id']}</WebId>";
			$dataXML .= "<PreferredCommunicationModeCode></PreferredCommunicationModeCode>";
			$dataXML .= "<OfflineDataEntryRegistrationId></OfflineDataEntryRegistrationId>";
			$dataXML .= "<TransactionalCode></TransactionalCode>";
			$dataXML .= "</Record>";
			
			$arrRecord[] = $dataXML;
			$dataXML ="";
		}
		
		if($arrRecord){
			$totalRecord = count($arrRecord);
			$xmlContent = implode('',$arrRecord);
			
			$xmlHeader = '<?xml version="1.0" encoding="UTF-8"?>
							<InboundDMFilePH xmlns:xs="http://www.w3.org/2001/XMLSchema-instance" >';
			$xmlFooter = '</InboundDMFilePH>';
			
			$xml = "{$xmlHeader}{$xmlContent}{$xmlFooter}";
			// print $xml;exit;
				$datetime = date("Ymd");
				$timeHours = date("Hm");
				$charsize = strlen($xml);
				/* sftp */
				GLOBAL $ENGINE_PATH, $CONFIG;
				
				require_once $ENGINE_PATH."Utility/phpseclib/Net/SFTP.php";
					
				try{
						$sftp = new Net_SFTP('qatransfer.pconnect.biz');
						if (!$sftp->login('jlorenzo1', 'Password123')) {
							return array('result'=>false);
						}
						$filename ="PMA_PH_A12_InboundDMFilePH_{$datetime}_{$timeHours}_{$totalRecord}_{$charsize}.xml";
						$sftp->chdir("/Distribution/Hippodrome/ArcLight Inbound - AP/drop_off/");					
						$sftp->get($filename, $xml);
						
						// echo $sftp->pwd();
						// nlist: glob 
						 // pr($sftp->nlist());
						// pr($sftp->getSFTPErrors());exit;
						return array('result'=>true,'record'=>$totalRecord,'filename'=>$filename);
				}catch (Exception $e){
							return array('result'=>false);	
				}
			
			/* sftp */
			
			
			
		}
		return array('result'=>false);
	}
	function edit(){
		
		global $CONFIG;
		$id 		= $this->_g('id');
		$authorid				= intval($this->_p("authorid"));
		if($authorid==0)  $authorid		= $this->Session->getVariable("uid");
	
		if(! $this->_p('simpan')){
		
			$sql = "SELECT * FROM {$this->dbclass}_news_content WHERE id={$id} LIMIT 1";
			$qData = $this->fetch($sql);
			// pr($qData);
			if($qData){
				if($qData['tags']!='')	$qData['tags'] = implode(',',unserialize($qData['tags']));
			
				foreach($qData as $key => $val){					
					$this->View->assign($key,$val);
				}
			}
		
		}else{
			$id 			= $this->_p('id');
			$title 			= $this->_p('title');
			$tags 			= $this->_p('tags');
			$topcontent 	= $this->_p('topcontent');
			$brief 			= $this->_p('brief');
			$content 		= $this->_p('content');
			$content 	  	= $this->fixTinyEditor( $content );
			$url 			= $this->_p('url');
			$sourceurl 		= $this->_p('sourceurl');
			if($this->roler['approver']) $status = $this->_p('n_status');
			else $status 	 = 0;
			$posted_date 	= $this->_p('posted_date');
			$expired_date 	= $this->_p('expired_date');
			$articleType	= $this->_p('articleType');
		
			if($this->type) {
				$arrType 	= explode(',',$this->type);				
				if(!in_array($articleType,$arrType)) {
					return $this->View->showMessage("you are not authorize for this type id", "index.php?s={$this->folder}");
				}
			}
			if($this->category) {
				$arrCategory 	= explode(',',$this->category);
				if(!in_array($categoryid,$arrCategory)) {
					return $this->View->showMessage('you are not authorize for this category id', "index.php?s={$this->folder}");
				}
			}
			
			if($title=='' ){
				$this->View->assign('msg',"Please complete the form!");
				return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_edit.html");
			}
			
			if($tags){
				$tags = serialize(explode(',',$tags));
			}
			$sql = "UPDATE marlborohunt_news_content SET 	title='{$title}',
														brief=\"{$brief}\",
														content=\"{$content}\",
														posted_date='{$posted_date}',
														expired_date='{$expired_date}',
														articleType='{$articleType}',
														n_status='{$status}',
														url='{$url}',
														tags='{$tags}',
														fromwho='{$this->fromwho}',
														sourceurl='{$sourceurl}',
														authorid='{$authorid}',
														topcontent='{$topcontent}'
														WHERE id={$id} LIMIT 1";
			
			
			$last_id = $id;
		
			// pr($sql);exit;
			if(!$this->query($sql)){
				$this->View->assign("msg","edit process failure");
				return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_edit.html");
			}else{
				//create Image function
				$this->createImage($last_id);				
				
				return $this->View->showMessage('Berhasil', "index.php?s={$this->folder}");
			}
		}
		
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_edit.html");
	}
	
	function hapus(){
		$id = $this->_g('id');
		if( !$this->query("UPDATE {$this->dbclass}_news_content SET n_status=3 WHERE id={$id}")){
			return $this->View->showMessage('Gagal',"index.php?s={$this->folder}");
		}else{
			return $this->View->showMessage('Berhasil',"index.php?s={$this->folder}");
		}
	}
	
	
	
	function getTypeList(){
		$sql = "SELECT * FROM {$this->dbclass}_news_content_type WHERE id IN ({$this->type}) AND  content =  {$this->contentType} ";
		$type = $this->fetch($sql,1);
		// pr($type);exit;
		return $type;
	}
	
	function getPageList(){
		$sql = "SELECT * FROM {$this->dbclass}_news_content_page WHERE n_status=1 ";
		$page = $this->fetch($sql,1);
		// pr($sql);
		return $page;
	}
	

	
	function fixTinyEditor($content){
		global $CONFIG;
		$content = str_replace("\\r\\n","",$content);
		$content = htmlspecialchars(stripslashes($content), ENT_QUOTES);
		$content = str_replace("../index.php", "index.php", $content);

		//$content = htmlspecialchars( stripslashes($content) );
		$content = str_replace("&lt;", "<", $content);
		$content = str_replace("&gt;", ">", $content);
		$content = str_replace("&quot;", "'", $content);
		$content = str_replace("&amp;", "&", $content);
		return $content;
	}
	
	function downloadreport_old(){
		$this->total_per_page = 10;
		$sql = "SELECT * FROM {$this->dbclass}_news_content con";
		$this->open(0);
		$list = $this->fetch($sql,1);
		$this->close();	
		
		$export_file = "Article_".date('Y-m-d').".xls";
		ob_end_clean();
		ini_set('zlib.output_compression','Off');
	   
		header('Pragma: public');
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");                  // Date in the past   
		header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');     // HTTP/1.1
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');    // HTTP/1.1
		header ("Pragma: no-cache");
		header("Expires: 0");
		header('Content-Transfer-Encoding: none');
		header('Content-Type: application/vnd.ms-excel;');                 // This should work for IE & Opera
		header("Content-type: application/x-msexcel");                    // This should work for the rest
		header('Content-Disposition: attachment; filename="'.basename($export_file).'"'); 
		$this->View->assign('list',$list);
		print $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
		exit;
	}	
	

}

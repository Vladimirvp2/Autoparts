<?php
/*
Classes for working with ad.ua partner
Both for loading /parsing web content and saving/fetching it from the database

*/


include_once( dirname(__DIR__)  . DIRECTORY_SEPARATOR . 'config.php');
include_once('curl_query.php');
include_once( dirname(__DIR__)  . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . 'logger' . DIRECTORY_SEPARATOR . 'Logger.php');
include_once( dirname(__DIR__)  . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . 'simple_html_dom.php');


class SiteAD {
	/*	Get web content of the partner ad.ua and parse it	*/

	// settings
    var $COOKIE_FILE = NULL;
	var $START_FILE = NULL;
	var $REFERER = NULL;
	var $log = NULL;
	var $username = NULL;
	var $password = NULL;
	
	
    function __construct($user, $pswd){
		$this->username = $user;
		$this->password = $pswd; 
		
        $this->COOKIE_FILE = __DIR__ . "/cookie_ad.txt";
		$this->START_FILE = __DIR__ . "/start_ad.html";
        $this->REFERER = "http://b2b.ad.ua/views/account/login";
		
		$this->log = Logger::getLogger('siteLogger');
    }


	function cleanFiles(){
        /*	clean cookies and temp files of the class	*/
		
        if (file_exists( $this->COOKIE_FILE )) { unlink ( $this->COOKIE_FILE ); }
		if (file_exists( $this->START_FILE )) { unlink ( $this->START_FILE ); }	
	}
	

    function getLoginPage(){
		/*	Get the page with the login form 	*/
		
		$data = curl_query2('http://b2b.ad.ua/views/account/login',
					$this->REFERER,
					"GET", 
					$this->COOKIE_FILE,
					$this->START_FILE,
					[]  );
		
		return $data;		
					
	}

	
	function getVerifyFormToken( $data ){
		
		$key = NULL;  // ver key to be found
		$regex = "/__RequestVerificationToken.+/";
		if (preg_match($regex, $data, $matches_out)) {
			$regexQ =  "/value=.+[\'\"]/";
			if (preg_match($regexQ, $matches_out[0], $matches_outQ)){
				$keyD = $matches_outQ[0];
				$keyDLen = strlen($keyD);
				$key = mb_substr($keyD, 7, $keyDLen-8);
			}	
		} 

		if (!$key){
			$this->log->error("class SiteAD, getVerifyFormToken: no match found for __RequestVerificationToken" . ", " . date("h:i:sa")); 
			// throw exception			
		}
		
		return $key;

	}
	
	
	function login(){
	
		$this->cleanFiles();
		
		$logPage = $this->getLoginPage();
		$verKey = $this->getVerifyFormToken( $logPage );	
		// delay
		sleep ( AD_REQUEST_DELAY );
			
		// send post login request
		$postFields = [
			'__RequestVerificationToken' =>	$verKey,
			'ComId' => '15',
			'UserName' => $this->username,
			'Password' =>    $this->password,
			'RememberMe' => 'false'
		];
		
		$data = curl_query_ad('http://b2b.ad.ua/views/account/login?',
					$this->REFERER,
					"POST", 
					$this->COOKIE_FILE,
					NULL,
					$postFields  );

		$this->log->info("Logged in to b2b.ad.ua OK");
	
	}
	
	
	function getWeb( $url ){
		/*	Get web content by url	*/
		
		sleep ( AD_REQUEST_DELAY );
		

		
		$data = $this->getRequest( $url, $this->REFERER, $this->COOKIE_FILE );
					
		// check if server returned correct data and not the login form. If not logged in, repeat the request
		if ( ! $this->isLoggedIn( $data ) ){
			$this->login();
			$data = $this->getRequest( $url, $this->REFERER, $this->COOKIE_FILE );	
		}
					
		return $data;
					
	}
	
	
	function getWebPOST( $url, $postFields){
		/*	Get web content by url	*/
		
		sleep ( AD_REQUEST_DELAY );
		
		$data = $this->getRequestPOST( $url, $this->REFERER, $postFields, $this->COOKIE_FILE );
					
		// check if server returned correct data and not the login form. If not logged in, repeat the request
		if ( ! $this->isLoggedIn( $data ) ){
			$this->login();
			$data = $this->getRequestPOST( $url, $this->REFERER, $postFields, $this->COOKIE_FILE );	
		}
					
		return $data;
					
	}	
	
	
	private	function getRequest( $link, $referer, $cookieFile ){
			$postFields = [];
			$data = curl_query_ad($link,
						$referer,
						"GET", 
						$cookieFile,
						NULL,
						$postFields  );	
						
			return $data;
	}
	
	
	private	function getRequestPOST( $link, $referer, $postFields, $cookieFile ){
			$data = curl_query_ad($link,
						$referer,
						"POST", 
						$cookieFile,
						NULL,
						$postFields  );	
						
			return $data;
	}	
	
	
	function isLoggedIn( $dataWeb ){

		// try to find some distinkte parts
		$regex = '/<section[ ]+id=[\'\"]loginForm[\'\"]>/';
		if ( preg_match($regex, $dataWeb, $matches) ){
			return False;
		}
		
		$regex2 = '/error[ ]+?has[ ]+?occurred/';
		if ( preg_match($regex2, $dataWeb, $matches) ){
			return False;
		}
		
		return True;
	}
		
		
    function getBrands(){
        /*	Get the list of all the  brand's models		*/
		
		// set request delay
        sleep ( AD_REQUEST_DELAY );
		
		$data = $this->getWeb( 'http://b2b.ad.ua/api/catalog/marks' );
		
		return $data;
				
	}
	
	
    function parseBrands($data){
        /*	Parse the brand's web data  that was reseaver from the getBrands method
			return array( assoc_array("code",  "brand" )) 	
		*/

		$res = array();
		$decoded_data = json_decode($data);

		foreach ($decoded_data as &$brandObj) {
			$dat = [];
			$dat["code"] = $brandObj->{'code'};
			$dat["brand"] = $brandObj->{'MARK'};
			
			$this->log->debug("Brand parsed:" . $dat["code"] . ", " . $dat["brand"]);
			$res[] = $dat;
		}
		
		$this->log->debug("All brand parsed successfully!");

		return $res;		
	}
	
	
    function getBrandLineUps($carCode){
        /*	Get the lineup of a particular car brand	*/
		
        $url = "http://b2b.ad.ua/api/catalog/models?code=" . $carCode;
		$data = $this->getWeb( $url );								
								
        return $data;	
		
	}
	
	
    function parseLineUps($data){
        /*    Parse the lipeup of a particulat car brand
			$data - result of getBrandLineUpsfunction
			return: array( assoc_array("code", "name", "year" )) 	
		*/

		$res = array();
		$decoded_data = json_decode($data);
		

		foreach ($decoded_data as &$lineupObj) {
			$dat = [];
			$dat['code'] = $lineupObj->{'MOD_ID'};
			$dat['name'] = $lineupObj->{'MODEL'};
			$dat['year'] = $lineupObj->{'MOD_TERMS'};

			$res[] = $dat;
			
			$this->log->debug("Lineup parsed:" . $dat['code'] . ", " . $dat["name"] . ", " . $dat['year']);
		}
		
		$this->log->debug("All lineups of a brand parsed successfully!");

		return $res;		
	}


    function getModels($lineupCode){
        /*	Get the arr of models of a particular lineup of a particular car brand	*/			
								
		$url = 'http://b2b.ad.ua/api/catalog/types?code=' . $lineupCode;						
		$data = $this->getWeb( $url );	

        return $data;
	}
	
	
    function parseModels($data){
        /*    Parse models of a particulat lineup and car brand
			$data - result of getModels function
			return array( assoc_array("code", "name", "body" , "year" ))	
		*/

		$res = array();
		$decoded_data = json_decode($data);

		foreach ($decoded_data as &$lineupObj) {
			$dat = [];
			$dat['code'] = $lineupObj->{'typ_id'};
			$dat['name'] = $lineupObj->{'name_full'};
			$dat['body'] = $lineupObj->{'body'};
			$dat['year'] = $lineupObj->{'terms'};
			
			$res[] = $dat;
			
			$this->log->debug("Model parsed:" . $dat['code'] . ", " . $dat['name'] . ", " . $dat['year']);
		}
		
		$this->log->debug("All models parsed successfully!");
		
		return $res;		
	}	
		

    function getItemsCategiries( $typeId ){
        /*	Get the categories  of a part. model of a particular lineup of a particular car brand	*/

        $url = "http://b2b.ad.ua/api/catalog/groups?code=" . (string)$typeId;
		$data = $this->getWeb( $url );		

        return $data;
		
	}

	
    function parseItemsCategiries($data){
        /*    Parse categories of a particulat model / lineup /  brand
			$data - result of getItemsCategiries  function
			return array(   assoc_array ( "code", "name" ,"name_f", array( assoc_array("code", "name", "name_f" )  )   ) )	
		*/

		$resGroups = array();
		$decoded_data = json_decode($data);
		$decoded_data->{'groups'};
		
		$groups =  $decoded_data->{'groups'}; 
		$resGroups = [];   // result accumulator
		// iterate through groups
		foreach ($groups as &$groupObj) {
		
			$subgroups = $groupObj->{'subgrp'};
			$resSubgroups  = [];
			// iterate through subgroups
			foreach ($subgroups as &$subgroupObj) {	
				$dat = [];
				$dat['code'] = $subgroupObj->{'code'};
				$dat['name'] = $subgroupObj->{'name'};
				$dat['name_f'] = $this->spaceReplacer($subgroupObj->{'name'});					

				$resSubgroups[] = $dat;				
			}
			// add subgroups data
			$resGroups[] = array(
				'code' => $groupObj->{'code'},
				'name' => $groupObj->{'name'},
				'name_f' => $this->spaceReplacer( $groupObj->{'name'} ),
				'subgroups' => $resSubgroups
			);			
		}
		
		$this->log->debug("All categories parsed successfully!");
		
		return $resGroups;		
	}


    function getItems( $modelId, $groupName = '' ){
        /*	Get the items list of a part subgroup / model of a particular lineup of a particular car brand	*/
		
		/*
		function spaceReplacer($str){
            $res = "";
            $spaceRow = False;
            for ($i = 0; $i < strlen($str); $i++){
				$c = $str[$i];
                if ($c == ' '){
                    if ( ! $spaceRow ){
                        $res = $res . '+';
                        $spaceRow = True;
					}
				}
                else{
                    $spaceRow = False;
                    $res = $res . $c;
				}
			}

            return $res;
		}
		
		*/
			

		// set request delay
        //sleep ( AD_REQUEST_DELAY );
		
        $groupNameF = $this->spaceReplacer($groupName);
        $url = 'http://b2b.ad.ua/api/catalog/items?code=' . $modelId . '&group=' . $groupNameF;	
		
	
		$data = $this->getWeb( $url );	
		//echo 	$url . '<br>';		
		//$postFields = [];
		//$data = curl_query_ad($url,
		//						$this->REFERER,
		//						"GET", 
		//						$this->COOKIE_FILE,
		//						NULL,
		//						$postFields  );	
								
				
        return $data; 
		
	}


	private function spaceReplacer($str){
	/*	replaces spaces with one + in group name	*/
		$res = "";
		$spaceRow = False;
		for ($i = 0; $i < strlen($str); $i++){
			$c = $str[$i];
			if ($c == ' '){
				if ( ! $spaceRow ){
					$res = $res . '+';
					$spaceRow = True;
				}
			}
			else{
				$spaceRow = False;
				$res = $res . $c;
			}
		}

		return $res;
	}	
	
	
	function parseItems( $data ){
		/*	Parse goods data
			input: string
			return: array(    assoc_array(item, brand, name, desc, price, retail, imgs)   ). imgs - array(   assoc_array(item, tab, id, url),.. )		
		*/
			
		$decoded_data = json_decode($data);
		
		$images =  $decoded_data->{'images'}; 
		$resItems = [];   // result accumulator
		$resImages = [];   // result accumulator
		
		function findImage($item, $imagesArr){
			//find image of a particulat item from previously parsed data
			$res = [];  //array  (assoc_array( item, tab, grdid)  )
			foreach ($imagesArr as &$image){
				if ($image['item'] == $item) {
					$res[] = $image;
				}
			}

			return $res;	
		}
		
		// iterate through images
		foreach ($images as &$imageObj) {
			// fill up the images array
			$imgUrl = 'http://b2b.ad.ua/img/tcd/' . $imageObj->{'GraTab'} . '/' . $imageObj->{'GraGrdId'} . '.jpg';
			$resImages[] = array(
				'item' => $imageObj->{'Item'},
				'tab' => $imageObj->{'GraTab'},
				'grdid' => $imageObj->{'GraGrdId'},
				'url' => $imgUrl
			);
		}
		
		
		// parse items
		$items =  $decoded_data->{'items'}; 
		foreach ($items as &$itemObj) {
		    $price = $itemObj->{'Price'} * (1 + PARTNER_MARKUP_AD);
			$resItems[] = array(
				'item' => $itemObj->{'Item'},
				'brand' => $itemObj->{'Бренд'},
				'name' => $itemObj->{'Название'},
				'desc' => $itemObj->{'Описание'},
				'price' => $price,
				'retail' => $itemObj->{'Retail'},
				'imgs' => findImage( $itemObj->{'Item'}, $resImages )
			);		
			
		}
		
		$this->log->debug("All items parsed successfully!");	
		
		return $resItems;
				
	}
	
	
	function getSearchItems($s){
	
        $url = 'http://b2b.ad.ua/api/catalog/search?grp=&item=' . $s;		
		$data = $this->getWeb( $url );
									
        return $data; 		
	}
	
	
	function getSearchVIN($s){
	
        $url = 'http://b2b.ad.ua/api/catalogoem/types';		
		$postFields = ['' => $s];
		$data = $this->getWebPOST( $url, $postFields);
								
        return $data; 	
		
	}


	function parseSearchVIN($data){
	/*	Parse json data ge from the  getSearchVIN
		@return:      array( assoc_array(   brand,   model,   data   )    ) ,  data -  assoc_array( date, framecolor, options, description,  model,  prodPeriod, trimcolor )   
	*/
	
		$decoded_data = json_decode($data);
		
		
		$autosData =  $decoded_data->{'types'};
		$autos =  $autosData->{'rowField'}; 
		
		$res = [];

		// if search returned no results
		if 	(empty($autos)){
			return	$res;		
		}	
		
		foreach($autos as &$auto){
			$brand = $auto->{'brand'};
			$modelName = $auto->{'nameField'};	
			$attribs = $auto->{'attributes'};
			
			$attrAcc = [];
			foreach($attribs as &$attr){
				if ($attr->{'key'} == "date"){
					$attrAcc['date'] = $attr->{'value'};
				}
				else if ($attr->{'key'} == "framecolor"){
					$attrAcc['framecolor'] = $attr->{'value'};
				}
				else if ($attr->{'key'} == "options"){
					$attrAcc['options'] = $attr->{'value'};
				}
				else if ($attr->{'key'} == "description"){
					$attrAcc['description'] = $attr->{'value'};
				}
				else if ($attr->{'key'} == "model"){
					$attrAcc['model'] = $attr->{'value'};
				}
				else if ($attr->{'key'} == "prodPeriod"){
					$attrAcc['prodPeriod'] = $attr->{'value'};
				}
				else if ($attr->{'key'} == "trimcolor"){
					$attrAcc['trimcolor'] = $attr->{'value'};
				}
			}
			
			$res[] = array(
				'brand' => $brand,
				'model' => $modelName,
				'data' => $attrAcc
			);
			
			
		}
		
		return $res;

	}
	
	
	function getGroupsVIN( $s ){
		/*	$s - vin code string	*/

        $url = 'http://b2b.ad.ua/api/vin/typegroups?type=0&vin=' . $s;		
		$data = $this->getWeb( $url );
									
        return $data;

	}	
	

	function parseGroupsVIN($dat){
		/*	return array( assoc_array( name, code, data  ) ), data - assoc_array (name, code, data)  */
		
		function addMenuItem($data){
			$objs = $data->{'rowField'};
			// exit condition			
			if (!is_array( $objs )){
				return [];
			}
			
			$acc = [];
			foreach($objs as $obj){
				$name = $obj->{'nameField'};
				$code = $obj->{ 'quickgroupidField'};
				$data = addMenuItem($obj);
				
				$acc[] = array(
					'name' => $name,
					'code' => $code,
					'data' => $data				
				);
			}
			
			return $acc;
			
			
		}
	
		$decoded_data = json_decode($dat);
		
		$autosData =  $decoded_data->{'tree'};
		$obls1 =  $autosData->{'rowField'}; 
		
		$res = []; // group acc		
		foreach($obls1 as $obl1){
			$obls2 = $obl1->{'rowField'}; 		
			foreach($obls2 as $obl2){
				$groupName = $obl2->{'nameField'};
				$groupCode = $obl2->{ 'quickgroupidField'};	
				$groupData = addMenuItem($obl2);
				
				$res[] = array(
				'name' => $groupName,
				'code' => $groupCode,
				'data' => $groupData
				);
			}

		}
		
		return $res;

	}


	function getSubcategoriesVIN($id='11865', $s='SB164ABN10E049174'){
        $url = 'http://b2b.ad.ua/api/vin/oemitems?id=' . $id . '&type=0&vin=' . $s;				
		$data = $this->getWeb( $url );
									
        return $data;		
	}	
	
	
	function parseSubcategoriesVIN($data){
		/*	return  array(  assoc_array( name, code,  image, data ) ),   data  -  assoc_array( code, name, codeF  )      */
	
		$decoded_data = json_decode($data);
		
		$items = $decoded_data->{'items'};
		$categs =  $items->{'categoryField'};
		if (!is_array( $categs )){
			return [];
		}
		
		$res = [];
		foreach ($categs as &$categ){
			$categData = $categ->{'unitField'};
			
			if (!is_array( $categData )){
				continue;
			}

				
			$sectionAcc = []; // accumulator
			foreach ($categData as $categD){
				$detF = $categD->{'detailField'};
				
				if (!is_array( $detF  )){
					continue;
				}
				
				$itemAcc = [];
				foreach ($detF as $d){			
					$itemAcc[] = array(
						'code' => $d->{'codeonimageField'},
						'name' => $d->{'nameField'},
						'codeF' => $d->{'oemField'}
					);
				}

				$img = str_replace("%size%", "200", $categD->{'imageurlField'});
				$sectionAcc[] = array(
					'name' => $categD->{'nameField'},
					'code' => $categD->{'codeField'},
					'image' => $img, //$categD->{'imageurlField'},
					'data' => $itemAcc
				);
			}
			
			$res[] = array(
				'name' => $categ->{'nameField'},
				'data' => $sectionAcc
			);
		}
		
		return $res;
	}


	function getItemsVIN( $s ){
		/*	$s - full code	*/
        $url = 'http://b2b.ad.ua/api/catalog/search?item=' . $s;		
		$data = $this->getWeb( $url );
											
        return $data; 		
	}


	function parseItemsVIN( $data ){
		/*	return  array(  assoc_array( item, brand,  name, desc, price, retail, img) )   */
		
		function findImageItemsVIN($arr, $name){
			foreach ($arr as &$ar){
				if ($ar['item'] == $name){
					return 'http://b2b.ad.ua/img/tcd/' . $ar['tab'] . '/' . $ar['grdid'] . '.jpg';
				}
			}
		}		
	
		$decoded_data = json_decode($data);
		
		$images =  $decoded_data->{'images'};
		if (!is_array( $images )){
			$images = [];
		}
		
		$imgsArr = [];
		foreach ($images as &$image){
			$imgsArr[] = array(
				'item' => $image->{'Item'},
				'tab' => $image->{'GraTab'},
				'grdid' => $image->{'GraGrdId'}
			);
		}
		
		$items = $decoded_data->{'items'};
		if (!is_array( $items )){
			$items = [];
		}
		
		$itemsArr = [];
		foreach ($items as &$item){
			$itemsArr[] = array(
				'code' => $item->{'Item'},
				'brand' => $item->{'Бренд'},
				'name' => $item->{'Название'},
				'desc' => $item->{'Описание'},
				'price' => $item->{'Price'},
				'retail' => $item->{'Retail'},
				'img' => findImageItemsVIN($imgsArr, $item->{'Item'})
			);
		}

		return 	$itemsArr;	
		
	}


	function getReplacedItemsVIN( $s ){
		/*	$s - code like FP 8160 R4-E	*/
        $url = 'http://b2b.ad.ua/api/catalog/replace?code=' . urlencode( $s );		
		$data = $this->getWeb( $url );
											
        return $data; 		
	}


	function parseReplacedItemsVIN( $data ){
		/* 	$data - string get from getReplacedItemsVIN
			return: array(  assoc_array( item, brand, name, desc,  price, retail)   ) 
		*/
				
		$decoded_data = json_decode($data);
		$items = $decoded_data->{'items'};
		
		$itemsAcc = [];
		foreach($items as &$item){
		
			$itemsAcc[] = array(
				'code' => $item->{'Item'},
				'brand' => $item->{'Бренд'},
				'name' => $item->{'Название'},
				'desc' => $item->{'Описание'},
				'price' => $item->{'Price'},
				'retail' => $item->{'Retail'}
			);
		}
		
		return $itemsAcc;
		
	}	
	
	
	function getApplicationItemsVIN( $s ){
		/*	Get apllication of the items
			$s - code like FP 8160 R4-E
		*/
        $url = 'http://b2b.ad.ua/api/catalog/carapplication?code=' . urlencode( $s );		
		$data = $this->getWeb( $url );
											
        return $data; 		
	}


	function parseApplicationItemsVIN( $data ){
		/* 	$data - string get from getApplicationItemsVIN
			return: array(  assoc_array( brand, name)   ) 
		*/
				
		$decoded_data = json_decode($data);
			
		$appAcc = [];
		foreach($decoded_data  as &$d){
			$brand= $d->{'MARK'};
			if (array_key_exists($brand, $appAcc)){
				$appAcc[$brand] = $appAcc[$brand] . ", " . $d->{'MODEL'};
			}
			else{
				$appAcc[$brand] =  $d->{'MODEL'};
			}
		}

		return $appAcc;
		
	}	
	
	
	
}

?>
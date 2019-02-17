<?php
/*
Main data models to work with partner sites
Currently, the data models are designed to work only with 2 main partners: ad.ua, zp.ua

*/

include_once('parsers/ad.php');
include_once('parsers/zp.php');
include_once('config.php');
include_once('libs/logger/Logger.php');



class DatabaseAccessException extends Exception {
	/*	Exception occuring while some problem with the database	*/
	public function errorMessage() {
    //error message
		$errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
			.': <b>'.$this->getMessage().'</b> Problem with the database';
		return $errorMsg;
	}
}


class SiteAccessException extends Exception {
	/*	Exception occuring while some problem with the access a partner's site	*/
	public function errorMessage() {
    //error message
		$errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
			.': <b>'.$this->getMessage().'</b> Problem with the site access';
		return $errorMsg;
	}
}



class DataCache{
	/*
	Casche data from the requests in the Data class
	*/
	
	const CACHE_DATA_KEY = 'cache_data_key';

    function __construct( $maxSize ){
		$this->chache = [];
		// max size in number of elements, not in bytes
		$this->MAX_SIZE = $maxSize;
		
	}
	
	
	function getData( $data ){
		/*
		Get cheched value by keys provided in the $data. All the keys and their values should be equel
		return; checked value
		*/
	
		foreach ($this->chache as $cacheVal){
			$allKeysChecked = True;
			foreach ($data as $key => $value){
				// check if all the keys exist
				if ( !array_key_exists($key, $cacheVal) ){
					$allKeysChecked = False;
					break;
				}
				// check if the keys are equal 
				if ( $cacheVal[$key] != $data[$key] ){
					$allKeysChecked = False;
					break;
				}
			}
			// all the keys exist and values are equal
			if ($allKeysChecked){
				//echo "Get from cache!!!" . '<br>';
				return $cacheVal[ self::CACHE_DATA_KEY ];
			}
			
		}
		
		return NULL;
		
	}
	
	
	function setData( $dataKey, $setVal ){
		/*	Set chached value by keys and their values	*/
	
		$this->shrinkIfLarge();
		
		$pos = [];	
		foreach ($dataKey as $key => $value){
			// set the keys
			$pos[$key] = $value;
		}
		
		// set the value
		$pos[ self::CACHE_DATA_KEY ] = $setVal;
		array_push($this->chache, $pos);
		//$this->chache[] = $pos;

	}
	
	
	function clean(){
		/*	Clean oll the elements	*/
		$this->chache = [];
	}
	
	
	private function shrinkIfLarge(){
		/*	Check wether the size of the cache is beyond the set limit. 
		If so, remove the largest elements one by one
		*/
		
		$cacheSize = count( $this->chache );
		if ( $cacheSize >= $this->MAX_SIZE ){
			$this->removeOldest();
		//	$this->removeLargest();
		}
	}
	
		
	private function removeOldest(){
		/*	Find and remove the oldest element from chache	*/
		
		$cacheLen = count( $this->chache );
		if ($cacheLen >= 1 ){
			unset($this->chache[ 0 ]);
			//echo "Remove element, index: " . 0 . " <br>";
		}

	}	
	

}





class ItemSorter{
	/*	Sort items 	*/

	function sort(&$items, $sortParam="name", $order = "ASC"){
	/*	Sort items  array by the given param and order	
		$items: array( assoc_array (  'name', 'price'...)  )  
		$sortParam: one of the fields in the assoc_array (  'name', 'price'...)  
	*/
	
		$sortFun = NULL;
		switch ($sortParam) {
			case "name":
				$sortFun = ($order == "ASC") ? [$this, 'cmpBySortNameASC'] : [$this, 'cmpBySortNameDESC'];
				break;
			case "price":
				$sortFun = ($order == "ASC") ? [$this, 'cmpBySortPriceASC'] : [$this, 'cmpBySortPriceDESC'];
				break;
			default :
				break;
		}
		

		
		if ( $sortFun ){
			usort( $items, $sortFun );
		}
		
	}

	
	private function cmpBySortNameASC($a, $b) {
		return strcmp ($a['name'], $b['name']);
	}
	
	
	private function cmpBySortNameDESC($a, $b) {
		return strcmp ( $b['name'], $a['name'] );
	}	

	
	private function cmpBySortPriceASC($a, $b) {
		return (float)$a["price"] - (float)$b["price"];
	}
	
	
	private function cmpBySortPriceDESC($a, $b) {
		return  (float)$b["price"] - (float)$a["price"];
	}	
	

}






	
class Data{
	/*
	Main class to get data for the site - get brands, lineups, models...
	Currently, it ise designed to work only with 2 main partners: ad.ua, zp.ua
	*/
	
	var $siteAD = NULL;
	var $siteZP = NULL;

    function __construct(){
		
		$this->log = Logger::getLogger( LOGGER_NAME );
		
		// init partners sites
		$this->siteAD = new SiteAD('20190', '976544');
		$this->siteZP = new SiteZP();	

		$this->dataCache = new DataCache( DATA_CACHE_MAX_SIZE );
		$this->itemSorter = new ItemSorter();
		
    }

	function getBrands(){
		/* 
			array(  assoc_array( text, link )  ) 
			link structure depends on the partner
			zp: host/models?partner=val1&act=models&data=val2
			ad:  host/lineups?partner=val1&act=lineups&code=val2
		*/
				
		$ret = []; // accumulator
		
		$brands = $this->fetchBrandsDB();
				
		foreach ($brands as &$brand){			
			$link = "";
			//Links are formed differently depending on the particular partner.
			// if the partner is ad
			if ( (string) ($brand['partner_code']) == PARTNER_CODE_AD ){
				//$link =	CURRENT_HOST . '/lineups/?' . 'partner=' . $brand['partner_code'] . '&' . 'act=lineups' . '&' . 'code=' . $brand['data'];
				//$link =	'/lineups/?' . 'partner=' . $brand['partner_code'] . '&' . 'act=lineups' . '&' . 'code=' . $brand['data'];
				$link = '?' . 'partner=' . $brand['partner_code'] . '&' . 'act=lineups' . '&' . 'code=' . $brand['data'] . '&brc=' . $brand['name'];
			}
			// if partner is zp
			else {
				//$link =	CURRENT_HOST . '/models/?' . 'partner=' . $brand['partner_code'] . '&' . 'act=models' . '&' . 'data=' . $brand['data'];
				//$link =	'/models/?' . 'partner=' . $brand['partner_code'] . '&' . 'act=models' . '&' . 'data=' . $brand['data'];
				$link =	'?' . 'partner=' . $brand['partner_code'] . '&' . 'act=models' . '&' . 'data=' . $brand['data'] . '&brc=' . $brand['name'];
			}
			$ret[] = array(
				'text' => $brand['name'],
				'link' => $link
			);
		}

		return $ret;
	
	}
	
	
	private function fetchBrandsDB(){
		/* Fetch previously saved brands data from the database
		return: array(  assoc_array( partner_code, name, data )  )
		*/
	
		$ret = []; // accumulator 
		
		// Create connection
		$conn = mysqli_connect(	DB_SERVER, DB_LOGIN, DB_PASSWORD, DB_DATABASE);
		mysqli_query($conn, "SET NAMES UTF8");
		// Check connection
		if (!$conn) {
			$this->log->error( "Data, fetchBrandsDB: Failed to connect to database "  . mysqli_connect_error() );
			throw new DatabaseAccessException( mysqli_connect_error() );
		}

		

		$sql = "SELECT partner_code, name, data FROM brands";

		$result = mysqli_query($conn, $sql);

		if ($result) {
			$this->log->debug( "Data, fetchBrandsDB: Select brands query processed successfully" );
		} else {
			$this->log->error("Data, fetchBrandsDB: Failed to fetch brands from the database "  . mysqli_error( $conn )  );
			throw new DatabaseAccessException( mysqli_error( $conn ) );
		}


		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$ret[] = array(
					'partner_code' => $row["partner_code"],
					'name' => $row["name"],
					'data' => $row["data"]
				);				
			}
		} else {
			$this->log->debug( "Data, fetchBrandsDB: 0 brands fetched while select" );
		}

		mysqli_close($conn);

		return $ret;
	
	}
	
	
	function getLineups( $data ){
		/* Generate lineups for a given brand
		$data: assoc_array( 'partner_code',  'code' )
		return: array(  assoc_array( 'name',  'year', 'link' ) ) 
		link structure depends on the partner
		ad: ?partner=val1&act=models&code=val2
		zp: none
		*/
		
		$cv = $this->dataCache->getData( $data );
		if ( $cv ){
			return $cv;
		}
		
		$res = [];   // accumulator
		$partner = $data['partner_code'];	
		
		if ( (string)$partner == PARTNER_CODE_AD){
			$lineupsWeb = $this->siteAD->getBrandLineUps( $data['code'] );
			$lineups = $this->siteAD->parseLineUps( $lineupsWeb );
			foreach ($lineups as &$lineup){		
				//$link = CURRENT_HOST . '/models?' . 'partner=' . $partner . '&' . 'act=models' . '&' .  'code=' . $lineup['code']; 
				$link = '?' . 'partner=' . $partner . '&' . 'act=models' . '&' .  'code=' . $lineup['code'] . '&brc=' . urlencode($lineup['name']); 
				$res[] = array (
					'name' => $lineup['name'],
					'year' => $lineup['year'],
					'link' => $link
				);
			}	
		}
		
		$this->dataCache->setData( $data, $res );

		return $res;
	
	}
	
	
	function getModels( $data ){
		/* Generate models for a given lineup
		$data: assoc_array( 'partner_code',  'code' or 'data'  - for zp.ua)
		return: for ad partner:  array(  assoc_array( 'name',  'year', 'body', 'link' ) ) 
		return  for zp partner:  array(  assoc_array( 'name',  'link' ) ) 
		link structure depends on the partner
		ad: ?partner=val1&act=groups&code=val2
		zp: ?partner=val1&act=groups&data=val2
		*/
		
		$cv = $this->dataCache->getData( $data );
		if ( $cv ){
			return $cv;
		}
		
		$res = [];   // accumulator
		$partner = $data['partner_code'];	
		
		if ( (string)$partner == PARTNER_CODE_AD){
			$modelsWeb = $this->siteAD->getModels( $data['code'] );
			$models = $this->siteAD->parseModels( $modelsWeb );
			foreach ($models as &$model){	
				$link = '?' . 'partner=' . $partner . '&' . 'act=groups' . '&' .  'code=' . $model['code'] . '&brc=' . urlencode($model['name']);
				$res[] = array (
					'name' => $model['name'],
					'body' => $model['body'],
					'year' => $model['year'],
					'link' => $link
				);
			}	
		}
		// if partner zp
		else{
			$fullLink = $this->siteZP->linkComplete( $data['data'] );
			$modelsWeb =  $this->siteZP->getWeb( $fullLink );
			$models = $this->siteZP->parseModels( $modelsWeb );	
			foreach ($models as &$model){
				$link = '?' . 'partner=' . $partner . '&' . 'act=groups' . '&' .  'data=' . urlencode($model['link']) . '&brc=' . urlencode($model['name']);
				$res[] = array (
					'name' => $model['name'],
					'link' => $link
				);
			}			
		}

		// write to cache
		$this->dataCache->setData( $data, $res );
		
		return $res;
	
	}	
	

	
	function getCategories( $data ){
		/* Generate categories for a given model
		$data: assoc_array( 'partner_code',  'code' or 'data'  - for zp.ua)
		return: for ad partner:  array(  assoc_array( 'name',  'link', 'subgroups', ) ) , 'subgroups' is assoc_array ( 'name', 'link') 
		link structure depends on the partner
		ad: ?partner=val1&act=items&code=modelCode&name_f=formatted+subgroup+name)
		zp: ?partner=val1&act=items&data=link)
		*/
		
		$cv = $this->dataCache->getData( $data );
		if ( $cv ){
			return $cv;
		}
		
		$res = [];   // accumulator
		$partner = $data['partner_code'];	
		$modelCode = $data['code'];	
		
		if ( (string)$partner == PARTNER_CODE_AD){
			$groupsWeb = $this->siteAD->getItemsCategiries( $modelCode );
			$groups = $this->siteAD->parseItemsCategiries( $groupsWeb );
			// if empty result try to relogin
			if ( count($groups) < 1){
				$this->siteAD->login();
				$groupsWeb = $this->siteAD->getItemsCategiries( $modelCode );
				$groups = $this->siteAD->parseItemsCategiries( $groupsWeb );			
			}

			// loop through main groups
			foreach ($groups as &$group){	
				$groupLink = '?' . 'partner=' . $partner . '&' . 'act=subgroups' . '&' .  'code=' . $group['code'] . '&' .  'name_f=' . urlencode($group['name_f']);
				$subgroups = $group['subgroups'];
				
				$resSubgroups  = [];
				foreach ($subgroups as &$subgroup){
					$dat = [];
					$subgroupLink = 'items/?' . 'partner=' . $partner . '&' . 'act=items' . '&' .  'code=' . $modelCode . '&' .  'name_f=' . urlencode($subgroup['name_f']) . '&brc=' . urlencode($group['name']);
					$dat['link'] = $subgroupLink;
					$dat['name'] = $subgroup["name"];
					$resSubgroups[] = 	$dat;
				}
				// add new group with subgroups
				$res[] = array (
					'name' => $group['name'],
					'link' => $groupLink,
					'subgroups' => $resSubgroups
				);
			}	
		}
		// if partner zp
		else{
			$fullLink = $this->siteZP->linkComplete( $data['data'] );
			$groupsWeb =  $this->siteZP->getWeb( $fullLink );
			$groups = $this->siteZP->parseCategories( $groupsWeb );	

			foreach ($groups as &$group){
				$groupLink = '?' . 'partner=' . $partner . '&' . 'act=subgroups' . '&' .  'data=' . urlencode($group['link']) . '&' .  'name=' . $group['name'];
				
				// find subcategories
				$subGroupsFullLink = $this->siteZP->linkComplete( $group['link'] );
				$subGroupsWeb =  $this->siteZP->getWeb( $subGroupsFullLink  );
				$subgroups = $this->siteZP->parseSubcategories( $subGroupsWeb );
				
				$resSubgroups  = [];
				foreach ($subgroups as &$subgroup){
					$dat = [];
					$subgroupLink = 'items/?' . 'partner=' . $partner . '&' . 'act=items' . '&' .  'data=' . urlencode($subgroup['link']) . '&brc=' . urlencode($group['name']);
					$dat['link'] = $subgroupLink;
					$dat['name'] = $subgroup["name"];
					$resSubgroups[] = 	$dat;
				
				}
				// add new group with subgroups
				$res[] = array (
					'name' => $group['name'],
					'link' => $groupLink,
					'subgroups' => $resSubgroups
				);
			}			
		}

		// write to cache
		$this->dataCache->setData( $data, $res );
		
		return $res;
	
	}		
	


	function getItems( $data, $itemPage, $sortParam, $sortOrder){
		/* Generate items for a given subcategorie
		$data: assoc_array( 'partner_code',  'code', 'name_f', or 'partner_code', 'data'  - for zp.ua)
		return: for ad partner:  return: array(    assoc_array(item, brand, name, desc, price, retail, imgs)   ). imgs - array(   assoc_array(item, tab, id, url),.. )	
			for zp.ua:  array (   assoc_array( 'img',  'producer', 'name',  'price',  'cade', 'desc' = "")     )  
		*/
		
		$res = [];   // accumulator	
		// shows wether the items are retrieved from the cache
		$fromCashe = False;
		
		$cv = $this->dataCache->getData( $data );
		if ( $cv ){
			$res = $cv;
			$fromCashe = True;
		}
		
		$partner = $data['partner_code'];
		
		// if no data found in cache retrive it from the sites
		if ( ! $fromCashe ){
			// retrive from the ad
			if ( (string)$partner == PARTNER_CODE_AD  ){
				$modelCode = $data["code"];
				// get the items
				$itemsWeb = $this->siteAD->getItems( $data["code"], $data["name_f"] );
				// get the items
				$items = $this->siteAD->parseItems( $itemsWeb );
				foreach ( $items as &$item){
					$img = "";
					if (count($item['imgs']) > 0){
						$img = $item['imgs'][0]['url'];
					}
					$res[] = array(
						'code' => $item['item'],
						'name' => $item['name'],
						'producer' => $item['brand'],
						'price' => $item['price'],
						'desc' => $item['desc'],
						'img' => $img
						
					);
				}
			}
			else  { // retrive from zp.ua
				$firstItemsPageLink = $this->siteZP->linkComplete( $data['data'] );
				$firstItemsPageWeb =  $this->siteZP->getWeb( $firstItemsPageLink  );
				$pages = [];
				$pages = $this->siteZP->parseItemsPages	( $firstItemsPageWeb );
				// get the array of links to all items pages
				$pages[] = $firstItemsPageLink;
				// parse each page
				
				$itemsAcc = [];
				foreach ( $pages as &$page){
					$pageLink = $this->siteZP->linkComplete( $page );
					$pageWeb =  $this->siteZP->getWeb( $pageLink  );
					$items = $this->siteZP->parseItems ( $pageWeb );
					$itemsAcc = array_merge( $itemsAcc, $items );
				}
				
				foreach ( $itemsAcc as &$item){
					$res[] = array(
						'code' => $item['plant_number'],
						'name' => $item['name'],
						'producer' => $item['producer'],
						'price' => $item['price'],
						'desc' => "",
						'img' => $item['img']
						
					);				
				}
			}			
		}
		
		// if result was not get from the cache, save it to cache
		if  (! $fromCashe ){
			$this->dataCache->setData( $data, $res );
		}
		
		// sort them
		$this->itemSorter->sort( $res, $sortParam, $sortOrder);
		
		// slice the items
		$maxPagesN = ( count($res) % ITEMS_ON_PAGE > 0  )? count($res) / ITEMS_ON_PAGE + 1 : count($res) / ITEMS_ON_PAGE;
		if ($itemPage > $maxPagesN ){
			$itemPage = $maxPagesN;
		}
		
		$pageItems['items'] = NULL;
		//if the page is not the last 
		if ($itemPage < $maxPagesN){
			$pageItems['items'] = array_slice($res, ($itemPage-1)*ITEMS_ON_PAGE, ITEMS_ON_PAGE );
		}
		else{
			$pageItems['items'] = array_slice($res, ($itemPage-1)*ITEMS_ON_PAGE );
		}
		
		
		// add page links
		$linksArr = [];
		for($i=1; $i<=$maxPagesN; $i++){
			$link = "";
			if ( (string)$partner == PARTNER_CODE_AD  ){
			
				$link = '?' . 'partner=' . $partner . '&' . 'act=items' . '&' .  'code=' . $data["code"] . '&' .  'name_f=' . $data['name_f'] . '&sort=' . $sortParam . '&sort_ord=' . $sortOrder;

			}
			else{
				$link ='?' . 'partner=' . $partner . '&' . 'act=items' . '&' .  'data=' . urlencode($data['data']) . '&sort=' . $sortParam . '&sort_ord=' . $sortOrder;;
			}
			
			if ($i > 1){
				$link .= ('&pg=' . $i);
			}			
			
			$linksArr[] = $link;
		}

		$pageItems['page_links'] = $linksArr;
		
		return $pageItems;
		
	}
		

	function getSearchItems( $data, $itemPage, $sortParam, $sortOrder ){
		/* Generate items for a given search query
		$data: assoc_array( 'partner_code',  'data', or 'partner_code', 'data'  - for zp.ua)
		return: for ad partner:  return: array(    assoc_array(item, brand, name, desc, price, retail, imgs)   ). imgs - array(   assoc_array(item, tab, id, url),.. )	
			for zp.ua:  array (   assoc_array( 'productid',  'img',  'producer', 'name',  'price',  'catalog_number',  'plant_number')     )  
		*/
		
		$res = [];   // accumulator	
		// shows wether the items are retrieved from the cache
		$fromCashe = False;
		
		$cv = $this->dataCache->getData( $data );
		if ( $cv ){
			$res = $cv;
			$fromCashe = True;
		}
		
		//$partner = $data['partner_code'];
		
		// if no data found in cache retrive it from the sites
		if ( ! $fromCashe ){
			// retrive from the ad
			//if ( (string)$partner == PARTNER_CODE_AD  ){
			// search in AD
    			//if (!$this->siteAD->isLoggedIn()){
    		    //    $this->siteAD->login();
    		    //}
				$itemsWeb = $this->siteAD->getSearchItems($data['data']);
				$items = $this->siteAD->parseItems( $itemsWeb );
				foreach ( $items as &$item){
					$img = "";
					if (count($item['imgs']) > 0){
						$img = $item['imgs'][0]['url'];
					}
					$res[] = array(
						'code' => $item['item'],
						'name' => $item['name'],
						'producer' => $item['brand'],
						'price' => $item['price'],
						'desc' => $item['desc'],
						'img' => $img
						
					);
				}
				
			//}
			//else  { // retrive from zp.ua
			// search in ZP
				$firstItemsPageLink = $this->siteZP->linkComplete( "/search/?search=" . $data['data'] );
				
				$firstItemsPageWeb =  $this->siteZP->getWeb( $firstItemsPageLink  );
				// take only first page
				$pages[] = $firstItemsPageLink;				
				$itemsAcc = [];
				foreach ( $pages as &$page){
					$pageLink = $this->siteZP->linkComplete( $page );
					$pageWeb =  $this->siteZP->getWeb( $pageLink  );
					$items = $this->siteZP->parseItems ( $pageWeb );
					$itemsAcc = array_merge( $itemsAcc, $items );
				}
				
				foreach ( $itemsAcc as &$item){
					$res[] = array(
						'code' => $item['plant_number'],
						'name' => $item['name'],
						'producer' => $item['producer'],
						'price' => $item['price'],
						'desc' => "",
						'img' => $item['img']
						
					);				
				}
			//}			
		}
		
		// if result was not get from the cache, save it to cache
		if  (! $fromCashe ){
			$this->dataCache->setData( $data, $res );
		}
		
		// sort them
		$this->itemSorter->sort( $res, $sortParam, $sortOrder);
		
		// slice the items
		$maxPagesN = ( count($res) % ITEMS_ON_PAGE > 0  )? count($res) / ITEMS_ON_PAGE + 1 : count($res) / ITEMS_ON_PAGE;
		if ($itemPage > $maxPagesN ){
			$itemPage = $maxPagesN;
		}
		
		$pageItems['items'] = NULL;
		//if the page is not the last 
		if ($itemPage < $maxPagesN){
			$pageItems['items'] = array_slice($res, ($itemPage-1)*ITEMS_ON_PAGE, ITEMS_ON_PAGE );
		}
		else{
			$pageItems['items'] = array_slice($res, ($itemPage-1)*ITEMS_ON_PAGE );
		}
		
		
		// add page links
		$linksArr = [];
		for($i=1; $i<=$maxPagesN; $i++){
			$link = "";
			$link ='?' . 'act=search' . '&' .  'data=' . urlencode($data['data']) . '&sort=' . $sortParam . '&sort_ord=' . $sortOrder;
			//if ( (string)$partner == PARTNER_CODE_AD  ){
			
			//	$link = '?' . 'act=search' . '&' .  'data=' . $data["data"]  . '&sort=' . $sortParam . '&sort_ord=' . $sortOrder;

			//}
			//else{
			//	$link ='?' . 'act=search' . '&' .  'data=' . urlencode($data['data']) . '&sort=' . $sortParam . '&sort_ord=' . $sortOrder;
			//}
			
			if ($i > 1){
				$link .= ('&pg=' . $i);
			}			
			
			$linksArr[] = $link;
		}

		$pageItems['page_links'] = $linksArr;
		
		return $pageItems;
		
	}
	
	
	function getVINSearch($data){
		/*	Get models by VIN
			@return:      array( assoc_array(   brand,   model,   data   )    ) ,  data -  assoc_array( date, framecolor, options, description,  model,  prodPeriod, trimcolor )   
		*/

		$cv = $this->dataCache->getData( $data );
		if ( $cv ){
			$res = $cv;
			
			return $res;
		}		
	
		$searchData = $data['data'];
		// search vin only for ad partner
		$searchVINWeb = $this->siteAD->getSearchVIN($searchData);
		
		$res = $this->siteAD->parseSearchVIN($searchVINWeb);
		
		// set data to cache
		$this->dataCache->setData( $data, $res );
		
		return $res;
		
	}


	function getGroupsVIN($data){
		/*	Get menu groups by VIN
			return array( assoc_array( name, code, data  ) ), data - assoc_array (name, code, data)   
		*/

		$cv = $this->dataCache->getData( $data );
		if ( $cv ){
			$res = $cv;
			
			return $res;
		}		
	
		$searchData = $data['data'];
		// search vin only for ad partner
		$groupsVINWeb = $this->siteAD->getGroupsVIN($searchData);
		
		
		$res = $this->siteAD->parseGroupsVIN( $groupsVINWeb  );	
		
		// set data to cache
		$this->dataCache->setData( $data, $res );
		
		return $res;
		
	}
	
	
	function getSubcategoriesVIN($data){
		/*	return  array(  assoc_array( name, code,  image, data ) ),   data  -  assoc_array( code, name, codeF  )      */
		$cv = $this->dataCache->getData( $data );
		if ( $cv ){
			$res = $cv;
			
			return $res;
		}

		$vin = $data['vin'];
		$id = $data['code'];
		// search vin only for ad partner
		$vinWeb = $this->siteAD->getSubcategoriesVIN($id, $vin);
		
		
		$res = $this->siteAD->parseSubcategoriesVIN( $vinWeb );	
		
		// set data to cache
		$this->dataCache->setData( $data, $res );
		
		return $res;		
	}
	
	
	function getItemsVIN( $data ){
		/*	return  array(  assoc_array( item, brand,  name, desc, price, retail, img) )   */
		$cv = $this->dataCache->getData( $data );
		if ( $cv ){
			$res = $cv;
			
			return $res;
		}

		$vinWeb = $this->siteAD->getItemsVIN($data['data']);
		$res = $this->siteAD->parseItemsVIN( $vinWeb );	

		// set data to cache
		$this->dataCache->setData( $data, $res );
		
		return $res;		
		
	}
	
	
	function getReplacedItemsVIN( $data ){
		/* 	
			return: array(  assoc_array( item, brand, name, desc,  price, retail)   ) 
		*/
		$cv = $this->dataCache->getData( $data );
		if ( $cv ){
			$res = $cv;
			
			return $res;
		}
		
		$vinWeb = $this->siteAD->getReplacedItemsVIN($data['data']);
		$res = $this->siteAD->parseReplacedItemsVIN( $vinWeb );	

		// set data to cache
		$this->dataCache->setData( $data, $res );
		
		return $res;
		
	}
	
	
	function getApplicationItemsVIN( $data ){
		/* 
			return: array(  assoc_array( brand, name)   ) 
		*/
		$cv = $this->dataCache->getData( $data );
		if ( $cv ){
			$res = $cv;
			
			return $res;
		}
		
		$vinWeb = $this->siteAD->getApplicationItemsVIN($data['data']);
		$res = $this->siteAD->parseApplicationItemsVIN( $vinWeb );	

		// set data to cache
		$this->dataCache->setData( $data, $res );
		
		return $res;		
		
	}		
	
	

}






?>
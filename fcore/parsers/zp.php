<?php

/*
Classes for working with zp.ua partner
Both for loading /parsing web content and saving/fetching it from the database

*/


include_once('curl_query.php');
include_once( dirname(__DIR__)  . DIRECTORY_SEPARATOR . 'config.php');
include_once( dirname(__DIR__)  . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . 'logger' . DIRECTORY_SEPARATOR . 'Logger.php');
include_once( dirname(__DIR__)  . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . 'simple_html_dom.php');




class SiteZP {
	/*	Get web content of the partner https://detali.zp.ua  and parse it	*/
	
	// settings
    var $COOKIE_FILE = NULL;
	var $REFERER = NULL;
	var $log = NULL;
	var $BASE_SITE_URL = NULL;
	
    function __construct(){		
        $this->COOKIE_FILE = __DIR__ . "/cookie_zp.txt";
        $this->REFERER = "https://detali.zp.ua";
		
		$this->log = Logger::getLogger('siteLogger');
		$this->BASE_SITE_URL = "https://detali.zp.ua";
    }
	
	
	function linkComplete( $link ){
		/* Add to the link hostname if the given link dousn't contain it  */
		
		$pos = strpos($link, $this->BASE_SITE_URL);

		if($pos === false) {
			// link is not full, complete
			return $this->BASE_SITE_URL . $link;
		}
		
		return $link;
	
	}
	
	
	function getWeb( $link ){
		/*	Universal function for getting web content by links	*/
		
		$postFields = [];
		
		$data = curl_query_ad($link,
					$this->REFERER,
					"GET", 
					$this->COOKIE_FILE,
					NULL,
					$postFields  );	

		return $data;	
	}
	
	
    function getBrands(){
        /*	Get the array of all the brands		*/	
		
		$ret = $this->getWeb("https://detali.zp.ua");

        return $ret;
	}
	
	
    function parseBrands( $data ){
        /*	Parse the brand's data that was reseaved from the getBrands() method.
			return:   arrray(  assoc_array( name, link)   )
		*/
		
        function getName( $data ){
			/*	Auxiliary function, get name of the brand	*/
			
			$regex1 = '/<td[ ]+class=[\'\"]cats-item-title[\'\"][\s\S]+?<\/td>/';
			if (preg_match($regex1, $data, $matches_out)){
				$nameData = $matches_out[0];
				
				$regex2 = "/>[\s\S]+?</";
				if (preg_match($regex2, $nameData, $matches2)){
					$lenM2 = strlen($matches2[0]);
					$name = mb_substr($matches2[0], 1, $lenM2-2);
					
					$regex3 = "/.+/";
					if (preg_match($regex3, $name, $matches3)){
						
						return $matches3[0];
					}
				}
			}
		}
			
        function getLink( $dataLink, $base_url ){
			/*	Auxiliary function, get full link of the brand	*/
			
			$regex1 = '/<a[ ]+href=[\'\"]\/catalog\/.+?[\'\"]/';
			if ( preg_match($regex1, $dataLink, $matches1) ){
				$linkData = $matches1[0];
				$regex2 = '/\".+?\"/';
				if (preg_match($regex2, $linkData, $matches2 )){
					$lenM2 = strlen($matches2[0]);
					$linkPart = mb_substr($matches2[0], 1, $lenM2-2);
					$fullLink = $base_url . $linkPart;
					
					return $fullLink;
				}
			}		
		}
		
		
		preg_match_all("/<div[ ]+class=[\'\"]cats-item[\'\"]>[\s\S]+?div[ ]*>/", 
						$data,
						$matches, PREG_PATTERN_ORDER);
									
		// loop through links		
        $res = [];		
		foreach ($matches[0] as &$match) {
		
			$brand = getName( $match );
			$link = getLink( $match, $this->BASE_SITE_URL );
			
			$res[] = array(
				'name' => $brand,
				'link' => $link
			);		
		}

		return	$res;	
			
	}
	
	
    function parseModels( $data ){
        /*	Parse the model's data 
			return:   arrray(  assoc_array( name, link)   )
		*/
		
		
		
        function getNameM( $data ){
			/*	Auxiliary function, get name of the model	*/
			
			$regex1 = '/<div[ ]+itemprop=[\'\"]name[\'\"]>[\s\S]+?<[ ]*\/[ ]*div[ ]*>/';
			if (preg_match($regex1, $data, $matches_out)){
				$nameData = $matches_out[0];
				
				$regex2 = "/>[\s\S]+?</";
				if (preg_match($regex2, $nameData, $matches2)){
					$lenM2 = strlen($matches2[0]);
					$name = mb_substr($matches2[0], 1, $lenM2-2);
					
					$regex3 = "/.+/";
					if (preg_match($regex3, $name, $matches3)){
						
						return $matches3[0];
					}
				}
			}
		}
			
        function getLinkM( $dataLink, $base_url ){
			/*	Auxiliary function,  get part link of the model	*/
			
			$regex1 = '/<a[ ]+href=[\'\"]\/catalog\/.+?[\'\"]/';
			if ( preg_match($regex1, $dataLink, $matches1) ){
				$linkData = $matches1[0];
				$regex2 = '/\".+?\"/';
				if (preg_match($regex2, $linkData, $matches2 )){
					$lenM2 = strlen($matches2[0]);
					$linkPart = mb_substr($matches2[0], 1, $lenM2-2);
					$fullLink = $linkPart;
					
					return $fullLink;
				}
			}		
		}
		
		preg_match_all("/<div[ ]+class=[\'\"]sub-cats-item[\'\"]>[\s\S]+?div[ ]*>/", 
						$data,
						$matches, PREG_PATTERN_ORDER);
									
		// loop through links		
        $res = [];		
		foreach ($matches[0] as &$match) {
			$name = getNameM( $match );
			$link = getLinkM( $match, $this->BASE_SITE_URL );
			
			$res[] = array(
				'name' => $name,
				'link' => $link
			);		
		}

		return	$res;	
			
	}
	
	
	
    function parseCategories( $data ){
        /*	Parse the categories's data 
			return:   arrray(  assoc_array( name, link)   )
		*/
		
		
        function getNameC( $data ){
			/*	Auxiliary function, get name of the category	*/
			
			$regex1 = '/<div[ ]+itemprop=[\'\"]name[\'\"]>[\s\S]+?<[ ]*\/[ ]*div[ ]*>/';
			if (preg_match($regex1, $data, $matches_out)){
				$nameData = $matches_out[0];
				
				$regex2 = "/>[\s\S]+?</";
				if (preg_match($regex2, $nameData, $matches2)){
					$lenM2 = strlen($matches2[0]);
					$name = mb_substr($matches2[0], 1, $lenM2-2);
					
					$regex3 = "/.+/";
					if (preg_match($regex3, $name, $matches3)){
						
						return $matches3[0];
					}
				}
			}
		}
			
        function getLinkC( $dataLink, $base_url ){
			/*	Auxiliary function, get full link of the category	*/
			
			$regex1 = '/<a[ ]+href=[\'\"]\/catalog\/.+?[\'\"]/';
			if ( preg_match($regex1, $dataLink, $matches1) ){
				$linkData = $matches1[0];
				$regex2 = '/\".+?\"/';
				if (preg_match($regex2, $linkData, $matches2 )){
					$lenM2 = strlen($matches2[0]);
					$linkPart = mb_substr($matches2[0], 1, $lenM2-2);
					$fullLink =  $linkPart;
					
					return $fullLink;
				}
			}		
		}

		preg_match_all("/<div[ ]+class=[\'\"]sub-cats-item[\'\"]>[\s\S]+?div[ ]*>/", 
						$data,
						$matches, PREG_PATTERN_ORDER);
									
		// loop through links		
        $res = [];		
		foreach ($matches[0] as &$match) {
		
			$name = getNameC( $match );
			$link = getLinkC( $match, $this->BASE_SITE_URL );
					
			$res[] = array(
				'name' => $name,
				'link' => $link
			);		
		}


		return	$res;	
			
	}	
	
	
	
    function parseSubcategories( $data ){
        /*	Parse the subcategories's data 
			return:   arrray(  assoc_array( name, link)   )
		*/
		

		preg_match_all("/<div[ ]+class=[\'\"]sub-cats-item[\'\"]>[\s\S]+?div[ ]*>/", 
						$data,
						$matches, PREG_PATTERN_ORDER);
									
		// loop through links		
        $res = [];		
		foreach ($matches[0] as &$match) {
			$name = $this->getNameSub( $match );
			$link = $this->getLinkSub( $match, $this->BASE_SITE_URL );
			
			$res[] = array(
				'name' => $name,
				'link' => $link
			);		
		}

		return	$res;	
			
	}
	

    private function getNameSub( $data ){
		/*	Auxiliary function, get name of the subcategorie	*/
		
		$regex1 = '/<div[ ]+itemprop=[\'\"]name[\'\"]>[\s\S]+?<[ ]*\/[ ]*div[ ]*>/';
		if (preg_match($regex1, $data, $matches_out)){
			$nameData = $matches_out[0];
			
			$regex2 = "/>[\s\S]+?</";
			if (preg_match($regex2, $nameData, $matches2)){
				$lenM2 = strlen($matches2[0]);
				$name = mb_substr($matches2[0], 1, $lenM2-2);
				
				$regex3 = "/.+/";
				if (preg_match($regex3, $name, $matches3)){
					
					return $matches3[0];
				}
			}
		}
	}


    private function getLinkSub( $dataLink, $base_url ){
		/*	Auxiliary function, get full link of the brand	*/
		
		$regex1 = '/<a[ ]+href=[\'\"]\/catalog\/.+?[\'\"]/';
		if ( preg_match($regex1, $dataLink, $matches1) ){
			$linkData = $matches1[0];
			$regex2 = '/\".+?\"/';
			if (preg_match($regex2, $linkData, $matches2 )){
				$lenM2 = strlen($matches2[0]);
				$linkPart = mb_substr($matches2[0], 1, $lenM2-2);
				$fullLink = $linkPart;
				
				return $fullLink;
			}
		}		
	}	


    function parseItemsPages( $data ){
        /*Parse first item page
		    return: array ( fulllink )
		*/
		
		// result accumulator
        $res = [];

		$regex1 = '/<[ ]*div[ ]+class=[\'\"]catalog-navigation[\'\"][ ]*>[\s\S]+?<\/[ ]*div[ ]*>/';
		if ( preg_match($regex1, $data, $matches1) ){	
			$regex2 = '/href=[\'\"]\/catalog\/.+?[\'\"]/';
			preg_match_all($regex2, 
						$matches1[0],
						$matches2, PREG_PATTERN_ORDER);
											
			foreach ($matches2[0] as &$match2) {
				$regex3 = '/[\'\"][\s\S]+?[\'\"]/';
				if ( preg_match($regex3, $match2, $matches3) ){
					$lenM3 = strlen($matches3[0]);
					$link = $this->BASE_SITE_URL . mb_substr($matches3[0], 1, $lenM3-2);
					
					if (! in_array($link, $res)) {
						$res[] = $link;
					}
				}
			}
		}
		
		return $res;
			
	}


//	function parseItems( $data ){
//		/*Parse items from the particular page
//			return: array (   assoc_array( 'productid',  'img',  'producer', 'name',  'price',  'catalog_number',  'plant_number')     )  
//		*/
		
/*		$html = new simple_html_dom();   
		$html->load( $data );  
		// het items
		$items = $html->find(".product-list .list-item"); 
		
		$res = []; // accumulator for items data
		foreach ($items as &$itemObj) {
			// accumulator for a current item data
			$itemData = [];
			
			// add procuct id
			$itemdata['productid'] = $itemObj->getAttribute("data-productid");
			
			// add producer
			$manufArr = $itemObj->find(".list-manufacturer");
			foreach ($manufArr as &$manudObj) {
				$itemdata['producer'] = $manudObj->innertext;
			}
			
			// add full image
			$imgArr = $itemObj->find(".list-image img");
			foreach ($imgArr as &$imgObj) {
				$itemdata['img'] = $this->BASE_SITE_URL . $imgObj->getAttribute("data-full-img");
			}

			// add name
			$nameArr = $itemObj->find(".list-title a.fw_b");
			foreach ($nameArr as &$nameObj) {
				$itemdata['name'] =  $nameObj->innertext;
			}

			// add price
			//$priceArr = $itemObj->find(".list-status span[itemprop=price]");
			$priceArr = $itemObj->find(".list-status span[itemprop=price], .list-status .fw_b");
			foreach ($priceArr as &$priceObj) {
				$itemdata['price'] = "";
				
				// try to format the price
				$regexPr = '/[0-9.,]+/';
				if ( preg_match($regexPr, ( $priceObj->innertext ), $matchesPr) ){
					$itemdata['price'] =  (float) $matchesPr[0];
				}
				else{
					$itemdata['price'] = $priceObj->innertext ;
				}
			}

			// add catalog and plant numbers
			$catalogNumArr = $itemObj->find(".list-content .list-parameters span.c_catalog_num");
			$catalogCount = 0;
			foreach ($catalogNumArr as &$catalogNumObj) {
				if ($catalogCount == 0){
					$itemdata['catalog_number'] = $catalogNumObj->innertext;
				}
				else{
					$itemdata['plant_number'] = $catalogNumObj->innertext;
				}
				$catalogCount += 1;
			}
			
			// add item's data to the accumulator
			$res[] = $itemdata;

		}

		return $res;		
	
	}
*/	
	

	function parseItems( $data ){
		/*Parse items
		return: array (   assoc_array( 'productid',  'img',  'producer', 'name',  'price',  'catalog_number',  'plant_number')     )  
		*/
		
	
		$res = []; //accum
		$itemsReg = "/<div[ ]+class=[\'\"]list-item[\'\"][\s\S]+?[\s\n]*<\/span>[\s\n]*<\/div>[\s\n]*<\/div>[\s\n]*<\/div>/";
		
		if (preg_match_all($itemsReg, 
						$data,
						$matches, PREG_PATTERN_ORDER)){
						
			$itemdata = [];
						
			foreach($matches[0] as &$item){
			
				$itemdata['producer'] = $this->getProducer($item);
				// do not take images because of water marks
				$itemdata['img']  = "";//$this->getImage($item);
				$itemdata['name'] = $this->getName($item);
				// for search query the price is get differently
				$price = $this->getPrice($item) * (1 + PARTNER_MARKUP_ZP);
				$itemdata['price'] = ($price > 0) ? $price : $this->getSearchPrice($item) * (1 + PARTNER_MARKUP_ZP);
				$plantCode = $this->getPlantCode($item);
				$itemdata['plant_number'] = (trim($plantCode) != ".") ? $plantCode : "";

				$res[] = $itemdata;
				
				$this->getSearchPrice($item);
			}			
		}
		
		return $res;
	
	}


	function getName($item){
	
		$reg1 = "/<a[ ]+class=[\'\"]fw_b block[\'\"][\s\S]+?<\/a>/";
		if ( preg_match($reg1, $item, $matches1) ){
			
			$reg2 = "/title=[\'\"][\s\S]+?[\'\"]/";
			if ( preg_match($reg2, $matches1[0], $matches2) ){
				$name = substr($matches2[0], 7, strlen($matches2[0]) - 8) ;
				return $name;
				
			}
		}
		
		return "";
	}
	
	function getImage($item){
	
		$reg1 = "/<img[\s\S]+?data-full-img=[\'\"][\s\S]+?[\'\"]/";	
		if ( preg_match($reg1, $item, $matches1) ){	

			$reg2 = "/data-full-img=[\'\"][\s\S]+?[\'\"]/";
			if ( preg_match($reg2, $matches1[0], $matches2) ){	
				$img = substr($matches2[0], 15, strlen($matches2[0]) - 16) ;
				return $this->BASE_SITE_URL . $img;
			}
			
		}
		
		return "";
	}
	
	
	function getPrice($item){
	
		$reg1 = "/<span[ ]+itemprop=[\'\"]price[\'\"][\s\S]+?<\/span>/";	
		if ( preg_match($reg1, $item, $matches1) ){	
			$reg2 = "/>[\s\S]+?</";
			if ( preg_match($reg2, $matches1[0], $matches2) ){	
				$price = substr($matches2[0], 1, strlen($matches2[0]) - 2) ;
				return $price;
			}	
		}
		
		return 0;
	}
	
	
	
	function getSearchPrice($item){
	
		$reg1 = "/<span[ ]+class=[\'\"]fw_b[ ]+fs_18[ ]+ff_o[ ]+c_gold[\'\"][\s\S]+?\/span>/";	
		if ( preg_match($reg1, $item, $matches1) ){
			$reg2 = "/<span>[\s\S]+?<\/span>/";
			if ( preg_match($reg2, $matches1[0], $matches2) ){
				$reg3 = "/>[\s\S]+?</";
				if ( preg_match($reg3, $matches2[0], $matches3) ){	
					$price = substr($matches3[0], 1, strlen($matches3[0]) - 2) ;
					return $price;
				}
			}	
		}
		
		return 0;
	}
	

	function getPlantCode($item){
	
		$reg1 = "/<span[ ]+class=[\'\"]c_catalog_num[\'\"][\s\S]+?\/span>/";	
		if ( preg_match($reg1, $item, $matches1) ){	
			$reg2 = "/>[\s\S]+?</";
			if ( preg_match($reg2, $matches1[0], $matches2) ){	
				$code = substr($matches2[0], 1, strlen($matches2[0]) - 2) ;
				return $code;
			}	
		}
		
		return "";
	}
	
	
	function getProducer($item){
	
		$reg1 = "/<div[ ]+class=[\'\"]list-manufacturer[ ]+fw_b[\'\"][\s\S]+?\/div>/";	
		if ( preg_match($reg1, $item, $matches1) ){	
			$reg2 = "/>[\s\S]+?</";
			if ( preg_match($reg2, $matches1[0], $matches2) ){	
				$producer = substr($matches2[0], 1, strlen($matches2[0]) - 2) ;
				return $producer;
			}	
		}
		
		return "";
	}
	
	
	
}



?>
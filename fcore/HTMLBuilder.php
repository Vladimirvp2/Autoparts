<?php

/*
Classes to build html markup based on the provided data
*/


include_once('config.php');
include_once('libs/logger/Logger.php');
include_once('data.php');
include_once('breadcrumbs.php');
include_once( 'general_functions.php');




		

class HTMLBuilder{
/*	Build html markup based on the provided data	*/

    function __construct(){
		
		$this->log = Logger::getLogger( LOGGER_NAME );
		$this->data = new Data();
		$this->breadCrumbs = new Breadcrumbs();	
		
		$this->lastItemsSearchEmpty = false;
    }


	function getBrandsMarkup(){
	/*	Construct html panel for brands	*/
		
		$colunmCount = 4;
		// html templates
		$brandFirstLetterT = "<h4 class='car-first-row'><span class='first-letter'>%s</span><a class='car-first-name' href='%s'>%s</a></h4>"; // 3 param (first letter, link, name)
		$brandT ="<h4><a class='car-name' href='%s'>%s</a></h4>";  // 2 param ( link, name)
		$columnContainerT = "<div class='col-lg-3 col-md-3 col-sm-3 table-row-content'>%s</div>"; // 1 param ( content )
		
		$brands = $this->data->getBrands();
		// define the nymber of brands in each column
		$brandsInColumn = count($brands) / $colunmCount;
		
		$currFirstLetter = "";
		$res = "";
		
		$brandsInCurrCol = 0;  // counter of the brands in the current column 
		$colAcc = "";
		foreach ($brands as &$brand){
			// if the column is filled add it to the accumulator
			if ( $brandsInCurrCol >= $brandsInColumn){
				$col = sprintf($columnContainerT, $colAcc);
				$res .= $col;
				// reset for a new column
				$colAcc = "";
				$brandsInCurrCol = 0;
			}
			
			// add brand
			$name = $brand['text'];
			$link = $brand['link'];	
			$firstLetter = strtoupper( substr($name, 0, 1 ) );	
			// check if new letter sterted
			if ( ($firstLetter  != $currFirstLetter) && preg_match("/[a-zA-Z0-9]+/", $firstLetter, $match)  ){
				$colAcc .= sprintf( $brandFirstLetterT, $firstLetter, $link, $name);
				$currFirstLetter = $firstLetter;
			}
			else{
				$colAcc .= sprintf( $brandT, $link, $name);
			}
			
			$brandsInCurrCol += 1;
		}
		
		// check - if last column is not empty, add it
		if (strlen($colAcc) > 0){
			$col = sprintf($columnContainerT, $colAcc);
			$res .= $col;			
		}
		
		return $res;
			
	
	}
	
	function getLineupMarkup( $data ){
		/*	Construct html panel for lineups
			Currently the method fetches data for only 1 partner - ad.ua
			$data - accos_array( 'partner_code', 'code' )
			return: html lineup structure
		*/
		
		$colunmCount = 2;
		// html templates
		$lineupT = "<h4><a class='lineup' href='%s'>%s</a></h4>";  // 2 params  link, name+year)
		$lineupContainerT = "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-12 table-row-content'>%s</div>"; // 1 param ( content )
		
		$lineups = $this->data->getLineups( $data );
		// define the nymber of brands in each column
		$lineupsInColumn = count($lineups) / $colunmCount;
		
		$lineupsInCurrCol = 0;  // counter of the lineups in the current column 
		$colAcc = "";
		foreach ($lineups as &$lineup){
			// if the column is filled add it to the accumulator
			if ( $lineupsInCurrCol >= $lineupsInColumn){
				$col = sprintf($lineupContainerT, $colAcc);
				$res .= $col;
				// reset for a new column
				$colAcc = "";
				$lineupsInCurrCol = 0;
			}

			// add lineup
			$name = $lineup['name'];
			$year = $lineup['year'];
			$link = $lineup['link'];	

			$colAcc .= sprintf( $lineupT, $link, $name . ', ' . $year );
			
			$lineupsInCurrCol += 1;	
		}
		
		// check - if last column is not empty, add it
		if (strlen($colAcc) > 0){
			$col = sprintf($lineupContainerT, $colAcc);
			$res .= $col;			
		}
		
		return $res;
	}


	function getModelsMarkup( $data ){
		/*	Construct html panel for models
			$data - accos_array( 'partner_code', 'code' for ad.ua and  'data' for zp.ua )
			return: html lineup structure
		*/
		
		$columnCount = 2;
		// html templates
		$modelT = "<h4><a class='model' href='%s'>%s</a></h4>";  // 2 params  link, name+year)
		$modelContainerT = "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-12 table-row-content'>%s</div>"; // 1 param ( content )
		
		$models = $this->data->getModels( $data );
		// define the nymber of brands in each column
		$modelsInColumn = count($models) / $columnCount;		
	
		$modelsInCurrCol = 0;  // counter of the models in the current column 
		$colAcc = "";
		$partnerCode = $data['partner_code'];
		foreach ($models as &$model){
			// if the column is filled add it to the accumulator
			if ( $modelsInCurrCol >= $modelsInColumn ){
				$col = sprintf($modelContainerT, $colAcc);
				$res .= $col;
				// reset for a new column
				$colAcc = "";
				$modelsInCurrCol = 0;
			}

			// add model
			if ($partnerCode == PARTNER_CODE_AD){
				$name = $model['name'];
				$body = $model['body'];
				$year = $model['year'];
				$link = $model['link'];	

				$colAcc .= sprintf( $modelT, $link, $name . ', ' . $body . ', ' . $year );
			}
			else if ($partnerCode == PARTNER_CODE_ZP){
				$name = $model['name'];
				$link = $model['link'];	

				$colAcc .= sprintf( $modelT, $link, $name );				
			}
			
			$modelsInCurrCol += 1;	
		}
		
		// check - if last column is not empty, add it
		if (strlen($colAcc) > 0){
			$col = sprintf($modelContainerT, $colAcc);
			$res .= $col;			
		}
		
		return $res;
	}


	function getGroupsMarkup( $groupsTitle, $subgroupsTitle, $data ){
		/*	Construct html panel for groups and subgroups
			$data - assoc_array( 'partner_code', 'code' for ad.ua and  'data' for zp.ua )
			return: html groups and subgroups structure
		*/
		
		$columnCount = 2;
		// html templates
		$groupT = "<h4 class='items-category-container'><a class='items-subcategory' cat_id='%s' href='%s'>%s</a></h4>"; // 3 params -  cat_id, link, name
		$groupTitleT = "<h3 class='items-category-title'>%s</h3>"; // 1 param - name
		$groupsContainerT = "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-12 table-row-content'>%s</div>";  // 1 param - content
		
		$subgroupT = "<h4 class='items-subcategory-container hidden' cat_id='%s'><a class='items-subcategory' href='%s'>%s</a></h4>"; // 3 params - lcat_id, ink, name
		$subgroupTitleT = "<h3 class='items-subcategory-title'>%s</h3>"; // 1 param - name
		$subgroupsContainerT = "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-12 table-row-content'>%s</div>";  // 1 param - content
		
		
		//$modelT = "<h4><a class='model' href='%s'>%s</a></h4>";  // 2 params  link, name+year)
		//$modelContainerT = "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-12'>%s</div>"; // 1 param ( content )
		
		$groupsAcc = ""; // groups container
		$groupsAcc .= sprintf($groupTitleT, $groupsTitle);
		$subgroupsAcc = ""; // subgroups container
		$subgroupsAcc .= sprintf($subgroupTitleT, $subgroupsTitle);
		$groups = $this->data->getCategories( $data );
		
		$currCategoryId = 0;
		foreach ($groups as &$group){
			$groupName = $group['name'];
			$groupLink = $group['link'];
			$subgroups = $group['subgroups'];
			// add group html
			$groupsAcc .= sprintf($groupT, $currCategoryId, $groupLink, $groupName);
			foreach ($subgroups as &$subgroup){
				$subgroupName = $subgroup['name'];
				$subgroupLink = $subgroup['link'];
				// add subgroup html
				$subgroupsAcc .= sprintf($subgroupT, $currCategoryId, $subgroupLink, $subgroupName);	
			}

			$currCategoryId += 1;
		}
		
		// add groups and subgroups to the containers and add them
		$groupC = sprintf($groupsContainerT, $groupsAcc);	
		$subgroupC = sprintf($subgroupsContainerT, $subgroupsAcc);
		
		return $groupC . $subgroupC;
		
	}
	
	
	function getItemsMarkup( $data, $itemPage=1, $sortParam=DEFAULT_SORT_PARAM, $sortOrder=DEFAULT_SORT_ORDER, $searchLink = "https://detali.zp.ua/search/?search=Болт карданного" ){
	
	
		$itemImageLargeT = "<div class='image-no-click-container' style='padding: 10px 0px;'>
									<div data='%s' style=\"background: url('%s') no-repeat; background-size: contain; background-position: left; width: 100%s; height: 100%s;\" class='image-no-click' oncontextmenu='return false;'></div>
								</div>";  
								// 2 params - image path, image path
								
		$itemNoImageLargeT = "<p><i class='fa fa-camera items-no-image-large'  aria-hidden='true' ></i></p>";  //  0 params
		
		$itemImageSmallT = "<p class='items-image-small-container'><i class='fa fa-picture-o items-image-small image-no-click' aria-hidden='true' data='%s'></i></p>"; // 1 param - image path
		
		$itemNoImageSmallT = "<p class='items-image-small-container'><i class='fa fa-camera items-image-small' aria-hidden='true'></i></p>"; // 0 params
		
	
		$itemT = "
					<div class='item %s' page='%s'>

						<div class='row items-body'>
							<div class='col-lg-2 col-md-2 col-sm-2 hidden-xs item-image-container'>

								%s


							</div>
							<div class='col-lg-6 col-md-6 col-sm-6 col-xs-9'>
								<div class='items-body-content'>
									<p class='items-code'>%s</p>
									<p class='items-producer-wrapper'><span class='producer-word'>" . $GLOBALS['producer'] . "</span> <span class='items-procucer'> %s </span> </p>
									<p class='items-name'>%s</p>
									<p class='items-description'>%s </p>

									
								</div>
							</div>
							
							<div class='hidden-lg hidden-md hidden-sm col-xs-3'>
								%s

							</div>
							

							<div class='col-lg-4 col-md-4 col-sm-4 hidden-xs lg-price-container large-container'>
								<p class='price'>%s</p>


								<div class='cart-button-container'>								
								 	<input type='text' class='item-numbers' name='number' value='1'>
									<a class='cart-button hidden-sm hidden-xs' href='#'>
			  							<i class='fa fa-cart-arrow-down'></i> " 
			  							 . $GLOBALS['into_busket'] .
			  						"</a>
									<a class='cart-button hidden-lg hidden-md' href='#'>
			  							<i class='fa fa-cart-arrow-down'></i> 
			  						</a>
									
								</div>	

							</div>

							<div class='hidden-lg hidden-md hidden-sm col-xs-12 small-container'>

								<div class='row'>

									<div class='col-xs-8'>

										<div class='cart-button-container-small'>		

										 	<input type='text' class='item-numbers' name='number' value='1'>
											<a class='cart-button' href='#'>
					  							<i class='fa fa-cart-arrow-down'></i> 
					  						</a>
											
										</div>
									</div>
									<div class='col-xs-4'>
										<p class='price'>%s</p>
									</div>	
								</div>								
							</div>							


						</div>
						

					
					</div> 		
		
		"; // params  hidden or "", page,  largeImageTemp,  code, producer, name, desc,  smallImageTemp,  price, price
	
		$pageT = "<span><a class='items-page' href='%s'>%s</a></span>"; // 2 params - link, page
		$pageActiveT = "<span><a class='items-page active' href='%s'>%s</a></span>";  // 2params, - link, page
		$nextPageT = "<span><a class='items-page' href='%s'>&gt&gt</a></span>";  // 1 params - link
		
		
		$itemHeadT = "
					<div class='row'>
						<div class='col-lg-6'>
							
							<div class='items-pagination'>
								%s
							</div>
							
						</div>
						<div class='col-lg-6'>

							<div class='items-sorter'>
								<span>Сортувати за:</span>
							 	<select name='seq' id='soflow'>
    								<option value='&sort=name&sort_ord=ASC'>Назва, алф.</option>
								    <option value='&sort=name&sort_ord=DESC'>Назва, звор.</option>
								    <option value='&sort=price&sort_ord=ASC'>Ціна, зрост.</option>
								    <option value='&sort=price&sort_ord=DESC'>Ціна, спар.</option>
								</select>
							</div>
						</div>
						
					</div>		
		";  // 1 param - pages
		
		
		$bottomPageContT = "<div class='row'>
						<div class='col-lg-12'>
							
							<div class='items-pagination'>
								%s
							</div>
							
						</div>
					</div>";  // 1 param - comtent
	
	
		$res = "";
		$pagesLinkAcc = "";
		$partnerCode = $data['partner_code'];
		$items = $this->data->getItems( $data, $itemPage, $sortParam, $sortOrder);
		
		//create pagination
		$pageCounter = 1;
		$activePage = $itemPage;
		
		// create pagination only if there are more than 1 page
		if (count($items['page_links']) > 1) {
			foreach ($items['page_links'] as &$link){
				if ( $pageCounter == $activePage ){
					$pagesLinkAcc .= sprintf($pageActiveT, $link, $pageCounter);
				}
				else{
					$pagesLinkAcc .= sprintf($pageT, $link, $pageCounter);
				}
				
				$pageCounter++;
			}
		}
		
		//add pagination
		$res .= sprintf($itemHeadT, $pagesLinkAcc);
		
		
		foreach ($items['items'] as &$item){
		
			$largeImageTemp = "";
			$smallImageTemp = "";
			if (array_key_exists('img', $item) && (trim( $item['img'] ) != "") ){
				// image exist, create the templates
				$largeImageTemp = sprintf($itemImageLargeT, $item['img'], $item['img'], "%", "%");
				$smallImageTemp = sprintf($itemImageSmallT, $item['img']);
			}
			else{
				// no image
				$largeImageTemp = sprintf($itemNoImageLargeT);
				$smallImageTemp = sprintf($itemNoImageSmallT);				
			}
			$res .= sprintf($itemT, "", 1,  $largeImageTemp,  $item['code'], $item['producer'], $item['name'], $item['desc'], $smallImageTemp, priceFormat($item['price']), priceFormat($item['price']) );
		}
		
		//add bottom pagination
		$res .= sprintf($bottomPageContT, $pagesLinkAcc);
		
		
		return $res;
	
	}
	
	
	
	function getShopCartMarkup( $cartObj ){
		/*	Generate shop cart html marlup by the given cart object	*/
	
		$cartImageT = "<div class='cart-image-no-click-container'>
							<div oncontextmenu='return false;' data='%s' style=\"background: url('%s') no-repeat; background-size: contain; background-position: left; width: 100%s; height: 90%s;\" class='image-no-click'></div>
						</div>";
						// 2 params - imagePath, imagePath
						
		$cartNoImageT = "<div class='cart-image-no-click-container'>
							<i class='fa fa-camera cart-item-no-image-large'  aria-hidden='true' ></i>
						</div>";
	
		$cartItemT = "<div class='row cart-item'>

							<div class='col-lg-8 col-md-8 col-sm-8 col-xs-8 right-part'>

								<div class='row'>
									<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 hidden'>
										<p class='cart-item-code'>%s</p>
									</div>
									<div class='cart-item-image-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12'>
										<p class='cart-item-name'>%s</p>
									</div>
									<div class='cart-item-image-wrapper col-lg-2 col-md-2 col-sm-2 col-xs-3 item-image-container'>
										%s
									</div>
									<div class='cart-item-image-wrapper col-lg-10 col-md-10 col-sm-10 col-xs-9'>
										<div class='cart-item-data'>
											<span class='price-word'>" . $GLOBALS['busket_price'] . "</span> <span class='price-value'>%s</span>
											<br>
											<span class='price-word'>" . $GLOBALS['busket_number'] . "</span> 
											<span class='price-value'><input type='text' disabled='disabled' class='cart-item-number' name='number' value='%s'></span>
										</div>
									</div>

								</div>
							</div>

							<div class='col-lg-4 col-md-4 col-sm-4 col-xs-4 left-part'>
								<div class='cart-item-remove-wrap'>
									<a class='cart-item-remove' href='#'>
										<i class='fa fa-times' aria-hidden='true'></i>"
										. $GLOBALS['busket_remove_item'] .
									"</a>
								</div>
								<p class='price-value-sum'>%s</p>
							</div>



						</div>"; // params - code,  name, imgTemp, price, number, totalsum
						
			$res = ""; // accumulator
						
			$cartItems = $cartObj->get();
			foreach($cartItems as &$cartItem){
				if ($cartItem['number'] < 1){
					continue;
				}

				$totalItemPrice = $cartItem['price'] * $cartItem['number'];
				
				$imageTemp = "";
				if (array_key_exists('img', $cartItem) && (trim( $cartItem['img'] ) != "") ){
					// image exist, create the templates
					$imageTemp = sprintf($cartImageT, $cartItem['img'], $cartItem['img'], "%", "%");
				}
				else{
					// no image
					$imageTemp = $cartNoImageT;				
				}				
				
				$res .= sprintf($cartItemT, $cartItem['code'], $cartItem['name'], $imageTemp, priceFormat( $cartItem['price'] ), $cartItem['number'], priceFormat($totalItemPrice) );
			}
			
			return $res;
	
	
	}	
	
	
	function setBreadCrumbs($act, $name, $link){
		$this->breadCrumbs->set($act, $name, $link);	
	}	
	
	
	function getBreadCrumbs( $act){
	
		$breadCrumbT = "<span><a class='bdcrumb' href='%s'>%s</a></span>";  //2 params - link, name
		$breadCrumbContT = "<div class='breadcrumbs-container row'>
								<div class='col-xs-12'>
									%s
								</div>
							</div>";  // 1 param - content
		
		$crumbsAcc = ""; // container
		
		$crumbs = $this->breadCrumbs->get( $act );
		$ln = count($crumbs);
		for($i = 0; $i < $ln; $i++){
			// if link not empty, add it
			$crumb =  $crumbs[$i];
			if ( $crumb['link']  ){
				$crumbsAcc .= (sprintf($breadCrumbT, $crumb['link'], $crumb['name']) );
			}
			
			//else{
			//	break;
			//}
		}
		
		$res = sprintf($breadCrumbContT, $crumbsAcc);
		
		return $res;
	}
	
	
	function getSearchMarkup( $searchString, $itemPage=1, $sortParam=DEFAULT_SORT_PARAM, $sortOrder=DEFAULT_SORT_ORDER ){
		/*	$data - assoc_array(data - search query)	*/
	
	
	    $this->lastItemsSearchEmpty = true;
	    
		$itemImageLargeT = "<div class='image-no-click-container' style='padding: 10px 0px;'>
									<div data='%s' style=\"background: url('%s') no-repeat; background-size: contain; background-position: left; width: 100%s; height: 100%s;\" class='image-no-click' oncontextmenu='return false;'></div>
								</div>";  
								// 2 params - image path, image path
								
		$itemNoImageLargeT = "<p><i class='fa fa-camera items-no-image-large'  aria-hidden='true' ></i></p>";  //  0 params
		
		$itemImageSmallT = "<p class='items-image-small-container'><i class='fa fa-picture-o items-image-small image-no-click' aria-hidden='true' data='%s'></i></p>"; // 1 param - image path
		
		$itemNoImageSmallT = "<p class='items-image-small-container'><i class='fa fa-camera items-image-small' aria-hidden='true'></i></p>"; // 0 params
		
		$itemT = "
					<div class='item %s' page='%s'>

						<div class='row items-body'>
							<div class='col-lg-2 col-md-2 col-sm-2 hidden-xs item-image-container'>

								%s


							</div>
							<div class='col-lg-6 col-md-6 col-sm-6 col-xs-9'>
								<div class='items-body-content'>
									<p class='items-code'>%s</p>
									<p class='items-producer-wrapper'><span class='producer-word'>" .  $GLOBALS['producer'] . "</span> <span class='items-procucer'> %s </span> </p>
									<p class='items-name'>%s</p>
									<p class='items-description'>%s </p>

									
								</div>
							</div>
							
							<div class='hidden-lg hidden-md hidden-sm col-xs-3'>
								%s

							</div>
							

							<div class='col-lg-4 col-md-4 col-sm-4 hidden-xs lg-price-container large-container'>
								<p class='price'>%s</p>


								<div class='cart-button-container'>								
								 	<input type='text' class='item-numbers' name='number' value='1'>
									<a class='cart-button hidden-sm hidden-xs' href='#'>
			  							<i class='fa fa-cart-arrow-down'></i> " . 
			  							$GLOBALS['into_busket'] .
			  						"</a>
									<a class='cart-button hidden-lg hidden-md' href='#'>
			  							<i class='fa fa-cart-arrow-down'></i> 
			  						</a>
									
								</div>	

							</div>

							<div class='hidden-lg hidden-md hidden-sm col-xs-12 small-container'>

								<div class='row'>

									<div class='col-xs-8'>

										<div class='cart-button-container-small'>		

										 	<input type='text' class='item-numbers' name='number' value='1'>
											<a class='cart-button' href='#'>
					  							<i class='fa fa-cart-arrow-down'></i> 
					  						</a>
											
										</div>
									</div>
									<div class='col-xs-4'>
										<p class='price'>%s</p>
									</div>	
								</div>								
							</div>							


						</div>
						

					
					</div> 		
		
		"; // params  hidden or "", page,  largeImageTemp,  code, producer, name, desc,  smallImageTemp,  price, price
	
		$pageT = "<span><a class='items-page' href='%s'>%s</a></span>"; // 2 params - link, page
		$pageActiveT = "<span><a class='items-page active' href='%s'>%s</a></span>";  // 2params, - link, page
		$nextPageT = "<span><a class='items-page' href='%s'>&gt&gt</a></span>";  // 1 params - link
		
		
		$itemHeadT = "
					<div class='row'>
						<div class='col-lg-6'>
							
							<div class='items-pagination'>
								%s
							</div>
							
						</div>
						<div class='col-lg-6'>

							<div class='items-sorter'>
								<span>Сортувати за:</span>
							 	<select name='seq' id='soflow'>
    								<option value='&sort=name&sort_ord=ASC'>Назва, алф.</option>
								    <option value='&sort=name&sort_ord=DESC'>Назва, звор.</option>
								    <option value='&sort=price&sort_ord=ASC'>Ціна, зрост.</option>
								    <option value='&sort=price&sort_ord=DESC'>Ціна, спад.</option>
								</select>
							</div>
						</div>
						
					</div>		
		";  // 1 param - pages
		
		
		$bottomPageContT = "<div class='row'>
						<div class='col-lg-12'>
							
							<div class='items-pagination'>
								%s
							</div>
							
						</div>
					</div>";  // 1 param - comtent
	
	
		$res = "";
		$pagesLinkAcc = "";
		$data = [];
		//$data['partner_code'] = PARTNER_CODE_ZP;
		$data['act'] = "search";
		$data['data'] = $searchString;
		
		$items = $this->data->getSearchItems( $data, $itemPage, $sortParam, $sortOrder);
		
		//create pagination
		$pageCounter = 1;
		$activePage = $itemPage;
		
		// create pagination only if there are more than 1 page
		if (count($items['page_links']) > 1) {
			foreach ($items['page_links'] as &$link){
				if ( $pageCounter == $activePage ){
					$pagesLinkAcc .= sprintf($pageActiveT, $link, $pageCounter);
				}
				else{
					$pagesLinkAcc .= sprintf($pageT, $link, $pageCounter);
				}
				
				$pageCounter++;
			}
		}
		
		//add pagination
		$res .= sprintf($itemHeadT, $pagesLinkAcc);
		
		
		foreach ($items['items'] as &$item){
		    $this->lastItemsSearchEmpty = false;
			$largeImageTemp = "";
			$smallImageTemp = "";
			if (array_key_exists('img', $item) && (trim( $item['img'] ) != "") ){
				// image exist, create the templates
				$largeImageTemp = sprintf($itemImageLargeT, $item['img'], $item['img'], "%", "%");
				$smallImageTemp = sprintf($itemImageSmallT, $item['img']);
			}
			else{
				// no image
				$largeImageTemp = sprintf($itemNoImageLargeT);
				$smallImageTemp = sprintf($itemNoImageSmallT);				
			}
			$res .= sprintf($itemT, "", 1,  $largeImageTemp,  $item['code'], $item['producer'], $item['name'], $item['desc'], $smallImageTemp, priceFormat($item['price']), priceFormat($item['price']) );
		}
		
		//add bottom pagination
		$res .= sprintf($bottomPageContT, $pagesLinkAcc);
		
		
		return $res;
		
		
	
	}
	
	
	function getVINSearchMarkup($data){
		/*	Create html marpup for vin search page
			@return: assoc_array(models, dates)
		*/
	
		$modelsTableT = "<div class=\"col-lg-6 col-md-6 col-sm-6 col-xs-12 table-row-content\">
							<h3 class=\"items-category-title\">Оберіть модель</h3><table>%s</table></div>";  // 1 param - rows
		$modelsT = "<tr>
						<td><a class=\"item-vin-search\" vin_id=\"%s\" href=\"#\">%s</a></td>
					</tr>"; // 2 params - id, models name
					
					
		$modelsDataContainerT = "<div class=\"col-lg-6 col-md-6 col-sm-6 col-xs-12 table-row-content\">
									<h3 class=\"items-subcategory-title\">Дані</h3>
									%s
								</div>";
							
		$modelsDataTableT = "<div class=\"vin-table hidden\" vin_id=\"%s\">
		                        <table>%s</table> <a href=\"#\" class=\"vin-link-details\" data=\"%s\">За категоріями</a>  </div>";  // 3 param -  id, rows, vin
		
		$modelsDataRowT = "<tr>
							    <th>%s</th>
							    <td>%s</td>
							  </tr>"; // 2 params - header, data
	
		
		$res = $this->data->getVINSearch($data);
		
		$modelCounter = 1;
		$modelsAcc = "";   // temp
		$modelsDataAcc = "";  // final
		$modelsTableAcc = ""; // final
		
		//  if search returned no results
		if (empty($res)){
			return array(
				'models' => "",
				'dates' => ""
			);
		}
		
		foreach($res as &$model){
			$brand = $model['brand'];
			$modelName = $model['model'];
			$modelsAcc .= sprintf($modelsT, $modelCounter, ($brand . "  " . $modelName) );
			
			$attr = $model['data'];
			
			$modelDataTableAcc = "";			
			//foreach($attributes as &$attr){
				$date = $attr['date'];
				$framecolor = $attr['framecolor'];
				$options = $attr['options'];
				$description = $attr['description'];
				$model = $attr['model'];
				$prodPeriod = $attr['prodPeriod'];
				$trimcolor = $attr['trimcolor'];
				
				if (!empty($date) ){
					$modelDataTableAcc .= sprintf($modelsDataRowT, "Дата випуска", $date);
				}
				if (!empty($framecolor) ){
					$modelDataTableAcc .= sprintf($modelsDataRowT, "Колір кузова", $framecolor);
				}
				if (!empty($options) ){
					$modelDataTableAcc .= sprintf($modelsDataRowT, "Опції", $options);
				}
				if (!empty($description) ){
					$modelDataTableAcc .= sprintf($modelsDataRowT, "Опис", $description);
				}
				if (!empty($model) ){
					$modelDataTableAcc .= sprintf($modelsDataRowT, "Модель", $model);
				}
				if (!empty($prodPeriod) ){
					$modelDataTableAcc .= sprintf($modelsDataRowT, "Період випуску", $prodPeriod);
				}
				if (!empty($trimcolor) ){
					$modelDataTableAcc .= sprintf($modelsDataRowT, "Колір салона", $trimcolor);
				}
				
			//}
			
			// add whole models data table
			$modelsDataAcc .= sprintf($modelsDataTableT, $modelCounter, $modelDataTableAcc, $data['data'] );
			
			$modelsDataAll = sprintf($modelsDataContainerT, $modelsDataAcc);
			
			
			$modelCounter += 1;
			
		}
		
		$modelsTableAcc = sprintf($modelsTableT, $modelsAcc);
		
		
		return array(
			'models' => $modelsTableAcc,
			'dates' => $modelsDataAll
		);
		
	}
	
	
	function lastItemsSearchIsEmpty(){
	    /*  @return bool     */
	    
	    return $this->lastItemsSearchEmpty;
	}
	
	
	function getVINMenuMarkup($data){
		/*	Create html marpup for vin search page
			@return: assoc_array(models, dates)
		*/
		
		
		$this->menuContainerT = "<div class=\"col-lg-3 col-md-3 col-sm-3 col-xs-12 vin-cat-menu-container\">
						<ul id=\"vin-cat-menu\" vincode=\"%s\" style=\"padding: 0px;\">%s</ul></div>";  // 2 param - VIN, menu items
		$this->submenuContainerT = "<li><a href=\"#\" vin-menu-id=\"%s\">%s</a>
						    <ul vin-menu-id=\"%s\" class=\"hidd\" style=\"display:none;\">
								%s
						    </ul>
						  </li>"; // 3 params - id, name, id, submenu items
					
					
		$this->menuItemT = "<li><a href=\"#\" code=\"%s\">%s</a></li>"; // 2 params - code, item name

		$this->vinMenuCounter = 0;		
		
			
		$res = $this->data->getGroupsVIN($data);
			
		$menuContent = $this->buildMenuStructure( $res );
		
		return sprintf($this->menuContainerT, $data['data'], $menuContent);

		
	}


	function buildMenuStructure( &$d ){
		$acc = "";
		if ( is_array( $d ) ){
			
			foreach( $d as &$e ){	
				$this->vinMenuCounter++;
				$id = $this->vinMenuCounter;
				if (  !empty($e['data']) ){
					$acc .= sprintf($this->submenuContainerT, $id, $e['name'], $id, $this->buildMenuStructure( $e['data']));
				}
				else{
					$acc .= sprintf($this->menuItemT, $e['code'], $e['name']);	
				}
			}
		}
		
		return $acc;
	}


	function getSubcategoriesVINMarkup( $dat ){

		$catContainerT = "<div class=\"vin-cat-content\">
							<div class=\"vin-cat-title\">%s</div>
								%s
							<br>

						</div>";
						//  2 params -  title, itemsContainers
						
						
		$itemComtainerT = "<div><div class=\"vin-cat-item-row\">
								<div class=\"vin-cat-item vin-cat-img\">
									<img src=\"%s\" alt=\"\">
									<div class=\"vin-cat-item\">
										<span class=\"vin-cat-content-code\">%s </span><span>%s</span>
									</div>
								</div>
								<div class=\"vin-cat-item\">
									<table>
										%s
									</table>

								</div>

							</div></div><br>" ;  // 4 params - img, code, name, table							
	
						
		$tableRowT = "<tr>
						<td class=\"vin-item-short-code\">%s</td>
						<td class=\"vin-item-full-code\"><a class=\"code\" href=\"#\">%s</a></td> 
						<td class=\"vin-item-descript\">%s</td>
					  </tr>"; // 3 params - code_short, code_full, desc
										
		$res = $this->data->getSubcategoriesVIN( $dat );
		
		if (count($res) < 1){
			return "<div class=\"menu-code\" data-vin=\"" . $data['vin'] . "\" data-menu-code=\"" . $data['code'] . "\"></div><div class=\"row error-message-container-small\">
                        <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">
							<p class=\"error-message-small\">" . "Пошук повернув 0 результатів" . "</p>
                        </div>
					</div>";
		}
		
		$acc = "<div class=\"menu-code\" data-vin=\"" . $dat['vin'] . "\" data-menu-code=\"" . $dat['code'] . "\"></div>";
		foreach( $res as &$e ){
			$mainName = $e['name'];			
			$data = $e['data'];
			
			$categAcc = "";
			foreach($data as &$d){
				$itemName = $d['name'];
				$itemCode = $d['code'];
				$itemImage = $d['image'];
				
				$rows = "";
				foreach($d['data'] as &$r){
					$code = $r['code'];
					$name = $r['name'];
					$codeF = $r['codeF'];
					$rows .= sprintf($tableRowT, $r['code'], $r['codeF'], $r['name']  );
				}
				
				$categAcc .= sprintf($itemComtainerT, $itemImage, $itemCode, $itemName, $rows);
			
			
			
			}
			
			$acc .= sprintf($catContainerT, $mainName, $categAcc);
		}
		
		return $acc;
		
	}
	
	
	function getItemsVINMarkup( $data ){
		
		$itemImageSmallT = "<p class='items-image-small-container'><i class='fa fa-picture-o items-image-small image-no-click' aria-hidden='true' data='%s'></i></p>"; // 1 param - image path
		
		$itemNoImageSmallT = "<p class='items-image-small-container'><i class='fa fa-camera items-image-small' aria-hidden='true'></i></p>"; // 0 params
		
		$itemT = "<div class=\"vin-item\">

						<div class=\"row items-body\">
							
							<div class=\"col-lg-9 col-md-9 col-sm-9 col-xs-9\">
								<div class=\"items-body-content\">
									<p class=\"items-code\">%s</p>
									<p class=\"items-producer-wrapper\"><span class=\"producer-word\">Виробник:</span> <span class=\"items-procucer\"> %s </span> </p>
									<p class=\"items-name\">%s</p>
									<p class=\"items-description\">%s</p>

									
								</div>
							</div>
							
							<div class=\"col-lg-3 col-md-3 col-sm-3 col-xs-3\">
								%s

							</div>
							

							<div class=\col-lg-12 col-md-12 col-sm-12 col-xs-12\">

								<div class=\"row\">

									<div class=\"col-xs-8\">

										<div class=\"cart-button-container-small\">		

										 	<input type=\"text\" class=\"item-numbers\" name=\"number\" value=\"1\">
											<a class=\"cart-button\" href=\"#\">
					  							<i class=\"fa fa-cart-arrow-down\"></i> 
					  						</a>
											
										</div>
									</div>
									<div class=\"col-xs-4\">
										<p class=\"price\">%s</p>
									</div>	
								</div>								
							</div>							


						</div>
						

					
					</div>"; // 6 params  - code, producer, name, desc, imageTemplate, price	
			
		$items = $this->data->getItemsVIN( $data );
		
		if (count($items) < 1){
		 return "<p><a class=\"vin-back-link-1\" href=\"#\" data-vin=\"" . $data['vin'] . "\" " . "data-menu-code=\"" . $data['code'] .   "\">< Назад</a></p>
					<div class=\"row error-message-container-small\">
                            <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">
								<p class=\"error-message-small\">" . "Пошук повернув 0 результатів" . "</p>
                            </div>
					</div>";
		}
		
		$res = "<p><a class=\"vin-back-link-1\" href=\"#\" data-vin=\"" . $data['vin'] . "\" " . "data-menu-code=\"" . $data['code'] .   "\">< Назад</a></p>";
		foreach ($items as &$item){
			$smallImageTemp = "";
			if (array_key_exists('img', $item) && (trim( $item['img'] ) != "") ){
				// image exist, create the templates
				$smallImageTemp = sprintf($itemImageSmallT, $item['img']);
			}
			else{
				// no image
				$smallImageTemp = sprintf($itemNoImageSmallT);				
			}
			
			$res .= sprintf($itemT, $item['code'], $item['brand'], $item['name'], $item['desc'], $smallImageTemp, priceFormat($item['price']) );
		}

		return $res;					
					
					
	}	
	
	
	function getReplacedItemsVINMarkup( $data ){	
		$htmlT = "<div class=\"vin-item-info-analog\">
								<span class=\"vin-item-info-code\">%s</span>
								<span class=\"vin-item-info-name\">%s</span>
								<span class=\"vin-item-info-producer\">%s</span>
								<div class=\"cart-button-container-small\">		
								<span class=\"vin-item-info-price\">%s</span>
								 	<input type=\"text\" class=\"item-numbers\" name=\"number\" value=\"1\">
									<a class=\"cart-button\" href=\"#\">
			  							<i class=\"fa fa-cart-arrow-down\"></i> 
			  						</a>
									
								</div>
					</div><hr style=\"color: black\";>"; // 4 params - code, name, producer, price
					
		$items = $this->data->getReplacedItemsVIN( $data );
		
		if (count($items) > 0){
			$res = "<div class=\"vin-analog-title\">Аналоги</div>";
		}
		else{
			$res = "<div class=\"vin-analog-title\">Аналоги(0)</div>";
			return $res;
		}
		foreach ($items as &$item){	
			$res .= sprintf($htmlT, $item['code'], $item['name'], $item['brand'], $item['price']);
		}

		return $res;		
					
	}
	
	
	function getApplicationItemsVINMarkup( $data ){
		$htmlT = "<div class=\"vin-item-info-usage\">
					<b>%s</b> : <span>%s</span>
				</div>";  // 1 param - content
				
		$items = $this->data->getApplicationItemsVIN( $data );
		if (count($items) > 0){
			$res = "<div class=\"vin-application-title\">Використання</div>";
		}
		else{
			$res = "<div class=\"vin-application-title\">Використання(0)</div>";
			return $res;			
		}
		foreach ($items as $key => $value){	
			$res .= sprintf($htmlT, $key, $value); 
		}

		return $res;
	}	
	

	function getBlogThumbnailsMarkup( $data ){
		/*	$data - assoc_array( pg ), 	*/
	
		$pagesContainerT = "<div class=\"row\">
						<div class=\"col-lg-12\">
							
							<div class=\"items-pagination\">
								%s
							</div>
							
						</div>
					</div>"; // 1 param - pagination

		$pageT = "<span><a class=\"items-page\" href=\"%s\">%s</a></span>";  // 2 param - link, number
		$pageActiveT = "<span><a class=\"items-page active\" href=\"%s\">%s</a></span>"; // 2 param - link, number
		
		
		$thumbnailArticleT = "<div class=\"atricle-thumbnail\" id=\"%s\">
								<p><span class=\"title\">%s</span><span class=\"date\">%s</span></p>
								<img src=\"%s\">
								<div class=\"text\">
									%s
								</div>
								<p class=\"clear\"></p>
							</div>"; //  6 params - postid,  title, date, img, text
					
		$articlesContainerT =   "<div class=\"blog-thumbs-container\" page=\"%s\">
									%s
								</div>"; // 2param - article-page, content
		
	
		$currPage = $data['pg'];
	
		$args = array( 'post_type'=>'blog', 'orderby'=> 'date', 'order' => 'DESC'); 
		$query = new WP_Query( $args ); $page_counter = 0; $post_counter = 0;
		$pagesAcc = "";
		$thumbnailArticleAcc = "";
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post(); 
				$post_counter += 1;
								
				// increase page number and add page link
				if ( ( $post_counter >  $page_counter * POSTS_ON_PAGE ) ){
					$page_counter += 1;
					
					$link =  ($page_counter > 1) ? "?pg=" . $page_counter : get_site_url() . '/blog/';
					if ($page_counter == $currPage){
						$pagesAcc .= sprintf($pageActiveT, $link, $page_counter);
					}
					else{
						$pagesAcc .= sprintf($pageT, $link, $page_counter);
					}					
				}
				
				// add the posts only for the current page
				if ( $page_counter == $currPage ){
					$thumbnailArticleAcc .= sprintf($thumbnailArticleT, get_the_ID(), get('article_title'), get_the_date(), get('article_icon'), get('article_short_text'));
				}
			}
		}
		
		wp_reset_postdata();
		
		$articlesFinal = sprintf($articlesContainerT, get_site_url() . "/" . BLOG_ARTICLES_PAGE . "/", $thumbnailArticleAcc);
		$pagesFinal = sprintf($pagesContainerT, $pagesAcc);
		$ret = ($page_counter > 1)? $pagesFinal . $articlesFinal . $pagesFinal : $articlesFinal;
		
		return $ret;
			
	}
	
	
	function getBlogArticleMarkup( $data ){
	    /*  $data - assoc_array( id )     */
		$articleT = "<div class=\"blog-article\">
						<p class=\"title\">%s</p>
						<p class=\"date\">%s</p>
						<p class=\"text\">
							%s
						</p>
						<p class=\"clear\"></p>
						

					
					</div>"; // 3 params - title, date, text
					
		if (!$data['id']){
			return "";
		}
	
				
		$args = array( 'p' => $data['id'], 'post_type'=>'blog'); 
		$query = new WP_Query( $args );
		$res = "";
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post(); 
				$res .= sprintf($articleT, get('article_title'), get_the_date(), get('article_text') );
			}		
		}
		
		wp_reset_postdata();

		return $res;
		
	}	
	
	
}




?>
<?php
/**
 * The template for the main page
 *
 * @package WordPress
 * @subpackage Autoparts
 * @since Autoparts 1.0
 */
?>



<?php

include_once('fcore/HTMLBuilder.php');
include_once('fcore/cart.php');


session_start();

if ( !isset($_SESSION['cart'])){
	$_SESSION['cart'] = new ShopCart();
}


$criticalError = false;
$searchEmpty = false;

?>


<?php get_header('pages'); ?>

	<div class="container-fluid2 menu-container" data-url="<?php echo admin_url('admin-ajax.php'); ?>"  data-loadtime="5">
		<div class="container">
			<div class="row">

				<div class="col-lg-9 col-md-9 hidden-sm  hidden-xs">
					<div class="main-menu">
						<ul>
							<li><a class="active" href="<?php echo get_site_url(); ?>"><?php echo $GLOBALS['menu1']; ?></a></li>
							<li><a href="../gallery/"><?php echo $GLOBALS['menu2']; ?></a></li>
							<li><a href="../paydel/"><?php echo $GLOBALS['menu3']; ?></a></li>
							<li><a href="../contacts/"><?php echo $GLOBALS['menu4']; ?></a></li>
							<li><a href="../blog/"><?php echo $GLOBALS['menu5']; ?></a></li>
						</ul>
					</div>
					<!-- <a href="#" class="toggle-mnu hidden-lg hidden-md hidden-sm"><span></span></a> -->
	  			</div>


	  			<div class="col-xs-8 col-sm-8 col-md-3 col-lg-3">
	  				<div class="cart-button-container">
						<a class="cart-button hidden-xs" href="../cart/">
		  					<i class="fa fa-cart-arrow-down"></i> 
		  					<?php echo $GLOBALS['busket']; ?>
		  				</a>
						<a class="cart-button hidden-lg hidden-md hidden-sm" href="../cart/">
		  					<i class="fa fa-cart-arrow-down"></i> 
		  				</a>

		  				<div class="cart-data">
		  					<span class="items-number"><?php $num = $_SESSION['cart']->getItemsNumber(); echo ($num == 0) ? "" : $num ; ?></span> 
		  					<span class="items-price"><?php $num = $_SESSION['cart']->getTotalSum(); echo ($num == 0) ? "" : number_format($num , 2, ',', ' '); ; ?></span> 

		  				</div>
	  				</div>
	  			</div>


	  			<div class="col-xs-4 col-sm-4 hidden-md  hidden-lg">
	  				<a href="#" class="toggle-mnu hidden-lg hidden-md"><span></span></a>
	  			</div>


				<div class="col-xs-12 hidden-main-menu-wrapper hidden-lg hidden-md col-sm-12">
					<div class="main-menu-hidden">
						<ul>
							<li><a class="active" href="<?php echo get_site_url(); ?>"><?php echo $GLOBALS['menu1']; ?></a></li>
							<li><a href="../gallery/"><?php echo $GLOBALS['menu2']; ?></a></li>
							<li><a href="../paydel/"><?php echo $GLOBALS['menu3']; ?></a></li>
							<li><a href="../contacts/"><?php echo $GLOBALS['menu4']; ?></a></li>
							<li><a href="../blog/"><?php echo $GLOBALS['menu5']; ?></a></li>
						</ul>
					</div>
	  			</div>

			</div>
		</div>
	</div>


	<div class="container head-panel">
		<div class="row">


			<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
				<img class="logo" src="<?php echo $GLOBALS['logo_image']; ?>" alt="<?php echo $GLOBALS['logo_image_alt']; ?>">
				<p class="logo-text"><?php echo $GLOBALS['company_name']; ?></p>
			</div>

			<div class="col-lg-6 col-md-6 col-sm-5  col-xs-12">
				<h1><?php echo $GLOBALS['company_slogan']; ?></h1>
				<p class="discount"><?php echo $GLOBALS['company_subslogan']; ?></p>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-sm-12">

					<div class="search-panel">

						    <!-- <span class="icon"><i class="fa fa-search"></i></span> -->
						    <input type="search" id="search" placeholder="<?php echo $GLOBALS['main_search']; ?>" />
						    <!-- <button class="search-button3"><i class="fa fa-search"></i></button> -->
							 <a class="search-button" href="#">
			  					<i class="fa fa-search"></i> 
			  				</a>
			  				

					</div>

			</div>

		</div>
	</div>
	
	
	<div class="container site-content">
		<div class="row">
		
		<?php
		
    		try{
    			// print breadcrumbs
    			if (!isset($_SESSION['html_builder']) ){
    				session_start();
    				$html = new HTMLBuilder();
    				$_SESSION['html_builder'] = $html;
    			}
    			
    			$breadcrumbName = "None";
    			if (isset($_GET['brc']) ){
    				$breadcrumbName = $_GET['brc'];
    			}
    			else{
    				$breadcrumbName = $GLOBALS['brand_site'];
    			}
    			$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    			$_SESSION['html_builder']->setBreadCrumbs($_GET['act'], $breadcrumbName, $actual_link ) ;
    			echo $_SESSION['html_builder']->getBreadCrumbs( $_GET['act'] );
    			
    		}
    		catch(Exception $e){
    			$criticalError = true;
    		}
		
		?>		
		

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<!-- BRANDS START -->
				<div class="main-panel">					
					
					<?php
					
					    try{
					
							//if (!isset($_SESSION['html_builder']) ){
							//	session_start();
							//	$html = new HTMLBuilder();
							//	$_SESSION['html_builder'] = $html;
							//}


							//	$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
							//	$_SESSION['html_builder']->setBreadCrumbs($_GET['act'], "M", $actual_link ) ;
							//	echo $_SESSION['html_builder']->getBreadCrumbs( $_GET['act'] );
							

							if ( $_GET['act'] == 'items' ){
								$data = [];
								$itemPage = 1;
								$sortParam = DEFAULT_SORT_PARAM; // see config.php
								$sortOrder = DEFAULT_SORT_ORDER; // see config.php
								if (isset( $_GET['pg'] )){
									$itemPage = $_GET['pg'];
								}
								if (isset( $_GET['sort'] )){
									$sortParam = $_GET['sort'];
								}
								if (isset( $_GET['sort_ord'] )){
									$sortOrder = $_GET['sort_ord'];
								}
								if ( (string) $_GET['partner'] == PARTNER_CODE_AD ){
									$data['partner_code'] = $_GET['partner'];
									$data['name_f'] = $_GET['name_f'];
									$data['code'] = $_GET['code'];
									
								
									//echo $itemsHTML;	
								}
								else{
									$data['partner_code'] = $_GET['partner'];
									$data['name_f'] = $_GET['name_f'];
									$data['data'] = $_GET['data'];								
								}
								
								$itemsHTML = $_SESSION['html_builder']->getItemsMarkup( $data, $itemPage, $sortParam, $sortOrder);
								echo $itemsHTML;
							}

							else if ( $_GET['act'] == 'search' ){
									//$data['partner_code'] = $_GET['partner'];
									//$data['name_f'] = '';
									$searchString = $_GET['data'];
									
									$itemPage = 1;
									$sortParam = DEFAULT_SORT_PARAM; // see config.php
									$sortOrder = DEFAULT_SORT_ORDER; // see config.php
									if (isset( $_GET['pg'] )){
										$itemPage = $_GET['pg'];
									}
									if (isset( $_GET['sort'] )){
										$sortParam = $_GET['sort'];
									}
									if (isset( $_GET['sort_ord'] )){
										$sortOrder = $_GET['sort_ord'];
									}									
									
									
									$itemsHTML = $_SESSION['html_builder']->getSearchMarkup( $searchString , $itemPage, $sortParam, $sortOrder, $data['data']);
									echo $itemsHTML;
									if ($_SESSION['html_builder']->lastItemsSearchIsEmpty() ){
									     $searchEmpty = true;
									}
								
									//echo $itemsHTML;	
								}
								
                    		}
                    		catch(Exception $e){
                    			$criticalError = true;
                    		}
														
					
					?>

                    <?php
                    
                        if ($criticalError){
                            
                            echo "<div class=\"row error-message-container\">
                                	<div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">
                                		<p class=\"error-message\">" . "Сталася помилка. Повторіть дію пізніше" . "</p>
                                	</div>
                                </div>";
                        }
                        else{
                            
                            if ($searchEmpty){
                                echo "<div class=\"row error-message-container\">
                                    	<div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">
                                    		<p class=\"error-message\">" . "Пошук повернув 0 результатів" . "</p>
                                    	</div>
                                    </div>";                                    
                            }
                        }
                    ?>                
					

				</div>
			</div>
		</div>
	</div>

<!--  footer -->	
<?php get_footer(); ?>					
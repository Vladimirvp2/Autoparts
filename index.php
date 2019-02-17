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

session_start();

if ( !isset($_SESSION['cart'])){
	$_SESSION['cart'] = new ShopCart();
	echo "index, create a new cart" . "<br>";
}

$criticalError = false;

?>

<?php get_header(); ?>  


	<div class="container-fluid2 menu-container" data-url="<?php echo admin_url('admin-ajax.php'); ?>">
		<div class="container">
			<div class="row">

				<div class="col-lg-9 col-md-9 hidden-sm  hidden-xs">
					<div class="main-menu">
						<ul>
							<li><a class="active" href="<?php echo get_site_url(); ?>"><?php echo $GLOBALS['menu1']; ?></a></li>
							<li><a href="gallery/"><?php echo $GLOBALS['menu2']; ?></a></li>
							<li><a href="paydel/"><?php echo $GLOBALS['menu3']; ?></a></li>
							<li><a href="contacts/"><?php echo $GLOBALS['menu4']; ?></a></li>
							<li><a href="blog/"><?php echo $GLOBALS['menu5']; ?></a></li>
						</ul>
					</div>
					<!-- <a href="#" class="toggle-mnu hidden-lg hidden-md hidden-sm"><span></span></a> -->
	  			</div>


	  			<div class="col-xs-8 col-sm-8 col-md-3 col-lg-3">
	  				<div class="cart-button-container">
						<a class="cart-button hidden-xs" href="cart/">
		  					<i class="fa fa-cart-arrow-down"></i> 
		  					<?php echo $GLOBALS['busket']; ?>
		  				</a>
						<a class="cart-button hidden-lg hidden-md hidden-sm" href="cart/">
		  					<i class="fa fa-cart-arrow-down"></i> 
		  				</a>

		  				<div class="cart-data">
		  					<span class="items-number"><?php $num = $_SESSION['cart']->getItemsNumber(); echo ($num == 0) ? "" : $num ; ?></span> 
		  					<span class="items-price"><?php $num = $_SESSION['cart']->getTotalSum(); echo ($num == 0) ? "" : number_format($num , 2, ',', ' ');  ?></span> 

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
							<li><a href="gallery/"><?php echo $GLOBALS['menu2']; ?></a></li>
							<li><a href="paydel/"><?php echo $GLOBALS['menu3']; ?></a></li>
							<li><a href="contacts/"><?php echo $GLOBALS['menu4']; ?></a></li>
							<li><a href="blog/"><?php echo $GLOBALS['menu5']; ?></a></li>
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
    			//$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    			$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    			$_SESSION['html_builder']->setBreadCrumbs($_GET['act'], $breadcrumbName, $actual_link ) ;
    			echo $_SESSION['html_builder']->getBreadCrumbs( $_GET['act'] );
    			
		    }
			catch(Exception $e){
				$criticalError = true;
			}
		
		?>
		
		

			<div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
				<!-- BRANDS START -->
				<div class="main-panel">
					
					<div class="row">
					
						<?php
						
						try{

						
							//if (!isset($_SESSION['html_builder']) ){
							//	session_start();
							//	$html = new HTMLBuilder();
							//	$_SESSION['html_builder'] = $html;
							//}

							// breadcrumbs
							//if ( isset($_GET['act']) ){
							
								//echo $_SERVER['HTTP_REFERER'] ;
							//}							
							
							//$html = new HTMLBuilder();
							
							// find out what content to load
							// check if load brands
							if ( ! isset($_GET['act']) ){
								$brandsHTML = $_SESSION['html_builder']->getBrandsMarkup();
								echo $brandsHTML;
							}
							// check if load lineups 
							else if ( $_GET['act'] == 'lineups' ){
								$data = [];
								$data['partner_code'] = $_GET['partner'];
								$data['code'] = $_GET['code'];
								$lineupsHTML = $_SESSION['html_builder']->getLineupMarkup( $data );
								
								
								echo 	"<div class='row'>
											<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
												<h3>" . $GLOBALS['lineup']  . "</h3>
											</div>
										</div>

										<div class='row'>
											<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
												<div class='filter-container'>
														<div class='filter'>

															<input class='glowing-border' type='search' id='search-main-panel' placeholder='" . $GLOBALS['search'] . "' />
															 <a class='search-button' href='#'>
																<i class='fa fa-search'></i> 
															</a>

														</div>
												</div>
											</div>
										</div>";
								
								
									echo $lineupsHTML;	
							
							}
							else if ( $_GET['act'] == 'models' ){
							
								echo "<div class='row'>
									<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
										<h3>"  . $GLOBALS['models'] .  "</h3>
									</div>
								</div>

								<div class='row'>
									<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
										<div class='filter-container'>
												<div class='filter'>

													<input class='glowing-border' type='search' id='search-main-panel' placeholder='" . $GLOBALS['search'] .  "' />
													 <a class='search-button' href='#'>
														<i class='fa fa-search'></i> 
													</a>

												</div>
										</div>
									</div>
								</div>";
								
								$data = [];
								if ( (string) $_GET['partner'] == PARTNER_CODE_AD ){
									$data['partner_code'] = $_GET['partner'];
									$data['code'] = $_GET['code'];
								}
								else if ( (string) $_GET['partner'] == PARTNER_CODE_ZP ){
									$data['partner_code'] = $_GET['partner'];
									$data['data'] = $_GET['data'];
								}
								
								$modelsHTML = $_SESSION['html_builder']->getModelsMarkup( $data );
								
								echo $modelsHTML;

								
							
							}
							else if ( $_GET['act'] == 'groups' ){
								$data = [];
								if ( (string) $_GET['partner'] == PARTNER_CODE_AD ){
									$data['partner_code'] = $_GET['partner'];
									$data['code'] = $_GET['code'];
								}
								else if ( (string) $_GET['partner'] == PARTNER_CODE_ZP ){
									$data['partner_code'] = $_GET['partner'];
									$data['data'] = $_GET['data'];
								}
								
								$modelsHTML = $_SESSION['html_builder']->getGroupsMarkup($GLOBALS['categories'], $GLOBALS['subcategories'], $data );
								
								echo $modelsHTML;							
							}
							
							else if ( $_GET['act'] == 'items' ){
								$data = [];
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
								
								$itemsHTML = $_SESSION['html_builder']->getItemsMarkup( $data );
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
                        ?>

					</div>

				</div> <!-- BRANDS END -->
			

			</div>

			

			<div class="col-lg-2 col-md-2 sidebar-container hidden-md hidden-sm hidden-xs">
				<div class="sidebar">
					<p class="title"><b><?php echo $GLOBALS['contacts_sidebar_title']; ?></b></p>
					<div class="contact-info">
					    <?php
					        // print phones
					        $phoneTemplate = "<p><i class=\"fa fa-phone\" aria-hidden=\"true\"></i>&nbsp<span class=\"phone\">%s</span></p>";
					        $phoness = explode(",", $GLOBALS['phones']);
					        foreach($phoness as $phone){
					            echo sprintf($phoneTemplate, trim($phone) );
					        }
					        
					        
					    ?>
					    
				
					    <?php
					        // print emails
					        $emailTemplate = "<p style=\"word-break: break-all;\"><i class=\"fa fa-envelope\" aria-hidden=\"true\"></i>&nbsp<span class=\"phone\">%s</span></p>";
					        $emailss = explode(",", $GLOBALS['emails']);
					        foreach($emailss as &$email){
					            echo sprintf($emailTemplate, trim($email) );
					        }
					    ?>				
				
				        <!--
						<p><i class="fa fa-phone" aria-hidden="true"></i>&nbsp<span class="phone">(044) 392-00-72</span></p>
						<p><i class="fa fa-phone" aria-hidden="true"></i>&nbsp<span class="phone">(044) 392-00-72</span></p>
						<p><i class="fa fa-phone" aria-hidden="true"></i>&nbsp<span class="phone">(044) 392-00-72</span></p>
						<p><i class="fa fa-phone" aria-hidden="true"></i>&nbsp<span class="phone">(044) 392-00-72</span></p>
						<p><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp<span class="phone">email</span></p>
						-->
						<!--
						<p class="address">Адрес</p>
						-->
						<p><i class="fa fa-home" style="font-size: 18px;" aria-hidden="true"></i>&nbsp<span class="address"><?php echo $GLOBALS['address']; ?></span></p>

						
						
					</div>
				</div>

			</div>

			


		</div>
	</div>



<!--  footer -->	
<?php get_footer(); ?>











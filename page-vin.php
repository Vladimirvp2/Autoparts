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
}

$criticalError = false;
$searchEmpty = false;
?>

<?php get_header('pages'); ?> 

	<div class="container-fluid2 menu-container" data-url="<?php echo admin_url('admin-ajax.php'); ?>">
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
    			//$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    			//$_SESSION['html_builder']->setBreadCrumbs($_GET['act'], $breadcrumbName, $actual_link ) ;
    			echo $_SESSION['html_builder']->getBreadCrumbs( LINK_ITEMS );
    			
    		}
    	    catch(Exception $e){
    			$criticalError = true;
    		}
		
		?>
		
		

			<div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">

				<div class="main-panel">

					
					<div class="row">


						<!--
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 table-row-content">

							<h3 class="items-category-title">Оберіть модель</h3>
							-->
							<!--

							<table>
							  <tr>
							    <td><a class="item-vin-search" vin_id="1" href="#">Subgroup1</a></td>
							  </tr>
							  <tr>
							    <td><a class="item-vin-search" vin_id="2" href="#">Subgroup2</a></td>
							  </tr>
							</table>

							-->

							<?php
							
    							try{
    								$searchS = "";
    								$act = "";
    								if (isset($_GET['data']) ){
    									$searchS = $_GET['data'];
    								}
    								if (isset($_GET['act']) ){
    									$act = $_GET['act'];
    								}
    								
    								$data = [];
    								$data['act'] = $act;
    								$data['data'] = $searchS;
    								$res = $_SESSION['html_builder']->getVINSearchMarkup( $data );
    								
    								echo $res['models'];
    								if (empty($res['models'])){
    								    
    								    $searchEmpty = true;
    								}
    							}
        						catch(Exception $e){
        							$criticalError = true;
        						}
								
							?>


						<!--
						</div>
						-->
						<!--
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 table-row-content">

							<h3 class="items-subcategory-title">Дані</h3>
							-->
							
								<?php
								
    								try{
    					
    									echo $res['dates'];
    									
        							}
            						catch(Exception $e){
            							$criticalError = true;
            						}
									
								?>

							<!--
							
							<table class="vin-table hidden" vin_id="1">
							  <tr>
							    <th>Firstname</th>
							    <td>Lastname</td>
							  </tr>
							  <tr>
							    <th>Peter</th>
							    <td>Griffin</td>
							  </tr>
							  <tr>
							    <th>Lois</th>
							    <td>Griffin</td>
							  </tr>
							</table>
							
							-->





						<!--
						</div>
						-->
						
						
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
				

						<p><i class="fa fa-home" style="font-size: 18px;" aria-hidden="true"></i>&nbsp<span class="address"><?php echo $GLOBALS['address']; ?></span></p>

						
						
					</div>
				</div>

			</div>

			


		</div>
	</div>



<!--  footer -->	
<?php get_footer(); ?>











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


?>


<?php get_header('pages'); ?> 





<style>

</style>




	<div class="container-fluid2 menu-container" data-url="<?php echo admin_url('admin-ajax.php'); ?>"  data-loadtime="5">
		<div class="container">
			<div class="row">

				<div class="col-lg-9 col-md-9 hidden-sm  hidden-xs">
					<div class="main-menu">
						<ul>
							<li><a href="<?php echo get_site_url(); ?>"><?php echo $GLOBALS['menu1']; ?></a></li>
							<li><a  href="../gallery/"><?php echo $GLOBALS['menu2']; ?></a></li>
							<li><a class="active" href="../paydel/"><?php echo $GLOBALS['menu3']; ?></a></li>
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
		  					<span class="items-price"><?php $num = $_SESSION['cart']->getTotalSum(); echo ($num == 0) ? "" : number_format($num , 2, ',', ' '); ?></span>

		  				</div>
	  				</div>
	  			</div>


	  			<div class="col-xs-4 col-sm-4 hidden-md  hidden-lg">
	  				<a href="#" class="toggle-mnu hidden-lg hidden-md"><span></span></a>
	  			</div>


				<div class="col-xs-12 hidden-main-menu-wrapper hidden-lg hidden-md col-sm-12">
					<div class="main-menu-hidden">
						<ul>
							<li><a href="<?php echo get_site_url(); ?>"><?php echo $GLOBALS['menu1']; ?></a></li>
							<li><a  href="../gallery/"><?php echo $GLOBALS['menu2']; ?></a></li>
							<li><a class="active" href="../paydel/"><?php echo $GLOBALS['menu3']; ?></a></li>
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

			<div class="col-lg-10 col-md-10 col-sm-9  col-xs-12">
				<h1><?php echo $GLOBALS['company_slogan']; ?></h1>
				<p class="discount"><?php echo $GLOBALS['company_subslogan']; ?></p>
			</div>


		</div>
	</div>	
	
	
	
	<div class="container site-content">
		<div class="row">

			<!-- to make top indent -->
			<div class="row bread-crumbs-spacer">
			</div>




			<div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
				<!-- BRANDS -->
				
				<div class="main-panel">
					
					<div class="row">

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

							<h2 class="payment-title"><span>Оплата</span></h2>

							<p class="payment-description"><span><?php echo $GLOBALS['payment_conditions']; ?></span></p>


							<h2 class="payment-title"><span>Доставка</span></h2>

							<p class="payment-description"><span><?php echo $GLOBALS['delivery_conditions']; ?></span></p>

						</div>
						


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
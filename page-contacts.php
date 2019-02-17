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
							<li><a href="../gallery/"><?php echo $GLOBALS['menu2']; ?></a></li>
							<li><a href="../paydel/"><?php echo $GLOBALS['menu3']; ?></a></li>
							<li><a class="active" href=""><?php echo $GLOBALS['menu4']; ?></a></li>
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
							<li><a href="../gallery/"><?php echo $GLOBALS['menu2']; ?></a></li>
							<li><a href="../paydel/"><?php echo $GLOBALS['menu3']; ?></a></li>
							<li><a class="active" href=""><?php echo $GLOBALS['menu4']; ?></a></li>
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

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<!-- BRANDS -->
				
				<div class="main-panel" style="padding: 15px;">


					
					<div class="row">

						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >
							<p><span class="contacts-home-icon"><i class="fa fa-home" aria-hidden="true"></i></span><span class="contacts-info"><?php echo $GLOBALS['address']; ?> </span>
							</p>

                            <!--
							<p><span class="contacts-home-icon"><i class="fa fa-phone" aria-hidden="true"></i></span><span class="contacts-info">Телефоны</span></p>
							-->
							
					    <?php
					        // print phones
					        $phoneTemplate = "<p><span class=\"contacts-home-icon\"><i class=\"fa fa-phone\" aria-hidden=\"true\"></i></span><span class=\"contacts-info\">%s</span></p>";
					        $phoness = explode(",", $GLOBALS['phones']);
					        foreach($phoness as $phone){
					            echo sprintf($phoneTemplate, trim($phone) );
					        }
					        
					        
					    ?>
					    
					    
					    <?php
					        // print emails
					        $emailTemplate = "<p><span class=\"contacts-home-icon\"><i class=\"fa fa-envelope\" aria-hidden=\"true\"></i></span><span class=\"contacts-info\">%s</span></p>";
					        $emailss = explode(",", $GLOBALS['emails']);
					        foreach($emailss as &$email){
					            echo sprintf($emailTemplate, trim($email) );
					        }
					    ?>
					    
					    <?php
					        $workTemplate = "<p><span class=\"contacts-home-icon\"><i class=\"fa fa-clock-o\" aria-hidden=\"true\"></i></span><span class=\"contacts-info\">%s</span></p>";
					        echo sprintf($workTemplate, trim( $GLOBALS['working_hours'] ) );
					        //echo "<p><span class=\"contacts-home-icon\"><i class=\"icon-time\"></i><span class=\"contacts-info\">" . "Графік работи: 8-00 - 18-00 Пн-Нд" . "</span></p>";
					    
					    ?>
							
							
						

						</div>

						<div class="col-lg-8 col-md-8 col-sm-12 hidden-xs contacts-map-container" >
							<!-- map  -->
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2585.724299436626!2d34.48458750899113!3d49.60294836807379!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40d8289bb6a3403b%3A0x65b77817349a7e04!2z0JPQvtC20YPQu9GW0LLRgdGM0LrQsCDQstGD0LvQuNGG0Y8sIDI2LCDQn9C-0LvRgtCw0LLQsCwg0J_QvtC70YLQsNCy0YHRjNC60LAg0L7QsdC70LDRgdGC0YwsIDM2MDAw!5e0!3m2!1suk!2sua!4v1504451161032" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>

						</div>
						
						<!--
						<div class="hidden-lg hidden-md hidden-sm col-xs-12 contacts-map-container" >
							
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2585.724299436626!2d34.48458750899113!3d49.60294836807379!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40d8289bb6a3403b%3A0x65b77817349a7e04!2z0JPQvtC20YPQu9GW0LLRgdGM0LrQsCDQstGD0LvQuNGG0Y8sIDI2LCDQn9C-0LvRgtCw0LLQsCwg0J_QvtC70YLQsNCy0YHRjNC60LAg0L7QsdC70LDRgdGC0YwsIDM2MDAw!5e0!3m2!1suk!2sua!4v1504451161032" width="400" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>

						</div>	
						-->
						
						

					</div>

				</div>
	

			</div>


		</div>
	</div>
							
							
							
							
<!--  footer -->	
<?php get_footer(); ?>	
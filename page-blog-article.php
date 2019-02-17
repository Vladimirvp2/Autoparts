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


?>

<?php get_header(); ?>  

	<div class="container-fluid2 menu-container" data-url="<?php echo admin_url('admin-ajax.php'); ?>">
		<div class="container">
			<div class="row">

				<div class="col-lg-9 col-md-9 hidden-sm  hidden-xs">
					<div class="main-menu">
						<ul>
							<li><a href="<?php echo get_site_url(); ?>"><?php echo $GLOBALS['menu1']; ?></a></li>
							<li><a href="../gallery/"><?php echo $GLOBALS['menu2']; ?></a></li>
							<li><a href="../paydel/"><?php echo $GLOBALS['menu3']; ?></a></li>
							<li><a href="../contacts/"><?php echo $GLOBALS['menu4']; ?></a></li>
							<li><a class="active" href=""><?php echo $GLOBALS['menu5']; ?></a></li>
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
						<a class="cart-button hidden-lg hidden-md hidden-sm" href="#">
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
							<li><a href="<?php echo get_site_url(); ?>"><?php echo $GLOBALS['menu1']; ?></a></li>
							<li><a href="../gallery/"><?php echo $GLOBALS['menu2']; ?></a></li>
							<li><a href="../paydel/"><?php echo $GLOBALS['menu3']; ?></a></li>
							<li><a href="../contacts/"><?php echo $GLOBALS['menu4']; ?></a></li>
							<li><a class="active" href=""><?php echo $GLOBALS['menu5']; ?></a></li>
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


					</div>

			</div>

		</div>
	</div>



	<div class="container site-content">
		<div class="row">
		
		<?php
			// print breadcrumbs
			if (!isset($_SESSION['html_builder']) ){
				session_start();
				$html = new HTMLBuilder();
				$_SESSION['html_builder'] = $html;
			}
				
		?>
		
		

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

				<div class="main-panel" style="margin-top: 15px;">
						
					<a class="back-blog-link"href="../blog/">&lt Назад</a>

					<?php
						$data['id'] = $_GET['id'];
						echo $_SESSION['html_builder']->getBlogArticleMarkup( $data );
					
					?>

				</div>		

			</div>

		</div>
	</div>



<!--  footer -->	
<?php get_footer(); ?>











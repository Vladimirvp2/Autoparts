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
@import "https://fonts.googleapis.com/css?family=Montserrat:400,700|Raleway:300,400";
/* colors */


.gallery-container{

	padding-right: 0px;
	padding-left: 0px;
}

.gallery {
  left: 50%;
  -webkit-transform: translateX(-50%);
          transform: translateX(-50%);
  position: relative;
  background: white;
  width: 100%;
  box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
  border-radius: 5px;
}
.gallery input[name$="control"] {
  display: none;
}
.gallery .carousel {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
      -ms-flex-direction: row;
          flex-direction: row;
  position: relative;
  height: 70vh;
  width: 100%;
}
.gallery .wrap {
  width: 100%;
  height: 100%;
  position: static;
  margin: 0 auto;
  overflow: hidden;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
      -ms-flex-direction: row;
          flex-direction: row;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  -ms-flex-wrap: nowrap;
      flex-wrap: nowrap;
  margin-left: 100px;
}

@media (max-width: 600px) {
  .gallery .wrap {
    margin-left: 20px;
  }
}



.gallery .wrap figure {
  padding: 10px;
  height: 100%;
  min-width: 100%;
  -webkit-transition: opacity 0.25s ease-in-out 0.05s;
  transition: opacity 0.25s ease-in-out 0.05s;
  position: relative;
  left: 0;
  -webkit-transform: translateX(0%);
          transform: translateX(0%);
  box-sizing: border-box;
  text-align: center;
  margin: 0;
  display: block;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  -webkit-box-pack: center;
      -ms-flex-pack: center;
          justify-content: center;
  opacity: 1;
}
.gallery .wrap figure label {
  //cursor: zoom-in;
  height: auto;
  width: 100%;
  height: 100%;
  position: relative;
  display: block;
}
.gallery .wrap figure img {
  cursor: inherit;
  height: auto;
  max-width: 100%;
  max-height: 100%;
  border-radius: 3px;
  margin: 0 auto;
  position: relative;
  top: 50%;
  -webkit-transform: translateY(-50%);
          transform: translateY(-50%);
}
.gallery .thumbnails {
  -webkit-box-flex: 1;
      -ms-flex: 1;
          flex: 1;
  min-width: 60px;
  max-height: 100%;
  height: auto;
  -webkit-box-flex: 0;
      -ms-flex-positive: 0;
          flex-grow: 0;
  -ms-flex-item-align: center;
      align-self: center;
  -ms-flex-preferred-size: auto;
      flex-basis: auto;
  position: relative;
  white-space: nowrap;
  overflow: hidden;
  overflow-y: auto;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
      -ms-flex-direction: column;
          flex-direction: column;
  padding: 0 30px 0px 10px;
  z-index: 20;
}
.gallery .thumbnails .thumb {
  min-width: 60px;
  height: 60px;
  background-position: center center;
  background-size: cover;
  box-sizing: border-box;
  opacity: 0.7;
  margin: 5px 0;
  -ms-flex-negative: 0;
      flex-shrink: 0;
  left: 0;
  border-radius: 3px;
  cursor: pointer;
  -webkit-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
  background-repeat: no-repeat;
}
.gallery .thumbnails .slider {
  position: absolute;
  display: block;
  width: 5px;
  height: calc(60px + 10px);
  z-index: 2;
  margin: 0;
  left: 0;
  -webkit-transition: all 0.33s cubic-bezier(0.3, 0, 0.33, 1);
  transition: all 0.33s cubic-bezier(0.3, 0, 0.33, 1);
}
.gallery .thumbnails .slider .indicator {
  width: 100%;
  height: 30px;
  max-height: calc(100% - 10px);
  position: relative;
  top: 50%;
  -webkit-transform: translateY(-50%);
          transform: translateY(-50%);
  background: #428BFF;
  border-radius: 1px;
}
/*
.gallery input#fullscreen:checked ~ .wrap figure {
  position: fixed;
  z-index: 10;
  height: 100vh;
  width: 100vw;
  padding: 0;
  top: 50%;
  left: 50%;
  -webkit-transform: translate(-50%, -50%) !important;
          transform: translate(-50%, -50%) !important;
  -webkit-animation-timing-function: ease-in-out;
          animation-timing-function: ease-in-out;
  -webkit-animation-fill-mode: forwards;
          animation-fill-mode: forwards;
}
.gallery input#fullscreen:checked ~ .wrap figure label {
  cursor: zoom-out;
}
.gallery input#fullscreen:checked ~ .wrap figure label img {
  -webkit-animation: shadow 0.2s;
          animation: shadow 0.2s;
  -webkit-animation-timing-function: ease-in-out;
          animation-timing-function: ease-in-out;
  -webkit-animation-direction: forwards;
          animation-direction: forwards;
  -webkit-animation-fill-mode: forwards;
          animation-fill-mode: forwards;
  border-radius: 0;
}

*/
.gallery input#image1:checked ~ .wrap figure {
  -webkit-transform: translateX(0);
          transform: translateX(0);
}
.gallery input#image1:checked ~ .wrap figure:not(:nth-of-type(1)) {
  opacity: 0;
}
.gallery input#image1:checked ~ .thumbnails .slider {
  -webkit-transform: translateY(0);
          transform: translateY(0);
}
.gallery input#image1:checked ~ .thumbnails .thumb:nth-of-type(1) {
  opacity: 1;
  cursor: default;
}
.gallery input#image2:checked ~ .wrap figure {
  -webkit-transform: translateX(-100%);
          transform: translateX(-100%);
}
.gallery input#image2:checked ~ .wrap figure:not(:nth-of-type(2)) {
  opacity: 0;
}
.gallery input#image2:checked ~ .thumbnails .slider {
  -webkit-transform: translateY(100%);
          transform: translateY(100%);
}
.gallery input#image2:checked ~ .thumbnails .thumb:nth-of-type(2) {
  opacity: 1;
  cursor: default;
}
.gallery input#image3:checked ~ .wrap figure {
  -webkit-transform: translateX(-200%);
          transform: translateX(-200%);
}
.gallery input#image3:checked ~ .wrap figure:not(:nth-of-type(3)) {
  opacity: 0;
}
.gallery input#image3:checked ~ .thumbnails .slider {
  -webkit-transform: translateY(200%);
          transform: translateY(200%);
}
.gallery input#image3:checked ~ .thumbnails .thumb:nth-of-type(3) {
  opacity: 1;
  cursor: default;
}
.gallery input#image4:checked ~ .wrap figure {
  -webkit-transform: translateX(-300%);
          transform: translateX(-300%);
}
.gallery input#image4:checked ~ .wrap figure:not(:nth-of-type(4)) {
  opacity: 0;
}
.gallery input#image4:checked ~ .thumbnails .slider {
  -webkit-transform: translateY(300%);
          transform: translateY(300%);
}
.gallery input#image4:checked ~ .thumbnails .thumb:nth-of-type(4) {
  opacity: 1;
  cursor: default;
}

@-webkit-keyframes full {
  from {
    -webkit-transform: translate(-50%, -50%) scale(0.8);
            transform: translate(-50%, -50%) scale(0.8);
  }
  to {
    -webkit-transform: translate(-50%, -50%) scale(1);
            transform: translate(-50%, -50%) scale(1);
  }
}

@keyframes full {
  from {
    -webkit-transform: translate(-50%, -50%) scale(0.8);
            transform: translate(-50%, -50%) scale(0.8);
  }
  to {
    -webkit-transform: translate(-50%, -50%) scale(1);
            transform: translate(-50%, -50%) scale(1);
  }
}
@-webkit-keyframes shadow {
  from {
    box-shadow: 0 0 0 100vmin rgba(24, 33, 45, 0), 0 0 10vmin rgba(13, 21, 31, 0);
  }
  to {
    box-shadow: 0 0 0 100vmin rgba(24, 33, 45, 0.6), 0 0 10vmin rgba(13, 21, 31, 0.6);
  }
}
@keyframes shadow {
  from {
    box-shadow: 0 0 0 100vmin rgba(24, 33, 45, 0), 0 0 10vmin rgba(13, 21, 31, 0);
  }
  to {
    box-shadow: 0 0 0 100vmin rgba(24, 33, 45, 0.6), 0 0 10vmin rgba(13, 21, 31, 0.6);
  }
}
</style>




	<div class="container-fluid2 menu-container" data-url="<?php echo admin_url('admin-ajax.php'); ?>"  data-loadtime="5">
		<div class="container">
			<div class="row">

				<div class="col-lg-9 col-md-9 hidden-sm  hidden-xs">
					<div class="main-menu">
						<ul>
							<li><a href="<?php echo get_site_url(); ?>"><?php echo $GLOBALS['menu1']; ?></a></li>
							<li><a class="active" href="../gallery/"><?php echo $GLOBALS['menu2']; ?></a></li>
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
							<li><a class="active" href="../gallery/"><?php echo $GLOBALS['menu2']; ?></a></li>
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

			<div class="col-lg-10 col-md-10 col-sm-9  col-xs-12">
				<h1><?php echo $GLOBALS['company_slogan']; ?></h1>
			</div>


		</div>
	</div>	
	
	
	
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 gallery-container">


  
  
  <?php
  
		$gallery_control_first = "<input type=\"radio\" id=\"image%s\" name=\"gallery-control\" checked>";  // 1 param - id number
		$gallery_control = "<input type=\"radio\" id=\"image%s\" name=\"gallery-control\">";  // 1 param - number
		
		$full_screen = "<figure>
        <label for=\"fullscreen\">
          <img src=\"%s\" alt=\"%s\"/>
        </label>
      </figure>";  // 2 param - img_url,  alt
	  
	  $thumbnail = "<label for=\"image%s\" class=\"thumb\" style=\"background-image: url('%s')\"></label>";  // 2 params - id number, image_url
	  
	  
	  $galleryT = "<section class=\"gallery\">
		<h2>%s</h2>
		  <div class=\"carousel\">
			
				%s
			
			
			<input type=\"checkbox\" id=\"fullscreen\" name=\"gallery-fullscreen-control\"/>
			
			<div class=\"wrap\">
			  
				%s
			</div>
			
			<div class=\"thumbnails\">
			  
			  <div class=\"slider\"><div class=\"indicator\"></div></div>
			  
				%s
			</div>
		  </div>
		</section>";  // 4 params - title, contr-container, full-image-container, thumbnail-container
	  
	  
	  
	  $controlsContaner = "";
	  $fullImageContainer = "";
	  $thumbNailContainer = "";
	  
	  
  
 		$args = array( 'post_type'=>'gallery', 'posts_per_page'=> 100, 'orderby'=> 'date', 'order' => 'ASC');  
				$gallery_query = new WP_Query( $args ); $image_counter = 1; 
				if ( $gallery_query->have_posts() ) : while ( $gallery_query->have_posts() ) : $gallery_query->the_post(); 
				
				
				
  ?>
  
  
	  <?php
	  // slider loop
	  // add controls
	  if ($image_counter == 1){
		$controlsContaner .= sprintf($gallery_control_first, $image_counter);
	  }
	  else{
		$controlsContaner .= sprintf($gallery_control, $image_counter);
	  }
	  
		//full screen
		$full_image_path = get('full_image');
		$image_alt = get('image_alt');
		$fullImageContainer .= sprintf($full_screen, $full_image_path, $image_alt);
		
		// add thumbnail
		$thumbnail_image_path = get('thumbnail_image');
		$thumbNailContainer .= sprintf($thumbnail, $image_counter, $thumbnail_image_path);
		
		$image_counter +=1;
	  
	  ?>
	    
	
  
 	<?php
		// gallery loop end
		endwhile; else:
	?>
	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
	<?php 
		endif;
		wp_reset_postdata();
	?>

	<?php
		// echo full gallery
		echo sprintf($galleryT, "",  $controlsContaner, $fullImageContainer, $thumbNailContainer);
	?>
  
<!--  
<section class="gallery">
<h2>Галерея</h2>
  <div class="carousel">  
    
    <input type="radio" id="image1" name="gallery-control" checked>
    <input type="radio" id="image2" name="gallery-control">
    <input type="radio" id="image3" name="gallery-control">
    <input type="radio" id="image4" name="gallery-control">
    
    
    <input type="checkbox" id="fullscreen" name="gallery-fullscreen-control"/>
    
    <div class="wrap">
      
      <figure>
        <label for="fullscreen">
          <img src="https://unsplash.it/1000/700/?random" alt="image1"/>
        </label>
      </figure>
      
      <figure>
        <label for="fullscreen">
          <img src="https://unsplash.it/1200/980/?random" alt="image2"/>
        </label>
      </figure>

      <figure>
        <label for="fullscreen">
          <img src="https://unsplash.it/1600/880/?random" alt="image3" />
        </label>
      </figure>

      <figure>
        <label for="fullscreen">
          <img src="https://unsplash.it/2000/1400/?random" alt="image4"/>
        </label>
      </figure>
    </div>
    
    <div class="thumbnails">
      
      <div class="slider"><div class="indicator"></div></div>
      
      <label for="image1" class="thumb" style="background-image: url('https://unsplash.it/700/480/?random')"></label>
      
      <label for="image2" class="thumb" style="background-image: url('https://unsplash.it/700/400/?random')"></label>
      
      <label for="image3" class="thumb" style="background-image: url('https://unsplash.it/700/410/?random')"></label>
        
      <label for="image4" class="thumb" style="background-image: url('https://unsplash.it/700/450/?random')"></label>
    </div>
  </div>
</section>
-->


</div>
</div>
</div>	
	
	
	
	
	
	
	

<!--  footer -->	
<?php get_footer(); ?>									
<?php
/**
 * The template for the main page
 *
 * @package WordPress
 * @subpackage Autoparts
 * @since Autoparts 1.0
 */
?>


<!DOCTYPE html>
<html <?php language_attributes(); ?>> 

<head>
	<title><?php bloginfo('name'); ?></title>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="description" content="<?php bloginfo('description'); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="google-site-verification" content="hATAyUJOuKxy97IwihSgxo7gUNbh64OTRnwc8McKenI" />

	<?php wp_head(); ?>
</head>

<body class="ishome">

  
<?php

// get general site settings


	$args = array( 'post_type'=>'general_settings', 'posts_per_page'=> 100, 'orderby'=> 'date', 'order' => 'ASC');  
			$gallery_query = new WP_Query( $args ); $image_counter = 1; 
			if ( $gallery_query->have_posts() ) : while ( $gallery_query->have_posts() ) : $gallery_query->the_post(); 
			
			$GLOBALS['logo_image'] = get('logo_image');
			$GLOBALS['logo_image_alt'] = get('logo_image_alt');
			$GLOBALS['company_slogan'] = get('company_slogan');
			$GLOBALS['company_subslogan'] = get('company_subslogan');
			$GLOBALS['main_search'] = get('main_search');
			$GLOBALS['busket'] = get('busket');
			$GLOBALS['phones'] = get('phones');
			$GLOBALS['emails'] = get('emails');
			$GLOBALS['menu1'] = get('menu1');
			$GLOBALS['menu2'] = get('menu2');
			$GLOBALS['menu3'] = get('menu3');
			$GLOBALS['menu4'] = get('menu4');
			$GLOBALS['menu5'] = get('menu5');
			
			$GLOBALS['lineup'] = get('lineup');
			$GLOBALS['models'] = get('models');
			$GLOBALS['categories'] = get('categories');
			$GLOBALS['subcategories'] = get('subcategories');
			$GLOBALS['brand_site'] = get('brand_site');
			$GLOBALS['search'] = get('search');
			
			$GLOBALS['contacts_sidebar_title'] = get('contacts_sidebar_title');
			$GLOBALS['address'] = get('address');
			$GLOBALS['look_map'] = get('look_map');
			
			$GLOBALS['producer'] = get('producer');
			$GLOBALS['into_busket'] = get('into_busket');
			
			$GLOBALS['busket_price'] = get('busket_price');
			$GLOBALS['busket_number'] = get('busket_number');
			$GLOBALS['busket_remove_item'] = get('busket_remove_item');
			$GLOBALS['busket_clear'] = get('busket_clear');
			
			$GLOBALS['busket_total'] = get('busket_total');
			$GLOBALS['busket_order'] = get('busket_order');
			$GLOBALS['busket_empty'] = get('busket_empty');
			
			$GLOBALS['working_hours'] = get('working_hours');
			
			$GLOBALS['payment_conditions'] = get('payment_conditions');
			$GLOBALS['delivery_conditions'] = get('delivery_conditions');
			$GLOBALS['company_name'] = get('company_name');
			


?> 



<?php
	// general loop end
	endwhile; else:
?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php 
	endif;
	wp_reset_postdata();
?> 
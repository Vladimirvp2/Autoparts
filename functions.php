<?php

/**
 * The template for the main page
 *
 * @package WordPress
 * @subpackage Autoparts
 * @since Autoparts 1.0
 */

include_once('fcore/HTMLBuilder.php');
include_once('fcore/cart.php');
include_once('fcore/general_functions.php');


session_start();

if ( !isset($_SESSION['cart'])){
	$_SESSION['cart'] = new ShopCart();
}

 
function autoparts_theme_setup(){

	$load_domain_result = load_theme_textdomain( 'ac', get_template_directory() . '/languages' );

	add_theme_support('title-tag');
	add_theme_support( 'post-thumbnails' ); 
	add_theme_support('custom-logo', array(
		'width' => 80,
		'flex-height' => true
	));
	
	add_theme_support('html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption'
	));

	add_theme_support('post-formats', array(
		'image',
		'gallery'
	));

	register_nav_menu('services_nav', 'Services nav menu');	
	add_filter('show_admin_bar', '__return_false');
		
	
	
}

add_action('after_setup_theme', 'autoparts_theme_setup');



//============================================ load styles and scripts in the header for the first page ========================================== 
function autoparts_header_scripts() {

	// bootstrap
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/libs/bootstrap/css/bootstrap.min.css' );
	// font-awesome
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/libs/font-awesome/css/font-awesome.min.css' );	
	
	// styles for the first page
	
	wp_enqueue_style( 'w3', get_template_directory_uri() . '/css/w3.css' );	
	wp_enqueue_style( 'header', get_template_directory_uri() . '/css/header6.min.css' );	

	
	// jstree
	//wp_enqueue_style( 'jstree', get_template_directory_uri() . '/libs/jstree/themes/default/style.min.css' );	
	
    // styles for the rest of pages
	wp_enqueue_style( 'main', get_template_directory_uri() . '/css/main6.min.css' );	
	
	// js scripts
	wp_enqueue_script( 'libs', get_template_directory_uri() . '/js/libs.js' );	
	wp_enqueue_script( 'common_js', get_template_directory_uri() . '/js/common6.js' ); 	
	
	wp_enqueue_style( 'style', get_template_directory_uri() . '/style.css' ); 
	
	
	//if lt IE 9 in footer
	wp_enqueue_script( 'libs', get_template_directory_uri() . 'libs/html5shiv/es5-shim.min.js');
	wp_enqueue_script( 'libs', get_template_directory_uri() . 'libs/html5shiv/html5shiv.min.js' );
	wp_enqueue_script( 'libs', get_template_directory_uri() . 'libs/html5shiv/html5shiv-printshiv.min.js' );
	wp_enqueue_script( 'libs', get_template_directory_uri() . 'libs/respond/respond.min.js' );

};

add_action('wp_enqueue_scripts', 'autoparts_header_scripts');



//============================================= ajax

add_action('wp_ajax_nopriv_autoparts_add_item_to_cart', 'autoparts_add_item_to_cart');
add_action('wp_ajax_autoparts_add_item_to_cart', 'autoparts_add_item_to_cart');


function autoparts_add_item_to_cart(){
	// load service
	$code = $_POST["code"];
	$name = $_POST["name"];
	$price = $_POST["price"];
	$img = $_POST["img"];
	$number = $_POST["number"];
	
	$data = array(
		'code' => $code, 
		'name' => $name, 
		'price' => $price,
		'img' => $img,
		'number' => $number
	);
	
	
	$_SESSION['cart']->add( $data );
	
	$totalNum = ( $_SESSION['cart']->getItemsNumber() > 0 ) ? $_SESSION['cart']->getItemsNumber() : "";
	$totalPrice = ( $_SESSION['cart']->getTotalSum() > 0 ) ? priceFormat( $_SESSION['cart']->getTotalSum() ) : "";
	echo $totalNum . "|" . $totalPrice;
	
	
	die();
	
	return;
	
};


add_action('wp_ajax_nopriv_autoparts_clear_cart', 'autoparts_clear_cart');
add_action('wp_ajax_autoparts_clear_cart', 'autoparts_clear_cart');


function autoparts_clear_cart(){

	if ( !isset($_SESSION['cart'])){
		$_SESSION['cart'] = new ShopCart();
	}
	
	$_SESSION['cart']->clear();
	
	echo 1;
	
	die();
	
	return;
	
};



add_action('wp_ajax_nopriv_autoparts_remove_item_from_cart', 'autoparts_remove_item_from_cart');
add_action('wp_ajax_autoparts_remove_item_from_cart', 'autoparts_remove_item_from_cart');


function autoparts_remove_item_from_cart(){

	if ( !isset($_SESSION['cart'])){
		$_SESSION['cart'] = new ShopCart();
	}
	
	// Items are identified by only 2 params - code and name
	$code = $_POST["code"];
	$name = $_POST["name"];
	$number = $_POST["number"];
		
	$data = array(
		'code' => $code, 
		'name' => $name,
		'number' => $number
	);
	
	$_SESSION['cart']->remove( $data );
	
	echo 1;
	
	die();
	
	return;
	
};




add_action('wp_ajax_nopriv_autoparts_submit_order_and_clear_cart', 'autoparts_submit_order_and_clear_cart');
add_action('wp_ajax_autoparts_submit_order_and_clear_cart', 'autoparts_submit_order_and_clear_cart');



function autoparts_submit_order_and_clear_cart(){

	if ( !isset($_SESSION['cart'])){
		$_SESSION['cart'] = new ShopCart();
	}
	
	$message = $_POST["data"];
	$name = $_POST["name"];
	
	
	// send mail
	$to = get_bloginfo('admin_email');
	$subject = 'Автодетали, контактная форма - ' . $name;
	$headers[] = "";
	$headers[] = 'From: ' . ' Autodetails ' . '<' . "email@nahodu.pl.ua"  .  '>';
	
	wp_mail($to, $subject, $message, $headers);	
	
	$_SESSION['cart']->clear();
	
	echo $_SESSION['cart']->getItemsNumber() . "|" . $_SESSION['cart']->getTotalSum();
	
	die();
	

	
};



add_action('wp_ajax_nopriv_autoparts_vin_show_items_cat', 'autoparts_vin_show_items_cat');
add_action('wp_ajax_autoparts_vin_show_items_cat', 'autoparts_vin_show_items_cat');


function autoparts_vin_show_items_cat(){

	$code = $_POST["code"];
	$vin = $_POST["vin"];
	
	
	//echo $code . ", " . $vin;
	$data['code'] = $code;
	$data['vin'] = $vin;
	$data['act'] = 'getsubcategoriesvin';	
	$res = $_SESSION['html_builder']->getSubcategoriesVINMarkup( $data );
	echo $res;
	
	die();
	
	
};


add_action('wp_ajax_nopriv_autoparts_vin_show_items_all', 'autoparts_vin_show_items_all');
add_action('wp_ajax_autoparts_vin_show_items_all', 'autoparts_vin_show_items_all');

function autoparts_vin_show_items_all(){
	$code = $_POST["code"];

	$data['data'] = $code;
	$data['act'] = 'getitemsallvin';
	$data['vin'] = $_POST["vin"];
	$data['code'] = $_POST["menuid"];
	$res = $_SESSION['html_builder']->getItemsVINMarkup( $data );
	echo $res;
	//echo $data['data'];
	
	die();

}


add_action('wp_ajax_nopriv_autoparts_vin_show_items_details', 'autoparts_vin_show_items_details');
add_action('wp_ajax_autoparts_vin_show_items_details', 'autoparts_vin_show_items_details');

function autoparts_vin_show_items_details(){

	$code = $_POST["code"];

	$data_r['data'] = $code;
	$data_r['act'] = 'getitemsreplacedvin';
	$res_r = $_SESSION['html_builder']->getReplacedItemsVINMarkup( $data_r );
	
	$data_app['data'] = $code;
	$data_app['act'] = 'getitemsapplicationdvin';
	$res_app = $_SESSION['html_builder']->getApplicationItemsVINMarkup( $data_app );	
	echo $res_r . $res_app;
	
	die();

}










/*
function mailtrap($phpmailer) {
  $phpmailer->isSMTP();
  $phpmailer->Host = 'smtp.mailtrap.io';
  $phpmailer->SMTPAuth = true;
  $phpmailer->Port = 2525;
  $phpmailer->Username = '05529052136bd2';
  $phpmailer->Password = '6e74bb2e0c877e';
}

add_action('phpmailer_init', 'mailtrap');

*/










	
?>	





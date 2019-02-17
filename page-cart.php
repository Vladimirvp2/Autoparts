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

	<div class="container-fluid2 menu-container" data-url="<?php echo admin_url('admin-ajax.php'); ?>"  data-loadtime="5">
		<div class="container">
			<div class="row">

				<div class="col-lg-9 col-md-9 hidden-sm  hidden-xs">
					<div class="main-menu">
						<ul>
							<li><a  href="<?php echo get_site_url(); ?>"><?php echo $GLOBALS['menu1']; ?></a></li>
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
							<li><a  href="<?php echo get_site_url(); ?>"><?php echo $GLOBALS['menu1']; ?></a></li>
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

			<div class="col-lg-2 col-md-2 col-sm-3">
				<img class="logo" src="<?php echo $GLOBALS['logo_image']; ?>" alt="<?php echo $GLOBALS['logo_image_alt']; ?>">
				<p class="logo-text"><?php echo $GLOBALS['company_name']; ?></p>
			</div>

			<div class="col-lg-10 col-md-10 col-sm-9">



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
		
		?>		
		
				

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				
				<div class="main-panel">
				
					<div class="row empty-cart-container <?php if ( $_SESSION['cart']->getItemsNumber() > 0 ) { echo "hidden"; } ?>">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<p class="empty-cart-message"><?php echo $GLOBALS['busket_empty']; ?></p>
							<p class="empty-cart-back-link hidden"><a href="<?php echo $_SERVER['HTTP_REFERER'];  ?>">Повернутись до вибору товарів</a></p>
						</div>					
					</div>


					<div class="cart <?php if ( $_SESSION['cart']->getItemsNumber() < 1 ) { echo "hidden"; } ?>">
					
					
					<?php
						$shopCartMarkup = $_SESSION['html_builder']->getShopCartMarkup( $_SESSION['cart'] );		
						echo $shopCartMarkup;
					
					?>

						<div class="row">  <!-- total -->
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<a class="clear-cart" href="#">
			  						<i class="fa fa-times" aria-hidden="true"></i>
			  						<?php echo $GLOBALS['busket_clear']; ?>
			  					</a>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cart-total-data">
								<p><span class="total-word"><?php echo $GLOBALS['busket_total']; ?>&nbsp</span><span class="cart-total-price">
									<?php 
										$sum = $_SESSION['cart']->getTotalSum();  
										echo number_format($sum , 2, ',', ' ');
									
									?></span></p>
								<a class="cart-order-button" href="#">
			  						<?php echo $GLOBALS['busket_order']; ?>
			  					</a>
							</div>

						</div>



					</div>
					
					
					
					<div class="w3-container">

					  <div id="id02" class="w3-modal">
						<div class="w3-modal-content w3-animate-top w3-card-4">
						  <header class="w3-container"> 
							<span onclick="document.getElementById('id02').style.display='none'" 
							class="w3-button w3-display-topright">&times;</span>
							<h2>Оформлення замовлення</h2>
						  </header>


											
											<div class="col-sm-12" id="m-Form">
											

												<form class="contact-form">
													<div class="form-group name">
														<label for="name" style="margin-top: 15px; ">Ім'я&nbsp<span class="obligatory-field-mark">*<span></label>
														<input type="name" class="form-control" id="name" placeholder="Ваше ім'я...">
														<small class="text-danger form-control-msg">Вкажіть ім'я</small>
													</div>
													<div class="form-group phone">
														<label for="phone">Телефон&nbsp<span class="obligatory-field-mark">*<span></label>
														<input type="phone" class="form-control" id="phone" placeholder="Телефон для зворотнього зв'язку...">
														<small class="text-danger form-control-msg">Вкажіть телефон</small>
													</div>

													<div class="form-group email">
														<label for="phone">E-mail</label>
														<input type="phone" class="form-control" id="email" placeholder="">
														<small class="text-danger form-control-msg">Вкажіть правильний e-mail або залиште поле пустим</small>
													</div>

													<div class="form-group payment hidden" style="margin-top: 15px; ">
														<label for="message">Тип сплати&nbsp<span class="obligatory-field-mark">*<span></label>
														<select class="selectpicker form-control" id="paytype" placeholder="Оберыть тип сплати...">
															<option>Картка</option>
															<option>Банківський переказ</option>
															<option>Післяплата</option>
															<option>Готівка</option>
														</select>
														<small class="text-danger form-control-msg">Вкажіть тип оплати</small>
													</div>


													<div class="form-group delivery hidden">
														<label for="message">Тип доставки&nbsp<span class="obligatory-field-mark">*<span></label>
														<select class="selectpicker form-control" id="devtype" placeholder="Оберіть тип доставки...">
															<option>Самовивіз</option>
															<option>Нова пошта</option>
															<option>Автолюкс</option>
															<option>Гюнсел</option>
															<option>Intime</option>
															<option>Delivery</option>
															<option>Інше</option>
														</select>
														<small class="text-danger form-control-msg">Вкажіть тип доставки</small>
													</div>


													<div class="form-group delivery-address hidden">
														<label for="phone">Адреса доставки/відділення кур'єрської служби&nbsp<span class="obligatory-field-mark">*<span></label>
														<input type="phone" class="form-control" id="adres" placeholder="Адреса">
														<small class="text-danger form-control-msg">Вкажіть адресу</small>
													</div>


													<div class="form-group delivery-name hidden">
														<label for="name">Ім'я особи для отримання замовлення&nbsp<span class="obligatory-field-mark">*<span></label>
														<input type="name" class="form-control" id="receiver" placeholder="Ім'я">
														<small class="text-danger form-control-msg">Вкажіть ім'я</small>
													</div>



													<div class="form-group comments hidden">
														<label for="message">Коментарі та побажання</label>
														<textarea style="resize: vertical;" class="form-control" maxlength="50" rows="2" id="message" placeholder=""></textarea>
													</div>

													<div class="forward-button-wrapper">
														<p><label for="phone" style="font-size: 90%; font-weight: 100;">Обов'язкові поля позначено зірочкою<span class="obligatory-field-mark">*<span></label></p>
														<button type="submit" class="btn btn-default forward-button">Далі</button>
													</div>
													<div class="submit-button-wrapper  hidden">
														<button type="submit" class="btn btn-default back-button">Назад</button>&nbsp&nbsp&nbsp<button type="submit" class="btn btn-default submit-button">Підтвердити замовлення</button>
														<small class="text-danger form-control-msg js-form-submission">Дані відправляються...</small>
														<small class="text-danger form-control-msg js-form-success">Замовлення успішно відправлено. Дякуємо</small>
														<small class="text-danger form-control-msg js-form-error">Виникла проблема при відправленні даних. Повторіть, будь-ласка</small>
														<small class="text-danger form-control-msg js-field-error">Не усі обов'язкові поля заповнено</small>
													</div>

												</form>
											</div>	


						  <footer class="w3-container">
							<p></p>
						  </footer>
						</div>
					  </div>

					  
					</div>					
					
					
					
					
					

				</div>

			</div>

		</div>
	</div>
	
	

<!--  footer -->	
<?php get_footer(); ?>					
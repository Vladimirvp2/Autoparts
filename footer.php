<?php
/**
 * The template for the main page
 *
 * @package WordPress
 * @subpackage Autoparts
 * @since Autoparts 1.0
 */
?>


	<!-- footer -->
	<div class="container-fluid footer">
		<div class="container">	
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-12">
					<div class="logo-footer">
						<img class="logo" src="<?php echo $GLOBALS['logo_image']; ?>" alt="<?php echo $GLOBALS['logo_image_alt']; ?>">
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

					<div class="contacts">
					    
					    <?php
					        // print phones
					        $phoneTemplate = "<p class=\"phone\">%s</p>";
					        $phoness = explode(",", $GLOBALS['phones']);
					        foreach($phoness as &$phone){
					            echo sprintf($phoneTemplate, trim($phone) );
					        }
					        
					    ?>					    
					    
					    <!--
						<p class="phone">(044) 392-00-72</p>
						<p class="phone">(050) 369-48-16</p>
						<p class="phone">(067) 673-14-25</p>
						<p class="phone">(073) 214-14-53</p>
						-->
						
					    <?php
					        // print email
					        $emailTemplate = "<p><a class=\"email\" href=\"\">%s</a></p>";
					        $emailss = explode(",", $GLOBALS['emails']);
					        foreach($emailss as &$email){
					            echo sprintf($emailTemplate, trim($email) );
					        }
					        
					    ?>						
						
						
						<!--
						<p><a class="email" href="">super-avtozapchasti@ukr.net</a></p>
						-->
						<p class="address"><?php echo $GLOBALS['address']; ?></p>
						<p><a class="address-map" href="https://nahodu.pl.ua/contacts/"><?php echo $GLOBALS['look_map']; ?></a></p>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="menu-footer">
							<ul>
								<li><a class="active" href="https://nahodu.pl.ua/"><?php echo $GLOBALS['menu1']; ?></a></li>
								<li><a href="https://nahodu.pl.ua/gallery/"><?php echo $GLOBALS['menu2']; ?></a></li>
								<li><a href="https://nahodu.pl.ua/paydel/"><?php echo $GLOBALS['menu3']; ?></a></li>
								<li><a href="https://nahodu.pl.ua/contacts/"><?php echo $GLOBALS['menu4']; ?></a></li>
							</ul>
						</div>
				</div>	
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				        <br>
						
				</div>	
				
			</div>
		</div>

	</div>



	
	<!--[if lt IE 9]>
	<script src="libs/html5shiv/es5-shim.min.js"></script>
	<script src="libs/html5shiv/html5shiv.min.js"></script>
	<script src="libs/html5shiv/html5shiv-printshiv.min.js"></script>
	<script src="libs/respond/respond.min.js"></script>
	<![endif]-->

	<!--  <link rel="stylesheet" href="libs/jstree/themes/default/style.min.css">	 -->

	<!-- Load CSS -->

	


</body>
</html>	
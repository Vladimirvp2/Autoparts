
var pageScrollTime = 1000;
var showUpArrowOffset = 400;
var smallMenuOpened = false;

var currentItemSorter = 0

$(function() {


	// add modal dialog to DOM
	var mdw = $("  \
	  \
	  <div id='idmodal' class='w3-modal'>\
	    <div class='w3-modal-content w3-animate-top w3-card-4'>\
	      <header class='w3-container'> \
	        <span onclick='document.getElementById('idmodal').style.display='none'' \
	        class='w3-button w3-display-topright'>&times;</span>\
	        <h2 class='head-content'>Modal Header</h2>\
	      </header>\
			<div><p class='modal-dialog-content'></p></div>\
			<div class='modal-dialog-button-wrapper'><button type='button' class='btn btn-default modal-dialog-button'>Oк</button></div>\
	\
	      <footer class='w3-container'>\
	        <p></p>\
	      </footer>\
	    </div>\
	  </div>\
	\
		");

	$('body').append(mdw);
	document.getElementById('idmodal').style.display='none';






	// set the item's sorter
	setItemsSorter();

	// set search box value
	setSearchBoxValue();









	$(".toggle-mnu").click(function() {
		$(this).toggleClass("on");
		$(".main-menu-hidden").slideToggle();
		//$(this).parent().next().next().find(".main-mnu").slideToggle();
		smallMenuOpened = !smallMenuOpened;
		return false;
	});

	var $nav_panel_opened = false; 
	$(".toggle-mnu-small").click(function() {
		//$(this).toggleClass("on");
		$(this).toggleClass("on");
		if (!$nav_panel_opened ){
			openNav();
			$nav_panel_opened = true;
		}
		else{
			closeNav();
			$nav_panel_opened = false;
		}
		//$(".hidden-menu-small").slideToggle();
		//$(this).parent().next().next().find(".main-mnu").slideToggle();
		return false;
	});	







	// click on the group
	var group_selector = '.items-category-container a';
	$(group_selector).on('click', function(event){

			event.preventDefault();
			var groupId = $(this).attr("cat_id");

			// selector for subcategories
			var subgroupAllSel = ".items-subcategory-container";
			$(subgroupAllSel).each(function( index ) {

				var groupIdS =  $(this).attr("cat_id");
				if (groupIdS == groupId){
					//$(this).removeClass('hidden');
					$(this).removeClass('hidden');
				}
				else{
					$(this).addClass('hidden');

				}

			});

	});




	// click on the vin search model
	var group_selector = '.main-panel .item-vin-search';
	$(group_selector).on('click', function(event){

			event.preventDefault();
			var vinId = $(this).attr("vin_id");

			// selector for data tables
			var subgroupAllSel = ".vin-table";
			$(subgroupAllSel).each(function( index ) {

				var vinIdS =  $(this).attr("vin_id");
				if (vinIdS == vinId){
					//$(this).removeClass('hidden');
					$(this).removeClass('hidden');
				}
				else{
					$(this).addClass('hidden');

				}

			});

	});




	$('#soflow').change(function() {

		var choosenIndex = $(this)[0].selectedIndex;
		if ( choosenIndex == currentItemSorter){

			return;
		}

		currentItemSorter = choosenIndex;

		//replace in url sort and sort-order values
		var currURL  = window.location.href; 
		var parsedURL = parse_query_string( currURL );

		var fullSort = "&sort=" + parsedURL.sort;
		var fullSortOrder = "&sort_ord=" + parsedURL.sort_ord;
		var fullPage = "&pg=" + parsedURL.pg;

		var urlClean = currURL.replace(fullSort, "" ).replace(fullSortOrder, "" ).replace(fullPage , "" );

		var choosenValue = $(this)[0].value;
		var newURL = urlClean + choosenValue;

		window.location.href = newURL;

	});


	// clear shop cart 
	$('.clear-cart').on('click', function(event){
		
		event.preventDefault();

		var currURL  = window.location.href; 



		//start ajax query
		var ajaxurl = $('.menu-container').data('url');
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 7000, 
			data: {
				action: 'autoparts_clear_cart'
			},
			error: function(response){
				showModalDialog('Ошибка', 'Произошла ошибка. Повторите действие позже');
			},
			success: function(response){
					console.log( response.trim() );

					if (response.trim() > 0){
						// realod cart
						window.location.href = currURL;
					}
			}
		
		});

	});



	// remove clicked cart item
	$('.cart-item-remove').on('click', function(event){
		
		event.preventDefault();

		//get the code and name of the clicked cart item
		var cartItem = $(this).parents( ".cart-item" );
		var itemCode = cartItem.find('.cart-item-code').text();
		var itemName = cartItem.find('.cart-item-name').text();
		var itemNumber = cartItem.find('.cart-item-number').val() ;
		var currURL  = window.location.href; 

		//start ajax query
		var ajaxurl = $('.menu-container').data('url');
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 7000, 
			data: {
				'code': itemCode,
				'name': itemName,
				'number': itemNumber,
				action: 'autoparts_remove_item_from_cart'
			},
			error: function(response){
				showModalDialog('Ошибка', 'Произошла ошибка. Повторите действие позже');
			},
			success: function(response){
					console.log( response.trim() );

					if (response.trim() > 0){
						// realod cart
						window.location.href = currURL;
					}
			}
		
		});

	});	






	// shop cart add item button clicked 
	$('.item .cart-button').on('click', function(event){

		
		console.log( "Cart button click" );

		event.preventDefault();

		$('.menu-container .cart-data .items-number').removeClass('blink-menu-cart');
		$('.menu-container .cart-data .items-price').removeClass('blink-menu-cart');		


		var item = $(this).parents( ".items-body" );

		var itemPrice = item.find('.lg-price-container p.price').text();

		var itemNumber = "";
		if (item.find('.large-container').css('display') != 'none'){
			itemNumber = item.find('.large-container .item-numbers').val();
		}
		else{
			itemNumber = item.find('.small-container .item-numbers').val();
		}

		//var itemNumber = item.find('large-container .item-numbers').val() ;
		var itemName = item.find('.items-body-content .items-name').text();
		var itemImage = item.find('.image-no-click').attr('data'); //item.find('.items-image').attr('src');
		// that's a key for saving
		var itemCode = item.find('.items-code').text();


		//start ajax query
		var ajaxurl = $('.menu-container').data('url');
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 7000,  //$('.menu-container').data('loadtime'),
			data: {
				'code': itemCode,
				'name': itemName,
				'number': itemNumber,
				'img': itemImage,
				'price': itemPrice,
				action: 'autoparts_add_item_to_cart'
			},
			error: function(response){
				showModalDialog('Ошибка', 'Произошла ошибка. Повторите действие позже');
				// show error dialog
			},
			success: function(response){
					console.log( response.trim() );

					var vars = response.trim().split('|');
					var itemsTotalNumber = vars[0];
					var itemsTotalSum = vars[1];	
			

					renewMenuCartVal(itemsTotalNumber, itemsTotalSum);
			}
		
		});
	});





	$(".cart-order-button").click(function(event) {   
		console.log("Order popup");
		event.preventDefault();

		removeAllErrorsFromSubmitForm();
		// show order dialog
		//document.getElementById('id02').style.display='block';	

		document.getElementById('id02').style.display='block';	

		// open olways on the first page
		$("#m-Form .back-button").trigger("click");


	});


	$("#m-Form .forward-button").click(function(event) { 
		event.preventDefault();


		// clean all the possible error messages
		// on the form
		removeAllErrorsFromSubmitForm();

		var form = $('#m-Form');
		var name = form.find('#name').val();
		var phone = form.find('#phone').val();
		var email = form.find('#email').val();	
		


		//validate data
		if (name.trim() === ""){
			form.find('#name').parent('.form-group').addClass('has-error');
			$('.js-field-error').addClass('js-show-feedback');
			return;
		}		


		// validate the phone
		if (!validatePhone(phone)){
			form.find('#phone').parent('.form-group').addClass('has-error');
			$('.js-field-error').addClass('js-show-feedback');
			return;
		}

		// validate thу email
		if ( email.trim() !== "" && !validateEmail(email) ){
			$('#email').parent('.form-group').addClass('has-error');
			$('.js-field-error').addClass('js-show-feedback');
			return;
		}



		form.find('.name').addClass('hidden');
		form.find('.phone').addClass('hidden');
		form.find('.email').addClass('hidden');

		form.find('.submit-button-wrapper').removeClass('hidden');
		form.find('.payment').removeClass('hidden');
		form.find('.delivery').removeClass('hidden');
		form.find('.delivery-address').removeClass('hidden');
		form.find('.delivery-name').removeClass('hidden');
		form.find('.comments').removeClass('hidden');


		form.find('.forward-button-wrapper').addClass('hidden');



		

		// show payment-delivery page on the form

	});




	$("#m-Form .back-button").click(function(event) { 
		event.preventDefault();


		var form = $('#m-Form');

		form.find('.name').removeClass('hidden');
		form.find('.phone').removeClass('hidden');
		form.find('.email').removeClass('hidden');

		form.find('.submit-button-wrapper').addClass('hidden');
		form.find('.payment').addClass('hidden');
		form.find('.delivery').addClass('hidden');
		form.find('.delivery-address').addClass('hidden');
		form.find('.delivery-name').addClass('hidden');
		form.find('.comments').addClass('hidden');

		form.find('.forward-button-wrapper').removeClass('hidden');


		
	});


	// sumbit click button
	$(".submit-button").click(function(event) { 	
		event.preventDefault();

		removeAllErrorsFromSubmitForm();


		var form = $('#m-Form');
		var type_of_payment = form.find( "#paytype option:selected" ).val();
		var type_of_delivery = form.find( "#devtype option:selected" ).val();
		var address = form.find('#adres').val();
		var receiver = form.find('#receiver').val();	


		// validate payment
		if (type_of_payment.trim() === ""){
			$('#paytype').parent('.form-group').addClass('has-error');
			//$('.js-field-error').addClass('js-show-feedback');
			return;
		}

		// validate delivery
		if (type_of_delivery.trim() === ""){
			$('#devtype').parent('.form-group').addClass('has-error');
			//$('.js-field-error').addClass('js-show-feedback');
			return;
		}

		// validate address
		if (address.trim() === ""){
			$('#adres').parent('.form-group').addClass('has-error');
			//$('.js-field-error').addClass('js-show-feedback');
			return;
		}

		// validate address
		if (receiver.trim() === ""){
			$('#receiver').parent('.form-group').addClass('has-error');
			//$('.js-field-error').addClass('js-show-feedback');
			return;
		}	


		// All the fields are correct, disable the form
		form.find('input, select, textarea, button').attr('disabled', 'disabled');
		
		// Show message In process
		$('.js-form-submission').addClass('js-show-feedback');			




		var cartData = getCartItemsData();
		var formData =  getSumbitFormsData();
		var orderData = formData  + cartData;
		var customerName = getSubmitFormName();

		console.log(formData  + cartData + ",\n" + customerName);

		// sent to server
		//start ajax query
		var ajaxurl = $('.menu-container').data('url');
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 7000, 
			data: {
				'name': customerName,
				'data': orderData,
				action: 'autoparts_submit_order_and_clear_cart'
			},
			error: function(response){
				console.log("Server error occured!" + response);
				// remove message In process
				$('.js-form-submission').removeClass('js-show-feedback');
				// show error message to the user
				$('.js-form-error').addClass('js-show-feedback');
				// unblock the form and clear the fields
				form.find('input, textarea, button, select').removeAttr('disabled');
			},
			success: function(response){
					console.log( response.trim() );

					// hide cart and show) empty cart message
					$('.cart').addClass('hidden');
					$('.empty-cart-container').removeClass('hidden');
					//$('.empty-cart-back-link').removeClass('hidden');
					// renew cart menu values
					var vars = response.trim().split('|');
					var itemsTotalNumber = vars[0];
					var itemsTotalSum = vars[1];					
					renewMenuCartVal(itemsTotalNumber, itemsTotalSum);

					$('.js-form-submission').removeClass('js-show-feedback');
					// unlock the form and clear the fields
					form.find('input, textarea, button, select').removeAttr('disabled');
					// clear fields
					form.find('input, textarea, button').val('');

					document.getElementById('id02').style.display='none';

					// show success sumbit dialog
					showModalDialog("Повідомлення", "Ваше замовлення прийнято. Найближчим часом з Вами зв'яжуться наші представники");			
					
			}
		
		});	


	});






	// sumbit click button
	$(".submit-button2").click(function(event) { 
		console.log("Submit pressed");

		event.preventDefault();


		//check provided values in the form
		var form = $('#m-Form');
		var name = form.find('#name').val();
		var phone = form.find('#phone').val();
		var type_of_payment = form.find( "#paytype option:selected" ).val();
		var message = form.find('#message').val();



		// clean all the possible error messages
		// on the form
		removeAllErrorsFromSubmitForm();

		// make fields checks
		// validate the name	
		if (name.trim() === ""){
			form.find('#name').parent('.form-group').addClass('has-error');
			$('.js-field-error').addClass('js-show-feedback');
			return;
		}		


		// validate the phone
		if (!validatePhone(phone)){
			form.find('#phone').parent('.form-group').addClass('has-error');
			$('.js-field-error').addClass('js-show-feedback');
			return;
		}


		// validate payment type
		if (type_of_payment.trim() === "" ){
			form.find( "#paytype" ).parent('.form-group').addClass('has-error');
			$('.js-field-error').addClass('js-show-feedback');
			return;
		}



		// All the fields are correct, disable the form
		form.find('input, select, textarea, button').attr('disabled', 'disabled');
		
		// Show message In process
		$('.js-form-submission').addClass('js-show-feedback');			




		var cartData = getCartItemsData();
		var formData =  getSumbitFormsData();
		var orderData = formData  + cartData;
		var customerName = getSubmitFormName();

		console.log(formData  + cartData + ",\n" + customerName);

		// sent to server
		//start ajax query
		var ajaxurl = $('.menu-container').data('url');
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 7000, 
			data: {
				'name': customerName,
				'data': orderData,
				action: 'autoparts_submit_order_and_clear_cart'
			},
			error: function(response){
				console.log("Server error occured!" + response);
				// remove message In process
				$('.js-form-submission').removeClass('js-show-feedback');
				// show error message to the user
				$('.js-form-error').addClass('js-show-feedback');
				// unblock the form and clear the fields
				form.find('input, textarea, button, select').removeAttr('disabled');
			},
			success: function(response){
					console.log( response.trim() );

					// hide cart and show) empty cart message
					$('.cart').addClass('hidden');
					$('.cart').addClass('hidden');
					$('.empty-cart-container').removeClass('hidden');
					//$('.empty-cart-back-link').removeClass('hidden');
					// renew cart menu values
					var vars = response.trim().split('|');
					var itemsTotalNumber = vars[0];
					var itemsTotalSum = vars[1];					
					renewMenuCartVal(itemsTotalNumber, itemsTotalSum);

					$('.js-form-submission').removeClass('js-show-feedback');
					// unlock the form and clear the fields
					form.find('input, textarea, button, select').removeAttr('disabled');
					// clear fields
					form.find('input, textarea, button').val('');

					document.getElementById('id02').style.display='none';

					// show success sumbit dialog
					showModalDialog("Сообщение", "Ваш заказ принят. В ближайшее время с Вами свяжутся наши представители");			
					
			}
		
		});
				

	});



	function validatePhone(phone){
		var re = /^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/;
		return re.test(phone);
	}



	function validateEmail(email) {

		var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
		if (email != "" && ( email == '' || !re.test(email) ) )
		{
			return false;
		}
		return true;
	}



	function validateVIN(vin){
		var re = /[A-Z0-9]{17}/;
		return re.test(vin);
	}



	function removeAllErrorsFromSubmitForm(){
		var form = $('#m-Form');

		// clean all the possible error messages
		// on the form
		form.find('#name').parent('.form-group').removeClass('has-error');
		form.find('#phone').parent('.form-group').removeClass('has-error');
		form.find('#paytype').parent('.form-group').removeClass('has-error');
		form.find('#email').parent('.form-group').removeClass('has-error');
		form.find('#devtype').parent('.form-group').removeClass('has-error');
		form.find('#adres').parent('.form-group').removeClass('has-error');
		form.find('#receiver').parent('.form-group').removeClass('has-error');

		// error messages
		form.find('.js-form-submission').removeClass('js-show-feedback');
		form.find('.js-form-error').removeClass('js-show-feedback');
		form.find('.js-form-success').removeClass('js-show-feedback');
		form.find('.js-field-error').removeClass('js-show-feedback');

	}








	function showModalDialog(head, text){
		var md = $('#idmodal');
		md.find('.head-content').text(head);
		md.find('.modal-dialog-content').empty();
		md.find('.modal-dialog-content').text(text);
		document.getElementById('idmodal').style.display='block';

	}




	//function showImageDialog(head, text){
	//	var md = $('#idmodal');
	//	md.find('.head-content').text(head);
	//	md.find('.modal-dialog-content').empty();
	//	md.find('.modal-dialog-content').append(text);
	//	document.getElementById('idmodal').style.display='block';

	//}





	$("#idmodal .modal-dialog-button, #idmodal .w3-display-topright").click(function(event) {   
		console.log("Close popup");
		event.preventDefault();

		document.getElementById('idmodal').style.display='none';	

	});




	// ===================================================================== main search field begin

	// click on the search panel button
	$(".search-panel .search-button").click(function(event) {  

		function lastLetter(str){
			return str.charAt(str.length - 1);
		} 

		event.preventDefault();
		console.log("Search button pressed");
		// get search value
		var searchVal = $('#search').val();
		
		if (searchVal.trim().length < 1 ){
			showModalDialog("Некоректні дані", "Запит для пошуку не повинен бути пустим")
			return;
		}

		$baseURL = getCurrentBaseURL();

		var resultURL = "";

		// if vin search
		if (validateVIN(searchVal)){
			resultURL += ($baseURL + "vin/");
			resultURL += ( "?act=search&data=" + searchVal );
			window.location.href = resultURL;

			return;

		}

		// if there are items in the url 
		if ($baseURL.indexOf("/items") > 0){

			resultURL += $baseURL;
		}
		else{
			if (lastLetter( $baseURL ) != '/'){
				resultURL += '/';
			}
			resultURL += ($baseURL + "items/");
		}


		resultURL += ( "?act=search&data=" + searchVal );

		// reload pade
		window.location.href = resultURL;



	});


	// press enter by search
	 $('#search').on('keypress', function (e) {
	         if(e.which === 13){
				$(".search-panel .search-button").trigger('click');

	         }
	   });


	// ===================================================================== main search field end



	// ===================================================================== search field in the main panel begin

	// click on the search panel button
	$(".main-panel .search-button").click(function(event) {  

		event.preventDefault();
		console.log("Search button pressed");
		// get search value
		var matchString = $('#search-main-panel').val().trim().toLowerCase();

		$('.main-panel .lineup, .main-panel .model').each(function(i, obj) {
		    
		    var el = $(this);
		    var elVal = $( this ).text();

			if (elVal.toLowerCase().indexOf(matchString) != -1){
			    el.parent().removeClass('hidden');
			}
			else{
				if (! el.parent().hasClass( "hidden" ) ) { 
					el.parent().addClass('hidden');
				}
			}

		});
		

	});


	// press enter by search
	 $('#search-main-panel').on('keypress', function (e) {
	         if(e.which === 13){
				$(".main-panel .search-button").trigger('click');

	         }
	   });


	// ===================================================================== search field in the main panel end



	// =============================================================================== VIN show categories link begin


	$("a.vin-link-details").click(function(event) {  

		event.preventDefault();

		$baseURL = getCurrentBaseURL();

		var resultURL = "";

		var vinVal = $(this).attr('data');


		resultURL += ($baseURL + "vin-details/");
		resultURL += ( "?act=search&data=" + vinVal );
		window.location.href = resultURL;


	});


	// =============================================================================== VIN show categories link end	



	// =============================================================================== VIN cat menu show/hide begin
	$(".main-panel #vin-cat-menu a").removeClass('active');

	$(".main-panel #vin-cat-menu a").click(function(event) {  

		event.preventDefault();


		console.log( "menu click" );


		var id = $(this).attr('vin-menu-id');

		//alert(id);
		if ( id ){


			$('.main-panel #vin-cat-menu ul').each(function( index ) {

				var ulId =  $(this).attr("vin-menu-id");

				//alert(ulId);
				if (ulId == id ){
					if ( $(this).hasClass('hidd')  ){
						$(this).removeClass('hidd');
						//if ( $(this).hasClass('hidden')  ){
						//	$(this).removeClass('hidden');
						//}	
						$(this).slideDown( "slow", function() {
    						// Animation complete.
 						 });
					}
					else{
						$(this).addClass('hidd');
						$(this).slideUp( "slow", function() {
    						// Animation complete.
 						 });
					}
				}


			});
		}
		else{
			var code = $(this).attr('code');
			
			var vin = $(".main-panel #vin-cat-menu").attr('vincode');
			//alert( vin + ", " +  code);


			var loadingClass = 'loading';
		
			// if  some category is being loaded ignore the click
			var loaderSel = ".loader";
			if ($(loaderSel).hasClass(loadingClass)){
				console.log("Ignore click");
				return;
			}



			$(loaderSel).addClass(loadingClass);

			$(".main-panel #vin-cat-menu a").removeClass('active');
			$(this).addClass('active');


			vinMenuClick( vin, code );


		}


	});





	// image window
	//$('.item .show-modal-by-click').on('click', function(event){
		$('.item .image-no-click').on('click', function(event){
		
		console.log( "image click" );
		event.preventDefault();

		var img_path = $(this).attr('data');

		showImageDialog('', getImageDialogStructure( img_path ) );

	});



	//function getImageDialogStructure(img_path){

	//	//html = "<img style='width: 90%;' oncontextmenu='return false;' class='items-image' src=" + img_path + ">";
	//	img_path2 = "'" + img_path + "'";
	//	html = "<div class='image-dialog-container' >  <div style=\"background: url("  + img_path2  +  " ) no-repeat; background-size:contain; background-position: center; width: 100%; height: 100%; \" oncontextmenu='return false;'></div></div>";

	//	return html;

	//}




















});








function setItemsSorter(){
		var selector = '#soflow';
		var currURL  = window.location.href; 
		var parsedURL = parse_query_string( currURL );
		if (parsedURL.sort == "name") {

			if (parsedURL.sort_ord == "ASC"){  $(selector).prop('selectedIndex', 0);  currentItemSorter = 0; }
			else{ $(selector).prop('selectedIndex', 1); currentItemSorter = 1; }
		}
		else if (parsedURL.sort == "price"){
			if (parsedURL.sort_ord == "ASC"){  $(selector).prop('selectedIndex', 2); currentItemSorter = 2; }
			else{ $(selector).prop('selectedIndex', 3); currentItemSorter = 3; }

		}
	
}


// set start search value to the main search box
function setSearchBoxValue(){

	//set the search value in the search box
	var currURL  = window.location.href; 
	var parsedURL = parse_query_string( currURL );

	if (currURL.indexOf('act=search') > 0) {
		var searchVal = parsedURL.data;
		$('#search').val( searchVal );
	}
}




function parse_query_string(query) {
  var vars = query.split("&");
  var query_string = {};
  for (var i = 0; i < vars.length; i++) {
    var pair = vars[i].split("=");
    // If first entry with this name
    if (typeof query_string[pair[0]] === "undefined") {
      query_string[pair[0]] = decodeURIComponent(pair[1]);
      // If second entry with this name
    } else if (typeof query_string[pair[0]] === "string") {
      var arr = [query_string[pair[0]], decodeURIComponent(pair[1])];
      query_string[pair[0]] = arr;
      // If third or later entry with this name
    } else {
      query_string[pair[0]].push(decodeURIComponent(pair[1]));
    }
  }
  return query_string;
}


// parse and return as string dates of items in the cart. For sumbmit form
function getCartItemsData(){

  	var itemsData = ""; // accumulator
  	$(".cart .cart-item").each(function(i, item) {
  		
  		var itemCode = $(this).find('.cart-item-code').text();
  		var itemPrice = $(this).find('.price-value').text();
		var itemName = $(this).find('.cart-item-name').text();
		var itemNumber = $(this).find('.cart-item-number').val() ;
  		var num = i + 1;
		itemsData += "Товар" + num + ": " + "Код: " + itemCode  + ", Найменування: " + itemName +  ", К-сть: "  + itemNumber + ", Ціна за одиницю товару: " +  itemPrice + "\n";

  	});

  	// add to total item's cost
  	var totalSum = $('.cart .cart-total-price').text().trim();
  	itemsData += ( "Загальна вартість: " + totalSum );


  	return itemsData;

}


// get formatted data from sumbit form
function getSumbitFormsData(){
	var form = $('#m-Form');

	var dataAcc=""; // accum for data

	var name = form.find('#name').val();
	var phone = form.find('#phone').val();
	var email = form.find('#email').val();
	var type_of_payment = form.find( "#paytype option:selected" ).val();
	var message = form.find('#message').val();

	var type_of_delivery = form.find( "#devtype option:selected" ).val();
	var address = form.find('#adres').val();
	var receiver = form.find('#receiver').val();

	dataAcc = "Ім\'я: " + name + ",\nТелефон: "  + phone + ",\nE-mail: " + email + ",\nВид оплати: " + type_of_payment + ",\nВид доставки: " + type_of_delivery + ",\nАдреса доставки: " + address + ",\nОтримувач замовлення: " + receiver  + ",\nКоментарій: " + message + "\n";

	return dataAcc;
}


function getSubmitFormName(){
	var form = $('#m-Form');
	var name = form.find('#name').val();

	return name;
}


// renew shop carrt values in the top menu
function renewMenuCartVal(num, cost){
	// do not show cart values if they are 0
	//var fNum = num > 0 ? num : "";
	//var fCost = cost > 0 ? cost : "";

	// do not show 0 values
	if (num == 0){ num = ""; }
	if (cost == 0) { cost = ""; }
	$('.menu-container .cart-data .items-number').text( num );
	// items cost

	$('.menu-container .cart-data .items-price').text( cost  );

	//blink price
	$('.menu-container .cart-data .items-number').addClass('blink-menu-cart');
	$('.menu-container .cart-data .items-price').addClass('blink-menu-cart');

}


// URL before ?
function getCurrentBaseURL(){
	var currURL  = window.location.href; 

	var urlParts = currURL .split("?");

	var baseURL = "";
	if ( urlParts.length > 0 ){
		baseURL = urlParts[0];
	}
	else{
		var loc = window.location;
	 	baseURL = loc.protocol + "//" + loc.hostname;
	}

	return baseURL;
}



// =============================================================================== VIN cat menu show/hide end	

$(document).on("click",".vin-cat-img img",function(event){
		event.preventDefault();
		//var img_path = $(this).attr('src');


		//showImageDialog('', getImageDialogStructure( img_path ) );		
});

// =============================================================================== VIN image window show




function getImageDialogStructure(img_path){

	//html = "<img style='width: 90%;' oncontextmenu='return false;' class='items-image' src=" + img_path + ">";
	img_path2 = "'" + img_path + "'";
	html = "<div class='image-dialog-container' >  <div style=\"background: url("  + img_path2  +  " ) no-repeat; background-size:contain; background-position: center; width: 100%; height: 100%; \" oncontextmenu='return false;'></div></div>";

	return html;

}


function showImageDialog(head, text){
	var md = $('#idmodal');
	md.find('.head-content').text(head);
	md.find('.modal-dialog-content').empty();
	md.find('.modal-dialog-content').append(text);
	document.getElementById('idmodal').style.display='block';

}



// VIN click on the full code
$(document).on("click",".vin-item-full-code .code",function(event){
		event.preventDefault();

		var code = $(this).html();


		var loadingClass = 'loading';
	
		// if  some category is being loaded ignore the click
		var loaderSel = ".loader";
		if ($(loaderSel).hasClass(loadingClass)){
			console.log("Ignore click");
			return;
		}

		$(loaderSel).addClass(loadingClass);

		if (! $(".main-panel .items-side-panel").hasClass("hidden")){
			$(".main-panel .items-side-panel").addClass("hidden");
		}

		var vin = $('.main-panel .menu-code').attr('data-vin');
		var menuId = $('.main-panel .menu-code').attr('data-menu-code');
		vinCodeAjax( code, vin, menuId );
		
});




function vinCodeAjax(code, vin, menuId){

		var loaderSel = ".loader";
		var loadingClass = 'loading';
		var ajaxurl = $('.menu-container').data('url');

		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 7000, 
			data: {
				'code': code,
				'vin': vin,
				'menuid': menuId,
				action: 'autoparts_vin_show_items_all'
			},
			error: function(response){
				console.log("Server error occured!" + response);
				$(loaderSel).removeClass(loadingClass);

			},
			success: function(response){
					console.log( response.trim() );

					$(loaderSel).removeClass(loadingClass);

					//return;
					var vars = response.trim();

					var contSel = '.main-panel .items-container';

					$( contSel ).removeClass('col-lg-9');
					$( contSel ).removeClass('col-md-9');
					$( contSel ).removeClass('col-sm-9');

					$( contSel ).addClass('col-sm-6');
					$( contSel ).addClass('col-md-6');
					$( contSel ).addClass('col-lg-6');

					$( contSel ).empty();
					$( contSel ).append( "<div class=\"loader\"><\/div>" + vars );
			
					
			}
		
		});

}



















// VIN click on the item
$(document).on("click",".main-panel .items-container .vin-item",function(event){
		event.preventDefault();

		//var code = $(this).html();
		var code = $(this).find('.items-code').html();


		var loadingClass = 'loading';
	
		// if  some category is being loaded ignore the click
		var loaderSel = ".loader";
		if ($(loaderSel).hasClass(loadingClass)){
			console.log("Ignore click");
			return;
		}

		$(loaderSel).addClass(loadingClass);

		$('.main-panel .items-container .vin-item').removeClass('active');
		$(this).addClass('active');

		var contSel = '.main-panel .items-side-panel';
		$( contSel ).empty();
		$(".main-panel .items-side-panel").removeClass("hidden");


		vinItemsAjax( code );
		
});



$(document).on("click",".main-panel a.vin-back-link-1",function(event){
		event.preventDefault();

		//var code = $(this).html();
		var code = $(this).attr('data');


		var loadingClass = 'loading';
	
		// if  some category is being loaded ignore the click
		var loaderSel = ".loader";
		if ($(loaderSel).hasClass(loadingClass)){
			console.log("Ignore click");
			return;
		}

		$(loaderSel).addClass(loadingClass);

		$('.main-panel .items-container .vin-item').removeClass('active');
		$(this).addClass('active');

		var contSel = '.main-panel .items-side-panel';
		$( contSel ).empty();
		$(".main-panel .items-side-panel").removeClass("hidden");


		vinItemsAjax( code );
		
});




function vinItemsAjax( code ){

		var loadingClass = 'loading';
		var loaderSel = ".loader";
		var contSel = '.main-panel .items-side-panel';
		var ajaxurl = $('.menu-container').data('url');
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 7000, 
			data: {
				'code': code,
				action: 'autoparts_vin_show_items_details'
			},
			error: function(response){
				console.log("Server error occured!" + response);
				$(loaderSel).removeClass(loadingClass);

			},
			success: function(response){
					console.log( response.trim() );

					$(loaderSel).removeClass(loadingClass);

					//return;
					var vars = response.trim();

					$( contSel ).empty();
					$( contSel ).append( "<div class=\"loader\"><\/div>" + vars );
			
					
			}
		
		});

}





	// shop cart add vin-item button clicked 
$(document).on("click",".vin-item .cart-button",function(event){
		event.stopPropagation();
		event.preventDefault();
		
		console.log( "Cart button click" );


		$('.menu-container .cart-data .items-number').removeClass('blink-menu-cart');
		$('.menu-container .cart-data .items-price').removeClass('blink-menu-cart');		


		var item = $(this).parents( ".items-body" );

		var itemPrice = item.find('p.price').text();

		var itemNumber = item.find('.item-numbers').val();

		//var itemNumber = item.find('large-container .item-numbers').val() ;
		var itemName = item.find('.items-body-content .items-name').text();
		var itemImage = item.find('.image-no-click').attr('data'); //item.find('.items-image').attr('src');
		if (!itemImage){
			itemImage = "";
		}
		// that's a key for saving
		var itemCode = item.find('.items-body-content .items-code').text();

		//alert(itemPrice + ", " + itemNumber + ", " + itemName + ", " + itemImage + ", " + itemCode);


		//start ajax query
		addToCartAjax(itemCode, itemName, itemNumber, itemImage, itemPrice);
});




	// shop cart add vin-item button clicked 
$(document).on("click",".vin-item .item-numbers",function(event){
		event.stopPropagation();
		event.preventDefault();
		
});


	// shop cart add vin-item button clicked 
$(document).on("onfocusout",".vin-item .item-numbers",function(event){
		event.stopPropagation();
		event.preventDefault();
		
});


	// shop cart add vin-item button clicked 
$(document).on("onfocus",".vin-item .item-numbers",function(event){
		event.stopPropagation();
		event.preventDefault();
		
});






	// shop cart add vin-analog item button clicked 
$(document).on("click",".vin-item-info-analog .cart-button",function(event){
		event.stopPropagation();
		event.preventDefault();
		
		console.log( "Cart button click" );

		$('.menu-container .cart-data .items-number').removeClass('blink-menu-cart');
		$('.menu-container .cart-data .items-price').removeClass('blink-menu-cart');		

		var item = $(this).parents( ".vin-item-info-analog" );

		var itemPrice = item.find('.vin-item-info-price').text();
		var itemNumber = item.find('.item-numbers').val();
		var itemName = item.find('.vin-item-info-name').text();
		var itemImage = "";
		var itemCode = item.find('.vin-item-info-code').text();

		//alert(itemPrice + ", " + itemNumber + ", " + itemName + ", " + itemImage + ", " + itemCode);
		//start ajax query
		addToCartAjax(itemCode, itemName, itemNumber, itemImage, itemPrice);
});




$(document).on("click",".vin-item .image-no-click",function(event){
		event.stopPropagation();
		event.preventDefault();		
		console.log( "image click" );

		var img_path = $(this).attr('data');

		showImageDialog('', getImageDialogStructure( img_path ) );

});



$(document).on("click",".vin-item .items-image-small-container i",function(event){
		event.stopPropagation();
		event.preventDefault();		

});






function addToCartAjax(code, name, number, img, price){
		var ajaxurl = $('.menu-container').data('url');
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 7000,  //$('.menu-container').data('loadtime'),
			data: {
				'code': code,
				'name': name,
				'number': number,
				'img': img,
				'price': price,
				action: 'autoparts_add_item_to_cart'
			},
			error: function(response){
				showModalDialog('Ошибка', 'Произошла ошибка. Повторите действие позже');
				// show error dialog
			},
			success: function(response){
					console.log( response.trim() );

					var vars = response.trim().split('|');
					var itemsTotalNumber = vars[0];
					var itemsTotalSum = vars[1];	
			

					renewMenuCartVal(itemsTotalNumber, itemsTotalSum);
			}
		
		});

}



$(document).on("click",".vin-back-link-1",function(event){
		event.preventDefault();

		var vin = $(this).attr('data-vin');
		var code = $(this).attr('data-menu-code');

		var loadingClass = 'loading';
	
		// if  some category is being loaded ignore the click
		var loaderSel = ".loader";

		$(loaderSel).addClass(loadingClass);

		$(".main-panel .items-side-panel").empty();
		$(".main-panel .items-side-panel").addClass("hidden");
		vinMenuClick( vin, code );
		
});




function vinMenuClick( vin, code ){

	var loadingClass = 'loading';
	// if  some category is being loaded ignore the click
	var loaderSel = ".loader";
	var ajaxurl = $('.menu-container').data('url');
	$.ajax({
		url: ajaxurl, 
		type: 'post',
		timeout: 7000, 
		data: {
			'vin': vin,
			'code': code,
			action: 'autoparts_vin_show_items_cat'
		},
		error: function(response){
			console.log("Server error occured!" + response);
			$(loaderSel).removeClass(loadingClass);

		},
		success: function(response){
				console.log( response.trim() );
				$(loaderSel).removeClass(loadingClass);

				var vars = response.trim();

				var contSel = '.main-panel .items-container';

				//if (! $(".main-panel .items-side-panel").hasClass('hidden')){
				$(".main-panel .items-side-panel").addClass("hidden");
				//}

				$( contSel ).removeClass('col-lg-6');
				$( contSel ).removeClass('col-md-6');
				$( contSel ).removeClass('col-sm-6');

				$( contSel ).addClass('col-sm-9');
				$( contSel ).addClass('col-md-9');
				$( contSel ).addClass('col-lg-9');

				$( contSel ).empty();

				$( contSel ).append( "<div class=\"loader\"><\/div>" + vars );

				//if (! $(".main-panel .items-side-panel").hasClass("hidden")){
				//}	
		
				
		}
	
	});
}




// blog thumbnail article click

$(document).on("click",".main-panel .atricle-thumbnail",function(event){
		event.preventDefault();		

		var id = $(this).attr('id');
		var articlePage = $(this).parents( ".blog-thumbs-container" ).attr('page');
		var resultURL = "";
		if (id){
			resultURL = articlePage + "?id=" + id ;
		}
		else{
			resultURL = articlePage;
		}

		window.location.href = resultURL;

});








		













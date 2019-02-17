
var pageScrollTime = 1000;
var showUpArrowOffset = 400;
var smallMenuOpened = false;

var currentItemSorter = 0

$(function() {

	// set the item's sorter
	setItemsSorter();

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


	// shop cart button click
	$('.cart-button2').on('click', function(event){

		$('.menu-container .cart-data .items-number').removeClass('blink-menu-cart');
		$('.menu-container .cart-data .items-price').removeClass('blink-menu-cart');		

		alert("Cart button pressed!");

		var item = $(this).parents( ".items-body" );

		var price = item.find('.lg-price-container p.price').text() ;
		var itemsNumber = item.find('.item-numbers').val() ;
		var itemsName = item.find('.items-body-content .items-name').text();
		var itemsImage = item.find('.items-image').attr('src');
		// that's a key for saving
		var itemsCode = item.find('.items-code').text();

		alert ( itemsCode );

		//renew shop cart data
		// items number
		$('.menu-container .cart-data .items-number').text(10);
		// items cost
		$('.menu-container .cart-data .items-price').text(100);

		//blink price
		$('.menu-container .cart-data .items-number').addClass('blink-menu-cart');
		$('.menu-container .cart-data .items-price').addClass('blink-menu-cart');

	});







//ajax
	// ajax services category choose
	$('.cart-button').on('click', function(event){

		event.preventDefault();
		$('.menu-container .cart-data .items-number').removeClass('blink-menu-cart');
		$('.menu-container .cart-data .items-price').removeClass('blink-menu-cart');		

		alert("Cart button pressed!");

		var item = $(this).parents( ".items-body" );

		var itemPrice = item.find('.lg-price-container p.price').text() ;
		var itemNumber = item.find('.item-numbers').val() ;
		var itemName = item.find('.items-body-content .items-name').text();
		var itemImage = item.find('.items-image').attr('src');
		// that's a key for saving
		var itemCode = item.find('.items-code').text();


		var loadingClass = 'loading';
		
		// if  some category is being loaded ignore the click
		//if ($('.services .content-panel').hasClass(loadingClass)){
		//	console.log("Ignore click");
		//	return;
		//}
	
		//var post_id =  $(this).data('url'); //data.node.li_attr.id;

		// ignore categories whitount ids(not leaf)
		//if (post_id == undefined || !post_id){
		//	return;
		//}
		
		//console.log(post_id);
		
		// set loading classes
		//$('.services .content-panel').addClass(loadingClass);
		//$('.services .loader').addClass(loadingClass);
		
		//$( ".toggle-mnu-small" ).trigger("click");

		//alert( $('.menu-container').data('url'));

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
				alert ("Error " + response);
				// show error dialog
				//alert( $('.services .content-panel').data('servererror') );
				// remove loading classes to stop loading animation
				//$('.services .content-panel').removeClass(loadingClass);
				//$('.services .loader').removeClass(loadingClass);
			},
			success: function(response){
					alert (response.trim() );
				//$('.content-panel h6').empty();
				// if server returned  'no_posts' 
				//if (response.trim() == 'no_posts'){
				//	$output = "<p class='message'>".concat( $('.services .content-panel').data('nodata') ,"</p>"); 
			//		$('.content-panel h6').append( $output );
			//	}
			//	else{
			//		$('.content-panel h6').append(response);
			//	}
			//	$('.services .content-panel').removeClass(loadingClass);
			//	$('.services .loader').removeClass(loadingClass);
			}
		
		});
	});






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






		













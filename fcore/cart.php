<?php
/*
	Shop cart file
*/


class ShopCart{

	function  __construct(){	
		// array for storing items
		$this->data = [];
	}

	
	function add( $args ){
		/*	add  the item to the cart
			$data - assoc_array(code, name, price, img, number) 
		*/
			
		foreach($this->data as &$item){
			// check if item already added. If so, encrease the value
			if (  ($item['name'] == $args['name']) && ($item['code'] == $args['code'])  ){
				$item['number'] += abs($args['number']);
				return;
			}
		}
		
		// if the item is new( no item with such code found in the cart
		$this->data[] = array(
			'name' => $args['name'],
			'price' => $this->priceConvert( $args['price']),
			'code' => $args['code'],
			'number' => $args['number'],
			'img' => $args['img'],

		);
	}
	
	
	function set( $args ){
		/*	set  the given number to the given item
			$data - assoc_array(code, name, price, img, number) 
		*/
			
		foreach($this->data as &$item){
			// check if item already added. If so, set the value
			if (  ($item['name'] == $args['name']) && ($item['code'] == $args['code'])  ){
				$item['number'] = abs($args['number']);
				return;
			}
		}
		
		// if the item is new( no item with such code found in the cart
		$this->data[] = array(
			'name' => $args['name'],
			'price' => $args['price'],
			'code' => $args['code'],
			'number' => $args['number'],
			'img' => $args['img'],

		);
	}	
	
	
	function remove( $args ){
		/*	remove the item from the cart
			$data - assoc_array(code, name, price, img, number) fileld is necessary
		*/	
		
		foreach($this->data as &$item){
			// check if item already added. If so, decrese the value
			if ( ($item['code'] == $args['code']) && ($item['name'] == $args['name']) ){
				$item['number'] -= abs($args['number']);
				if ($item['number'] < 0){
					$item['number'] = 0;
				}
				return;
			}
		}

		// if no item found in the cart, do nothing
	}

	function get(){
		/*	get all the items from the cart with number > 0	*/

		$res = []; //accumulator
		foreach($this->data as &$item){
			if ($item['number'] > 0){
				$res[] = $item;
			}
		}

		return $res;
		
	}
	
	
	function clear(){
		/*	Clear the cart	*/

		$this->data = [];
	}


	function getItemsNumber(){
		/*	Get the total number of items - not unique	*/

		$total = 0;
		foreach($this->data as &$item){
			$total += $item['number'];
		}
		
		return $total;
	}	
	
	
	function getTotalSum(){
		/*	Get the total sum of all the items 	*/

		$totalSum = 0;
		foreach($this->data as &$item){
			$totalSum += $item['number'] * $item['price'];
		}
		return round($totalSum , 2);
	}
	
	
	function priceConvert($price){
	    $price1 = str_replace(',', '.', (string)$price );
		$price2 = str_replace(' ', '', (string)$price1 );
		return (float) $price2;
	}

}




?>